<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrashController extends Controller
{
    /**
     * Get trash items untuk current user atau semua untuk owner
     */
    public function getTrashedItems(Request $request)
    {
        $user = Auth::user();
        $type = $request->query('type', 'all'); // all, peminjaman, alat

        if ($user->isOwner()) {
            // Owner dapat melihat semua trash
            return response()->json([
                'success' => true,
                'data' => $this->getAllTrashedItems($type),
            ]);
        } elseif ($user->hasRole('admin')) {
            // Admin dapat melihat trash milik mereka
            return response()->json([
                'success' => true,
                'data' => $this->getAdminTrashedItems($type),
            ]);
        } else {
            // Peminjam hanya bisa lihat peminjaman mereka sendiri yang di-trash
            return response()->json([
                'success' => true,
                'data' => $this->getUserTrashedItems($user->id_user, $type),
            ]);
        }
    }

    /**
     * Get all trashed items (untuk owner)
     */
    private function getAllTrashedItems($type = 'all')
    {
        $data = [];

        if ($type === 'all' || $type === 'alat') {
            $data['alat'] = Alat::onlyTrashed()
                ->with('kategori')
                ->orderBy('deleted_at', 'desc')
                ->get();
        }

        if ($type === 'all' || $type === 'peminjaman') {
            $data['peminjaman'] = Peminjaman::onlyTrashed()
                ->with(['user', 'alat.kategori'])
                ->orderBy('deleted_at', 'desc')
                ->get();
        }

        return $data;
    }

    /**
     * Get admin's trashed items
     */
    private function getAdminTrashedItems($type = 'all')
    {
        $data = [];

        if ($type === 'all' || $type === 'alat') {
            $data['alat'] = Alat::onlyTrashed()
                ->with('kategori')
                ->orderBy('deleted_at', 'desc')
                ->get();
        }

        if ($type === 'all' || $type === 'peminjaman') {
            $data['peminjaman'] = Peminjaman::onlyTrashed()
                ->with(['user', 'alat.kategori'])
                ->orderBy('deleted_at', 'desc')
                ->get();
        }

        return $data;
    }

    /**
     * Get user's trashed items
     */
    private function getUserTrashedItems($id_user, $type = 'all')
    {
        $data = [];

        if ($type === 'all' || $type === 'peminjaman') {
            $data['peminjaman'] = Peminjaman::onlyTrashed()
                ->where('id_user', $id_user)
                ->with(['user', 'alat.kategori'])
                ->orderBy('deleted_at', 'desc')
                ->get();
        }

        return $data;
    }

    /**
     * Restore item dari trash (hanya owner yang bisa)
     */
    public function restore(Request $request)
    {
        // Hanya owner yang bisa restore
        if (!Auth::user()->isOwner()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya owner yang dapat memulihkan data',
            ], 403);
        }

        $request->validate([
            'type' => 'required|in:alat,peminjaman',
            'id' => 'required|integer',
        ]);

        $type = $request->input('type');
        $id = $request->input('id');

        if ($type === 'alat') {
            $alat = Alat::onlyTrashed()->findOrFail($id);
            $alat->restore();

            // Log activity
            ActivityLogService::log('restore', 'Alat', $id);

            return response()->json([
                'success' => true,
                'message' => 'Alat berhasil dipulihkan',
                'data' => $alat->fresh()->load('kategori'),
            ]);
        } else {
            $peminjaman = Peminjaman::onlyTrashed()->findOrFail($id);
            $peminjaman->restore();

            // Log activity
            ActivityLogService::log('restore', 'Peminjaman', $id);

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil dipulihkan',
                'data' => $peminjaman->fresh()->with(['user', 'alat.kategori']),
            ]);
        }
    }

    /**
     * Permanent delete item dari trash
     */
    public function permanentlyDelete(Request $request)
    {
        // Hanya owner yang bisa permanent delete
        if (!Auth::user()->isOwner()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya owner yang dapat menghapus permanen',
            ], 403);
        }

        $request->validate([
            'type' => 'required|in:alat,peminjaman',
            'id' => 'required|integer',
        ]);

        $type = $request->input('type');
        $id = $request->input('id');

        if ($type === 'alat') {
            $alat = Alat::onlyTrashed()->findOrFail($id);
            
            // Delete image if exists
            if ($alat->gambar && \Storage::disk('public')->exists($alat->gambar)) {
                \Storage::disk('public')->delete($alat->gambar);
            }

            $alat->forceDelete();

            // Log activity
            ActivityLogService::log('permanent_delete', 'Alat', $id);

            return response()->json([
                'success' => true,
                'message' => 'Alat berhasil dihapus permanen',
            ]);
        } else {
            $peminjaman = Peminjaman::onlyTrashed()->findOrFail($id);
            $peminjaman->forceDelete();

            // Log activity
            ActivityLogService::log('permanent_delete', 'Peminjaman', $id);

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil dihapus permanen',
            ]);
        }
    }

    /**
     * Empty trash (hanya owner)
     */
    public function emptyTrash(Request $request)
    {
        // Hanya owner yang bisa empty trash
        if (!Auth::user()->isOwner()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya owner yang dapat mengosongkan sampah',
            ], 403);
        }

        $request->validate([
            'type' => 'required|in:all,alat,peminjaman',
        ]);

        $type = $request->input('type');
        $count = 0;

        if ($type === 'all' || $type === 'alat') {
            $alats = Alat::onlyTrashed()->get();
            foreach ($alats as $alat) {
                if ($alat->gambar && \Storage::disk('public')->exists($alat->gambar)) {
                    \Storage::disk('public')->delete($alat->gambar);
                }
                $alat->forceDelete();
                $count++;
            }
        }

        if ($type === 'all' || $type === 'peminjaman') {
            Peminjaman::onlyTrashed()->forceDelete();
            $count += Peminjaman::onlyTrashed()->count();
        }

        // Log activity
        ActivityLogService::log('empty_trash', 'System', 0, ['type' => $type, 'count' => $count]);

        return response()->json([
            'success' => true,
            'message' => "Sampah berhasil dikosongkan ({$count} item)",
        ]);
    }
}
