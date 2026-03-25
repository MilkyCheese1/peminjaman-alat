<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to modify status column (can't drop and recreate in Laravel easily)
        DB::statement("ALTER TABLE peminjaman 
            MODIFY status ENUM('pending', 'booked', 'in_use', 'returned', 'rejected', 'maintenance') 
            NOT NULL DEFAULT 'pending' AFTER tgl_kembali,
            ADD buffer_checked BOOLEAN DEFAULT false AFTER denda,
            ADD actual_return_date TIMESTAMP NULL AFTER buffer_checked");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['buffer_checked', 'actual_return_date']);
            $table->dropColumn('status');
            $table->enum('status', ['pending', 'disetujui', 'dikembalikan'])->default('pending')->after('tgl_kembali');
        });
    }
};
