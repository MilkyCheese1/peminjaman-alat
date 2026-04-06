<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipment';
    protected $primaryKey = 'id_equipment';
    public $timestamps = true;

    protected $fillable = [
        'id_category',
        'name',
        'description',
        'quantity',
        'condition',
        'photo',
        'is_available',
        'fine_per_day',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'fine_per_day' => 'decimal:2',
    ];

    /**
     * Get the category this equipment belongs to
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category', 'id_category');
    }

    /**
     * Get all borrowings for this equipment
     */
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class, 'id_equipment', 'id_equipment');
    }

    /**
     * Check if equipment is available
     */
    public function getAvailableQuantity()
    {
        $borrowed = $this->borrowings()
            ->whereIn('status', ['approved', 'picked_up', 'ready_for_pickup'])
            ->sum('quantity');
        
        return $this->quantity - $borrowed;
    }
}

