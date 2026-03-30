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
        Schema::table('peminjaman', function (Blueprint $table) {
            // Kode verifikasi unik untuk setiap peminjaman
            if (!Schema::hasColumn('peminjaman', 'kode_verifikasi')) {
                $table->string('kode_verifikasi', 50)->nullable()->unique()->after('qr_code');
            }
            
            // Timestamp saat kode dibuat (saat disetujui)
            if (!Schema::hasColumn('peminjaman', 'kode_dibuat_at')) {
                $table->timestamp('kode_dibuat_at')->nullable()->after('kode_verifikasi');
            }
            
            // Timestamp saat kode expired (1 jam setelah dibuat)
            if (!Schema::hasColumn('peminjaman', 'kode_expired_at')) {
                $table->timestamp('kode_expired_at')->nullable()->after('kode_dibuat_at');
            }
            
            // Flag untuk tracking regenerasi dan verifikasi
            if (!Schema::hasColumn('peminjaman', 'kode_regenerasi_count')) {
                $table->integer('kode_regenerasi_count')->default(0)->after('kode_expired_at');
            }
            
            if (!Schema::hasColumn('peminjaman', 'kode_diverifikasi_at')) {
                $table->timestamp('kode_diverifikasi_at')->nullable()->after('kode_regenerasi_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropIndex(['kode_verifikasi']);
            $table->dropIndex(['status']);
            $table->dropColumn([
                'kode_verifikasi',
                'kode_dibuat_at',
                'kode_expired_at',
                'kode_regenerasi_count',
                'kode_diverifikasi_at',
            ]);
        });
    }
};
