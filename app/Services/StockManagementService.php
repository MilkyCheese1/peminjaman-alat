<?php

namespace App\Services;

use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;

class StockManagementService
{
    /**
     * Reserve stock for a booking (with transaction)
     * Prevents race conditions through ACID transaction
     * 
     * @param int $id_alat
     * @param int $qty (default 1)
     * @return bool
     * @throws \Exception
     */
    public function reserveStock($id_alat, $qty = 1)
    {
        return DB::transaction(function () use ($id_alat, $qty) {
            // Use pessimistic lock to prevent race condition
            $alat = Alat::lockForUpdate()->findOrFail($id_alat);

            // Check if enough stock available
            $available = $alat->stok - $alat->dipinjam;
            if ($available < $qty) {
                throw new \Exception('Stok tidak cukup. Tersedia: ' . $available);
            }

            // Update dipinjam (reserved count)
            $alat->dipinjam += $qty;
            $alat->save();

            return true;
        });
    }

    /**
     * Release reserved stock (when booking is rejected/cancelled)
     * 
     * @param int $id_alat
     * @param int $qty (default 1)
     * @return bool
     * @throws \Exception
     */
    public function releaseStock($id_alat, $qty = 1)
    {
        return DB::transaction(function () use ($id_alat, $qty) {
            $alat = Alat::lockForUpdate()->findOrFail($id_alat);

            // Ensure dipinjam doesn't go below 0
            if ($alat->dipinjam < $qty) {
                throw new \Exception('Tidak bisa release stok yang lebih dari yang dipinjam');
            }

            $alat->dipinjam -= $qty;
            $alat->save();

            return true;
        });
    }

    /**
     * Return stock (item is back)
     * 
     * @param int $id_alat
     * @param int $qty (default 1)
     * @return bool
     * @throws \Exception
     */
    public function returnStock($id_alat, $qty = 1)
    {
        return DB::transaction(function () use ($id_alat, $qty) {
            $alat = Alat::lockForUpdate()->findOrFail($id_alat);

            // Ensure dipinjam doesn't go below 0
            if ($alat->dipinjam < $qty) {
                throw new \Exception('Stok pengembalian tidak valid');
            }

            $alat->dipinjam -= $qty;
            $alat->save();

            return true;
        });
    }

    /**
     * Check available stock
     * 
     * @param int $id_alat
     * @return int
     */
    public function getAvailableStock($id_alat)
    {
        $alat = Alat::findOrFail($id_alat);
        return $alat->stok - $alat->dipinjam;
    }

    /**
     * Update alat status based on current bookings
     * 
     * @param int $id_alat
     * @return string (status)
     */
    public function updateAlatStatus($id_alat)
    {
        return DB::transaction(function () use ($id_alat) {
            $alat = Alat::lockForUpdate()->findOrFail($id_alat);

            // Get active bookings
            $active_bookings = Peminjaman::where('id_alat', $id_alat)
                ->whereIn('status', ['pending', 'booked', 'in_use'])
                ->count();

            if ($alat->stok - $alat->dipinjam <= 0) {
                $alat->status_alat = 'booked';
            } elseif ($active_bookings > 0) {
                $alat->status_alat = 'booked';
            } else {
                $alat->status_alat = 'tersedia';
            }

            $alat->save();
            return $alat->status_alat;
        });
    }

    /**
     * Mark alat as in maintenance
     * 
     * @param int $id_alat
     * @return bool
     */
    public function setMaintenance($id_alat)
    {
        $alat = Alat::findOrFail($id_alat);
        $alat->status_alat = 'maintenance';
        $alat->save();
        return true;
    }

    /**
     * Check stock level and return availability status
     * 
     * @param int $id_alat
     * @return array
     */
    public function getStockInfo($id_alat)
    {
        $alat = Alat::findOrFail($id_alat);

        return [
            'id_alat' => $id_alat,
            'total_stok' => $alat->stok,
            'sedang_dipinjam' => $alat->dipinjam,
            'tersedia' => $alat->stok - $alat->dipinjam,
            'status_alat' => $alat->status_alat,
            'persentase_tersedia' => round(
                (($alat->stok - $alat->dipinjam) / $alat->stok) * 100,
                2
            ),
        ];
    }
}
