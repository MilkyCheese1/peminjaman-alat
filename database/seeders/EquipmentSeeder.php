<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('equipment')->insertOrIgnore([
            [
                'id_category' => 1,
                'name' => 'Laptop Dell XPS 15',
                'description' => 'Laptop gaming high-performance Intel i7, RAM 16GB, SSD 512GB',
                'quantity' => 5,
                'condition' => 'good',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 1,
                'name' => 'Kamera DSLR Canon 5D Mark IV',
                'description' => 'Kamera profesional dengan resolution 30MP, video 4K',
                'quantity' => 3,
                'condition' => 'good',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 2,
                'name' => 'Proyektor 4K Epson',
                'description' => 'Proyektor HD 4K untuk presentasi profesional, brightness 3500 lumens',
                'quantity' => 4,
                'condition' => 'good',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 2,
                'name' => 'Microphone Studio Rode NT1',
                'description' => 'Microphone studio condenser dengan pop filter dan shock mount',
                'quantity' => 6,
                'condition' => 'good',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 3,
                'name' => 'Monitor 4K LG 27"',
                'description' => 'Monitor ultra HD IPS panel, 99% sRGB color accuracy',
                'quantity' => 8,
                'condition' => 'good',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 3,
                'name' => 'Meja Kerja Ergonomis',
                'description' => 'Meja dapat diatur ketinggian untuk standing desk, kapasitas 50kg',
                'quantity' => 10,
                'condition' => 'good',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 4,
                'name' => 'Ring Light Professional',
                'description' => 'Ring light LED 18" untuk fotografi dan streaming, dengan phone holder',
                'quantity' => 7,
                'condition' => 'good',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 4,
                'name' => 'Portable Sound System JBL',
                'description' => 'Speaker portabel Bluetooth dengan bass boost, battery 12 jam',
                'quantity' => 9,
                'condition' => 'good',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

