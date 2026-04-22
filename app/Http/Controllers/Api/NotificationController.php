<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    private const TYPE_MAP = [
        'Info' => 1,
        'Success' => 2,
        'Warning' => 3,
        'Error' => 4,
    ];

    public function index()
    {
        return Notification::query()
            ->orderByDesc('id')
            ->get()
            ->map(fn (Notification $item) => [
                'id' => $item->id,
                'userId' => $item->user_id,
                'judul' => $item->judul,
                'pesan' => $item->pesan,
                'tipe' => array_search((int) $item->tipe, self::TYPE_MAP, true) ?: 'Info',
                'isRead' => (bool) $item->is_read,
                'createdAt' => $item->created_at?->format('Y-m-d H:i:s'),
            ])
            ->values();
    }
}
