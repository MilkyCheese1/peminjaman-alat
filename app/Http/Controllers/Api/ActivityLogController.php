<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        return ActivityLog::query()
            ->orderByDesc('id')
            ->get()
            ->map(fn (ActivityLog $item) => [
                'id' => $item->id,
                'userId' => $item->user_id,
                'aksi' => $item->aksi,
                'entitas' => $item->entitas,
                'entitasId' => $item->entitas_id,
                'deskripsi' => $item->deskripsi,
                'ipAddress' => $this->formatIpAddress($item->ip_address),
                'createdAt' => $item->created_at?->format('Y-m-d H:i:s'),
            ])
            ->values();
    }

    private function formatIpAddress(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $decoded = @inet_ntop($value);
        if ($decoded !== false) {
            return $decoded;
        }

        return bin2hex($value);
    }
}
