<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
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

    public function index()
    {
        return User::query()
            ->orderByDesc('id')
            ->get()
            ->map(fn (User $user) => $this->toDto($user))
            ->values();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email'],
            'role' => ['required', 'string', Rule::in(array_keys(self::ROLE_MAP))],
            'status' => ['required', 'string', Rule::in(array_keys(self::STATUS_MAP))],
            'telepon' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
            'gambar' => ['nullable'],
        ]);

        $gambar = $this->resolveGambarPath($request, folder: 'users');

        $user = User::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'role' => self::ROLE_MAP[$data['role']],
            'status' => self::STATUS_MAP[$data['status']],
            'telepon' => $data['telepon'],
            'password_hash' => Hash::make($data['password']),
            'gambar' => $gambar,
        ]);

        return response()->json($this->toDto($user), 201);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:120', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', 'string', Rule::in(array_keys(self::ROLE_MAP))],
            'status' => ['required', 'string', Rule::in(array_keys(self::STATUS_MAP))],
            'telepon' => ['required', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:6'],
            'gambar' => ['nullable'],
        ]);

        $updatePayload = [
            'nama' => $data['nama'],
            'email' => $data['email'],
            'role' => self::ROLE_MAP[$data['role']],
            'status' => self::STATUS_MAP[$data['status']],
            'telepon' => $data['telepon'],
        ];

        if (isset($data['password']) && $data['password'] !== '') {
            $updatePayload['password_hash'] = Hash::make($data['password']);
        }

        if ($request->hasFile('gambar') || $request->has('gambar')) {
            $updatePayload['gambar'] = $this->resolveGambarPath($request, folder: 'users');
        }

        $user->update($updatePayload);

        return $this->toDto($user->fresh());
    }

    public function updatePassword(Request $request, User $user)
    {
        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($data['current_password'], (string) $user->password_hash)) {
            return response()->json(['message' => 'Password saat ini tidak sesuai.'], 422);
        }

        $user->update([
            'password_hash' => Hash::make($data['password']),
        ]);

        return response()->json([
            'message' => 'Password berhasil diperbarui.',
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
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
