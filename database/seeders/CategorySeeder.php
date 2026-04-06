<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insertOrIgnore([
            [
                'name' => 'Elektronik',
                'description' => 'Peralatan elektronik seperti laptop, proyektor, dan kamera',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Audio Visual',
                'description' => 'Peralatan audiovisual untuk presentasi dan recording',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Peralatan Kantor',
                'description' => 'Peralatan umum untuk kantor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Perlengkapan Event',
                'description' => 'Peralatan untuk event dan konferensi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

