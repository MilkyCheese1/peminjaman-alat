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
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada user yang login',
            ], 401);
        }

        try {
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
            } else { // peminjam
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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching stats: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all users (admin only)
     */
    public function getUsers(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak',
            ], 403);
        }

        try {
            $users = User::select('id_user', 'username', 'email', 'phone', 'role', 'is_active', 'created_at')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $users,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching users: ' . $e->getMessage(),
            ], 500);
        }
    }
}
