<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    /**
     * Upload gambar untuk alat
     */
    public function uploadAlatImage(Request $request, $id)
    {
        // Check authorization - only admin or owner
        if (!Auth::user()->isOwnerOrAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $request->validate([
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
        ]);

        $alat = Alat::withTrashed()->findOrFail($id);

        // Delete old image if exists
        if ($alat->gambar && Storage::disk('public')->exists($alat->gambar)) {
            Storage::disk('public')->delete($alat->gambar);
        }

        // Store new image
        $path = $request->file('gambar')->store('alat', 'public');
        $alat->update(['gambar' => $path]);

        // Log activity
        ActivityLogService::log('upload_image', 'Alat', $id, [
            'old_image' => $alat->getOriginal('gambar'),
            'new_image' => $path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil diupload',
            'data' => [
                'id_alat' => $alat->id_alat,
                'gambar' => $path,
                'url' => asset('storage/' . $path),
            ],
        ]);
    }

    /**
     * Get alat image
     */
    public function getAlatImage($id)
    {
        $alat = Alat::withTrashed()->findOrFail($id);

        if (!$alat->gambar || !Storage::disk('public')->exists($alat->gambar)) {
            return response()->json([
                'success' => false,
                'message' => 'Gambar tidak ditemukan',
            ], 404);
        }

        return response()->file(Storage::disk('public')->path($alat->gambar));
    }

    /**
     * Delete alat image
     */
    public function deleteAlatImage($id)
    {
        // Check authorization
        if (!Auth::user()->isOwnerOrAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $alat = Alat::withTrashed()->findOrFail($id);

        if (!$alat->gambar) {
            return response()->json([
                'success' => false,
                'message' => 'Gambar tidak ditemukan',
            ], 404);
        }

        if (Storage::disk('public')->exists($alat->gambar)) {
            Storage::disk('public')->delete($alat->gambar);
        }

        $oldImage = $alat->gambar;
        $alat->update(['gambar' => null]);

        // Log activity
        ActivityLogService::log('delete_image', 'Alat', $id, [
            'deleted_image' => $oldImage,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil dihapus',
        ]);
    }
}
