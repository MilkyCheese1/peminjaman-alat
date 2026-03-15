<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    use HasFactory;

    protected $table = 'alat';
    protected $primaryKey = 'id_alat';
    public $timestamps = false;

    protected $fillable = [
        'nama_alat',
        'id_kategori',
        'stok',
        'dipinjam',
    ];

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
