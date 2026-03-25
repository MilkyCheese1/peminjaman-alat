<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_alat',
        'tgl_peminjaman',
        'tgl_kembali',
        'status',
        'denda',
        'buffer_checked',
        'actual_return_date',
    ];

    protected $casts = [
        'tgl_peminjaman' => 'date',
        'tgl_kembali' => 'date',
        'denda' => 'decimal:2',
        'buffer_checked' => 'boolean',
        'actual_return_date' => 'datetime',
    ];

    /**
     * Relationship: Peminjaman belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relationship: Peminjaman belongs to Alat
     */
    public function alat()
    {
        return $this->belongsTo(Alat::class, 'id_alat', 'id_alat');
    }
}
