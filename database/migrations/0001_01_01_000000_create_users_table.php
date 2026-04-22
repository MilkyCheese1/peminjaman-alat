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
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nama', 100);
                $table->string('email', 120)->unique();
                $table->string('password_hash', 255);
                $table->unsignedTinyInteger('role')->comment('1=Admin,2=Owner,3=Staff,4=Peminjam');
                $table->unsignedTinyInteger('status')->default(1)->comment('1=Aktif,2=Nonaktif,3=Ditangguhkan');
                $table->string('telepon', 20);
                $table->string('gambar', 255)->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

                $table->index(['role', 'status'], 'idx_users_role_status');
                $table->index('nama', 'idx_users_name');
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
