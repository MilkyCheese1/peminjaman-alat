<?php

namespace App\Services;

use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Str;

class VerificationCodeService
{
    /**
     * Panjang kode verifikasi
     */
    private const CODE_LENGTH = 12;

    /**
     * Durasi ekspirasi kode (dalam jam)
     */
    private const EXPIRY_HOURS = 1;

    /**
     * Generate kode verifikasi unik (huruf + angka)
     * Format: ABC123DEF456
     */
    public function generateCode(): string
    {
        do {
            // Generate random string: huruf uppercase + angka
            $code = strtoupper(Str::random(CODE_LENGTH / 2));
            $numbers = rand(100000, 999999);
            $code = substr_replace($code, $numbers, CODE_LENGTH / 2, 0);
            
            // Ensure unique
        } while (Peminjaman::where('kode_verifikasi', $code)->exists());

        return $code;
    }

    /**
     * Buat kode verifikasi untuk peminjaman
     */
    public function createVerificationCode(Peminjaman $peminjaman): Peminjaman
    {
        $kode = $this->generateCode();
        $now = Carbon::now();
        $expired_at = $now->copy()->addHours(self::EXPIRY_HOURS);

        $peminjaman->update([
            'kode_verifikasi' => $kode,
            'kode_dibuat_at' => $now,
            'kode_expired_at' => $expired_at,
            'kode_regenerasi_count' => 0,
        ]);

        return $peminjaman->fresh();
    }

    /**
     * Regenerate kode verifikasi (reset timer ekspirasi)
     */
    public function regenerateCode(Peminjaman $peminjaman): Peminjaman
    {
        $kode = $this->generateCode();
        $now = Carbon::now();
        $expired_at = $now->copy()->addHours(self::EXPIRY_HOURS);

        $peminjaman->update([
            'kode_verifikasi' => $kode,
            'kode_dibuat_at' => $now,
            'kode_expired_at' => $expired_at,
            'kode_regenerasi_count' => $peminjaman->kode_regenerasi_count + 1,
        ]);

        return $peminjaman->fresh();
    }

    /**
     * Check apakah kode sudah expired
     */
    public function isExpired(Peminjaman $peminjaman): bool
    {
        if (!$peminjaman->kode_expired_at) {
            return true;
        }
        return now()->isAfter($peminjaman->kode_expired_at);
    }

    /**
     * Verify kode dan update status peminjaman ke 'sedang dipinjam'
     */
    public function verifyCode(Peminjaman $peminjaman, string $inputCode): bool
    {
        // Samakan kode input dengan kode yang tersimpan
        if ($peminjaman->kode_verifikasi !== $inputCode) {
            return false;
        }

        // Check apakah masih berlaku (belum expired)
        if ($this->isExpired($peminjaman)) {
            return false;
        }

        // Update status ke 'sedang dipinjam' dan catat waktu verifikasi
        $peminjaman->update([
            'status' => 'in_use',
            'kode_diverifikasi_at' => Carbon::now(),
        ]);

        return true;
    }

    /**
     * Format data peminjaman menjadi struktur struk
     */
    public function formatStruk(Peminjaman $peminjaman): array
    {
        $user = $peminjaman->user;
        $alat = $peminjaman->alat;

        return [
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'nomor_struk' => 'STR-' . str_pad($peminjaman->id_peminjaman, 6, '0', STR_PAD_LEFT),
            'kode_verifikasi' => $peminjaman->kode_verifikasi,
            'kode_expired_at' => $peminjaman->kode_expired_at?->format('Y-m-d H:i'),
            'status_kode' => $this->isExpired($peminjaman) ? 'EXPIRED' : 'VALID',
            
            // Informasi Pemesan
            'pemesan' => [
                'nama' => $user->nama ?? $user->name,
                'email' => $user->email,
                'telepon' => $user->telepon ?? '-',
                'alamat' => $user->alamat ?? '-',
            ],

            // Informasi Alat
            'alat' => [
                'nama_alat' => $alat->nama_alat,
                'kategori' => $alat->kategori->nama_kategori ?? '-',
                'kondisi' => $alat->status_alat,
            ],

            // Informasi Peminjaman
            'peminjaman' => [
                'tgl_peminjaman' => $peminjaman->tgl_peminjaman->format('d-m-Y'),
                'tgl_kembali' => $peminjaman->tgl_kembali->format('d-m-Y'),
                'durasi_hari' => $peminjaman->tgl_peminjaman->diffInDays($peminjaman->tgl_kembali),
                'status' => $this->getStatusLabel($peminjaman->status),
                'tanpa_biaya' => 'Ya',
                'catatan' => 'Sistem peminjaman kelompok (kantor/sekolah)',
            ],

            // Timeline
            'created_at' => $peminjaman->created_at?->format('d-m-Y H:i'),
            'verified_at' => $peminjaman->kode_diverifikasi_at?->format('d-m-Y H:i'),
        ];
    }

