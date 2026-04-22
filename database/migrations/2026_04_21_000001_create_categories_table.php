<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('categories')) {
            return;
        }

        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_kategori', 60);
            $table->string('kode_kategori', 10)->unique('uq_categories_code');
            $table->unsignedTinyInteger('status')->default(1)->comment('1=Aktif,2=Nonaktif');
            $table->string('deskripsi', 255);
            $table->string('gambar', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('status', 'idx_categories_status');
            $table->index('nama_kategori', 'idx_categories_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
