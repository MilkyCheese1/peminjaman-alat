<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatusUpdateController extends Controller
{
    /**
     * Valid status transitions
     */
    private $validTransitions = [
        'pending' => ['booked', 'rejected'],
        'booked' => ['in_use', 'rejected', 'pending'],
        'in_use' => ['returned', 'maintenance'],
        'returned' => ['returned'], // Can stay returned
        'rejected' => ['pending'], // Can cancel rejection
        'maintenance' => ['tersedia', 'in_use'],
    ];

    /**
     * Update status peminjaman (untuk petugas/admin/owner)
     */
    public function updateStatus(Request $request, $id_peminjaman)
    {
        $user = Auth::user();
        
        // Check authorization: only petugas, admin, or owner
        if ($user->role !== 'petugas' && $user->role !== 'admin' && !$user->isOwner()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya petugas, admin, atau owner yang dapat mengubah status',
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,booked,in_use,returned,rejected,maintenance',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
        $oldStatus = $peminjaman->status;
        $newStatus = $request->input('status');
        $keterangan = $request->input('keterangan');

        // Validate status transition
        if (!$this->isValidTransition($oldStatus, $newStatus)) {
            return response()->json([
                'success' => false,
                'message' => "Transisi dari {$oldStatus} ke {$newStatus} tidak diperbolehkan",
                'valid_transitions' => $this->validTransitions[$oldStatus] ?? [],
            ], 400);
        }

        try {
            DB::transaction(function () use ($peminjaman, $oldStatus, $newStatus, $user, $keterangan) {
                $peminjaman->update([
                    'status' => $newStatus,
                    'status_updated_by' => $user->id_user,
                    'status_updated_at' => now(),
                ]);

                // If status is in_use, set approved_by if not already set
                if ($newStatus === 'in_use' && !$peminjaman->approved_by) {
                    $peminjaman->update(['approved_by' => $user->id_user]);
                }

                // If status is returned, set actual_return_date
                if ($newStatus === 'returned' && !$peminjaman->actual_return_date) {
                    $peminjaman->update(['actual_return_date' => now()]);
                }

                // Log activity
                ActivityLogService::log('update_status', 'Peminjaman', $peminjaman->id_peminjaman, [
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'keterangan' => $keterangan,
                    'updated_by' => Auth::user()->username,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => "Status peminjaman berhasil diubah dari {$oldStatus} ke {$newStatus}",
                'data' => $peminjaman->fresh()->with(['user', 'alat.kategori', 'statusUpdatedBy', 'approvedBy']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk update status untuk multiple peminjamans
     */
    public function bulkUpdateStatus(Request $request)
    {
        $user = Auth::user();
        
        // Check authorization
        if ($user->role !== 'petugas' && $user->role !== 'admin' && !$user->isOwner()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $request->validate([
            'peminjamans' => 'required|array|min:1',
            'peminjamans.*' => 'required|array',
            'peminjamans.*.id' => 'required|integer|exists:peminjaman,id_peminjaman',
            'peminjamans.*.status' => 'required|in:pending,booked,in_use,returned,rejected,maintenance',
        ]);

        $updates = $request->input('peminjamans');
        $successful = 0;
        $failed = 0;
        $errors = [];

        DB::transaction(function () use ($updates, $user, &$successful, &$failed, &$errors) {
            foreach ($updates as $item) {
                $peminjaman = Peminjaman::find($item['id']);
                $oldStatus = $peminjaman->status;
                $newStatus = $item['status'];

                if (!$this->isValidTransition($oldStatus, $newStatus)) {
                    $failed++;
                    $errors[] = "Peminjaman #{$item['id']}: Transisi dari {$oldStatus} ke {$newStatus} tidak valid";
                    continue;
                }

                try {
                    $peminjaman->update([
                        'status' => $newStatus,
                        'status_updated_by' => $user->id_user,
                        'status_updated_at' => now(),
                    ]);

                    if ($newStatus === 'returned' && !$peminjaman->actual_return_date) {
                        $peminjaman->update(['actual_return_date' => now()]);
                    }

                    ActivityLogService::log('bulk_update_status', 'Peminjaman', $peminjaman->id_peminjaman, [
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus,
                    ]);

                    $successful++;
                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = "Peminjaman #{$item['id']}: " . $e->getMessage();
                }
            }
        });

        return response()->json([
            'success' => $failed === 0,
            'message' => "Berhasil: {$successful}, Gagal: {$failed}",
            'data' => [
                'successful' => $successful,
                'failed' => $failed,
                'errors' => $errors,
            ],
        ]);
    }

    /**
     * Get valid status transitions untuk current user
     */
    public function getValidTransitions($id_peminjaman)
    {
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
        $currentStatus = $peminjaman->status;

        return response()->json([
            'success' => true,
            'current_status' => $currentStatus,
            'valid_transitions' => $this->validTransitions[$currentStatus] ?? [],
        ]);
    }

    /**
     * Check if status transition is valid
     */
    private function isValidTransition($from, $to)
    {
        if (!isset($this->validTransitions[$from])) {
            return false;
        }

        return in_array($to, $this->validTransitions[$from]);
    }
}
