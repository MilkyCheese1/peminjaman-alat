<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    /**
     * Get all categories
     */
    public function index()
    {
        $kategoris = Kategori::withCount('alats')->get();

        return response()->json([
            'success' => true,
            'data' => $kategoris->map(function($k) {
                return [
                    'id_kategori' => $k->id_kategori,
                    'nama_kategori' => $k->nama_kategori,
                    'alat_count' => $k->alats_count,
                ];
            }),
        ]);
    }

    /**
     * Get single category
     */
    public function show($id)
    {
        $kategori = Kategori::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $kategori,
        ]);
    }

    /**
     * Create new category (admin/owner only)
     */
    public function store(Request $request)
    {
        // Check authorization
        if (!Auth::user() || !in_array(Auth::user()->role, ['admin', 'owner'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:50|unique:kategori,nama_kategori',
        ]);

        $kategori = Kategori::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $kategori,
        ], 201);
    }

    /**
     * Update category (admin/owner only)
     */
    public function update(Request $request, $id)
    {
        // Check authorization
        if (!Auth::user() || !in_array(Auth::user()->role, ['admin', 'owner'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $kategori = Kategori::findOrFail($id);

        $validated = $request->validate([
            'nama_kategori' => 'sometimes|string|max:50|unique:kategori,nama_kategori,' . $id . ',id_kategori',
        ]);

        $kategori->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui',
            'data' => $kategori,
        ]);
    }

    /**
     * Delete category (admin/owner only)
     */
    public function destroy($id)
    {
        // Check authorization
        if (!Auth::user() || !in_array(Auth::user()->role, ['admin', 'owner'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $kategori = Kategori::findOrFail($id);

        // Check if kategori is used
        $alatCount = $kategori->alat()->count();
        if ($alatCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus kategori yang sedang digunakan (' . $alatCount . ' alat)',
            ], 422);
        }

        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus',
        ]);
    }
}
