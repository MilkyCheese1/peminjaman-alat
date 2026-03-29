<?php

namespace App\Services;

use App\Models\Peminjaman;
use Carbon\Carbon;

class BookingValidationService
{
    /**
     * Buffer time in days after return before alat can be borrowed again
     */
    const BUFFER_DAYS = 1;

    /**
     * Check if alat is available between two dates
     * Uses mathematical formula: StartA ≤ EndB AND EndA ≥ StartB
     * 
     * @param int $id_alat
     * @param Carbon|string $start_date
     * @param Carbon|string $end_date
     * @param int|null $exclude_id Exclude peminjaman ID (for updates)
     * @return bool
     */
    public function isAlatAvailable($id_alat, $start_date, $end_date, $exclude_id = null)
    {
        $start_date = $this->toCarbon($start_date);
        $end_date = $this->toCarbon($end_date);

        // Get all active bookings for this alat (ignore soft-deleted)
        $query = Peminjaman::where('id_alat', $id_alat)
            ->whereIn('status', ['pending', 'booked', 'in_use'])
            ->whereNull('deleted_at'); // Exclude soft-deleted records

        if ($exclude_id) {
            $query->where('id_peminjaman', '!=', $exclude_id);
        }

        $bookings = $query->get();

        foreach ($bookings as $booking) {
            $booking_start = $this->toCarbon($booking->tgl_peminjaman);
            $booking_end = $this->toCarbon($booking->tgl_kembali);

            // Check collision: StartA ≤ EndB AND EndA ≥ StartB
            if ($start_date <= $booking_end && $end_date >= $booking_start) {
                return false; // Collision detected
            }
        }

        // Check if there's enough buffer time from previous rentals
        if (!$this->hasBufferTime($id_alat, $start_date)) {
            return false;
        }

        return true; // Alat available
    }

    /**
     * Check if there's enough buffer time after previous return
     * 
     * @param int $id_alat
     * @param Carbon|string $requested_start
     * @param int $buffer_days
     * @return bool
     */
    public function hasBufferTime($id_alat, $requested_start, $buffer_days = self::BUFFER_DAYS)
    {
        $requested_start = $this->toCarbon($requested_start);

        // Get the last returned alat (ignore soft-deleted)
        // Use COALESCE to handle NULL actual_return_date (fallback to tgl_kembali)
        $last_return = Peminjaman::where('id_alat', $id_alat)
            ->where('status', 'returned')
            ->whereNull('deleted_at') // Exclude soft-deleted
            ->orderByRaw('COALESCE(actual_return_date, tgl_kembali) DESC')
            ->first();

        if (!$last_return) {
            return true; // No previous rental
        }

        // Use actual_return_date if set, otherwise fall back to tgl_kembali
        $return_date = $last_return->actual_return_date ?? $last_return->tgl_kembali;
        if (!$return_date) {
            return true;
        }

        $last_return_date = $this->toCarbon($return_date);
        $earliest_available = $last_return_date->addDays($buffer_days);

        return $requested_start >= $earliest_available;
    }

    /**
     * Get all active bookings for an alat
     * 
     * @param int $id_alat
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveBookings($id_alat)
    {
        return Peminjaman::where('id_alat', $id_alat)
            ->whereIn('status', ['pending', 'booked', 'in_use'])
            ->whereNull('deleted_at') // Exclude soft-deleted
            ->with('user')
            ->orderBy('tgl_peminjaman', 'asc')
            ->get();
    }

    /**
     * Get available dates for an alat
     * 
     * @param int $id_alat
     * @param Carbon|string $start_date
     * @param Carbon|string $end_date
     * @return array
     */
    public function getAvailableDates($id_alat, $start_date, $end_date)
    {
        $start = $this->toCarbon($start_date);
        $end = $this->toCarbon($end_date);
        $available_dates = [];

        $current = $start->copy();
        while ($current <= $end) {
            if ($this->isAlatAvailable($id_alat, $current, $current)) {
                $available_dates[] = $current->copy();
            }
            $current->addDay();
        }

        return $available_dates;
    }

    /**
     * Helper: Convert to Carbon instance
     */
    private function toCarbon($date)
    {
        if ($date instanceof Carbon) {
            return $date;
        }
        return Carbon::parse($date);
    }

    /**
     * Get unavailable periods for an alat
     * Including buffer time
     */
    public function getUnavailablePeriods($id_alat)
    {
        $bookings = Peminjaman::where('id_alat', $id_alat)
            ->whereIn('status', ['pending', 'booked', 'in_use', 'returned'])
            ->whereNull('deleted_at') // Exclude soft-deleted
            ->orderBy('tgl_peminjaman', 'asc')
            ->get();

        $periods = [];
        foreach ($bookings as $booking) {
            $period = [
                'start' => $booking->tgl_peminjaman,
                'end' => $booking->tgl_kembali,
                'status' => $booking->status,
            ];

            // Add buffer time if returned
            if ($booking->status === 'returned' && $booking->actual_return_date) {
                $period['buffer_end'] = Carbon::parse($booking->actual_return_date)
                    ->addDays(self::BUFFER_DAYS)
                    ->format('Y-m-d');
            }

            $periods[] = $period;
        }

        return $periods;
    }
}
