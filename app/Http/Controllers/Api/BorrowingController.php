<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BorrowingController extends Controller
{
    private const STATUS_MAP = [
        'Pending' => 1,
        'Disetujui' => 2,
        'Ditolak' => 3,
        'Dipinjam' => 4,
        'Dikembalikan' => 5,
        'Selesai' => 6,
    ];

    public function index()
    {
        return Borrowing::query()
            ->orderByDesc('id')
            ->get()
            ->map(fn (Borrowing $borrowing) => $this->toDto($borrowing))
            ->values();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode' => ['nullable', 'string', 'max:20', 'unique:borrowings,kode'],
            'peminjamId' => ['nullable', 'integer', 'exists:users,id'],
            'namaPeminjam' => ['nullable', 'string', 'max:100'],
            'divisi' => ['nullable', 'string', 'max:60'],
            'namaAlat' => ['required', 'string', 'max:100'],
            'kategori' => ['required', 'string', 'max:60'],
            'tanggalPinjam' => ['required', 'date'],
            'tanggalKembaliRencana' => ['required', 'date'],
            'tanggalKembaliAktual' => ['nullable', 'date'],
            'status' => ['required', 'string', Rule::in(array_keys(self::STATUS_MAP))],
            'petugas' => ['required', 'string', 'max:100'],
            'keperluan' => ['required', 'string', 'max:255'],
            'biaya' => ['nullable', 'integer', 'min:0'],
            'catatan' => ['nullable', 'string', 'max:255'],
        ]);

        $gambar = $this->resolveGambarPath($request, folder: 'borrowings');

        $kode = isset($data['kode']) && $data['kode'] !== '' ? $data['kode'] : null;
        if (!$kode) {
            $kode = $this->generateKode($data['tanggalPinjam']);
        }

        $payload = [
            'peminjam_id' => $data['peminjamId'] ?? null,
            'nama_peminjam' => trim((string) ($data['namaPeminjam'] ?? 'Peminjam')) ?: 'Peminjam',
            'divisi' => trim((string) ($data['divisi'] ?? 'Internal')) ?: 'Internal',
            'alat_id' => null,
            'nama_alat' => $data['namaAlat'],
            'kategori' => $data['kategori'],
            'petugas_id' => null,
            'petugas_nama' => $data['petugas'],
            'keperluan' => $data['keperluan'],
            'tgl_pinjam' => $data['tanggalPinjam'],
            'tgl_kembali_rencana' => $data['tanggalKembaliRencana'],
            'tgl_kembali_aktual' => $data['tanggalKembaliAktual'] ?? null,
            'status' => self::STATUS_MAP[$data['status']],
            'biaya' => (int) ($data['biaya'] ?? 0),
            'catatan' => $data['catatan'] ?? null,
            'gambar' => $gambar,
        ];

        $borrowing = null;
        for ($attempt = 0; $attempt < 5; $attempt++) {
            try {
                $borrowing = Borrowing::create(array_merge($payload, ['kode' => $kode]));
                break;
            } catch (QueryException $exception) {
                $isDuplicate = $exception->getCode() === '23000';

                if (!$isDuplicate || isset($data['kode'])) {
                    throw $exception;
                }

                $kode = $this->incrementKodeSuffix($kode);
            }
        }

        if (!$borrowing) {
            abort(500, 'Gagal membuat kode peminjaman. Silakan coba lagi.');
        }

        return response()->json($this->toDto($borrowing), 201);
    }

    public function update(Request $request, Borrowing $borrowing)
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:20', Rule::unique('borrowings', 'kode')->ignore($borrowing->id)],
            'peminjamId' => ['nullable', 'integer', 'exists:users,id'],
            'namaPeminjam' => ['nullable', 'string', 'max:100'],
            'divisi' => ['nullable', 'string', 'max:60'],
            'namaAlat' => ['required', 'string', 'max:100'],
            'kategori' => ['required', 'string', 'max:60'],
            'tanggalPinjam' => ['required', 'date'],
            'tanggalKembaliRencana' => ['required', 'date'],
            'tanggalKembaliAktual' => ['nullable', 'date'],
            'status' => ['required', 'string', Rule::in(array_keys(self::STATUS_MAP))],
            'petugas' => ['required', 'string', 'max:100'],
            'keperluan' => ['required', 'string', 'max:255'],
            'biaya' => ['nullable', 'integer', 'min:0'],
            'catatan' => ['nullable', 'string', 'max:255'],
        ]);

        $updatePayload = [
            'kode' => $data['kode'],
            'peminjam_id' => $data['peminjamId'] ?? $borrowing->peminjam_id,
            'nama_peminjam' => trim((string) ($data['namaPeminjam'] ?? 'Peminjam')) ?: 'Peminjam',
            'divisi' => trim((string) ($data['divisi'] ?? 'Internal')) ?: 'Internal',
            'nama_alat' => $data['namaAlat'],
            'kategori' => $data['kategori'],
            'petugas_nama' => $data['petugas'],
            'keperluan' => $data['keperluan'],
            'tgl_pinjam' => $data['tanggalPinjam'],
            'tgl_kembali_rencana' => $data['tanggalKembaliRencana'],
            'tgl_kembali_aktual' => $data['tanggalKembaliAktual'] ?? null,
            'status' => self::STATUS_MAP[$data['status']],
            'biaya' => (int) ($data['biaya'] ?? 0),
            'catatan' => $data['catatan'] ?? null,
        ];

        if ($request->hasFile('gambar') || $request->has('gambar')) {
            $updatePayload['gambar'] = $this->resolveGambarPath($request, folder: 'borrowings');
        }

        $borrowing->update($updatePayload);

        return $this->toDto($borrowing->fresh());
    }

    public function destroy(Borrowing $borrowing)
    {
        $borrowing->delete();
        return response()->noContent();
    }

    private function toDto(Borrowing $borrowing): array
    {
        $statusLabel = array_search((int) $borrowing->status, self::STATUS_MAP, true) ?: 'Pending';

        return [
            'id' => $borrowing->id,
            'kode' => $borrowing->kode,
            'peminjamId' => $borrowing->peminjam_id,
            'namaPeminjam' => $borrowing->nama_peminjam,
            'divisi' => $borrowing->divisi,
            'namaAlat' => $borrowing->nama_alat,
            'kategori' => $borrowing->kategori,
            'tanggalPinjam' => $borrowing->tgl_pinjam?->format('Y-m-d'),
            'tanggalKembaliRencana' => $borrowing->tgl_kembali_rencana?->format('Y-m-d'),
            'tanggalKembaliAktual' => $borrowing->tgl_kembali_aktual?->format('Y-m-d'),
            'status' => $statusLabel,
            'petugas' => $borrowing->petugas_nama,
            'keperluan' => $borrowing->keperluan,
            'biaya' => (int) $borrowing->biaya,
            'catatan' => $borrowing->catatan,
            'gambar' => $borrowing->gambar,
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

    private function generateKode(string $tanggalPinjam): string
    {
        $normalizedDate = str_replace('-', '', $tanggalPinjam);
        $prefix = "PMJ-{$normalizedDate}-";

        $maxSeq = Borrowing::query()
            ->where('kode', 'like', $prefix . '%')
            ->selectRaw('MAX(CAST(SUBSTRING(kode, -3) AS UNSIGNED)) as max_seq')
            ->value('max_seq');

        $next = ((int) $maxSeq) + 1;
        $suffix = str_pad((string) $next, 3, '0', STR_PAD_LEFT);

        return $prefix . $suffix;
    }

    private function incrementKodeSuffix(string $kode): string
    {
        $parts = explode('-', $kode);
        $last = array_pop($parts);
        $number = (int) $last;
        $number++;
        $parts[] = str_pad((string) $number, 3, '0', STR_PAD_LEFT);

        return implode('-', $parts);
    }
}
