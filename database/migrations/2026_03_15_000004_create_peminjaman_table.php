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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->increments('id_peminjaman');
            $table->unsignedInteger('id_user');
            $table->unsignedInteger('id_alat');
            $table->date('tgl_peminjaman');
            $table->date('tgl_kembali');
            $table->enum('status', ['pending', 'disetujui', 'dikembalikan'])->default('pending');
            $table->decimal('denda', 10, 2)->default(0);

            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_alat')->references('id_alat')->on('alat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
