<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function getStats(Request $request)
    {
        $user = Auth::user();
        $stats = [];

        if ($user->role === 'admin') {
            $stats = [
                'total_users' => User::count(),
                'total_alat' => Alat::count(),
                'total_peminjaman' => Peminjaman::count(),
                'peminjaman_pending' => Peminjaman::where('status', 'pending')->count(),
                'peminjaman_approved' => Peminjaman::where('status', 'disetujui')->count(),
                'peminjaman_returned' => Peminjaman::where('status', 'dikembalikan')->count(),
            ];
        } elseif ($user->role === 'petugas') {
            $stats = [
                'total_peminjaman' => Peminjaman::count(),
                'peminjaman_pending' => Peminjaman::where('status', 'pending')->count(),
                'peminjaman_approved' => Peminjaman::where('status', 'disetujui')->count(),
                'alat_tersedia' => Alat::whereColumn('stok', '>', 'dipinjam')->count(),
            ];
        } else {
            $stats = [
                'total_peminjaman' => Peminjaman::where('id_user', $user->id_user)->count(),
                'peminjaman_pending' => Peminjaman::where('id_user', $user->id_user)
                    ->where('status', 'pending')->count(),
                'peminjaman_approved' => Peminjaman::where('id_user', $user->id_user)
                    ->where('status', 'disetujui')->count(),
                'peminjaman_returned' => Peminjaman::where('id_user', $user->id_user)
                    ->where('status', 'dikembalikan')->count(),
            ];
        }

        return response()->json([
            'success' => true,
            'stats' => $stats,
        ]);
    }

    /**
     * Get all users (admin only)
     */
    public function getUsers(Request $request)
    {
        // Authorization check - admin only
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin yang dapat melihat daftar user',
            ], 403);
        }

        $users = User::select('id_user', 'username', 'email', 'phone', 'role', 'is_active', 'created_at')->get();

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }
}
