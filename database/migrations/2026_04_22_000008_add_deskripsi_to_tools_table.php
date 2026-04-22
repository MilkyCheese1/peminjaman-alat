<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tools')) {
            return;
        }

        Schema::table('tools', function (Blueprint $table) {
            if (!Schema::hasColumn('tools', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('nama_alat');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('tools')) {
            return;
        }

        Schema::table('tools', function (Blueprint $table) {
            if (Schema::hasColumn('tools', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
        });
    }
};
