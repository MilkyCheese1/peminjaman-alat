<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'alat';
    protected $primaryKey = 'id_alat';
    public $timestamps = false;

    protected $fillable = [
        'nama_alat',
        'sku',
        'id_kategori',
        'deskripsi',
        'stok',
        'dipinjam',
        'status_alat',
        'gambar',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Relationship: Alat belongs to Kategori
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Relationship: Alat has many Peminjaman
     */
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'id_alat', 'id_alat');
    }
}
