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
        try {
            $alats = Alat::with('kategori')->get();

            return response()->json([
                'success' => true,
                'data' => $alats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching alat: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get alat by ID
     */
    public function show($id)
    {
        try {
            $alat = Alat::with('kategori')->find($id);

            if (!$alat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alat tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $alat,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching alat: ' . $e->getMessage(),
            ], 500);
        }
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

        try {
            $alat = Alat::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Alat berhasil ditambahkan',
                'data' => $alat,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating alat: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update alat
     */
    public function update(Request $request, $id)
    {
        $alat = Alat::find($id);

        if (!$alat) {
            return response()->json([
                'success' => false,
                'message' => 'Alat tidak ditemukan',
            ], 404);
        }

        $validated = $request->validate([
            'nama_alat' => 'sometimes|string|max:50',
            'id_kategori' => 'sometimes|exists:kategori,id_kategori',
            'stok' => 'sometimes|integer|min:0',
            'dipinjam' => 'sometimes|integer|min:0',
        ]);

        try {
            $alat->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Alat berhasil diperbarui',
                'data' => $alat,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating alat: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete alat
     */
    public function destroy($id)
    {
        $alat = Alat::find($id);

        if (!$alat) {
            return response()->json([
                'success' => false,
                'message' => 'Alat tidak ditemukan',
            ], 404);
        }

        try {
            $alat->delete();

            return response()->json([
                'success' => true,
                'message' => 'Alat berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting alat: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all categories
     */
    public function getCategories()
    {
        try {
            $categories = Kategori::all();

            return response()->json([
                'success' => true,
                'data' => $categories,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching categories: ' . $e->getMessage(),
            ], 500);
        }
    }
}
