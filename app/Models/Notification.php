<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'judul', 'pesan', 'tipe', 'is_read'])]
class Notification extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'tipe' => 'integer',
            'is_read' => 'boolean',
            'created_at' => 'datetime',
        ];
    }
}
