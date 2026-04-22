<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'kode',
    'peminjam_id',
    'nama_peminjam',
    'divisi',
    'alat_id',
    'alat_harga_asli',
    'nama_alat',
    'kategori',
    'petugas_id',
    'petugas_nama',
    'keperluan',
    'tgl_pinjam',
    'tgl_kembali_rencana',
    'tgl_kembali_aktual',
    'status',
    'biaya',
    'denda_kerusakan',
    'denda_kehilangan',
    'denda_keterlambatan',
    'status_pengembalian',
    'kondisi_pengembalian',
    'laporan_peminjam',
    'laporan_staff',
    'catatan',
    'gambar',
    'bukti_pengambilan',
    'bukti_pengembalian',
])]
class Borrowing extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'tgl_pinjam' => 'date',
            'tgl_kembali_rencana' => 'date',
            'tgl_kembali_aktual' => 'date',
            'alat_harga_asli' => 'integer',
            'biaya' => 'integer',
            'denda_kerusakan' => 'integer',
            'denda_kehilangan' => 'integer',
            'denda_keterlambatan' => 'integer',
        ];
    }

    public function peminjam(): BelongsTo
    {
        return $this->belongsTo(User::class, 'peminjam_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function alat(): BelongsTo
    {
        return $this->belongsTo(Tool::class, 'alat_id');
    }
}
