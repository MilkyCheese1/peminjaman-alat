<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('borrowings')) {
            return;
        }

        Schema::create('borrowings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 20)->unique('uq_borrowings_kode');
            $table->unsignedInteger('peminjam_id')->nullable();
            $table->string('nama_peminjam', 100);
            $table->string('divisi', 60);
            $table->unsignedInteger('alat_id')->nullable();
            $table->string('nama_alat', 100);
            $table->string('kategori', 60);
            $table->unsignedInteger('petugas_id')->nullable();
            $table->string('petugas_nama', 100);
            $table->string('keperluan', 255);
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali_rencana');
            $table->date('tgl_kembali_aktual')->nullable();
            $table->unsignedTinyInteger('status')->default(1)->comment('1=Pending,2=Disetujui,3=Ditolak,4=Dipinjam,5=Dikembalikan,6=Selesai');
            $table->unsignedInteger('biaya')->default(0);
            $table->string('catatan', 255)->nullable();
            $table->string('gambar', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index(['status', 'tgl_pinjam'], 'idx_borrowings_status_date');
            $table->index('peminjam_id', 'idx_borrowings_peminjam');
            $table->index('alat_id', 'idx_borrowings_alat');
            $table->index('petugas_id', 'idx_borrowings_petugas');
            $table->index(['nama_peminjam', 'nama_alat', 'kategori', 'petugas_nama'], 'idx_borrowings_search');

            $table
                ->foreign('peminjam_id', 'fk_borrowings_peminjam')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table
                ->foreign('alat_id', 'fk_borrowings_alat')
                ->references('id')
                ->on('tools')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table
                ->foreign('petugas_id', 'fk_borrowings_petugas')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
