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
            if (!Schema::hasColumn('users', 'nik')) {
                $table->string('nik', 50)->nullable()->after('nama');
                $table->index('nik', 'idx_users_nik');
            }

            if (!Schema::hasColumn('users', 'jenis_kelamin')) {
                $table->string('jenis_kelamin', 20)->nullable()->after('telepon');
            }

            if (!Schema::hasColumn('users', 'tempat_lahir')) {
                $table->string('tempat_lahir', 100)->nullable()->after('jenis_kelamin');
            }

            if (!Schema::hasColumn('users', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            }

            if (!Schema::hasColumn('users', 'alamat')) {
                $table->text('alamat')->nullable()->after('tanggal_lahir');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'alamat')) {
                $table->dropColumn('alamat');
            }

            if (Schema::hasColumn('users', 'tanggal_lahir')) {
                $table->dropColumn('tanggal_lahir');
            }

            if (Schema::hasColumn('users', 'tempat_lahir')) {
                $table->dropColumn('tempat_lahir');
            }

            if (Schema::hasColumn('users', 'jenis_kelamin')) {
                $table->dropColumn('jenis_kelamin');
            }

            if (Schema::hasColumn('users', 'nik')) {
                $table->dropIndex('idx_users_nik');
                $table->dropColumn('nik');
            }
        });
    }
};
