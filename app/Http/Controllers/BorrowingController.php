<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Equipment;
use App\Models\BorrowingReturn;
use App\Helpers\BorrowingHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DateTime;

class BorrowingController extends Controller
{
    /**
     * Display a listing of borrowings
     */
    public function index(Request $request)
    {
        try {
            $query = Borrowing::with('user', 'equipment', 'returnDetails');

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by user
            if ($request->has('user_id')) {
                $query->where('id_user', $request->user_id);
            }

            $borrowings = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $borrowings,
                'message' => 'Borrowings retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve borrowings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created borrowing request
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_user' => 'required|exists:users,id_user',
                'id_equipment' => 'required|exists:equipment,id_equipment',
                'quantity' => 'required|integer|min:1',
                'tanggal_mulai_peminjaman' => 'required|date_format:Y-m-d',
                'tanggal_rencana_kembali' => 'required|date_format:Y-m-d|after:tanggal_mulai_peminjaman',
                'keperluan' => 'required|string|max:500',
                'catatan' => 'nullable|string|max:500',
            ]);

            // Parse dates
            $startDate = new DateTime($validated['tanggal_mulai_peminjaman']);
            $endDate = new DateTime($validated['tanggal_rencana_kembali']);
            
            // Validate no holidays
            $dateErrors = BorrowingHelper::validateDates($startDate, $endDate);
            if (!empty($dateErrors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi tanggal gagal: ' . implode(', ', $dateErrors)
                ], 400);
            }
            
            // Validate duration (min 1 hour, max 14 days)
            $durationErrors = BorrowingHelper::validateDuration($startDate, $endDate);
            if (!empty($durationErrors)) {
                return response()->json([
                    'success' => false,
                    'message' => implode(', ', $durationErrors)
                ], 400);
            }

            // Check equipment availability
            $equipment = Equipment::find($validated['id_equipment']);
            if ($equipment->getAvailableQuantity() < $validated['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alat tidak tersedia dengan jumlah yang diminta'
                ], 400);
            }

            // Calculate duration in hours
            $duration = BorrowingHelper::calculateDuration($startDate, $endDate);
            
            // Generate verification code (8 digits)
            $kodeVerifikasi = BorrowingHelper::generateVerificationCode();

            // Create borrowing request
            $data = [
                'id_user' => $validated['id_user'],
                'id_equipment' => $validated['id_equipment'],
                'quantity' => $validated['quantity'],
                'borrow_date' => $startDate->format('Y-m-d H:i:s'),
                'planned_return_date' => $endDate->format('Y-m-d H:i:s'),
                'durasi_jam' => $duration['hours'],
                'keperluan' => $validated['keperluan'],
                'notes' => $validated['catatan'] ?? null,
                'status' => 'applied',
                'kode_verifikasi' => $kodeVerifikasi,
            ];

            $borrowing = Borrowing::create($data);
            $borrowing->load('user', 'equipment');

            return response()->json([
                'success' => true,
                'data' => $borrowing,
                'kode_verifikasi' => $kodeVerifikasi,
                'message' => 'Permohonan peminjaman berhasil dibuat, silakan simpan kode verifikasi: ' . $kodeVerifikasi
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat permohonan peminjaman: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified borrowing
     */
    public function show(Borrowing $borrowing)
    {
        try {
            $borrowing->load('user', 'equipment', 'returnDetails');

            return response()->json([
                'success' => true,
                'data' => $borrowing,
                'message' => 'Borrowing retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve borrowing: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update borrowing status (Approve/Reject)
     */
    public function update(Request $request, Borrowing $borrowing)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:approved,rejected,ready_for_pickup,picked_up,returned,cancelled,overdue',
                'notes' => 'nullable|string',
            ]);

            $borrowing->update($validated);

            return response()->json([
                'success' => true,
                'data' => $borrowing->load('user', 'equipment'),
                'message' => 'Borrowing updated successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation failed'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update borrowing: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the borrowing (Cancel)
     */
    public function destroy(Borrowing $borrowing)
    {
        try {
            if ($borrowing->status === 'returned' || $borrowing->status === 'rejected') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel this borrowing'
                ], 400);
            }

            $borrowing->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Borrowing cancelled successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel borrowing: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve borrowing request
     */
    public function approveBorrowing(Request $request, Borrowing $borrowing)
    {
        try {
            if ($borrowing->status !== 'applied') {
                return response()->json([
                    'success' => false,
                    'message' => 'Borrowing cannot be approved'
                ], 400);
            }

            $borrowing->update(['status' => 'approved']);

            return response()->json([
                'success' => true,
                'data' => $borrowing,
                'message' => 'Borrowing approved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve borrowing: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject borrowing request
     */
    public function rejectBorrowing(Request $request, Borrowing $borrowing)
    {
        try {
            $validated = $request->validate([
                'reason' => 'required|string',
            ]);

            if ($borrowing->status !== 'applied') {
                return response()->json([
                    'success' => false,
                    'message' => 'Borrowing cannot be rejected'
                ], 400);
            }

            $borrowing->update([
                'status' => 'rejected',
                'notes' => $validated['reason']
            ]);

            return response()->json([
                'success' => true,
                'data' => $borrowing,
                'message' => 'Borrowing rejected successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject borrowing: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate pickup code
     */
    public function generatePickupCode(Request $request, Borrowing $borrowing)
    {
        try {
            if ($borrowing->status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pickup code can only be generated for approved borrowings'
                ], 400);
            }

            $pickupCode = strtoupper(Str::random(3) . '-' . Str::random(3));

            $borrowing->update([
                'pickup_code' => $pickupCode,
                'pickup_code_generated_at' => now(),
                'status' => 'ready_for_pickup'
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'pickup_code' => $pickupCode,
                    'borrowing' => $borrowing
                ],
                'message' => 'Pickup code generated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate pickup code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify pickup code and mark as picked up
     */
    public function verifyPickUp(Request $request, Borrowing $borrowing)
    {
        try {
            $validated = $request->validate([
                'pickup_code' => 'required|string',
                'photo_url' => 'nullable|string',
            ]);

            if ($borrowing->status !== 'ready_for_pickup') {
                return response()->json([
                    'success' => false,
                    'message' => 'Borrowing is not ready for pickup'
                ], 400);
            }

            if (strtoupper($validated['pickup_code']) !== $borrowing->pickup_code) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid pickup code'
                ], 400);
            }

            $borrowing->update([
                'status' => 'picked_up',
                'pickup_verified_at' => now(),
                'pickup_photo_before' => $validated['photo_url'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'data' => $borrowing,
                'message' => 'Pickup verified successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation failed'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify pickup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process return verification
     */
    public function verifyReturn(Request $request, Borrowing $borrowing)
    {
        try {
            $validated = $request->validate([
                'return_date' => 'required|date_format:Y-m-d H:i:s',
                'condition' => 'required|in:good,fair,poor',
                'condition_notes' => 'nullable|string',
                'damage_notes' => 'nullable|string',
                'photo_url' => 'nullable|string',
            ]);

            if ($borrowing->status !== 'picked_up') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only picked up borrowings can be returned'
                ], 400);
            }

            // Calculate fine if late
            $returnDate = new \DateTime($validated['return_date']);
            $plannedDate = new \DateTime($borrowing->planned_return_date);
            $lateDays = max(0, $returnDate->diff($plannedDate)->days);
            $fineAmount = $lateDays > 0 ? min($lateDays, 30) * 50000 : 0;

            // Create return record
            $borrowing->returnDetails()->create([
                'return_date' => $validated['return_date'],
                'condition' => $validated['condition'],
                'condition_notes' => $validated['condition_notes'],
                'damage_notes' => $validated['damage_notes'],
                'photo_after' => $validated['photo_url'],
                'fine_amount' => $fineAmount,
            ]);

            // Update borrowing
            $borrowing->update([
                'status' => 'returned',
                'actual_return_date' => $validated['return_date'],
                'fine_amount' => $fineAmount,
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'borrowing' => $borrowing,
                    'fine_amount' => $fineAmount,
                    'late_days' => $lateDays
                ],
                'message' => 'Return verified successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation failed'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify return: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get borrowings by user
     */
    public function getUserBorrowings($userId)
    {
        try {
            $borrowings = Borrowing::where('id_user', $userId)
                ->with('equipment', 'returnDetails')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $borrowings,
                'message' => 'User borrowings retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user borrowings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get overdue borrowings
     */
    public function getOverdueeBorrowings()
    {
        try {
            $now = now();
            $borrowings = Borrowing::where('status', 'picked_up')
                ->where('planned_return_date', '<', $now)
                ->with('user', 'equipment')
                ->get();

            // Update status to overdue
            foreach ($borrowings as $borrowing) {
                $borrowing->update(['status' => 'overdue']);
            }

            return response()->json([
                'success' => true,
                'data' => $borrowings,
                'message' => 'Overdue borrowings retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve overdue borrowings: ' . $e->getMessage()
            ], 500);
        }
    }
}

