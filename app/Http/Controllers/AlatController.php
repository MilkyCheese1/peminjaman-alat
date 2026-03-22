<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    /**
     * Get all alat with kategori
     */
    public function index(Request $request)
    {
        $alats = Alat::with('kategori')->get();

        return response()->json([
            'success' => true,
            'data' => $alats,
        ]);
    }

    /**
     * Get alat by ID
     */
    public function show($id)
    {
        $alat = Alat::with('kategori')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $alat,
        ]);
    }

    /**
     * Create new alat
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_alat' => 'required|string|max:50',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'stok' => 'required|integer|min:0',
            'dipinjam' => 'required|integer|min:0',
        ]);

        $alat = Alat::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Alat berhasil ditambahkan',
            'data' => $alat,
        ], 201);
    }

    /**
     * Update alat
     */
    public function update(Request $request, $id)
    {
        $alat = Alat::findOrFail($id);

        $validated = $request->validate([
            'nama_alat' => 'sometimes|string|max:50',
            'id_kategori' => 'sometimes|exists:kategori,id_kategori',
            'stok' => 'sometimes|integer|min:0',
            'dipinjam' => 'sometimes|integer|min:0',
        ]);

        $alat->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Alat berhasil diperbarui',
            'data' => $alat,
        ]);
    }

    /**
     * Delete alat
     */
    public function destroy($id)
    {
        $alat = Alat::findOrFail($id);
        $alat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Alat berhasil dihapus',
        ]);
    }

    /**
     * Get all categories
     */
    public function getCategories()
    {
        $categories = Kategori::all();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }
}
