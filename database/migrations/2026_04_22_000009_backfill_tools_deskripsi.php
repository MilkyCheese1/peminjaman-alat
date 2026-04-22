<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $items = [
            'Multimeter Digital' => 'Alat ukur listrik untuk pemeriksaan tegangan, arus, dan resistansi.',
            'Oscilloscope 100MHz' => 'Perangkat analisis sinyal untuk pengujian rangkaian elektronik.',
            'Bor Listrik' => 'Bor listrik serbaguna untuk pekerjaan perakitan dan perawatan.',
            'Kunci Torsi 1/2' => 'Kunci torsi presisi untuk pengencangan baut sesuai spesifikasi.',
            'Caliper Digital' => 'Alat ukur digital untuk pengukuran dimensi yang presisi.',
            'Helm Safety' => 'Helm pelindung untuk menunjang keselamatan kerja di lapangan.',
            'Speaker Portable' => 'Speaker portabel untuk kebutuhan audio meeting dan presentasi.',
            'Laptop Kantor' => 'Laptop kerja untuk administrasi, dokumentasi, dan presentasi.',
            'Proyektor HD' => 'Proyektor untuk menampilkan materi rapat dan presentasi.',
            'Router WiFi 6' => 'Router jaringan untuk kebutuhan konektivitas ruang kerja.',
        ];

        foreach ($items as $name => $description) {
            DB::table('tools')
                ->where('nama_alat', $name)
                ->update(['deskripsi' => $description]);
        }
    }

    public function down(): void
    {
        DB::table('tools')->update(['deskripsi' => null]);
    }
};
