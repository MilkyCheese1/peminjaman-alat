<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Get activity logs
     * 
     * Endpoint ini melihat activity logs berdasarkan role:
     * - Owner: dapat melihat semua activity logs
     * - Admin: dapat melihat activity logs mereka
     * - Petugas: dapat melihat activity logs mereka
     * - Peminjam: dapat melihat activity logs mereka
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);

        if ($user->isOwner()) {
            // Owner dapat melihat semua
            $logs = ActivityLogService::getAllActivityLogs($limit);
        } else {
            // User lain hanya melihat miliknya
            $logs = ActivityLogService::getActivityLogs($user->id_user, $limit);
        }

        return response()->json([
            'success' => true,
            'data' => $logs,
        ]);
    }

    /**
     * Get activity logs untuk model tertentu (Alat atau Peminjaman)
     */
    public function getModelLogs(Request $request, $modelType, $modelId)
    {
        $request->validate([
            'limit' => 'sometimes|integer|min:1|max:500',
        ]);

        $user = Auth::user();
        $limit = $request->query('limit', 100);

        // Authorization check
        if (!$user->isOwnerOrAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        // Validate model type
        if (!in_array($modelType, ['Alat', 'Peminjaman'])) {
            return response()->json([
                'success' => false,
                'message' => 'Model tidak valid',
            ], 400);
        }

        $logs = ActivityLogService::getModelActivityLogs($modelType, $modelId, $limit);

        return response()->json([
            'success' => true,
            'data' => $logs,
        ]);
    }

    /**
     * Get activity logs untuk user tertentu
     */
    public function getUserLogs($id_user, Request $request)
    {
        $user = Auth::user();

        // Authorization: owner dapat melihat semua, admin/petugas hanya diri sendiri, peminjam hanya diri sendiri
        if (!$user->isOwner() && $user->id_user !== $id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $limit = $request->query('limit', 100);
        $logs = ActivityLogService::getActivityLogs($id_user, $limit);

        return response()->json([
            'success' => true,
            'data' => $logs,
        ]);
    }

    /**
     * Get summary of activities
     */
    public function getSummary(Request $request)
    {
        $user = Auth::user();

        // Only owner dan admin dapat melihat summary
        if (!$user->isOwnerOrAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $days = $request->query('days', 7);
        $fromDate = now()->subDays($days);

        $summary = ActivityLog::where('created_at', '>=', $fromDate)
            ->groupBy('action')
            ->selectRaw('action, COUNT(*) as count')
            ->get();

        $byUser = ActivityLog::where('created_at', '>=', $fromDate)
            ->with('user')
            ->groupBy('id_user')
            ->selectRaw('id_user, COUNT(*) as count')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'period' => "{$days} hari terakhir",
                'total_activities' => ActivityLog::where('created_at', '>=', $fromDate)->count(),
                'by_action' => $summary,
                'by_user' => $byUser,
            ],
        ]);
    }

    /**
     * Clear old activity logs (hanya owner)
     */
    public function clearOldLogs(Request $request)
    {
        if (!Auth::user()->isOwner()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya owner yang dapat menghapus activity logs',
            ], 403);
        }

        $request->validate([
            'days' => 'required|integer|min:1',
        ]);

        $days = $request->input('days');
        $beforeDate = now()->subDays($days);

        $deleted = ActivityLog::where('created_at', '<', $beforeDate)->delete();

        return response()->json([
            'success' => true,
            'message' => "Activity logs sebelum {$days} hari telah dihapus",
            'deleted_count' => $deleted,
        ]);
    }
}
