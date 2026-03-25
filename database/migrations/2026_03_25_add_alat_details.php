<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('alat', function (Blueprint $table) {
            // Add new columns
            $table->string('sku', 50)->unique()->nullable()->after('nama_alat');
            $table->text('deskripsi')->nullable()->after('sku');
            $table->enum('status_alat', ['tersedia', 'booked', 'in_use', 'maintenance'])->default('tersedia')->after('dipinjam');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alat', function (Blueprint $table) {
            $table->dropColumn(['sku', 'deskripsi', 'status_alat']);
        });
    }
};
