<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of all users
     */
    public function index()
    {
        try {
            $users = User::select(
                'id_user',
                'username',
                'nama_lengkap',
                'email',
                'phone',
                'role',
                'alamat',
                'kota',
                'provinsi',
                'is_active',
                'created_at'
            )
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($user) {
                return [
                    'id_user' => $user->id_user,
                    'username' => $user->username,
                    'nama_lengkap' => $user->nama_lengkap,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                    'alamat' => $user->alamat,
                    'kota' => $user->kota,
                    'provinsi' => $user->provinsi,
                    'is_active' => $user->is_active,
                    'created_at' => $user->created_at,
                    'borrowing_count' => $user->borrowings()->count(),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $users,
                'total' => User::count(),
                'message' => 'Users retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:50|unique:users',
                'nama_lengkap' => 'required|string|max:100',
                'email' => 'required|email|max:100|unique:users',
                'phone' => 'nullable|string|max:20',
                'password' => 'required|string|min:8',
                'role' => 'required|in:admin,staff,customer,owner',
                'alamat' => 'nullable|string',
                'kota' => 'nullable|string|max:50',
                'provinsi' => 'nullable|string|max:50',
                'kode_pos' => 'nullable|string|max:10',
                'is_active' => 'boolean',
            ]);

            $validated['password'] = Hash::make($validated['password']);
            $user = User::create($validated);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'User created successfully'
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation failed'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified user
     */
    public function show(string $id)
    {
        try {
            $user = User::with('borrowings')->find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'User retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $validated = $request->validate([
                'username' => 'string|max:50|unique:users,username,' . $id . ',id_user',
                'nama_lengkap' => 'string|max:100',
                'email' => 'email|max:100|unique:users,email,' . $id . ',id_user',
                'phone' => 'nullable|string|max:20',
                'password' => 'nullable|string|min:8',
                'role' => 'in:admin,staff,customer,owner',
                'alamat' => 'nullable|string',
                'kota' => 'nullable|string|max:50',
                'provinsi' => 'nullable|string|max:50',
                'kode_pos' => 'nullable|string|max:10',
                'is_active' => 'boolean',
            ]);

            if (isset($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'User updated successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation failed'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(string $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Prevent deleting owner
            if ($user->role === 'owner') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete owner user'
                ], 403);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get users by role
     */
    public function getUsersByRole($role)
    {
        try {
            $users = User::where('role', $role)
                ->select('id_user', 'username', 'nama_lengkap', 'email', 'role', 'is_active')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => 'Users retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle user active status
     */
    public function toggleActive(string $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $user->update(['is_active' => !$user->is_active]);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'User status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * User login with email and password
     */
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:8'
            ]);

            // Find user by email
            $user = User::where('email', $validated['email'])->first();

            // Check if user exists
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email tidak terdaftar'
                ], 401);
            }

            // Check if user is active
            if (!$user->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.'
                ], 403);
            }

            // Verify password
            if (!Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password salah'
                ], 401);
            }

            // Return user data (without password)
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id_user,
                    'fullname' => $user->nama_lengkap,
                    'email' => $user->email,
                    'role' => $user->role,
                    'phone' => $user->phone,
                    'school' => $user->kota, // Using kota as school for compatibility
                    'address' => $user->alamat,
                    'avatar' => '👤', // Default avatar
                    'status' => $user->is_active ? 'active' : 'inactive',
                    'joinDate' => $user->created_at->format('d F Y')
                ],
                'message' => 'Login berhasil'
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * User registration
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'fullName' => 'required|string|max:100',
                'email' => 'required|email|max:100|unique:users',
                'password' => 'required|string|min:8|max:12',
                'phone' => 'nullable|string|max:20',
            ]);

            // Generate username from fullname
            $username = strtolower(str_replace(' ', '_', $validated['fullName'])) . '_' . time();

            $user = User::create([
                'username' => $username,
                'nama_lengkap' => $validated['fullName'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'role' => 'customer', // Default role for new users
                'is_active' => true
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id_user,
                    'fullname' => $user->nama_lengkap,
                    'email' => $user->email,
                    'role' => $user->role
                ],
                'message' => 'Registrasi berhasil. Silakan login.'
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registrasi gagal: ' . $e->getMessage()
            ], 500);
        }
    }
}

