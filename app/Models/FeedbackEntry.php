<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackEntry extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'email',
        'stars',
        'pesan',
        'status',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'stars' => 'integer',
            'status' => 'integer',
            'created_at' => 'datetime',
        ];
    }
}
