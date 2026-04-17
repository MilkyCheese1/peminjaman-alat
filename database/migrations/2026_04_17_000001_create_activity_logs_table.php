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
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id('id_activity');
                $table->unsignedBigInteger('id_user')->nullable();
                $table->string('action', 50); // 'create', 'update', 'delete', 'view', etc
                $table->text('description')->nullable();
                $table->string('model_type', 100)->nullable(); // Model class name
                $table->unsignedBigInteger('model_id')->nullable(); // Record ID
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->ipAddress('ip_address')->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();

                // Indexes
                $table->index('id_user');
                $table->index('action');
                $table->index('model_type');
                $table->index('created_at');

                // Foreign key
                $table->foreign('id_user')
                    ->references('id_user')
                    ->on('users')
                    ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
