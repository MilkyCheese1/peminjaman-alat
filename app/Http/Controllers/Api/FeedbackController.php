<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FeedbackEntry;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    private const STATUS_MAP = [
        'Pending' => 1,
        'Ditampilkan' => 2,
        'Ditolak' => 3,
    ];

    public function index()
    {
        return FeedbackEntry::query()
            ->where('status', self::STATUS_MAP['Ditampilkan'])
            ->orderByDesc('id')
            ->get()
            ->map(fn (FeedbackEntry $entry) => $this->toDto($entry))
            ->values();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:120'],
            'stars' => ['nullable', 'integer', 'min:1', 'max:5'],
            'pesan' => ['required', 'string', 'max:255'],
        ]);

        $entry = FeedbackEntry::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'stars' => $data['stars'] ?? null,
            'pesan' => $data['pesan'],
            'status' => self::STATUS_MAP['Ditampilkan'],
            'created_at' => now(),
        ]);

        return response()->json($this->toDto($entry), 201);
    }

    private function toDto(FeedbackEntry $entry): array
    {
        $statusLabel = array_search((int) $entry->status, self::STATUS_MAP, true) ?: 'Ditampilkan';

        return [
            'id' => $entry->id,
            'nama' => $entry->nama,
            'email' => $entry->email,
            'stars' => $entry->stars ? (int) $entry->stars : 5,
            'pesan' => $entry->pesan,
            'status' => $statusLabel,
            'createdAt' => $entry->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
