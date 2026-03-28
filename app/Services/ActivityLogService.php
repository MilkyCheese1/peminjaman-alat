<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Log aktivitas user
     */
    public static function log($action, $modelType, $modelId, $changes = null)
    {
        $user = Auth::user();
        
        if (!$user) {
            return null;
        }

        return ActivityLog::create([
            'id_user' => $user->id_user,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'changes' => $changes,
            'ip_address' => Request::ip(),
            'created_at' => now(),
        ]);
    }

    /**
     * Get activity logs untuk user tertentu
     */
    public static function getActivityLogs($id_user = null, $limit = 100)
    {
        $query = ActivityLog::with('user');

        if ($id_user) {
            $query->where('id_user', $id_user);
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Get activity logs untuk model tertentu
     */
    public static function getModelActivityLogs($modelType, $modelId, $limit = 100)
    {
        return ActivityLog::where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Get all activity logs (untuk owner/admin)
     */
    public static function getAllActivityLogs($limit = 100)
    {
        return ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }
}
