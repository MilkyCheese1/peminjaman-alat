<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'id_category';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the equipment in this category
     */
    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'id_category', 'id_category');
    }
}

