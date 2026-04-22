<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    private const STATUS_MAP = [
        'Aktif' => 1,
        'Nonaktif' => 2,
    ];

    public function index(Request $request)
    {
        $query = Category::query()->orderByDesc('id');

        $statusLabel = $request->query('status');
        if (is_string($statusLabel) && isset(self::STATUS_MAP[$statusLabel])) {
            $query->where('status', self::STATUS_MAP[$statusLabel]);
        }

        return $query
            ->get()
            ->map(fn (Category $category) => $this->toDto($category))
            ->values();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'namaKategori' => ['required', 'string', 'max:60'],
            'kodeKategori' => ['required', 'string', 'max:10', 'unique:categories,kode_kategori'],
            'status' => ['required', 'string', Rule::in(array_keys(self::STATUS_MAP))],
            'deskripsi' => ['required', 'string', 'max:255'],
        ]);

        $gambar = $this->resolveGambarPath($request, folder: 'categories');

        $category = Category::create([
            'nama_kategori' => $data['namaKategori'],
            'kode_kategori' => $data['kodeKategori'],
            'status' => self::STATUS_MAP[$data['status']],
            'deskripsi' => $data['deskripsi'],
            'gambar' => $gambar,
        ]);

        return response()->json($this->toDto($category), 201);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'namaKategori' => ['required', 'string', 'max:60'],
            'kodeKategori' => [
                'required',
                'string',
                'max:10',
                Rule::unique('categories', 'kode_kategori')->ignore($category->id),
            ],
            'status' => ['required', 'string', Rule::in(array_keys(self::STATUS_MAP))],
            'deskripsi' => ['required', 'string', 'max:255'],
        ]);

        $updatePayload = [
            'nama_kategori' => $data['namaKategori'],
            'kode_kategori' => $data['kodeKategori'],
            'status' => self::STATUS_MAP[$data['status']],
            'deskripsi' => $data['deskripsi'],
        ];

        if ($request->hasFile('gambar') || $request->has('gambar')) {
            $updatePayload['gambar'] = $this->resolveGambarPath($request, folder: 'categories');
        }

        $category->update($updatePayload);

        return $this->toDto($category->fresh());
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->noContent();
    }

    private function toDto(Category $category): array
    {
        $statusLabel = array_search((int) $category->status, self::STATUS_MAP, true) ?: 'Aktif';

        return [
            'id' => $category->id,
            'namaKategori' => $category->nama_kategori,
            'kodeKategori' => $category->kode_kategori,
            'status' => $statusLabel,
            'deskripsi' => $category->deskripsi,
            'gambar' => $category->gambar,
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
