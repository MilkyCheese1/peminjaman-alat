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
        Schema::table('borrowings', function (Blueprint $table) {
            // Add verification code column
            if (!Schema::hasColumn('borrowings', 'kode_verifikasi')) {
                $table->string('kode_verifikasi')->nullable()->unique();
            }
            
            // Add purpose/keperluan column
            if (!Schema::hasColumn('borrowings', 'keperluan')) {
                $table->text('keperluan')->nullable();
            }
            
            // Add duration in hours (for validation)
            if (!Schema::hasColumn('borrowings', 'durasi_jam')) {
                $table->integer('durasi_jam')->default(24);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            if (Schema::hasColumn('borrowings', 'kode_verifikasi')) {
                $table->dropColumn('kode_verifikasi');
            }
            if (Schema::hasColumn('borrowings', 'keperluan')) {
                $table->dropColumn('keperluan');
            }
            if (Schema::hasColumn('borrowings', 'durasi_jam')) {
                $table->dropColumn('durasi_jam');
            }
        });
    }
};
