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
        if (!Schema::hasTable('notification_logs')) {
            Schema::create('notification_logs', function (Blueprint $table) {
                $table->id('id_log');
                $table->unsignedBigInteger('id_notification')->nullable();
                $table->unsignedBigInteger('id_user')->nullable();
                $table->enum('action', ['sent', 'read', 'archived', 'deleted', 'replied'])->default('sent');
                $table->json('details')->nullable();
                $table->ipAddress('ip_address')->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();

                // Indexes
                $table->index('id_notification');
                $table->index('id_user');
                $table->index('action');
                $table->index('created_at');

                // Foreign keys
                $table->foreign('id_notification')
                    ->references('id_notification')
                    ->on('notifications')
                    ->onDelete('cascade');

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
        Schema::dropIfExists('notification_logs');
    }
};
