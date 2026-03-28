<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peminjaman extends Model
{
    use HasFactory, SoftDeletes;

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
        'qr_code',
        'approved_by',
        'status_updated_by',
        'status_updated_at',
    ];

    protected $casts = [
        'tgl_peminjaman' => 'date',
        'tgl_kembali' => 'date',
        'denda' => 'decimal:2',
        'buffer_checked' => 'boolean',
        'actual_return_date' => 'datetime',
        'status_updated_at' => 'datetime',
    ];

    protected $dates = ['deleted_at'];

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

    /**
     * Relationship: Peminjaman belongs to approved user (staff/petugas)
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id_user');
    }

    /**
     * Relationship: Peminjaman belongs to user who updated status
     */
    public function statusUpdatedBy()
    {
        return $this->belongsTo(User::class, 'status_updated_by', 'id_user');
    }
}
