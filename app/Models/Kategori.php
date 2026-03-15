<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    public $timestamps = false;

    protected $fillable = [
        'nama_kategori',
    ];

    /**
     * Relationship: Kategori has many Alat
     */
    public function alats()
    {
        return $this->hasMany(Alat::class, 'id_kategori', 'id_kategori');
    }
}
