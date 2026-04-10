<?php

namespace App\Helpers;

use App\Services\NotificationService;
use App\Models\Borrowing;

/**
 * NotificationHelper - Helper class untuk memudahkan penggunaan notification system
 * 
 * Usage:
 * - NotificationHelper::sendApproval($borrowing);
 * - NotificationHelper::sendRejection($borrowing, 'Stok tidak tersedia');
 * - NotificationHelper::sendReminder($userId, 'Title', 'Message');
 */
class NotificationHelper
{
    protected static ?NotificationService $service = null;

    /**
     * Get NotificationService instance
     */
    private static function service(): NotificationService
    {
        if (self::$service === null) {
            self::$service = app(NotificationService::class);
        }
        return self::$service;
    }

    // ============================================================
    // BORROWING NOTIFICATIONS
    // ============================================================

    /**
     * Send approval notification
     */
    public static function sendApproval(Borrowing $borrowing)
    {
        return self::service()->notifyBorrowingApproved($borrowing);
    }

    /**
     * Send rejection notification
     */
    public static function sendRejection(Borrowing $borrowing, ?string $reason = null)
    {
        return self::service()->notifyBorrowingRejected($borrowing, $reason);
    }

    /**
     * Send created notification
     */
    public static function sendCreated(Borrowing $borrowing)
    {
        return self::service()->notifyBorrowingCreated($borrowing);
    }

    /**
     * Send return ready notification
     */
    public static function sendReturnReady(Borrowing $borrowing)
    {
        return self::service()->notifyReturnReady($borrowing);
    }

    /**
     * Send return verified notification
     */
    public static function sendReturnVerified(Borrowing $borrowing)
    {
        return self::service()->notifyReturnVerified($borrowing);
    }

    /**
     * Send overdue notification
     */
    public static function sendOverdue(Borrowing $borrowing)
    {
        return self::service()->notifyReturnOverdue($borrowing);
    }

    // ============================================================
    // REMINDER NOTIFICATIONS
    // ============================================================

    /**
     * Send return reminder (1 day before)
     */
    public static function sendReminder1Day(Borrowing $borrowing)
    {
        return self::service()->scheduleReturnReminder1Day($borrowing);
    }

    /**
     * Send return reminder (due date)
     */
    public static function sendReminderDue(Borrowing $borrowing)
    {
        return self::service()->scheduleReturnReminderDueDate($borrowing);
    }

    /**
     * Send return reminder (overdue)
     */
    public static function sendReminderOverdue(Borrowing $borrowing)
    {
        return self::service()->scheduleReturnReminderOverdue($borrowing);
    }

    // ============================================================
    // CUSTOM NOTIFICATIONS
    // ============================================================

    /**
     * Send custom notification to user
     */
    public static function sendCustom(
        $userId,
        $title,
        $message,
        $icon = '📬',
        $color = 'info',
        array $channels = ['in_app'],
        $priority = 'normal'
    ) {
        return self::service()->sendCustom($userId, $title, $message, $icon, $color, $channels, $priority);
    }

    /**
     * Send to multiple users
     */
    public static function sendToMultiple(
        array $userIds,
        $title,
        $message,
        $icon = '📬',
        $color = 'info',
        array $channels = ['in_app']
    ) {
        return self::service()->createBulk($userIds, [
            'category' => 'system',
            'type' => 'custom',
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'color' => $color,
            'channels' => $channels,
            'priority' => 'normal'
        ]);
    }

    /**
     * Broadcast to all active users
     */
    public static function broadcastAll($title, $message, $icon = '📬', $color = 'info')
    {
        return self::service()->broadcastToAll([
            'category' => 'system',
            'type' => 'system_announcement',
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'color' => $color,
            'channels' => ['in_app', 'email'],
            'priority' => 'normal'
        ]);
    }

    /**
     * Broadcast ke admin/operators
     */
    public static function broadcastToOperators($title, $message, $icon = '📬', $color = 'info')
    {
        return self::service()->broadcastByRole('admin', [
            'category' => 'system',
            'type' => 'system_announcement',
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'color' => $color,
            'channels' => ['in_app', 'email'],
            'priority' => 'high'
        ]);
    }

    // ============================================================
    // UTILITY METHODS
    // ============================================================

    /**
     * Get full notification label
     */
    public static function getTypeLabel(string $type): string
    {
        $labels = [
            'borrowing_created' => '📝 Peminjaman Baru',
            'borrowing_approved' => '✅ Disetujui',
            'borrowing_rejected' => '❌ Ditolak',
            'return_ready' => '📦 Siap Diambil',
            'return_verified' => '✔️ Dikonfirmasi',
            'return_overdue' => '⏰ Terlambat',
            'return_reminder_1day' => '⏰ Pengingat 1 Hari',
            'return_reminder_due' => '⚠️ Deadline Hari Ini',
            'return_reminder_overdue' => '❌ Sudah Terlambat',
            'system_announcement' => '📢 Pengumuman',
            'custom' => '📬 Notifikasi'
        ];
        return $labels[$type] ?? $type;
    }

    /**
     * Get category color
     */
    public static function getCategoryColor(string $category): string
    {
        $colors = [
            'approval' => 'primary',
            'return' => 'success',
            'reminder' => 'warning',
            'system' => 'info'
        ];
        return $colors[$category] ?? 'info';
    }

    /**
     * Get priority badge class
     */
    public static function getPriorityClass(string $priority): string
    {
        $classes = [
            'low' => 'badge-light',
            'normal' => 'badge-info',
            'high' => 'badge-warning',
            'urgent' => 'badge-danger'
        ];
        return $classes[$priority] ?? 'badge-info';
    }
}
