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
        Schema::create('notifications', function (Blueprint $table) {
            // Primary & Foreign Keys
            $table->id('id_notification');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_borrowing')->nullable();

            // Notification Type Classification
            // Approval: borrowing approval/rejection
            // Return: return-related notifications
            // Reminder: reminder notifications
            // System: system announcements
            $table->enum('category', ['approval', 'return', 'reminder', 'system']);
            
            $table->enum('type', [
                // Approval Events
                'borrowing_created',        // Peminjaman baru dibuat
                'borrowing_approved',       // Peminjaman disetujui
                'borrowing_rejected',       // Peminjaman ditolak
                
                // Return Events
                'return_ready',             // Item siap untuk diambil
                'return_submitted',         // Pengembalian diajukan
                'return_verified',          // Pengembalian diverifikasi
                'return_overdue',           // Pengembalian terlambat (overdue)
                'return_not_verified',      // Pengembalian tidak terverifikasi
                
                // Reminder Events
                'return_reminder_1day',     // Pengingat 1 hari sebelum deadline
                'return_reminder_due',      // Pengingat hari deadline
                'return_reminder_overdue',  // Pengingat pengembalian terlambat
                
                // System
                'system_announcement',      // Pengumuman sistem
                'equipment_unavailable',    // Alat tidak tersedia
                'custom'                    // Custom notification
            ]);

            // Notification Details
            $table->string('title', 255);
            $table->text('message');
            $table->string('icon', 10)->default('📬'); // emoji icon
            $table->string('color', 20)->default('info'); // primary, success, warning, danger, info
            
            // Action Link (optional)
            $table->string('action_url')->nullable(); // URL for action button
            $table->string('action_label')->nullable(); // Label untuk button (e.g., "Lihat Detail")

            // Status Fields
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_archived')->default(false);
            $table->timestamp('archived_at')->nullable();
            $table->boolean('is_deleted')->default(false); // soft delete simulation
            $table->timestamp('deleted_at')->nullable();

            // Notification Channels & Status
            // Bisa multi-channel: in_app, email, sms, push
            $table->json('channels')->nullable(); // ['in_app', 'email', 'sms']
            $table->json('channel_status')->nullable(); // {'email': 'sent', 'sms': 'failed'}
            
            // Email Details
            $table->string('email_address')->nullable();
            $table->string('email_status')->default('pending'); // pending, sent, failed, bounced
            $table->text('email_error')->nullable();
            
            // SMS Details
            $table->string('phone_number')->nullable();
            $table->string('sms_status')->default('pending'); // pending, sent, failed, delivered
            $table->text('sms_error')->nullable();
            
            // Push Notification Details
            $table->string('push_status')->default('pending'); // pending, sent, failed
            $table->text('push_error')->nullable();

            // Metadata & Additional Data
            $table->json('metadata')->nullable(); // equipment_name, borrowing_details, etc.
            $table->json('recipient_details')->nullable(); // user info at time of notification

            // Priority & Importance
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            
            // Retention & Expiration
            $table->timestamp('expires_at')->nullable(); // auto-delete after this date
            $table->integer('retention_days')->default(30); // berapa lama disimpan
            
            // Grouping & Threading
            $table->string('notification_group')->nullable(); // untuk grouping notifikasi sejenis
            $table->unsignedBigInteger('parent_notification_id')->nullable(); // untuk nested replies

            // Timestamps
            $table->timestamps();

            // Constraints
            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('id_borrowing')
                ->references('id_borrowing')
                ->on('borrowings')
                ->onDelete('set null');

            // Composite Indexes for efficient queries
            $table->index(['id_user', 'is_read']);          // Get unread for user
            $table->index(['id_user', 'is_archived']);      // Get archived for user
            $table->index(['id_user', 'created_at']);       // Get recent for user
            $table->index(['id_user', 'is_deleted']);       // Get non-deleted for user
            $table->index(['category', 'created_at']);      // For filtering by category
            $table->index(['type', 'created_at']);          // For filtering by type
            $table->index(['email_status']);                // For tracking email delivery
            $table->index(['sms_status']);                  // For tracking SMS delivery
            $table->index(['expires_at']);                  // For cleanup jobs
            $table->index(['notification_group']);          // For grouping queries
        });

        // Notifications History Table (untuk audit trail)
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id('id_log');
            $table->unsignedBigInteger('id_notification');
            $table->unsignedBigInteger('id_user');
            
            $table->enum('action', [
                'created',
                'read',
                'unread',
                'archived',
                'unarchived',
                'deleted',
                'restored',
                'email_sent',
                'email_failed',
                'sms_sent',
                'sms_failed',
                'push_sent',
                'push_failed'
            ]);
            
            $table->text('details')->nullable(); // JSON details tentang action
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            
            $table->timestamps();
            
            $table->foreign('id_notification')
                ->references('id_notification')
                ->on('notifications')
                ->onDelete('cascade');
                
            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');
                
            $table->index(['id_notification']);
            $table->index(['id_user']);
            $table->index(['action']);
            $table->index(['created_at']);
        });

        // Notification Preferences Table
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id('id_preference');
            $table->unsignedBigInteger('id_user');
            
            // Channel Preferences
            $table->boolean('in_app_enabled')->default(true);
            $table->boolean('email_enabled')->default(true);
            $table->boolean('sms_enabled')->default(false);
            $table->boolean('push_enabled')->default(false);
            
            // Notification Type Preferences
            $table->boolean('approval_notifications')->default(true);
            $table->boolean('return_notifications')->default(true);
            $table->boolean('reminder_notifications')->default(true);
            $table->boolean('system_announcements')->default(true);
            
            // Email Settings
            $table->string('email_address')->nullable();
            $table->boolean('email_digest')->default(false); // daily digest instead of individual
            
            // SMS Settings
            $table->string('phone_number')->nullable();
            $table->boolean('sms_urgent_only')->default(true); // hanya untuk urgent
            
            // Quiet Hours
            $table->boolean('quiet_hours_enabled')->default(false);
            $table->time('quiet_hours_start')->nullable(); // e.g., 22:00
            $table->time('quiet_hours_end')->nullable(); // e.g., 08:00
            
            $table->timestamps();
            
            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');
                
            $table->unique('id_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('notifications');
    }
};
