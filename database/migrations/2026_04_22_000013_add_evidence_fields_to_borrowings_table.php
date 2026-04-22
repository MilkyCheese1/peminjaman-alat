<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('borrowings')) {
            return;
        }

        Schema::table('borrowings', function (Blueprint $table) {
            if (!Schema::hasColumn('borrowings', 'bukti_pengambilan')) {
                $table->string('bukti_pengambilan', 255)->nullable()->after('gambar');
            }

            if (!Schema::hasColumn('borrowings', 'bukti_pengembalian')) {
                $table->string('bukti_pengembalian', 255)->nullable()->after('bukti_pengambilan');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('borrowings')) {
            return;
        }

        Schema::table('borrowings', function (Blueprint $table) {
            if (Schema::hasColumn('borrowings', 'bukti_pengembalian')) {
                $table->dropColumn('bukti_pengembalian');
            }

            if (Schema::hasColumn('borrowings', 'bukti_pengambilan')) {
                $table->dropColumn('bukti_pengambilan');
            }
        });
    }
};
