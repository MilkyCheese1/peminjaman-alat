<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tools')) {
            return;
        }

        Schema::create('tools', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->string('nama_alat', 100);
            $table->unsignedSmallInteger('stok')->default(0);
            $table->unsignedTinyInteger('kondisi')->default(1)->comment('1=Baik,2=PerluKalibrasi,3=RusakRingan,4=RusakBerat');
            $table->unsignedTinyInteger('status')->default(1)->comment('1=Tersedia,2=Dipinjam,3=Maintenance');
            $table->string('lokasi', 80);
            $table->string('gambar', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('category_id', 'idx_tools_category');
            $table->index(['status', 'kondisi'], 'idx_tools_status_kondisi');
            $table->index('nama_alat', 'idx_tools_name');

            $table
                ->foreign('category_id', 'fk_tools_category')
                ->references('id')
                ->on('categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
