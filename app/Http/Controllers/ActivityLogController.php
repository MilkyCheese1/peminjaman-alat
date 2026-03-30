<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Get activity logs (Admin ONLY)
     * 
     * Endpoint untuk melihat semua activity logs
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $limit = $request->query('limit', 100);

        // Authorization: only admin
        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin yang dapat melihat activity logs',
            ], 403);
        }

        // Admin dapat melihat semua
        $logs = ActivityLogService::getAllActivityLogs($limit);

        return response()->json([
            'success' => true,
            'data' => $logs,
        ]);
    }

    /**
     * Get activity logs untuk model tertentu (Admin ONLY)
     */
    public function getModelLogs(Request $request, $modelType, $modelId)
    {
        $request->validate([
            'limit' => 'sometimes|integer|min:1|max:500',
        ]);

        $user = Auth::user();
        $limit = $request->query('limit', 100);

        // Authorization check - only admin
        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin yang dapat melihat activity logs model',
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
     * Get activity logs untuk user tertentu (Admin ONLY)
     */
    public function getUserLogs($id_user, Request $request)
    {
        $user = Auth::user();

        // Authorization: only admin
        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin yang dapat melihat activity logs user',
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
     * Get summary of activities (Admin ONLY)
     */
    public function getSummary(Request $request)
    {
        $user = Auth::user();

        // Only admin
        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin yang dapat melihat summary activity logs',
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
