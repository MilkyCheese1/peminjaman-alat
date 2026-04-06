<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowingReturn extends Model
{
    use HasFactory;

    protected $table = 'borrowing_returns';
    protected $primaryKey = 'id_return';
    public $timestamps = true;

    protected $fillable = [
        'id_borrowing',
        'return_date',
        'condition',
        'condition_notes',
        'damage_notes',
        'photo_after',
        'fine_paid',
        'fine_amount',
    ];

    protected $casts = [
        'return_date' => 'datetime',
        'fine_paid' => 'boolean',
        'fine_amount' => 'decimal:2',
    ];

    /**
     * Get the borrowing this return belongs to
     */
    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class, 'id_borrowing', 'id_borrowing');
    }
}

