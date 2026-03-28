<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users|min:3|max:50',
            'email' => 'required|email|unique:users',
            'phone' => 'sometimes|min:10|max:20',
            'password' => 'required|min:6|confirmed',
            'alamat' => 'required|min:5',
        ]);

        try {
            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? '',
                'password' => Hash::make($validated['password']),
                'alamat' => $validated['alamat'],
                'role' => 'peminjam',
                'email_verified' => false,
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil! Silakan login.',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registrasi gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Authenticate user and login
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username_or_email' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $validated['username_or_email'])
            ->orWhere('email', $validated['username_or_email'])
            ->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username/Email atau password salah',
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak aktif',
            ], 403);
        }

        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'user' => $user,
            'redirect' => $this->getDashboardRoute($user->role),
        ], 200);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }

    /**
     * Get user profile
     */
    public function getProfile(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'sometimes|min:10|max:20',
            'alamat' => 'sometimes|min:5',
        ]);

        try {
            Auth::user()->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'user' => Auth::user(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Update profil gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password lama tidak sesuai',
            ], 401);
        }

        try {
            $user->update(['password' => Hash::make($validated['new_password'])]);

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Perubahan password gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get dashboard route based on user role
     */
    private function getDashboardRoute($role)
    {
        $routes = [
            'admin' => '/dashboard',
            'petugas' => '/dashboard',
            'peminjam' => '/dashboard',
            'owner' => '/dashboard',
        ];

        return $routes[$role] ?? '/dashboard';
    }
}
