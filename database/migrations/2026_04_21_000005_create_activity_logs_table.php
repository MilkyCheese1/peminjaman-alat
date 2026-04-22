<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('activity_logs')) {
            return;
        }

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('aksi', 60);
            $table->string('entitas', 30);
            $table->unsignedInteger('entitas_id')->nullable();
            $table->string('deskripsi', 255);
            $table->binary('ip_address', 16)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'created_at'], 'idx_logs_user_time');
            $table->index(['entitas', 'entitas_id'], 'idx_logs_entity');

            $table
                ->foreign('user_id', 'fk_logs_user')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