    /**
     * Get human-readable status label
     */
    private function getStatusLabel(string $status): string
    {
        $labels = [
            'pending' => 'Menunggu Persetujuan',
            'booked' => 'Sudah Dipesan',
            'in_use' => 'Sedang Dipinjam',
            'returned' => 'Sudah Dikembalikan',
            'rejected' => 'Ditolak',
            'maintenance' => 'Maintenance',
        ];

        return $labels[$status] ?? $status;
    }

    /**
     * Generate simple HTML struk untuk display
     */
    public function generateStrekHTML(Peminjaman $peminjaman): string
    {
        $data = $this->formatStruk($peminjaman);
        $statusClass = $data['status_kode'] === 'VALID' ? 'valid' : 'expired';
        
        $html = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Peminjaman #{$data['nomor_struk']}</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 0 auto; padding: 20px; }
        .struk { border: 2px solid #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { margin: 5px 0; }
        .section { margin: 15px 0; }
        .section-title { font-weight: bold; border-bottom: 1px dotted #333; padding-bottom: 5px; margin-bottom: 10px; }
        .row { display: flex; justify-content: space-between; padding: 3px 0; }
        .label { font-weight: bold; }
        .kode-box { 
            border: 3px solid #000; 
            padding: 15px; 
            text-align: center; 
            margin: 20px 0; 
            background: #f9f9f9;
        }
        .kode-box .label { display: block; margin-bottom: 5px; }
        .kode-box .kode { font-size: 28px; font-weight: bold; letter-spacing: 3px; font-family: monospace; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; }
        .status { 
            padding: 5px 10px; 
            border-radius: 3px; 
            font-weight: bold;
            display: inline-block;
        }
        .status.valid { background: #00b894; color: white; }
        .status.expired { background: #d63031; color: white; }
    </style>
</head>
<body>
    <div class="struk">
        <div class="header">
            <h2>STRUK PEMINJAMAN ALAT</h2>
            <p>No. Struk: {$data['nomor_struk']}</p>
            <p>Dibuat: {$data['created_at']}</p>
        </div>

        <div class="kode-box">
            <div class="label">KODE VERIFIKASI</div>
            <div class="kode">{$data['kode_verifikasi']}</div>
            <div style="margin-top: 10px;">
                <span class="status {$statusClass}">
                    {$data['status_kode']}
                </span>
            </div>
            <p style="margin: 5px 0 0 0; font-size: 12px;">Berlaku hingga: {$data['kode_expired_at']}</p>
        </div>

        <div class="section">
            <div class="section-title">INFORMASI PEMESAN</div>
            <div class="row">
                <span class="label">Nama:</span>
                <span>{$data['pemesan']['nama']}</span>
            </div>
            <div class="row">
                <span class="label">Email:</span>
                <span>{$data['pemesan']['email']}</span>
            </div>
            <div class="row">
                <span class="label">Telepon:</span>
                <span>{$data['pemesan']['telepon']}</span>
            </div>
        </div>

        <div class="section">
            <div class="section-title">INFORMASI ALAT</div>
            <div class="row">
                <span class="label">Nama Alat:</span>
                <span>{$data['alat']['nama_alat']}</span>
            </div>
            <div class="row">
                <span class="label">Kategori:</span>
                <span>{$data['alat']['kategori']}</span>
            </div>
        </div>

        <div class="section">
            <div class="section-title">DETAIL PEMINJAMAN</div>
            <div class="row">
                <span class="label">Tgl Peminjaman:</span>
                <span>{$data['peminjaman']['tgl_peminjaman']}</span>
            </div>
            <div class="row">
                <span class="label">Tgl Kembali:</span>
                <span>{$data['peminjaman']['tgl_kembali']}</span>
            </div>
            <div class="row">
                <span class="label">Durasi:</span>
                <span>{$data['peminjaman']['durasi_hari']} hari</span>
            </div>
            <div class="row">
                <span class="label">Status:</span>
                <span>{$data['peminjaman']['status']}</span>
            </div>
        </div>

        <div class="footer">
            <p>Tunjukkan kode verifikasi ini kepada petugas saat penjemputan alat</p>
            <p>Kode berlaku selama 1 jam sejak pemesanan disetujui</p>
            <p style="margin-top: 20px;">© Sistem Peminjaman Alat</p>
        </div>
    </div>
</body>
</html>
HTML;

        return $html;
    }
}
