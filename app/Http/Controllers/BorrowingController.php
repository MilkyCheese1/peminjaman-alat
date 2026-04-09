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
            // Build query with all needed relationships
            $query = Borrowing::query();

            // Filter by status if provided
            if ($request->has('status') && !empty($request->status)) {
                $query->where('borrowings.status', $request->status);
            }

            // Filter by user if provided
            if ($request->has('user_id') && !empty($request->user_id)) {
                $query->where('borrowings.id_user', $request->user_id);
            }

            // Get borrowings with all relationships
            $borrowings = $query
                ->with(['user', 'equipment.category'])
                ->orderBy('borrowings.created_at', 'desc')
                ->get();

            if ($borrowings->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'No borrowings found'
                ]);
            }

            // Format each borrowing
            $formatted = [];
            foreach ($borrowings as $borrowing) {
                $formatted[] = $this->formatBorrowingResponse($borrowing);
            }

            return response()->json([
                'success' => true,
                'data' => $formatted,
                'message' => count($formatted) . ' borrowings retrieved successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in BorrowingController@index: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'data' => [],
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
            $borrowing->load('user', 'equipment', 'equipment.category');

            return response()->json([
                'success' => true,
                'data' => $this->formatBorrowingResponse($borrowing),
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
                ->with('equipment', 'equipment.category', 'user')
                ->orderBy('created_at', 'desc')
                ->get();

            // Format response
            $formattedBorrowings = $borrowings->map(function ($borrowing) {
                return $this->formatBorrowingResponse($borrowing);
            });

            return response()->json([
                'success' => true,
                'data' => $formattedBorrowings,
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
                ->with('user', 'equipment', 'equipment.category')
                ->get();

            // Update status to overdue
            foreach ($borrowings as $borrowing) {
                $borrowing->update(['status' => 'overdue']);
            }

            // Format response
            $formattedBorrowings = $borrowings->map(function ($borrowing) {
                return $this->formatBorrowingResponse($borrowing);
            });

            return response()->json([
                'success' => true,
                'data' => $formattedBorrowings,
                'message' => 'Overdue borrowings retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve overdue borrowings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format borrowing response for frontend
     */
    private function formatBorrowingResponse($borrowing)
    {
        try {
            // Get user info - with defaults
            $userName = 'Unknown';
            $userEmail = '';
            if ($borrowing->user) {
                $userName = $borrowing->user->name ?? 'Unknown';
                $userEmail = $borrowing->user->email ?? '';
            }

            // Get equipment info - with defaults
            $equipmentName = 'Unknown';
            $categoryName = '-';
            if ($borrowing->equipment) {
                $equipmentName = $borrowing->equipment->name ?? 'Unknown';
                if ($borrowing->equipment->category) {
                    $categoryName = $borrowing->equipment->category->name ?? '-';
                }
            }

            // Safe date handling with exception safety
            $borrowDate = date('Y-m-d');
            $returnDate = date('Y-m-d');
            $duration = 0;

            // Parse borrow date safely
            $borrowDateStr = $borrowing->borrow_date;
            if ($borrowDateStr) {
                try {
                    $dt = new \DateTime($borrowDateStr);
                    $borrowDate = $dt->format('Y-m-d');
                } catch (\Exception $e) {
                    \Log::warning('Invalid borrow_date format: ' . $borrowDateStr);
                }
            }

            // Parse return date safely
            $returnDateStr = $borrowing->planned_return_date;
            if ($returnDateStr) {
                try {
                    $dt = new \DateTime($returnDateStr);
                    $returnDate = $dt->format('Y-m-d');
                    
                    // Calculate duration if both dates are valid
                    if ($borrowDateStr) {
                        try {
                            $startDt = new \DateTime($borrowDateStr);
                            $interval = $startDt->diff($dt);
                            $duration = (int)$interval->days;
                            if ($duration < 0) $duration = 0;
                        } catch (\Exception $e) {
                            \Log::warning('Could not calculate duration: ' . $e->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    \Log::warning('Invalid planned_return_date format: ' . $returnDateStr);
                }
            }

            // Build response with relationships
            $response = [
                'id_peminjaman' => (int)$borrowing->id_borrowing,
                'id_user' => (int)$borrowing->id_user,
                'id_equipment' => (int)$borrowing->id_equipment,
                'quantity' => (int)($borrowing->quantity ?? 1),
                'status' => (string)($borrowing->status ?? 'applied'),
                'nama_peminjam' => (string)$userName,
                'email_peminjam' => (string)$userEmail,
                'nama_alat' => (string)$equipmentName,
                'equipment_name' => (string)$equipmentName,
                'category_name' => (string)$categoryName,
                'tanggal_permohonan' => $borrowing->created_at ? $borrowing->created_at->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'),
                'tanggal_peminjaman' => $borrowDate,
                'tanggal_mulai_peminjaman' => $borrowDate,
                'tanggal_rencana_kembali' => $returnDate,
                'tanggal_persetujuan' => $borrowing->updated_at ? $borrowing->updated_at->format('Y-m-d H:i:s') : '',
                'durasi_peminjaman' => (int)$duration,
                'catatan' => (string)($borrowing->notes ?? ''),
                'notes' => (string)($borrowing->notes ?? ''),
                'pickup_code' => $borrowing->pickup_code ?? null,
                'fine_amount' => (float)($borrowing->fine_amount ?? 0),
                'fine_paid' => (bool)($borrowing->fine_paid ?? false),
                'kode_verifikasi' => $borrowing->kode_verifikasi ?? null,
            ];

            // Add user relationship
            if ($borrowing->user) {
                $response['user'] = [
                    'id_user' => $borrowing->user->id_user,
                    'nama_lengkap' => $borrowing->user->name ?? $borrowing->user->nama_lengkap ?? 'Unknown',
                    'email' => $borrowing->user->email ?? '',
                    'phone' => $borrowing->user->phone ?? '',
                    'role' => $borrowing->user->role ?? '',
                ];
            }

            // Add equipment relationship
            if ($borrowing->equipment) {
                $equipment = $borrowing->equipment;
                $response['equipment'] = [
                    'id_equipment' => $equipment->id_equipment,
                    'nama_alat' => $equipment->name ?? 'Unknown',
                    'name' => $equipment->name ?? 'Unknown',
                    'description' => $equipment->description ?? '',
                    'deskripsi' => $equipment->description ?? '',
                    'quantity' => $equipment->quantity ?? 0,
                    'total_stok' => $equipment->quantity ?? 0,
                    'condition' => $equipment->condition ?? '',
                    'kondisi' => $equipment->condition ?? '',
                    'photo' => $equipment->photo ?? '',
                    'gambar' => $equipment->photo ?? '',
                    'fine_per_day' => $equipment->fine_per_day ?? '50000.00',
                    'is_available' => $equipment->is_available ?? true,
                ];

                // Add category if available
                if ($equipment->category) {
                    $response['equipment']['category'] = [
                        'id_category' => $equipment->category->id_category ?? null,
                        'nama_kategori' => $equipment->category->name ?? '',
                        'name' => $equipment->category->name ?? '',
                    ];
                }
            }

            return $response;
        } catch (\Exception $e) {
            \Log::error('Error formatting borrowing: ' . $e->getMessage(), [
                'borrowing_id' => $borrowing->id_borrowing ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            
            return [
                'id_peminjaman' => (int)($borrowing->id_borrowing ?? 0),
                'status' => 'error',
                'nama_peminjam' => 'Error',
                'nama_alat' => 'Error loading',
                'error' => true
            ];
        }
    }
}


