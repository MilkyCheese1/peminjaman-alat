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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nama_lengkap')->nullable()->after('username');
            $table->string('foto')->nullable()->after('password');
            $table->string('kota')->nullable()->after('alamat');
            $table->string('provinsi')->nullable()->after('kota');
            $table->string('kode_pos', 10)->nullable()->after('provinsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nama_lengkap', 'foto', 'kota', 'provinsi', 'kode_pos']);
        });
    }
};
