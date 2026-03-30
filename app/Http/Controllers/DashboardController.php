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
     * Get dashboard statistics - Real data from database
     */
    public function getStats(Request $request)
    {
        $user = Auth::user();
        $stats = [];

        if ($user->role === 'admin') {
            // Admin view: System overview
            $stats = [
                'total_users' => User::count(),
                'total_users_peminjam' => User::where('role', 'peminjam')->count(),
                'total_alat' => Alat::count(),
                'total_peminjaman' => Peminjaman::count(),
                'peminjaman_pending' => Peminjaman::where('status', 'pending')->count(),
                'peminjaman_booked' => Peminjaman::where('status', 'booked')->count(),
                'peminjaman_in_use' => Peminjaman::where('status', 'in_use')->count(),
                'peminjaman_returned' => Peminjaman::where('status', 'returned')->count(),
                'peminjaman_rejected' => Peminjaman::where('status', 'rejected')->count(),
                'alat_tersedia' => Alat::where('stok', '>', 0)->sum('stok'),
                'alat_terpinjam' => Alat::sum('dipinjam'),
            ];
        } elseif ($user->role === 'petugas') {
            // Staff view: Borrowing management
            $stats = [
                'total_peminjaman' => Peminjaman::count(),
                'peminjaman_pending' => Peminjaman::where('status', 'pending')->count(),
                'peminjaman_booked' => Peminjaman::where('status', 'booked')->count(),
                'peminjaman_in_use' => Peminjaman::where('status', 'in_use')->count(),
                'peminjaman_returned' => Peminjaman::where('status', 'returned')->count(),
                'peminjaman_rejected' => Peminjaman::where('status', 'rejected')->count(),
                'alat_tersedia' => Alat::where('stok', '>', 0)->sum('stok'),
                'alat_terpinjam' => Alat::sum('dipinjam'),
            ];
        } else {
            // User/Customer view: Personal borrowings
            $stats = [
                'total_peminjaman' => Peminjaman::where('id_user', $user->id_user)->count(),
                'peminjaman_pending' => Peminjaman::where('id_user', $user->id_user)
                    ->where('status', 'pending')->count(),
                'peminjaman_booked' => Peminjaman::where('id_user', $user->id_user)
                    ->where('status', 'booked')->count(),
                'peminjaman_in_use' => Peminjaman::where('id_user', $user->id_user)
                    ->where('status', 'in_use')->count(),
                'peminjaman_returned' => Peminjaman::where('id_user', $user->id_user)
                    ->where('status', 'returned')->count(),
                'peminjaman_rejected' => Peminjaman::where('id_user', $user->id_user)
                    ->where('status', 'rejected')->count(),
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
