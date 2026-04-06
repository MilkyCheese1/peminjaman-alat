<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $table = 'borrowings';
    protected $primaryKey = 'id_borrowing';
    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'id_equipment',
        'quantity',
        'borrow_date',
        'planned_return_date',
        'actual_return_date',
        'status',
        'kode_verifikasi',
        'keperluan',
        'durasi_jam',
        'pickup_code',
        'pickup_code_generated_at',
        'pickup_verified_at',
        'pickup_photo_before',
        'fine_amount',
        'fine_paid',
        'notes',
    ];

    protected $casts = [
        'borrow_date' => 'datetime',
        'planned_return_date' => 'datetime',
        'actual_return_date' => 'datetime',
        'pickup_code_generated_at' => 'datetime',
        'pickup_verified_at' => 'datetime',
        'fine_amount' => 'decimal:2',
        'fine_paid' => 'boolean',
    ];

    /**
     * Get the user who borrowed this
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Get the equipment being borrowed
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'id_equipment', 'id_equipment');
    }

    /**
     * Get the return details for this borrowing
     */
    public function returnDetails()
    {
        return $this->hasOne(BorrowingReturn::class, 'id_borrowing', 'id_borrowing');
    }

    /**
     * Check if borrowing is overdue
     */
    public function isOverdue()
    {
        return $this->status === 'picked_up' && now() > $this->planned_return_date;
    }

    /**
     * Calculate late days
     */
    public function getLateDays()
    {
        if ($this->isOverdue() || $this->status === 'returned' || $this->status === 'overdue') {
            $returnDate = $this->actual_return_date ?? now();
            return max(0, $returnDate->diffInDays($this->planned_return_date));
        }
        return 0;
    }

    /**
     * Calculate fine (Rp 50,000 per day, max 30 days)
     */
    public function calculateFine()
    {
        $lateDays = $this->getLateDays();
        $dailyRate = 50000;
        $maxDays = 30;
        
        $days = min($lateDays, $maxDays);
        return $days * $dailyRate;
    }
}

