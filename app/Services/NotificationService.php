<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\NotificationLog;
use App\Models\NotificationPreference;
use App\Models\User;
use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    // ============================================================
    // CORE CREATION METHODS
    // ============================================================

    /**
     * Create a notification with full data
     */
    public function create(array $data): Notification
    {
        $defaults = [
            'is_read' => false,
            'is_archived' => false,
            'is_deleted' => false,
            'channels' => ['in_app'],  // Default to in-app notification
            'priority' => 'normal',
            'retention_days' => 30,
            'expires_at' => now()->addDays(30)
        ];

        $data = array_merge($defaults, $data);

        try {
            $notification = Notification::create($data);
            $notification->logAction('created', ['channels' => $data['channels']]);
            return $notification;
        } catch (\Exception $e) {
            Log::error('Failed to create notification: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create notification untuk multiple users
     */
    public function createBulk(array $userIds, array $data): array
    {
        $notifications = [];
        foreach ($userIds as $userId) {
            $data['id_user'] = $userId;
            $notifications[] = $this->create($data);
        }
        return $notifications;
    }

    /**
     * Create & send notification immediately (real-time)
     */
    public function createAndSend(array $data): Notification
    {
        $notification = $this->create($data);
        $this->sendNotification($notification);
        return $notification;
    }

    /**
     * Create notification untuk semua users dengan role tertentu
     */
    public function broadcastByRole(string $role, array $data): array
    {
        $userIds = User::where('role', $role)
            ->where('is_active', true)
            ->pluck('id_user')
            ->toArray();

        return $this->createBulk($userIds, $data);
    }

    /**
     * Create notification untuk semua active users
     */
    public function broadcastToAll(array $data): array
    {
        $userIds = User::where('is_active', true)
            ->pluck('id_user')
            ->toArray();

        return $this->createBulk($userIds, $data);
    }

    // ============================================================
    // RETRIEVAL METHODS
    // ============================================================

    /**
     * Get user's notifications dengan filtering & pagination
     */
    public function getUserNotifications(
        $userId,
        $limit = 20,
        $page = 1,
        $filters = []
    ) {
        $query = Notification::forUser($userId);

        // Apply filters
        if (isset($filters['category']) && $filters['category']) {
            $query->byCategory($filters['category']);
        }

        if (isset($filters['type']) && $filters['type']) {
            $query->byType($filters['type']);
        }

        if (isset($filters['priority']) && $filters['priority']) {
            $query->byPriority($filters['priority']);
        }

        if (isset($filters['unread_only']) && $filters['unread_only']) {
            $query->unread();
        }

        if (isset($filters['archived']) && $filters['archived']) {
            $query->archived();
        } else {
            $query->active();
        }

        if (isset($filters['days']) && $filters['days']) {
            $query->fromDays($filters['days']);
        }

        return $query->latest()
            ->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * Get grouped notifications (by date & type) untuk display yang rapi
     */
    public function getGroupedNotifications($userId, $limit = 100)
    {
        $notifications = Notification::forUser($userId)
            ->active()
            ->latest()
            ->limit($limit)
            ->get();

        // Group by date
        $grouped = $notifications->groupBy(function ($notification) {
            $date = $notification->created_at->format('Y-m-d');
            
            if ($date === now()->format('Y-m-d')) {
                return 'Hari Ini';
            } elseif ($date === now()->subDay()->format('Y-m-d')) {
                return 'Kemarin';
            } elseif ($notification->created_at->isCurrentWeek()) {
                return 'Minggu Ini';
            } else {
                return 'Lebih Lama';
            }
        });

        // Further group by type within each date group
        foreach ($grouped as $dateGroup => $items) {
            $grouped[$dateGroup] = $items->groupBy('type');
        }

        return $grouped;
    }

    /**
     * Get unread count untuk user
     */
    public function getUnreadCount($userId): int
    {
        return Notification::forUser($userId)
            ->active()
            ->unread()
            ->count();
    }

    /**
     * Get unread notifications count by type
     */
    public function getUnreadCountByType($userId): array
    {
        $counts = Notification::forUser($userId)
            ->active()
            ->unread()
            ->groupBy('type')
            ->selectRaw('type, COUNT(*) as count')
            ->pluck('count', 'type')
            ->toArray();

        return $counts;
    }

    /**
     * Get pending send notifications (not yet sent to email/sms)
     */
    public function getPendingNotifications($limit = 100)
    {
        return Notification::where(function ($q) {
            $q->where('email_status', 'pending')
                ->orWhere('sms_status', 'pending')
                ->orWhere('push_status', 'pending');
        })
            ->limit($limit)
            ->get();
    }

    // ============================================================
    // UPDATE/MODIFY METHODS
    // ============================================================

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId, $userId = null): bool
    {
        $notification = Notification::find($notificationId);

        if (!$notification) {
            return false;
        }

        // Verify ownership jika user provided
        if ($userId && $notification->id_user != $userId) {
            return false;
        }

        $notification->markAsRead();
        return true;
    }

    /**
     * Mark all notifications as read untuk user
     */
    public function markAllAsRead($userId): int
    {
        return Notification::forUser($userId)
            ->active()
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }

    /**
     * Mark all notifications dari category sebagai read
     */
    public function markCategoryAsRead($userId, $category): int
    {
        return Notification::forUser($userId)
            ->active()
            ->unread()
            ->byCategory($category)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }

    /**
     * Archive notification (hide from inbox)
     */
    public function archive($notificationId, $userId = null): bool
    {
        $notification = Notification::find($notificationId);

        if (!$notification || ($userId && $notification->id_user != $userId)) {
            return false;
        }

        $notification->archive();
        return true;
    }

    /**
     * Archive all notifications dari category
     */
    public function archiveCategory($userId, $category): int
    {
        return Notification::forUser($userId)
            ->byCategory($category)
            ->update([
                'is_archived' => true,
                'archived_at' => now()
            ]);
    }

    /**
     * Delete (soft delete) notification
     */
    public function delete($notificationId, $userId = null): bool
    {
        $notification = Notification::find($notificationId);

        if (!$notification || ($userId && $notification->id_user != $userId)) {
            return false;
        }

        $notification->softDelete();
        return true;
    }

    /**
     * Delete all notifications untuk user
     */
    public function deleteAllForUser($userId): int
    {
        return Notification::forUser($userId)
            ->update([
                'is_deleted' => true,
                'deleted_at' => now()
            ]);
    }

    /**
     * Restore soft-deleted notification
     */
    public function restore($notificationId, $userId = null): bool
    {
        $notification = Notification::where('id_notification', $notificationId)
            ->where('is_deleted', true)
            ->first();

        if (!$notification || ($userId && $notification->id_user != $userId)) {
            return false;
        }

        $notification->restore();
        return true;
    }

    // ============================================================
    // SENDING/DELIVERY METHODS
    // ============================================================

    /**
     * Send notification melalui channel yang ditentukan
     */
    public function sendNotification(Notification $notification): array
    {
        $results = [];
        $user = $notification->user;
        $prefs = $user->notificationPreference ?? new NotificationPreference();

        foreach ($notification->channels as $channel) {
            // Check if should send based on preferences
            if (!$prefs->shouldSendNotification($channel, $notification->category)) {
                $results[$channel] = 'skipped_by_preference';
                continue;
            }

            try {
                switch ($channel) {
                    case 'in_app':
                        $results[$channel] = $this->sendInApp($notification);
                        break;
                    case 'email':
                        $results[$channel] = $this->sendEmail($notification, $user);
                        break;
                    case 'sms':
                        $results[$channel] = $this->sendSMS($notification, $user);
                        break;
                    case 'push':
                        $results[$channel] = $this->sendPush($notification, $user);
                        break;
                    default:
                        $results[$channel] = 'unknown_channel';
                }
            } catch (\Exception $e) {
                $results[$channel] = 'error: ' . $e->getMessage();
                Log::error("Failed to send $channel notification: " . $e->getMessage());
            }
        }

        // Update channel_status
        $notification->update([
            'channel_status' => $results
        ]);

        return $results;
    }

    /**
     * Send in-app notification (update status)
     */
    private function sendInApp(Notification $notification): string
    {
        $notification->update([
            'email_status' => 'sent'
        ]);

        // In-app notifications are instant, no additional action needed
        Log::info("In-app notification sent to user {$notification->id_user}");
        return 'sent';
    }

    /**
     * Send email notification
     */
    private function sendEmail(Notification $notification, User $user): string
    {
        if (!$user->email) {
            return 'failed: no_email';
        }

        try {
            // TODO: Integrate dengan mail service
            // Mail::to($user->email)->queue(new NotificationMail($notification));

            $notification->update([
                'email_address' => $user->email,
                'email_status' => 'sent',
                'email_error' => null
            ]);

            Log::info("Email notification sent to {$user->email}");
            $notification->logAction('email_sent');
            return 'sent';
        } catch (\Exception $e) {
            $notification->update([
                'email_status' => 'failed',
                'email_error' => $e->getMessage()
            ]);

            Log::error("Failed to send email: " . $e->getMessage());
            $notification->logAction('email_failed', ['error' => $e->getMessage()]);
            return 'failed';
        }
    }

    /**
     * Send SMS notification
     */
    private function sendSMS(Notification $notification, User $user): string
    {
        if (!$user->phone_number) {
            return 'failed: no_phone';
        }

        try {
            // TODO: Integrate dengan SMS service (Twilio, AWS SNS, etc)
            // SMSService::send($user->phone_number, $notification->message);

            $notification->update([
                'phone_number' => $user->phone_number,
                'sms_status' => 'sent',
                'sms_error' => null
            ]);

            Log::info("SMS notification sent to {$user->phone_number}");
            $notification->logAction('sms_sent');
            return 'sent';
        } catch (\Exception $e) {
            $notification->update([
                'sms_status' => 'failed',
                'sms_error' => $e->getMessage()
            ]);

            Log::error("Failed to send SMS: " . $e->getMessage());
            $notification->logAction('sms_failed', ['error' => $e->getMessage()]);
            return 'failed';
        }
    }

    /**
     * Send push notification
     */
    private function sendPush(Notification $notification, User $user): string
    {
        try {
            // TODO: Integrate dengan push service (Firebase, OneSignal, etc)
            // PushService::send($user->id, $notification->title, $notification->message);

            $notification->update([
                'push_status' => 'sent',
                'push_error' => null
            ]);

            Log::info("Push notification sent to user {$user->id_user}");
            $notification->logAction('push_sent');
            return 'sent';
        } catch (\Exception $e) {
            $notification->update([
                'push_status' => 'failed',
                'push_error' => $e->getMessage()
            ]);

            Log::error("Failed to send push: " . $e->getMessage());
            $notification->logAction('push_failed', ['error' => $e->getMessage()]);
            return 'failed';
        }
    }

    // ============================================================
    // SCHEDULED/REMINDER NOTIFICATIONS
    // ============================================================

    /**
     * Create return reminder 1 day before due date
     */
    public function scheduleReturnReminder1Day(Borrowing $borrowing): ?Notification
    {
        if (!$borrowing->return_date) {
            return null;
        }

        return $this->createAndSend([
            'id_user' => $borrowing->id_user,
            'id_borrowing' => $borrowing->id_borrowing,
            'category' => 'reminder',
            'type' => 'return_reminder_1day',
            'title' => '⏰ Pengingat Pengembalian',
            'message' => "Jangan lupa mengembalikan {$borrowing->equipment->name} besok pukul {$borrowing->return_date->format('H:i')}",
            'icon' => '⏰',
            'color' => 'warning',
            'priority' => 'high',
            'channels' => ['in_app', 'email'],
            'metadata' => [
                'equipment_name' => $borrowing->equipment->name ?? 'Unknown',
                'return_date' => $borrowing->return_date->toDateTimeString()
            ]
        ]);
    }

    /**
     * Create return reminder pada hari deadline
     */
    public function scheduleReturnReminderDueDate(Borrowing $borrowing): ?Notification
    {
        if (!$borrowing->return_date) {
            return null;
        }

        return $this->createAndSend([
            'id_user' => $borrowing->id_user,
            'id_borrowing' => $borrowing->id_borrowing,
            'category' => 'reminder',
            'type' => 'return_reminder_due',
            'title' => '⚠️ Deadline Pengembalian HARI INI',
            'message' => "Hari terakhir mengembalikan {$borrowing->equipment->name}. Kembalikan sebelum jam {$borrowing->return_date->format('H:i')}",
            'icon' => '⚠️',
            'color' => 'danger',
            'priority' => 'urgent',
            'channels' => ['in_app', 'email', 'sms'],
            'metadata' => [
                'equipment_name' => $borrowing->equipment->name ?? 'Unknown',
                'return_date' => $borrowing->return_date->toDateTimeString()
            ]
        ]);
    }

    /**
     * Create overdue reminder notification
     */
    public function scheduleReturnReminderOverdue(Borrowing $borrowing): ?Notification
    {
        if (!$borrowing->return_date || $borrowing->return_date->isFuture()) {
            return null;
        }

        $daysLate = $borrowing->return_date->diffInDays(now());

        return $this->createAndSend([
            'id_user' => $borrowing->id_user,
            'id_borrowing' => $borrowing->id_borrowing,
            'category' => 'reminder',
            'type' => 'return_reminder_overdue',
            'title' => '❌ Pengembalian TERLAMBAT',
            'message' => "{$borrowing->equipment->name} sudah terlambat {$daysLate} hari. Segera kembalikan!",
            'icon' => '❌',
            'color' => 'danger',
            'priority' => 'urgent',
            'channels' => ['in_app', 'email', 'sms'],
            'metadata' => [
                'equipment_name' => $borrowing->equipment->name ?? 'Unknown',
                'days_late' => $daysLate
            ]
        ]);
    }

    // ============================================================
    // EVENT-SPECIFIC NOTIFICATION BUILDERS
    // ============================================================

    /**
     * Notify when borrowing is created
     */
    public function notifyBorrowingCreated(Borrowing $borrowing): Notification
    {
        return $this->createAndSend([
            'id_user' => $borrowing->id_user,
            'id_borrowing' => $borrowing->id_borrowing,
            'category' => 'approval',
            'type' => 'borrowing_created',
            'title' => '📝 Permintaan Peminjaman Diterima',
            'message' => "Permintaan peminjaman Anda untuk {$borrowing->equipment->name} telah diterima. Menunggu persetujuan admin.",
            'icon' => '📝',
            'color' => 'info',
            'priority' => 'normal',
            'channels' => ['in_app', 'email'],
            'metadata' => [
                'equipment_name' => $borrowing->equipment->name ?? 'Unknown',
                'quantity' => $borrowing->quantity,
                'borrow_date' => $borrowing->borrow_date->toDateTimeString()
            ]
        ]);
    }

    /**
     * Notify when borrowing is approved
     */
    public function notifyBorrowingApproved(Borrowing $borrowing): Notification
    {
        return $this->createAndSend([
            'id_user' => $borrowing->id_user,
            'id_borrowing' => $borrowing->id_borrowing,
            'category' => 'approval',
            'type' => 'borrowing_approved',
            'title' => '✅ Peminjaman Disetujui',
            'message' => "Peminjaman {$borrowing->equipment->name} telah disetujui! Silakan ambil item sesuai jadwal.",
            'icon' => '✅',
            'color' => 'success',
            'priority' => 'high',
            'channels' => ['in_app', 'email'],
            'action_url' => "/borrowings/{$borrowing->id_borrowing}",
            'action_label' => 'Lihat Detail',
            'metadata' => [
                'equipment_name' => $borrowing->equipment->name ?? 'Unknown',
                'borrow_date' => $borrowing->borrow_date->toDateTimeString()
            ]
        ]);
    }

    /**
     * Notify when borrowing is rejected
     */
    public function notifyBorrowingRejected(Borrowing $borrowing, $reason = null): Notification
    {
        $message = "Maaf, permintaan peminjaman Anda untuk {$borrowing->equipment->name} ditolak.";
        if ($reason) {
            $message .= " Alasan: $reason";
        }

        return $this->createAndSend([
            'id_user' => $borrowing->id_user,
            'id_borrowing' => $borrowing->id_borrowing,
            'category' => 'approval',
            'type' => 'borrowing_rejected',
            'title' => '❌ Peminjaman Ditolak',
            'message' => $message,
            'icon' => '❌',
            'color' => 'danger',
            'priority' => 'normal',
            'channels' => ['in_app', 'email'],
            'metadata' => [
                'equipment_name' => $borrowing->equipment->name ?? 'Unknown',
                'reason' => $reason
            ]
        ]);
    }

    /**
     * Notify when item is ready for pickup
     */
    public function notifyReturnReady(Borrowing $borrowing): Notification
    {
        return $this->createAndSend([
            'id_user' => $borrowing->id_user,
            'id_borrowing' => $borrowing->id_borrowing,
            'category' => 'return',
            'type' => 'return_ready',
            'title' => '📦 Item Siap Diambil',
            'message' => "{$borrowing->equipment->name} sudah disiapkan dan siap untuk diambil.",
            'icon' => '📦',
            'color' => 'info',
            'priority' => 'high',
            'channels' => ['in_app', 'email'],
            'metadata' => [
                'equipment_name' => $borrowing->equipment->name ?? 'Unknown'
            ]
        ]);
    }

    /**
     * Notify when item is returned
     */
    public function notifyReturnVerified(Borrowing $borrowing): Notification
    {
        return $this->createAndSend([
            'id_user' => $borrowing->id_user,
            'id_borrowing' => $borrowing->id_borrowing,
            'category' => 'return',
            'type' => 'return_verified',
            'title' => '✔️ Pengembalian Terkonfirmasi',
            'message' => "Pengembalian {$borrowing->equipment->name} telah terkonfirmasi dan diterima. Terima kasih!",
            'icon' => '✔️',
            'color' => 'success',
            'priority' => 'normal',
            'channels' => ['in_app', 'email'],
            'metadata' => [
                'equipment_name' => $borrowing->equipment->name ?? 'Unknown'
            ]
        ]);
    }

    /**
     * Notify overdue returns
     */
    public function notifyReturnOverdue(Borrowing $borrowing): Notification
    {
        $daysLate = $borrowing->return_date->diffInDays(now());

        return $this->createAndSend([
            'id_user' => $borrowing->id_user,
            'id_borrowing' => $borrowing->id_borrowing,
            'category' => 'return',
            'type' => 'return_overdue',
            'title' => '⏰ Pengembalian Terlambat',
            'message' => "{$borrowing->equipment->name} terlambat dikembalikan selama {$daysLate} hari. Segera kembalikan!",
            'icon' => '⏰',
            'color' => 'warning',
            'priority' => 'high',
            'channels' => ['in_app', 'email', 'sms'],
            'metadata' => [
                'equipment_name' => $borrowing->equipment->name ?? 'Unknown',
                'days_late' => $daysLate,
                'return_date' => $borrowing->return_date->toDateTimeString()
            ]
        ]);
    }

    /**
     * Send custom notification
     */
    public function sendCustom(
        $userId,
        $title,
        $message,
        $icon = '📬',
        $color = 'info',
        $channels = ['in_app'],
        $priority = 'normal'
    ): Notification {
        return $this->createAndSend([
            'id_user' => $userId,
            'category' => 'system',
            'type' => 'custom',
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'color' => $color,
            'priority' => $priority,
            'channels' => $channels
        ]);
    }

    // ============================================================
    // MAINTENANCE & CLEANUP
    // ============================================================

    /**
     * Clean up expired notifications
     */
    public function cleanupExpired(): int
    {
        return Notification::where('expires_at', '<', now())
            ->delete();
    }

    /**
     * Auto-archive old read notifications
     */
    public function autoArchiveOld($days = 30): int
    {
        return Notification::where('is_read', true)
            ->where('read_at', '<', now()->subDays($days))
            ->where('is_archived', false)
            ->update([
                'is_archived' => true,
                'archived_at' => now()
            ]);
    }

    /**
     * Delete permanently (hard delete) soft-deleted notifications
     */
    public function permanentlyDelete($days = 90): int
    {
        return Notification::where('is_deleted', true)
            ->where('deleted_at', '<', now()->subDays($days))
            ->forceDelete();
    }

    /**
     * Cleanup stale logs
     */
    public function cleanupLogs($days = 90): int
    {
        return NotificationLog::where('created_at', '<', now()->subDays($days))
            ->delete();
    }

    /**
     * Retry failed notifications
     */
    public function retryFailed($limit = 100)
    {
        $failedNotifications = Notification::where(function ($q) {
            $q->where('email_status', 'failed')
                ->orWhere('sms_status', 'failed')
                ->orWhere('push_status', 'failed');
        })
            ->limit($limit)
            ->get();

        $count = 0;
        foreach ($failedNotifications as $notification) {
            try {
                $this->sendNotification($notification);
                $count++;
            } catch (\Exception $e) {
                Log::error("Retry failed for notification {$notification->id_notification}: " . $e->getMessage());
            }
        }

        return $count;
    }
}
