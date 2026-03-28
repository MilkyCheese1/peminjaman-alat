<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Get profile data for current user
     */
    public function show()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id_user' => $user->id_user,
                'username' => $user->username,
                'nama_lengkap' => $user->nama_lengkap,
                'email' => $user->email,
                'phone' => $user->phone,
                'alamat' => $user->alamat,
                'kota' => $user->kota,
                'provinsi' => $user->provinsi,
                'kode_pos' => $user->kode_pos,
                'foto' => $user->foto,
                'role' => $user->role,
                'email_verified' => $user->email_verified,
                'is_active' => $user->is_active,
                'created_at' => $user->created_at,
            ],
        ]);
    }

    /**
     * Update profile data
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        try {
            $validated = $request->validate([
                'nama_lengkap' => 'nullable|string|max:100',
                'email' => 'nullable|email|unique:users,email,' . $user->id_user . ',id_user',
                'phone' => 'nullable|string|max:20',
                'alamat' => 'nullable|string',
                'kota' => 'nullable|string|max:50',
                'provinsi' => 'nullable|string|max:50',
                'kode_pos' => 'nullable|string|max:10',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        // Handle photo upload
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            
            $path = $request->file('foto')->store('profiles', 'public');
            $validated['foto'] = $path;
        }

        // Convert empty strings to null for optional fields only
        // Note: email, phone, alamat are required fields, so they should not be null
        foreach (['nama_lengkap', 'kota', 'provinsi', 'kode_pos'] as $field) {
            if (isset($validated[$field]) && trim($validated[$field]) === '') {
                $validated[$field] = null;
            }
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => [
                'id_user' => $user->id_user,
                'username' => $user->username,
                'nama_lengkap' => $user->nama_lengkap,
                'email' => $user->email,
                'phone' => $user->phone,
                'alamat' => $user->alamat,
                'kota' => $user->kota,
                'provinsi' => $user->provinsi,
                'kode_pos' => $user->kode_pos,
                'foto' => $user->foto,
            ],
        ]);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $validated = $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:6|confirmed',
        ]);

        // Check old password
        if (!Hash::check($validated['password_lama'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password lama tidak sesuai',
            ], 422);
        }

        $user->update([
            'password' => Hash::make($validated['password_baru']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah',
        ]);
    }

    /**
     * Get user profile photo
     */
    public function getPhoto($userId)
    {
        $user = User::findOrFail($userId);

        if (!$user->foto || !Storage::disk('public')->exists($user->foto)) {
            return response()->json([
                'success' => false,
                'message' => 'Foto tidak ditemukan',
            ], 404);
        }

        return response()->file(Storage::disk('public')->path($user->foto));
    }
}
