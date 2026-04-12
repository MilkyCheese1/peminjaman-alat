<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\User;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StatisticsController extends Controller
{
    /**
     * Get dashboard statistics
     * Returns: total equipment, active users, satisfaction rate
     * Cache untuk 2 jam
     */
    public function getDashboardStats()
    {
        try {
            // Gunakan cache untuk 2 jam (7200 detik)
            $statistics = Cache::remember('dashboard_statistics', 7200, function () {
                // 1. Hitung total JENIS equipment (distinct count)
                $totalEquipment = Equipment::where('is_available', true)
                    ->count();

                // 2. Hitung total pengguna aktif
                $totalActiveUsers = User::where('is_active', true)
                    ->where('role', '!=', 'admin') // Exclude admin users
                    ->count();

                // 3. Hitung satisfaction rate
                // = successful borrowings (status: returned) / total borrowings
                $totalBorrowings = Borrowing::count();
                $successfulBorrowings = Borrowing::where('status', 'returned')->count();
                
                $satisfactionRate = $totalBorrowings > 0 
                    ? round(($successfulBorrowings / $totalBorrowings) * 100)
                    : 98; // Default 98% jika belum ada data

                return [
                    'total_equipment' => $totalEquipment,
                    'total_active_users' => $totalActiveUsers,
                    'satisfaction_rate' => $satisfactionRate,
                    'cached_at' => now(),
                    'cache_expires_in_hours' => 2
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $statistics,
                'message' => 'Statistics retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear statistics cache (untuk admin/testing)
     */
    public function clearCache()
    {
        try {
            Cache::forget('dashboard_statistics');
            
            return response()->json([
                'success' => true,
                'message' => 'Statistics cache cleared successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detailed statistics for admin dashboard
     */
    public function getDetailedStats()
    {
        try {
            $stats = [
                'equipment' => [
                    'total' => Equipment::sum('quantity'),
                    'available' => Equipment::where('is_available', true)->sum('quantity'),
                    'categories' => Equipment::distinct('id_category')->count(),
                ],
                'users' => [
                    'total' => User::count(),
                    'active' => User::where('is_active', true)->count(),
                    'by_role' => User::select('role')->selectRaw('count(*) as count')
                        ->groupBy('role')
                        ->get()
                        ->pluck('count', 'role'),
                ],
                'borrowings' => [
                    'total' => Borrowing::count(),
                    'active' => Borrowing::whereIn('status', ['applied', 'approved', 'ready_for_pickup', 'picked_up'])->count(),
                    'completed' => Borrowing::where('status', 'returned')->count(),
                    'rejected' => Borrowing::where('status', 'rejected')->count(),
                    'overdue' => Borrowing::where('status', 'overdue')->count(),
                ],
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Detailed statistics retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve detailed statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}
