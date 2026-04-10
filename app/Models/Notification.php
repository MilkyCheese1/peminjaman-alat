<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $primaryKey = 'id_notification';
    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'id_borrowing',
        'category',
        'type',
        'title',
        'message',
        'icon',
        'color',
        'action_url',
        'action_label',
        'is_read',
        'read_at',
        'is_archived',
        'archived_at',
        'is_deleted',
        'deleted_at',
        'channels',
        'channel_status',
        'email_address',
        'email_status',
        'email_error',
        'phone_number',
        'sms_status',
        'sms_error',
        'push_status',
        'push_error',
        'metadata',
        'recipient_details',
        'priority',
        'expires_at',
        'retention_days',
        'notification_group',
        'parent_notification_id'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_archived' => 'boolean',
        'is_deleted' => 'boolean',
        'channels' => 'array',
        'channel_status' => 'array',
        'metadata' => 'array',
        'recipient_details' => 'array',
        'read_at' => 'datetime',
        'archived_at' => 'datetime',
        'deleted_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $hidden = [
        'email_address',
        'phone_number',
        'email_error',
        'sms_error',
        'push_error'
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    /**
     * Notification belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Notification belongs to a Borrowing
     */
    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class, 'id_borrowing', 'id_borrowing');
    }

    /**
     * Notification has many logs
     */
    public function logs()
    {
        return $this->hasMany(NotificationLog::class, 'id_notification', 'id_notification');
    }

    /**
     * Parent notification (for threaded notifications)
     */
    public function parentNotification()
    {
        return $this->belongsTo(Notification::class, 'parent_notification_id', 'id_notification');
    }

    /**
     * Child notifications (for threaded notifications)
     */
    public function childNotifications()
    {
        return $this->hasMany(Notification::class, 'parent_notification_id', 'id_notification');
    }

    // ============================================================
    // SCOPES - FOR FILTERING & QUERYING
    // ============================================================

    /**
     * Get notifications for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('id_user', $userId)
            ->where('is_deleted', false)
            ->where(function ($q) {
                // Show active & recent expired (last 2 hours)
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now()->subHours(2));
            });
    }

    /**
     * Get unread notifications only
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Get read notifications only
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Get archived notifications
     */
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    /**
     * Get active (non-archived) notifications
     */
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    /**
     * Get non-deleted notifications
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', false);
    }

    /**
     * Get notifications by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get notifications by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get notifications by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Get notifications from last N days
     */
    public function scopeFromDays($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get notifications grouped by date
     * Returns: Today, Yesterday, This Week, Older
     */
    public function scopeGroupedByDate($query)
    {
        return $query->select(
            '*',
            \DB::raw("
                CASE 
                    WHEN DATE(created_at) = CURDATE() THEN 'Hari Ini'
                    WHEN DATE(created_at) = CURDATE() - INTERVAL 1 DAY THEN 'Kemarin'
                    WHEN WEEK(created_at) = WEEK(CURDATE()) THEN 'Minggu Ini'
                    ELSE 'Lebih Lama'
                END as date_group
            ")
        );
    }

    /**
     * Get grouped by type for UI display
     */
    public function scopeGroupedByType($query)
    {
        return $query->orderBy('type');
    }

    /**
     * Get latest notifications first
     */
    public function scopeLatest($query)
    {
        return $query->orderByDesc('created_at');
    }

    /**
     * Get oldest notifications first
     */
    public function scopeOldest($query)
    {
        return $query->orderBy('created_at');
    }

    /**
     * Get pending send notifications (not yet sent)
     */
    public function scopePending($query)
    {
        return $query->where(function ($q) {
            $q->where('email_status', 'pending')
                ->orWhere('sms_status', 'pending')
                ->orWhere('push_status', 'pending');
        });
    }

    /**
     * Get Failed notifications (failed to send)
     */
    public function scopeFailed($query)
    {
        return $query->where(function ($q) {
            $q->where('email_status', 'failed')
                ->orWhere('sms_status', 'failed')
                ->orWhere('push_status', 'failed');
        });
    }

    // ============================================================
    // METHODS - ACTIONS & BEHAVIORS
    // ============================================================

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now()
            ]);
            $this->logAction('read');
        }
        return $this;
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread()
    {
        if ($this->is_read) {
            $this->update([
                'is_read' => false,
                'read_at' => null
            ]);
            $this->logAction('unread');
        }
        return $this;
    }

    /**
     * Archive notification (hide from main inbox)
     */
    public function archive()
    {
        $this->update([
            'is_archived' => true,
            'archived_at' => now()
        ]);
        $this->logAction('archived');
        return $this;
    }

    /**
     * Unarchive notification
     */
    public function unarchive()
    {
        $this->update([
            'is_archived' => false,
            'archived_at' => null
        ]);
        $this->logAction('unarchived');
        return $this;
    }

    /**
     * Soft delete notification
     */
    public function softDelete()
    {
        $this->update([
            'is_deleted' => true,
            'deleted_at' => now()
        ]);
        $this->logAction('deleted');
        return $this;
    }

    /**
     * Restore soft-deleted notification
     */
    public function restore()
    {
        $this->update([
            'is_deleted' => false,
            'deleted_at' => null
        ]);
        $this->logAction('restored');
        return $this;
    }

    /**
     * Check if notification is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if notification is overdue to be read
     */
    public function isOverdue(): bool
    {
        // Notifikasi dianggap overdue jika tidak dibaca dalam 7 hari
        return !$this->is_read && $this->created_at->diffInDays(now()) > 7;
    }

    /**
     * Get time since creation (human readable)
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Check if notification was sent successfully
     */
    public function isSent(): bool
    {
        $statuses = ['email_status', 'sms_status', 'push_status'];
        $sent = false;
        
        foreach ($statuses as $status) {
            if ($this->{$status} === 'sent') {
                $sent = true;
                break;
            }
        }
        
        return $sent;
    }

    /**
     * Log action untuk audit trail
     */
    public function logAction(string $action, ?array $details = null)
    {
        NotificationLog::create([
            'id_notification' => $this->id_notification,
            'id_user' => $this->id_user,
            'action' => $action,
            'details' => $details ? json_encode($details) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    // ============================================================
    // STATIC METHODS - LOOKUP TABLES & LABELS
    // ============================================================

    /**
     * Get all available notification types
     */
    public static function getTypes(): array
    {
        return [
            'borrowing_created' => 'Peminjaman Baru Dibuat',
            'borrowing_approved' => 'Peminjaman Disetujui',
            'borrowing_rejected' => 'Peminjaman Ditolak',
            'return_ready' => 'Item Siap Diambil',
            'return_submitted' => 'Pengembalian Diajukan',
            'return_verified' => 'Pengembalian Diverifikasi',
            'return_overdue' => 'Pengembalian Terlambat',
            'return_not_verified' => 'Pengembalian Tidak Terverifikasi',
            'return_reminder_1day' => 'Pengingat 1 Hari',
            'return_reminder_due' => 'Pengingat Deadline',
            'return_reminder_overdue' => 'Pengingat Overdue',
            'system_announcement' => 'Pengumuman Sistem',
            'equipment_unavailable' => 'Alat Tidak Tersedia',
            'custom' => 'Custom'
        ];
    }

    /**
     * Get label untuk tipe tertentu
     */
    public static function getTypeLabel(string $type): string
    {
        return self::getTypes()[$type] ?? $type;
    }

    /**
     * Get all categories
     */
    public static function getCategories(): array
    {
        return ['approval', 'return', 'reminder', 'system'];
    }

    /**
     * Get priority levels
     */
    public static function getPriorities(): array
    {
        return ['low', 'normal', 'high', 'urgent'];
    }

    /**
     * Get notification channels
     */
    public static function getChannels(): array
    {
        return ['in_app', 'email', 'sms', 'push'];
    }
}
