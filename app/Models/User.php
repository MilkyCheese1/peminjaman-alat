<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'nama_lengkap',
        'email',
        'phone',
        'password',
        'role',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'foto',
        'email_verified',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified' => 'boolean',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Relationship: User has many Peminjaman
     */
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'id_user', 'id_user');
    }

    /**
     * Relationship: User has many ActivityLogs
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'id_user', 'id_user');
    }

    /**
     * Relationship: User has many approved peminjamans
     */
    public function approvedPeminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'approved_by', 'id_user');
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if user is owner or admin
     */
    public function isOwnerOrAdmin()
    {
        return $this->role === 'owner' || $this->role === 'admin';
    }

    /**
     * Check if user is owner
     */
    public function isOwner()
    {
        return $this->role === 'owner';
    }
}
