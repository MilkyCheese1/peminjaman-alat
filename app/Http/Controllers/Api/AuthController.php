<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private const ROLE_MAP = [
        'Admin' => 1,
        'Owner' => 2,
        'Staff' => 3,
        'Peminjam' => 4,
    ];

    private const STATUS_MAP = [
        'Aktif' => 1,
        'Nonaktif' => 2,
        'Ditangguhkan' => 3,
    ];

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'max:120'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()
            ->where('email', $data['email'])
            ->first();

        if (!$user || !Hash::check($data['password'], (string) $user->password_hash)) {
            return response()->json(['message' => 'Email atau password salah.'], 422);
        }

        if ((int) $user->status !== self::STATUS_MAP['Aktif']) {
            return response()->json(['message' => 'Akun Anda tidak aktif.'], 403);
        }

        return response()->json($this->toDto($user));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email'],
            'telepon' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'telepon' => $data['telepon'],
            'role' => self::ROLE_MAP['Peminjam'],
            'status' => self::STATUS_MAP['Aktif'],
            'password_hash' => Hash::make($data['password']),
        ]);

        return response()->json($this->toDto($user), 201);
    }

    private function toDto(User $user): array
    {
        $roleLabel = array_search((int) $user->role, self::ROLE_MAP, true) ?: 'Peminjam';
        $statusLabel = array_search((int) $user->status, self::STATUS_MAP, true) ?: 'Aktif';

        return [
            'id' => $user->id,
            'nama' => $user->nama,
            'email' => $user->email,
            'role' => $roleLabel,
            'status' => $statusLabel,
            'telepon' => $user->telepon,
            'gambar' => $user->gambar,
        ];
    }
}
