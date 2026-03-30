<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Alat;
use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create categories
        $categories = [
            ['nama_kategori' => 'Laptop & Komputer'],
            ['nama_kategori' => 'Peralatan Presentasi'],
            ['nama_kategori' => 'Alat Kebersihan'],
            ['nama_kategori' => 'Peralatan Olahraga'],
            ['nama_kategori' => 'Peralatan Kantor'],
        ];

        foreach ($categories as $category) {
            Kategori::create($category);
        }

        // Create equipment
        $equipments = [
            ['nama_alat' => 'Laptop ASUS VivoBook 14', 'id_kategori' => 1, 'stok' => 10, 'dipinjam' => 3],
            ['nama_alat' => 'Proyektor Epson XGA', 'id_kategori' => 2, 'stok' => 5, 'dipinjam' => 2],
            ['nama_alat' => 'Sapu', 'id_kategori' => 3, 'stok' => 4, 'dipinjam' => 1],
            ['nama_alat' => 'Bola Basket', 'id_kategori' => 4, 'stok' => 2, 'dipinjam' => 1],
            ['nama_alat' => 'Kursi', 'id_kategori' => 5, 'stok' => 8, 'dipinjam' => 4],
        ];

        foreach ($equipments as $equipment) {
            Alat::create($equipment);
        }

        // Create sample users
        $users = [
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'phone' => '081234567890',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'alamat' => 'Jl. Admin No. 1',
                'email_verified' => true,
                'is_active' => true,
            ],
            [
                'username' => 'petugas',
                'email' => 'petugas@example.com',
                'phone' => '082345678901',
                'password' => Hash::make('password123'),
                'role' => 'petugas',
                'alamat' => 'Jl. Petugas No. 2',
                'email_verified' => true,
                'is_active' => true,
            ],
            [
                'username' => 'user1',
                'email' => 'user1@example.com',
                'phone' => '083456789012',
                'password' => Hash::make('password123'),
                'role' => 'peminjam',
                'alamat' => 'Jl. Peminjam No. 3',
                'email_verified' => true,
                'is_active' => true,
            ],
            [
                'username' => 'user2',
                'email' => 'user2@example.com',
                'phone' => '084567890123',
                'password' => Hash::make('password123'),
                'role' => 'peminjam',
                'alamat' => 'Jl. Peminjam No. 4',
                'email_verified' => false,
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        // Create sample borrowings
        $peminjamans = [
            [
                'id_user' => 3,
                'id_alat' => 1,
                'tgl_peminjaman' => '2026-02-01',
                'tgl_kembali' => '2026-02-05',
                'status' => 'returned',
                'denda' => 0,
            ],
            [
                'id_user' => 4,
                'id_alat' => 2,
                'tgl_peminjaman' => '2026-02-10',
                'tgl_kembali' => '2026-02-12',
                'status' => 'returned',
                'denda' => 5000,
            ],
            [
                'id_user' => 3,
                'id_alat' => 5,
                'tgl_peminjaman' => '2026-02-15',
                'tgl_kembali' => '2026-02-20',
                'status' => 'pending',
                'denda' => 0,
            ],
            [
                'id_user' => 4,
                'id_alat' => 3,
                'tgl_peminjaman' => '2026-02-18',
                'tgl_kembali' => '2026-02-22',
                'status' => 'booked',
                'denda' => 0,
            ],
            [
                'id_user' => 3,
                'id_alat' => 4,
                'tgl_peminjaman' => '2026-02-20',
                'tgl_kembali' => '2026-02-25',
                'status' => 'booked',
                'denda' => 0,
            ],
        ];

        foreach ($peminjamans as $peminjaman) {
            Peminjaman::create($peminjaman);
        }

        echo "Database seeded successfully!";
    }
}
