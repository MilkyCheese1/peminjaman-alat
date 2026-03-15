<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    /**
     * Get all peminjaman
     */
    public function index(Request $request)
    {
        try {
            $peminjamans = Peminjaman::with(['user', 'alat.kategori'])->get();

            return response()->json([
                'success' => true,
                'data' => $peminjamans,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching peminjaman: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user's own borrowings
     */
    public function getMyBorrowings(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada user yang login',
            ], 401);
        }

        try {
            $peminjamans = Peminjaman::where('id_user', Auth::user()->id_user)
                ->with(['alat.kategori'])
                ->get();

            return response()->json([
                'success' => true,
                'data' => $peminjamans,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching borrowings: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get borrow history
     */
    public function getBorrowHistory(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada user yang login',
            ], 401);
        }

        try {
            $history = Peminjaman::where('id_user', Auth::user()->id_user)
                ->where('status', 'dikembalikan')
                ->with(['alat.kategori'])
                ->orderBy('tgl_kembali', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $history,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching history: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create new peminjaman (borrow request)
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada user yang login',
            ], 401);
        }

        $validated = $request->validate([
            'id_alat' => 'required|exists:alat,id_alat',
            'tgl_peminjaman' => 'required|date',
            'tgl_kembali' => 'required|date|after:tgl_peminjaman',
        ]);

        try {
            $alat = Alat::find($validated['id_alat']);

            if (!$alat || $alat->stok <= $alat->dipinjam) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok alat tidak tersedia',
                ], 400);
            }

            $peminjaman = Peminjaman::create([
                'id_user' => Auth::user()->id_user,
                'id_alat' => $validated['id_alat'],
                'tgl_peminjaman' => $validated['tgl_peminjaman'],
                'tgl_kembali' => $validated['tgl_kembali'],
                'status' => 'pending',
                'denda' => 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permintaan peminjaman berhasil dibuat',
                'data' => $peminjaman,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating peminjaman: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update peminjaman status
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,disetujui,dikembalikan',
            'denda' => 'sometimes|numeric|min:0',
        ]);

        try {
            $peminjaman = Peminjaman::find($id);

            if (!$peminjaman) {
                return response()->json([
                    'success' => false,
                    'message' => 'Peminjaman tidak ditemukan',
                ], 404);
            }

            // Update alat stock if status changes
            $alat = Alat::find($peminjaman->id_alat);
            if ($peminjaman->status !== $validated['status']) {
                if ($validated['status'] === 'disetujui' && $peminjaman->status === 'pending') {
                    $alat->dipinjam += 1;
                } elseif ($validated['status'] === 'dikembalikan' && $peminjaman->status === 'disetujui') {
                    $alat->dipinjam -= 1;
                }
                $alat->save();
            }

            $peminjaman->update([
                'status' => $validated['status'],
                'denda' => $validated['denda'] ?? $peminjaman->denda,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status peminjaman berhasil diperbarui',
                'data' => $peminjaman,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating peminjaman: ' . $e->getMessage(),
            ], 500);
        }
    }
}
