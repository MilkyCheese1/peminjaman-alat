<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Services\ActivityLogService;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QrCodeController extends Controller
{
    /**
     * Get QR code untuk peminjaman
     */
    public function getQrCode($id_peminjaman)
    {
        $peminjaman = Peminjaman::with(['user', 'alat.kategori'])->findOrFail($id_peminjaman);

        // Check authorization - pemesan atau petugas
        $user = Auth::user();
        if ($user->id_user !== $peminjaman->id_user && $user->role !== 'petugas') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'qr_code' => $peminjaman->qr_code,
                'qr_image' => QrCodeService::generateQrImage($peminjaman->qr_code),
                'peminjaman' => $peminjaman,
            ],
        ]);
    }

    /**
     * Scan QR code untuk verifikasi peminjaman
     */
    public function scanQrCode(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        $qrCode = $request->input('qr_code');
        
        // Verify QR code format dan dapatkan id_peminjaman
        $id_peminjaman = QrCodeService::verifyQrCode($qrCode);

        if (!$id_peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'QR code tidak valid',
            ], 400);
        }

        $peminjaman = Peminjaman::with(['user', 'alat.kategori'])->find($id_peminjaman);

        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman tidak ditemukan',
            ], 404);
        }

        // Log scan activity
        ActivityLogService::log('scan_qr', 'Peminjaman', $id_peminjaman, [
            'qr_code' => $qrCode,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'QR code berhasil dipindai',
            'data' => $peminjaman,
        ]);
    }

    /**
     * Approve peminjaman via QR code (Petugas ONLY)
     */
    public function approveViaScan(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        $user = Auth::user();

        // Check authorization - only petugas
        if ($user->role !== 'petugas') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya petugas yang dapat menyetujui peminjaman via QR',
            ], 403);
        }

        $qrCode = $request->input('qr_code');
        $id_peminjaman = QrCodeService::verifyQrCode($qrCode);

        if (!$id_peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'QR code tidak valid',
            ], 400);
        }

        $peminjaman = Peminjaman::findOrFail($id_peminjaman);

        // Can only approve if status is pending
        if ($peminjaman->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => "Peminjaman sudah berstatus {$peminjaman->status}",
            ], 400);
        }

        try {
            DB::transaction(function () use ($peminjaman, $user) {
                $peminjaman->update([
                    'status' => 'booked',
                    'approved_by' => $user->id_user,
                    'status_updated_by' => $user->id_user,
                    'status_updated_at' => now(),
                ]);

                // Log activity
                ActivityLogService::log('approve_via_scan', 'Peminjaman', $peminjaman->id_peminjaman, [
                    'old_status' => 'pending',
                    'new_status' => 'booked',
                    'approved_by' => $user->username,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil disetujui oleh ' . $user->username,
                'data' => $peminjaman->fresh()->with(['user', 'alat.kategori', 'approvedBy']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui peminjaman: ' . $e->getMessage(),
            ], 500);
        }
    }
}
