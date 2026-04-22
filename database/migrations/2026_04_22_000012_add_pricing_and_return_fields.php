<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tools')) {
            Schema::table('tools', function (Blueprint $table) {
                if (!Schema::hasColumn('tools', 'harga_asli')) {
                    $table->unsignedInteger('harga_asli')->default(0)->after('nama_alat');
                }
            });
        }

        if (Schema::hasTable('borrowings')) {
            Schema::table('borrowings', function (Blueprint $table) {
                if (!Schema::hasColumn('borrowings', 'alat_harga_asli')) {
                    $table->unsignedInteger('alat_harga_asli')->default(0)->after('alat_id');
                }

                if (!Schema::hasColumn('borrowings', 'status_pengembalian')) {
                    $table->string('status_pengembalian', 40)->nullable()->after('tgl_kembali_aktual');
                }

                if (!Schema::hasColumn('borrowings', 'kondisi_pengembalian')) {
                    $table->string('kondisi_pengembalian', 30)->nullable()->after('status_pengembalian');
                }

                if (!Schema::hasColumn('borrowings', 'laporan_peminjam')) {
                    $table->text('laporan_peminjam')->nullable()->after('kondisi_pengembalian');
                }

                if (!Schema::hasColumn('borrowings', 'laporan_staff')) {
                    $table->text('laporan_staff')->nullable()->after('laporan_peminjam');
                }

                if (!Schema::hasColumn('borrowings', 'denda_kerusakan')) {
                    $table->unsignedInteger('denda_kerusakan')->default(0)->after('biaya');
                }

                if (!Schema::hasColumn('borrowings', 'denda_kehilangan')) {
                    $table->unsignedInteger('denda_kehilangan')->default(0)->after('denda_kerusakan');
                }

                if (!Schema::hasColumn('borrowings', 'denda_keterlambatan')) {
                    $table->unsignedInteger('denda_keterlambatan')->default(0)->after('denda_kehilangan');
                }

                $table->index(['status_pengembalian', 'tgl_kembali_rencana'], 'idx_borrowings_return_status');
            });

            $rows = DB::table('borrowings')
                ->leftJoin('tools', 'tools.id', '=', 'borrowings.alat_id')
                ->select('borrowings.id', 'tools.harga_asli as tool_harga_asli')
                ->where(function ($query) {
                    $query->whereNull('borrowings.alat_harga_asli')
                        ->orWhere('borrowings.alat_harga_asli', 0);
                })
                ->get();

            foreach ($rows as $row) {
                DB::table('borrowings')
                    ->where('id', $row->id)
                    ->update([
                        'alat_harga_asli' => (int) ($row->tool_harga_asli ?? 0),
                    ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('borrowings')) {
            Schema::table('borrowings', function (Blueprint $table) {
                if (Schema::hasColumn('borrowings', 'denda_keterlambatan')) {
                    $table->dropIndex('idx_borrowings_return_status');
                    $table->dropColumn('denda_keterlambatan');
                }

                if (Schema::hasColumn('borrowings', 'denda_kehilangan')) {
                    $table->dropColumn('denda_kehilangan');
                }

                if (Schema::hasColumn('borrowings', 'denda_kerusakan')) {
                    $table->dropColumn('denda_kerusakan');
                }

                if (Schema::hasColumn('borrowings', 'laporan_staff')) {
                    $table->dropColumn('laporan_staff');
                }

                if (Schema::hasColumn('borrowings', 'laporan_peminjam')) {
                    $table->dropColumn('laporan_peminjam');
                }

                if (Schema::hasColumn('borrowings', 'kondisi_pengembalian')) {
                    $table->dropColumn('kondisi_pengembalian');
                }

                if (Schema::hasColumn('borrowings', 'status_pengembalian')) {
                    $table->dropColumn('status_pengembalian');
                }

                if (Schema::hasColumn('borrowings', 'alat_harga_asli')) {
                    $table->dropColumn('alat_harga_asli');
                }
            });
        }

        if (Schema::hasTable('tools')) {
            Schema::table('tools', function (Blueprint $table) {
                if (Schema::hasColumn('tools', 'harga_asli')) {
                    $table->dropColumn('harga_asli');
                }
            });
        }
    }
};
