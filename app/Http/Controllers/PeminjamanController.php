<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Alat;
use App\Services\BookingValidationService;
use App\Services\StockManagementService;
use App\Services\QrCodeService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    private $bookingService;
    private $stockService;

    public function __construct(
        BookingValidationService $bookingService,
        StockManagementService $stockService
    ) {
        $this->bookingService = $bookingService;
        $this->stockService = $stockService;
    }

    /**
     * Get all peminjaman
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'alat.kategori']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $peminjamans = $query->orderBy('tgl_peminjaman', 'desc')->get();

        return response()->json([
            'success' => true,
            'count' => count($peminjamans),
            'data' => $peminjamans,
        ]);
    }

    /**
     * Get user's own active borrowings
     */
    public function getMyBorrowings(Request $request)
    {
        $peminjamans = Peminjaman::where('id_user', Auth::user()->id_user)
            ->whereIn('status', ['pending', 'booked', 'in_use'])
            ->with(['alat.kategori'])
            ->orderBy('tgl_peminjaman', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'count' => count($peminjamans),
            'data' => $peminjamans,
        ]);
    }

    /**
     * Get borrow history
     */
    public function getBorrowHistory(Request $request)
    {
        $history = Peminjaman::where('id_user', Auth::user()->id_user)
            ->where('status', 'returned')
            ->with(['alat.kategori'])
            ->orderBy('tgl_kembali', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'count' => count($history),
            'data' => $history,
        ]);
    }

    /**
     * Create new peminjaman (borrow request) WITH DOUBLE-BOOKING VALIDATION
     * Uses ACID transaction for safety
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_alat' => 'required|exists:alat,id_alat',
            'tgl_peminjaman' => 'required|date|after_or_equal:today',
            'tgl_kembali' => 'required|date|after:tgl_peminjaman',
        ]);

        try {
            $peminjaman = DB::transaction(function () use ($validated) {
                $alat = Alat::lockForUpdate()->findOrFail($validated['id_alat']);
                $user = Auth::user();

                // 1. Check if stock available
                $available_stock = $alat->stok - $alat->dipinjam;
                if ($available_stock <= 0) {
                    throw new \Exception('Stok alat tidak tersedia');
                }

                // 2. Check for double-booking using booking validation service
                // Using formula: StartA ≤ EndB AND EndA ≥ StartB
                if (!$this->bookingService->isAlatAvailable(
                    $validated['id_alat'],
                    $validated['tgl_peminjaman'],
                    $validated['tgl_kembali']
                )) {
                    throw new \Exception('Alat sudah dipesan di tanggal tersebut. Periksa tanggal ketersediaan.');
                }

                // 3. Check buffer time
                if (!$this->bookingService->hasBufferTime($validated['id_alat'], $validated['tgl_peminjaman'])) {
                    throw new \Exception('Alat masih dalam buffer time untuk pengecekan kondisi. Coba tanggal lain.');
                }

                // 4. Reserve stock (increment dipinjam)
                $this->stockService->reserveStock($validated['id_alat']);

                // 5. Create peminjaman record
                $peminjaman = Peminjaman::create([
                    'id_user' => $user->id_user,
                    'id_alat' => $validated['id_alat'],
                    'tgl_peminjaman' => $validated['tgl_peminjaman'],
                    'tgl_kembali' => $validated['tgl_kembali'],
                    'status' => 'pending',
                    'denda' => 0,
                    'buffer_checked' => false,
                ]);

                // 6. Generate QR code untuk peminjaman
                $qrCode = QrCodeService::generateQrCode($peminjaman->id_peminjaman);
                $peminjaman->update(['qr_code' => $qrCode]);

                // 7. Update alat status
                $this->stockService->updateAlatStatus($validated['id_alat']);

                // 8. Log activity
                ActivityLogService::log('create', 'Peminjaman', $peminjaman->id_peminjaman, [
                    'alat_name' => $alat->nama_alat,
                    'tgl_peminjaman' => $validated['tgl_peminjaman'],
                    'tgl_kembali' => $validated['tgl_kembali'],
                ]);

                return $peminjaman;
            });

            return response()->json([
                'success' => true,
                'message' => 'Permintaan peminjaman berhasil dibuat',
                'data' => $peminjaman->load(['user', 'alat']),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Update peminjaman status WITH PROPER STATE TRANSITIONS
     * pending -> booked -> in_use -> returned
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:booked,in_use,returned,rejected,maintenance',
            'denda' => 'sometimes|numeric|min:0',
            'actual_return_date' => 'sometimes|date',
        ]);

        try {
            $peminjaman = DB::transaction(function () use ($validated, $id) {
                $peminjaman = Peminjaman::lockForUpdate()->findOrFail($id);
                $alat = Alat::lockForUpdate()->findOrFail($peminjaman->id_alat);
                $new_status = $validated['status'];
                $old_status = $peminjaman->status;

                // Validate state transitions
                $valid_transitions = [
                    'pending' => ['booked', 'rejected'],
                    'booked' => ['in_use', 'rejected'],
                    'in_use' => ['returned', 'maintenance'],
                    'returned' => [],
                    'rejected' => [],
                    'maintenance' => ['in_use', 'returned'],
                ];

                if (!isset($valid_transitions[$old_status]) || 
                    !in_array($new_status, $valid_transitions[$old_status])) {
                    throw new \Exception(
                        "Status transition tidak valid: {$old_status} -> {$new_status}"
                    );
                }

                // Handle status transition side effects
                if ($new_status === 'rejected' && $old_status !== 'rejected') {
                    // Release stock if rejected
                    $this->stockService->releaseStock($peminjaman->id_alat);
                } 
                elseif ($new_status === 'returned' && $old_status === 'in_use') {
                    // Return stock if returned
                    $this->stockService->returnStock($peminjaman->id_alat);
                    $peminjaman->actual_return_date = $validated['actual_return_date'] ?? Carbon::now();
                    $peminjaman->buffer_checked = false;
                }

                // Update status and denda
                $peminjaman->status = $new_status;
                $peminjaman->denda = $validated['denda'] ?? $peminjaman->denda;
                $peminjaman->save();

                // Update alat status
                $this->stockService->updateAlatStatus($peminjaman->id_alat);

                return $peminjaman;
            });

            return response()->json([
                'success' => true,
                'message' => 'Status peminjaman berhasil diperbarui',
                'data' => $peminjaman->load(['user', 'alat']),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Check available dates for an alat
     */
    public function checkAvailability($id_alat, Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after:date_from',
        ]);

        $available = $this->bookingService->isAlatAvailable(
            $id_alat,
            $request->date_from,
            $request->date_to
        );

        $unavailable_periods = $this->bookingService->getUnavailablePeriods($id_alat);

        return response()->json([
            'success' => true,
            'available' => $available,
            'date_range' => [
                'from' => $request->date_from,
                'to' => $request->date_to,
            ],
            'unavailable_periods' => $unavailable_periods,
        ]);
    }

    /**
     * Get all reservations for reporting
     */
    public function getSchedule(Request $request)
    {
        $request->validate([
            'id_alat' => 'required|exists:alat,id_alat',
        ]);

        $bookings = $this->bookingService->getActiveBookings($request->id_alat);

        return response()->json([
            'success' => true,
            'id_alat' => $request->id_alat,
            'count' => count($bookings),
            'bookings' => $bookings,
        ]);
    }
}
