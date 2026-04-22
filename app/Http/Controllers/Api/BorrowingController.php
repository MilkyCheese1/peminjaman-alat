<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Services\BorrowingFineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BorrowingController extends Controller
{
    public function __construct(private readonly BorrowingFineService $fineService)
    {
    }

    private const STATUS_MAP = [
        'Pending' => 1,
        'Disetujui' => 2,
        'Ditolak' => 3,
        'Dipinjam' => 4,
        'Dikembalikan' => 5,
        'Selesai' => 6,
    ];

    public function index(Request $request)
    {
        $query = Borrowing::query()
            ->orderByDesc('id')
            ->with(['peminjam', 'alat']);

        $peminjamId = $request->integer('peminjamId');
        if ($peminjamId > 0) {
            $query->where('peminjam_id', $peminjamId);
        }

        $peminjamNama = trim((string) $request->input('peminjamNama', ''));
        if ($peminjamNama !== '') {
            $query->whereRaw('LOWER(nama_peminjam) = ?', [Str::lower($peminjamNama)]);
        }

        $peminjamEmail = trim((string) $request->input('peminjamEmail', ''));
        if ($peminjamEmail !== '') {
            $query->whereHas('peminjam', fn ($userQuery) => $userQuery->whereRaw('LOWER(email) = ?', [Str::lower($peminjamEmail)]));
        }

        return $query
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
            'alatId' => ['nullable', 'integer', 'exists:tools,id'],
            'alatHargaAsli' => ['nullable', 'integer', 'min:0'],
            'namaAlat' => ['required', 'string', 'max:100'],
            'kategori' => ['required', 'string', 'max:60'],
            'tanggalPinjam' => ['required', 'date'],
            'tanggalKembaliRencana' => ['required', 'date'],
            'tanggalKembaliAktual' => ['nullable', 'date'],
            'status' => ['required', 'string', Rule::in(array_keys(self::STATUS_MAP))],
            'petugas' => ['required', 'string', 'max:100'],
            'keperluan' => ['required', 'string', 'max:255'],
            'biaya' => ['nullable', 'integer', 'min:0'],
            'statusPengembalian' => ['nullable', 'string', Rule::in(['Belum Dikembalikan', 'Dikembalikan'])],
            'kondisiPengembalian' => ['nullable', 'string', Rule::in(['Normal', 'Rusak', 'Hilang'])],
            'laporanPeminjam' => ['nullable', 'string', 'max:1000'],
            'laporanStaff' => ['nullable', 'string', 'max:1000'],
            'dendaKerusakan' => ['nullable', 'integer', 'min:0'],
            'dendaKehilangan' => ['nullable', 'integer', 'min:0'],
            'dendaKeterlambatan' => ['nullable', 'integer', 'min:0'],
            'catatan' => ['nullable', 'string', 'max:255'],
            'buktiPengambilan' => ['nullable'],
            'buktiPengembalian' => ['nullable'],
        ]);

        $buktiPengambilan = $this->resolveEvidencePath($request, folder: 'borrowings', inputNames: ['buktiPengambilan', 'gambar']);
        $buktiPengembalian = $this->resolveEvidencePath($request, folder: 'borrowings', inputNames: ['buktiPengembalian']);
        $alatHargaAsli = $this->fineService->resolveToolPriceByInput($data['alatId'] ?? null, $data['alatHargaAsli'] ?? null);
        $effectiveReturnStatus = $data['statusPengembalian'] ?? (
            in_array($data['status'], ['Dikembalikan', 'Selesai'], true) ? 'Dikembalikan' : null
        );

        $kode = isset($data['kode']) && $data['kode'] !== '' ? $data['kode'] : null;
        if (!$kode) {
            $kode = $this->generateKode($data['tanggalPinjam']);
        }

        $fineBreakdown = $this->fineService->calculateFineBreakdown(
            price: $alatHargaAsli,
            dueDate: $data['tanggalKembaliRencana'],
            actualDate: $data['tanggalKembaliAktual'] ?? null,
            statusPengembalian: $effectiveReturnStatus,
            kondisiPengembalian: $data['kondisiPengembalian'] ?? null,
        );

        $payload = [
            'peminjam_id' => $data['peminjamId'] ?? null,
            'nama_peminjam' => trim((string) ($data['namaPeminjam'] ?? 'Peminjam')) ?: 'Peminjam',
            'divisi' => trim((string) ($data['divisi'] ?? 'Internal')) ?: 'Internal',
            'alat_id' => $data['alatId'] ?? null,
            'alat_harga_asli' => $alatHargaAsli,
            'nama_alat' => $data['namaAlat'],
            'kategori' => $data['kategori'],
            'petugas_id' => null,
            'petugas_nama' => $data['petugas'],
            'keperluan' => $data['keperluan'],
            'tgl_pinjam' => $data['tanggalPinjam'],
            'tgl_kembali_rencana' => $data['tanggalKembaliRencana'],
            'tgl_kembali_aktual' => $data['tanggalKembaliAktual'] ?? null,
            'status' => self::STATUS_MAP[$data['status']],
            'status_pengembalian' => $effectiveReturnStatus,
            'kondisi_pengembalian' => $data['kondisiPengembalian'] ?? null,
            'laporan_peminjam' => $data['laporanPeminjam'] ?? null,
            'laporan_staff' => $data['laporanStaff'] ?? null,
            'denda_kerusakan' => $fineBreakdown['kerusakan'],
            'denda_kehilangan' => $fineBreakdown['kehilangan'],
            'denda_keterlambatan' => $fineBreakdown['keterlambatan'],
            'biaya' => $this->fineService->resolveTotalFine($data, $fineBreakdown),
            'catatan' => $data['catatan'] ?? null,
            'gambar' => $buktiPengambilan,
            'bukti_pengambilan' => $buktiPengambilan,
            'bukti_pengembalian' => $buktiPengembalian,
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
            'alatId' => ['nullable', 'integer', 'exists:tools,id'],
            'alatHargaAsli' => ['nullable', 'integer', 'min:0'],
            'namaAlat' => ['required', 'string', 'max:100'],
            'kategori' => ['required', 'string', 'max:60'],
            'tanggalPinjam' => ['required', 'date'],
            'tanggalKembaliRencana' => ['required', 'date'],
            'tanggalKembaliAktual' => ['nullable', 'date'],
            'status' => ['required', 'string', Rule::in(array_keys(self::STATUS_MAP))],
            'petugas' => ['required', 'string', 'max:100'],
            'keperluan' => ['required', 'string', 'max:255'],
            'biaya' => ['nullable', 'integer', 'min:0'],
            'statusPengembalian' => ['nullable', 'string', Rule::in(['Belum Dikembalikan', 'Dikembalikan'])],
            'kondisiPengembalian' => ['nullable', 'string', Rule::in(['Normal', 'Rusak', 'Hilang'])],
            'laporanPeminjam' => ['nullable', 'string', 'max:1000'],
            'laporanStaff' => ['nullable', 'string', 'max:1000'],
            'dendaKerusakan' => ['nullable', 'integer', 'min:0'],
            'dendaKehilangan' => ['nullable', 'integer', 'min:0'],
            'dendaKeterlambatan' => ['nullable', 'integer', 'min:0'],
            'catatan' => ['nullable', 'string', 'max:255'],
            'buktiPengambilan' => ['nullable'],
            'buktiPengembalian' => ['nullable'],
        ]);

        if (trim((string) $data['kode']) !== trim((string) $borrowing->kode)) {
            abort(422, 'Kode transaksi harus sama dengan transaksi yang sedang diproses.');
        }

        $alatHargaAsli = $this->fineService->resolveToolPriceByInput($data['alatId'] ?? $borrowing->alat_id, $data['alatHargaAsli'] ?? $borrowing->alat_harga_asli);
        $effectiveReturnStatus = $data['statusPengembalian'] ?? (
            in_array($data['status'], ['Dikembalikan', 'Selesai'], true) ? 'Dikembalikan' : $borrowing->status_pengembalian
        );
        $fineBreakdown = $this->fineService->calculateFineBreakdown(
            price: $alatHargaAsli,
            dueDate: $data['tanggalKembaliRencana'],
            actualDate: $data['tanggalKembaliAktual'] ?? null,
            statusPengembalian: $effectiveReturnStatus,
            kondisiPengembalian: $data['kondisiPengembalian'] ?? $borrowing->kondisi_pengembalian,
        );

        $updatePayload = [
            'kode' => $data['kode'],
            'peminjam_id' => $data['peminjamId'] ?? $borrowing->peminjam_id,
            'nama_peminjam' => trim((string) ($data['namaPeminjam'] ?? 'Peminjam')) ?: 'Peminjam',
            'divisi' => trim((string) ($data['divisi'] ?? 'Internal')) ?: 'Internal',
            'alat_id' => $data['alatId'] ?? $borrowing->alat_id,
            'alat_harga_asli' => $alatHargaAsli,
            'nama_alat' => $data['namaAlat'],
            'kategori' => $data['kategori'],
            'petugas_nama' => $data['petugas'],
            'keperluan' => $data['keperluan'],
            'tgl_pinjam' => $data['tanggalPinjam'],
            'tgl_kembali_rencana' => $data['tanggalKembaliRencana'],
            'tgl_kembali_aktual' => $data['tanggalKembaliAktual'] ?? null,
            'status' => self::STATUS_MAP[$data['status']],
            'status_pengembalian' => $effectiveReturnStatus,
            'kondisi_pengembalian' => $data['kondisiPengembalian'] ?? $borrowing->kondisi_pengembalian,
            'laporan_peminjam' => $data['laporanPeminjam'] ?? $borrowing->laporan_peminjam,
            'laporan_staff' => $data['laporanStaff'] ?? $borrowing->laporan_staff,
            'denda_kerusakan' => $data['dendaKerusakan'] ?? $fineBreakdown['kerusakan'],
            'denda_kehilangan' => $data['dendaKehilangan'] ?? $fineBreakdown['kehilangan'],
            'denda_keterlambatan' => $data['dendaKeterlambatan'] ?? $fineBreakdown['keterlambatan'],
            'biaya' => $this->fineService->resolveTotalFine($data, $fineBreakdown),
            'catatan' => $data['catatan'] ?? null,
        ];

        if ($request->hasFile('buktiPengambilan') || $request->has('buktiPengambilan') || $request->hasFile('gambar') || $request->has('gambar')) {
            $buktiPengambilan = $this->resolveEvidencePath($request, folder: 'borrowings', inputNames: ['buktiPengambilan', 'gambar']);
            $updatePayload['gambar'] = $buktiPengambilan;
            $updatePayload['bukti_pengambilan'] = $buktiPengambilan;
        }

        if ($request->hasFile('buktiPengembalian') || $request->has('buktiPengembalian')) {
            $updatePayload['bukti_pengembalian'] = $this->resolveEvidencePath($request, folder: 'borrowings', inputNames: ['buktiPengembalian']);
        }

        $borrowing->update($updatePayload);

        return $this->toDto($borrowing->fresh());
    }

    public function confirmReturn(Request $request, Borrowing $borrowing)
    {
        $data = $request->validate([
            'statusPengembalian' => ['required', 'string', Rule::in(['Belum Dikembalikan', 'Dikembalikan'])],
            'kondisiPengembalian' => ['nullable', 'string', Rule::in(['Normal', 'Rusak', 'Hilang'])],
            'tanggalKembaliAktual' => ['nullable', 'date'],
            'laporanPeminjam' => ['nullable', 'string', 'max:1000'],
            'laporanStaff' => ['nullable', 'string', 'max:1000'],
            'catatan' => ['nullable', 'string', 'max:255'],
            'hapusBuktiPengembalian' => ['nullable', 'boolean'],
            'buktiPengembalian' => ['nullable'],
        ]);

        $price = $this->fineService->resolveBorrowingPrice($borrowing);
        $actualDate = $data['statusPengembalian'] === 'Dikembalikan'
            ? ($data['tanggalKembaliAktual'] ?? now()->toDateString())
            : null;

        $fineBreakdown = $this->fineService->calculateFineBreakdown(
            price: $price,
            dueDate: (string) $borrowing->tgl_kembali_rencana?->toDateString(),
            actualDate: $actualDate,
            statusPengembalian: $data['statusPengembalian'],
            kondisiPengembalian: $data['kondisiPengembalian'] ?? null,
        );

        $buktiPengembalian = null;
        $hapusBuktiPengembalian = !empty($data['hapusBuktiPengembalian']);
        if (!$hapusBuktiPengembalian && ($request->hasFile('buktiPengembalian') || $request->has('buktiPengembalian'))) {
            $buktiPengembalian = $this->resolveEvidencePath($request, folder: 'borrowings', inputNames: ['buktiPengembalian']);
        }

        $updatePayload = [
            'alat_harga_asli' => $borrowing->alat_harga_asli ?: $price,
            'status_pengembalian' => $data['statusPengembalian'],
            'kondisi_pengembalian' => $data['statusPengembalian'] === 'Dikembalikan'
                ? ($data['kondisiPengembalian'] ?? 'Normal')
                : null,
            'laporan_peminjam' => $data['laporanPeminjam'] ?? $borrowing->laporan_peminjam,
            'laporan_staff' => $data['laporanStaff'] ?? $data['catatan'] ?? $borrowing->laporan_staff,
            'tgl_kembali_aktual' => $actualDate,
            'status' => $data['statusPengembalian'] === 'Dikembalikan'
                ? self::STATUS_MAP['Dikembalikan']
                : self::STATUS_MAP['Dipinjam'],
            'denda_kerusakan' => $fineBreakdown['kerusakan'],
            'denda_kehilangan' => $fineBreakdown['kehilangan'],
            'denda_keterlambatan' => $fineBreakdown['keterlambatan'],
            'biaya' => $fineBreakdown['total'],
            'catatan' => $data['catatan'] ?? $borrowing->catatan,
        ];

        if ($hapusBuktiPengembalian) {
            $updatePayload['bukti_pengembalian'] = null;
        } elseif ($buktiPengembalian !== null) {
            $updatePayload['bukti_pengembalian'] = $buktiPengembalian;
        }

        $borrowing->update($updatePayload);

        return response()->json($this->toDto($borrowing->fresh()));
    }

    public function destroy(Borrowing $borrowing)
    {
        $borrowing->delete();
        return response()->noContent();
    }

    private function toDto(Borrowing $borrowing): array
    {
        $statusLabel = array_search((int) $borrowing->status, self::STATUS_MAP, true) ?: 'Pending';
        $statusPengembalian = $borrowing->status_pengembalian ?: ($statusLabel === 'Dipinjam' ? 'Belum Dikembalikan' : ($borrowing->tgl_kembali_aktual ? 'Dikembalikan' : null));
        $price = $this->fineService->resolveBorrowingPrice($borrowing);
        $finePreview = $this->fineService->calculateFineBreakdown(
            price: $price,
            dueDate: (string) $borrowing->tgl_kembali_rencana?->toDateString(),
            actualDate: $statusPengembalian === 'Dikembalikan'
                ? ($borrowing->tgl_kembali_aktual?->toDateString() ?? now()->toDateString())
                : now()->toDateString(),
            statusPengembalian: $statusPengembalian,
            kondisiPengembalian: $borrowing->kondisi_pengembalian,
        );
        $storedBiaya = (int) $borrowing->biaya;
        $biaya = $storedBiaya > 0
            ? $storedBiaya
            : ($statusPengembalian === 'Belum Dikembalikan' ? $finePreview['keterlambatan'] : 0);

        return [
            'id' => $borrowing->id,
            'kode' => $borrowing->kode,
            'createdAt' => $borrowing->created_at?->format('Y-m-d H:i:s'),
            'updatedAt' => $borrowing->updated_at?->format('Y-m-d H:i:s'),
            'peminjamId' => $borrowing->peminjam_id,
            'namaPeminjam' => $borrowing->nama_peminjam,
            'divisi' => $borrowing->divisi,
            'alatId' => $borrowing->alat_id,
            'alatHargaAsli' => $price,
            'namaAlat' => $borrowing->nama_alat,
            'kategori' => $borrowing->kategori,
            'tanggalPinjam' => $borrowing->tgl_pinjam?->format('Y-m-d'),
            'tanggalKembaliRencana' => $borrowing->tgl_kembali_rencana?->format('Y-m-d'),
            'tanggalKembaliAktual' => $borrowing->tgl_kembali_aktual?->format('Y-m-d'),
            'status' => $statusLabel,
            'petugas' => $borrowing->petugas_nama,
            'keperluan' => $borrowing->keperluan,
            'statusPengembalian' => $statusPengembalian,
            'kondisiPengembalian' => $borrowing->kondisi_pengembalian,
            'laporanPeminjam' => $borrowing->laporan_peminjam,
            'laporanStaff' => $borrowing->laporan_staff,
            'dendaKerusakan' => (int) ($borrowing->denda_kerusakan ?: $finePreview['kerusakan']),
            'dendaKehilangan' => (int) ($borrowing->denda_kehilangan ?: $finePreview['kehilangan']),
            'dendaKeterlambatan' => (int) ($borrowing->denda_keterlambatan ?: $finePreview['keterlambatan']),
            'biaya' => $biaya,
            'catatan' => $borrowing->catatan,
            'gambar' => $borrowing->gambar,
            'buktiPengambilan' => $borrowing->bukti_pengambilan ?: $borrowing->gambar,
            'buktiPengembalian' => $borrowing->bukti_pengembalian,
        ];
    }

    private function resolveEvidencePath(Request $request, string $folder, array $inputNames = ['gambar']): ?string
    {
        foreach ($inputNames as $inputName) {
            if (!$request->hasFile($inputName)) {
                continue;
            }

            $file = $request->file($inputName);

            if (!$file || !$file->isValid()) {
                return null;
            }

            $request->validate([
                $inputName => ['image', 'max:2048'],
            ]);

            $destination = public_path("uploads/{$folder}");
            File::ensureDirectoryExists($destination);

            $ext = $file->getClientOriginalExtension() ?: 'jpg';
            $filename = (string) Str::uuid() . '.' . $ext;
            $file->move($destination, $filename);

            return "/uploads/{$folder}/{$filename}";
        }

        foreach ($inputNames as $inputName) {
            if ($request->has($inputName)) {
                $request->validate([
                    $inputName => ['nullable', 'string', 'max:255'],
                ]);

                return $request->input($inputName);
            }
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
