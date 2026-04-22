<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ToolController extends Controller
{
    private const KONDISI_MAP = [
        'Baik' => 1,
        'Perlu Kalibrasi' => 2,
        'Rusak Ringan' => 3,
        'Rusak Berat' => 4,
    ];

    private const STATUS_MAP = [
        'Tersedia' => 1,
        'Dipinjam' => 2,
        'Maintenance' => 3,
    ];

    public function index()
    {
        return Tool::query()
            ->with('category')
            ->orderByDesc('id')
            ->get()
            ->map(fn (Tool $tool) => $this->toDto($tool))
            ->values();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'namaAlat' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'hargaAsli' => ['nullable', 'integer', 'min:0'],
            'stok' => ['required', 'integer', 'min:0', 'max:65535'],
            'kondisi' => ['required', 'string', Rule::in(array_keys(self::KONDISI_MAP))],
            'status' => ['required', 'string', Rule::in(array_keys(self::STATUS_MAP))],
            'lokasi' => ['required', 'string', 'max:80'],
        ]);

        $gambar = $this->resolveGambarPath($request, folder: 'tools');

        $tool = Tool::create([
            'category_id' => (int) $data['category_id'],
            'nama_alat' => $data['namaAlat'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'harga_asli' => (int) ($data['hargaAsli'] ?? 0),
            'stok' => (int) $data['stok'],
            'kondisi' => self::KONDISI_MAP[$data['kondisi']],
            'status' => self::STATUS_MAP[$data['status']],
            'lokasi' => $data['lokasi'],
            'gambar' => $gambar,
        ]);

        $tool->load('category');

        return response()->json($this->toDto($tool), 201);
    }

    public function update(Request $request, Tool $tool)
    {
        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'namaAlat' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'hargaAsli' => ['nullable', 'integer', 'min:0'],
            'stok' => ['required', 'integer', 'min:0', 'max:65535'],
            'kondisi' => ['required', 'string', Rule::in(array_keys(self::KONDISI_MAP))],
            'status' => ['required', 'string', Rule::in(array_keys(self::STATUS_MAP))],
            'lokasi' => ['required', 'string', 'max:80'],
        ]);

        $updatePayload = [
            'category_id' => (int) $data['category_id'],
            'nama_alat' => $data['namaAlat'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'harga_asli' => (int) ($data['hargaAsli'] ?? 0),
            'stok' => (int) $data['stok'],
            'kondisi' => self::KONDISI_MAP[$data['kondisi']],
            'status' => self::STATUS_MAP[$data['status']],
            'lokasi' => $data['lokasi'],
        ];

        if ($request->hasFile('gambar') || $request->has('gambar')) {
            $updatePayload['gambar'] = $this->resolveGambarPath($request, folder: 'tools');
        }

        $tool->update($updatePayload);

        $tool->load('category');

        return $this->toDto($tool);
    }

    public function destroy(Tool $tool)
    {
        $tool->delete();
        return response()->noContent();
    }

    private function toDto(Tool $tool): array
    {
        $kondisiLabel = array_search((int) $tool->kondisi, self::KONDISI_MAP, true) ?: 'Baik';
        $statusLabel = array_search((int) $tool->status, self::STATUS_MAP, true) ?: 'Tersedia';

        return [
            'id' => $tool->id,
            'category_id' => $tool->category_id,
            'kategori' => $tool->category?->nama_kategori ?? '-',
            'namaAlat' => $tool->nama_alat,
            'deskripsi' => $tool->deskripsi,
            'hargaAsli' => (int) $tool->harga_asli,
            'stok' => (int) $tool->stok,
            'kondisi' => $kondisiLabel,
            'status' => $statusLabel,
            'lokasi' => $tool->lokasi,
            'gambar' => $tool->gambar,
        ];
    }

    private function resolveGambarPath(Request $request, string $folder): ?string
    {
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');

            if (!$file || !$file->isValid()) {
                return null;
            }

            $request->validate([
                'gambar' => ['image', 'max:2048'],
            ]);

            $destination = public_path("uploads/{$folder}");
            File::ensureDirectoryExists($destination);

            $ext = $file->getClientOriginalExtension() ?: 'jpg';
            $filename = (string) Str::uuid() . '.' . $ext;
            $file->move($destination, $filename);

            return "/uploads/{$folder}/{$filename}";
        }

        if ($request->has('gambar')) {
            $request->validate([
                'gambar' => ['nullable', 'string', 'max:255'],
            ]);

            return $request->input('gambar');
        }

        return null;
    }
}
