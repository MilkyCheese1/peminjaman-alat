<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama_lengkap' => 'Admin TrustEquip',
                'email' => 'admin@trustequip.id',
                'phone' => '083456789012',
                'password' => Hash::make('admin123456'),
                'role' => 'admin',
                'alamat' => 'Jl. Admin No. 1',
                'is_active' => 1,
            ]
        );
        User::updateOrCreate(
            ['username' => 'staff'],
            [
                'nama_lengkap' => 'Staff TrustEquip',
                'email' => 'staff@trustequip.id',
                'phone' => '085678901234',
                'password' => Hash::make('staff123456'),
                'role' => 'staff',
                'alamat' => 'Jl. Kerja No. 1',
                'is_active' => 1,
            ]
        );
        User::updateOrCreate(
            ['username' => 'customer'],
            [
                'nama_lengkap' => 'Customer TrustEquip',
                'email' => 'customer@trustequip.id',
                'phone' => '088901234567',
                'password' => Hash::make('customer123'),
                'role' => 'customer',
                'alamat' => 'Jl. Pelajar No. 1',
                'is_active' => 1,
            ]
        );
        User::updateOrCreate(
            ['username' => 'owner'],
            [
                'nama_lengkap' => 'Owner TrustEquip',
                'email' => 'owner@trustequip.id',
                'phone' => '081234567890',
                'password' => Hash::make('owner123456'),
                'role' => 'owner',
                'alamat' => 'Jl. Pendidikan No. 1',
                'is_active' => 1,
            ]
        );
    }
}
