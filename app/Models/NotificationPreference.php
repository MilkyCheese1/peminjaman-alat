<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $table = 'notification_preferences';
    protected $primaryKey = 'id_preference';
    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'in_app_enabled',
        'email_enabled',
        'sms_enabled',
        'push_enabled',
        'approval_notifications',
        'return_notifications',
        'reminder_notifications',
        'system_announcements',
        'email_address',
        'email_digest',
        'phone_number',
        'sms_urgent_only',
        'quiet_hours_enabled',
        'quiet_hours_start',
        'quiet_hours_end'
    ];

    protected $casts = [
        'in_app_enabled' => 'boolean',
        'email_enabled' => 'boolean',
        'sms_enabled' => 'boolean',
        'push_enabled' => 'boolean',
        'approval_notifications' => 'boolean',
        'return_notifications' => 'boolean',
        'reminder_notifications' => 'boolean',
        'system_announcements' => 'boolean',
        'email_digest' => 'boolean',
        'sms_urgent_only' => 'boolean',
        'quiet_hours_enabled' => 'boolean',
        'quiet_hours_start' => 'datetime:H:i',
        'quiet_hours_end' => 'datetime:H:i',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Methods
    /**
     * Check if currently in quiet hours
     */
    public function isInQuietHours(): bool
    {
        if (!$this->quiet_hours_enabled) {
            return false;
        }

        $now = now()->format('H:i');
        $start = $this->quiet_hours_start?->format('H:i');
        $end = $this->quiet_hours_end?->format('H:i');

        if ($start && $end) {
            if ($start < $end) {
                return $now >= $start && $now < $end;
            } else {
                // Quiet hours span midnight
                return $now >= $start || $now < $end;
            }
        }

        return false;
    }

    /**
     * Check if a channel is enabled
     */
    public function isChannelEnabled(string $channel): bool
    {
        $mapping = [
            'in_app' => 'in_app_enabled',
            'email' => 'email_enabled',
            'sms' => 'sms_enabled',
            'push' => 'push_enabled'
        ];

        $attribute = $mapping[$channel] ?? null;
        return $attribute ? $this->{$attribute} : false;
    }

    /**
     * Check if notification category is enabled
     */
    public function isCategoryEnabled(string $category): bool
    {
        $mapping = [
            'approval' => 'approval_notifications',
            'return' => 'return_notifications',
            'reminder' => 'reminder_notifications',
            'system' => 'system_announcements'
        ];

        $attribute = $mapping[$category] ?? null;
        return $attribute ? $this->{$attribute} : false;
    }

    /**
     * Get enabled channels
     */
    public function getEnabledChannels(): array
    {
        $channels = [];
        foreach (['in_app', 'email', 'sms', 'push'] as $channel) {
            if ($this->isChannelEnabled($channel)) {
                $channels[] = $channel;
            }
        }
        return $channels;
    }

    /**
     * Get enabled categories
     */
    public function getEnabledCategories(): array
    {
        $categories = [];
        foreach (['approval', 'return', 'reminder', 'system'] as $category) {
            if ($this->isCategoryEnabled($category)) {
                $categories[] = $category;
            }
        }
        return $categories;
    }

    /**
     * Should send notification based on preferences
     */
    public function shouldSendNotification(string $channel, string $category): bool
    {
        // Check if in quiet hours (skip for in_app messages usually)
        if ($this->isInQuietHours() && $channel !== 'in_app') {
            return false;
        }

        // Check if channel enabled
        if (!$this->isChannelEnabled($channel)) {
            return false;
        }

        // Check if category enabled
        if (!$this->isCategoryEnabled($category)) {
            return false;
        }

        return true;
    }
}
