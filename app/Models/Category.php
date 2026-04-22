<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_kategori', 'kode_kategori', 'status', 'deskripsi', 'gambar'])]
class Category extends Model
{
    use HasFactory;

    public function tools(): HasMany
    {
        return $this->hasMany(Tool::class);
    }
}

