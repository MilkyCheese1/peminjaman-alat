<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'aksi', 'entitas', 'entitas_id', 'deskripsi', 'ip_address'])]
class ActivityLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'entitas_id' => 'integer',
            'created_at' => 'datetime',
        ];
    }
}
