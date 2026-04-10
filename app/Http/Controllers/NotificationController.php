<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationPreference;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected NotificationService $service;

    public function __construct(NotificationService $service)
    {
        $this->service = $service;
    }

    // ============================================================
    // RETRIEVAL ENDPOINTS
    // ============================================================

    /**
     * GET /api/notifications
     * Get user's notifications with filters & pagination
     */
    public function index(Request $request)
    {
        try {
            $userId = $this->userId($request);
            if (!$userId) {
                return $this->unauthorized('User not authenticated');
            }

            // Validation
            $validated = $request->validate([
                'limit' => 'integer|min:1|max:100',
                'page' => 'integer|min:1',
                'category' => 'in:approval,return,reminder,system',
                'type' => 'string',
                'priority' => 'in:low,normal,high,urgent',
                'unread_only' => 'boolean',
                'archived' => 'boolean',
                'days' => 'integer|min:1|max:365'
            ]);

            $limit = $request->get('limit', 20);
            $page = $request->get('page', 1);

            $filters = array_filter([
                'category' => $validated['category'] ?? null,
                'type' => $validated['type'] ?? null,
                'priority' => $validated['priority'] ?? null,
                'unread_only' => $validated['unread_only'] ?? null,
                'archived' => $validated['archived'] ?? null,
                'days' => $validated['days'] ?? null
            ]);

            $result = $this->service->getUserNotifications($userId, $limit, $page, $filters);

            return $this->success([
                'notifications' => $result->items(),
                'pagination' => [
                    'total' => $result->total(),
                    'per_page' => $result->perPage(),
                    'current_page' => $result->currentPage(),
                    'last_page' => $result->lastPage(),
                ],
                'unread_count' => $this->service->getUnreadCount($userId),
                'unread_by_type' => $this->service->getUnreadCountByType($userId)
            ], 'Notifications retrieved');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation error: ' . json_encode($e->errors()), 422);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve notifications: ' . $e->getMessage());
        }
    }

    /**
     * GET /api/notifications/grouped
     * Get notifications grouped by date and type
     */
    public function grouped(Request $request)
    {
        try {
            $userId = $this->userId($request);
            if (!$userId) {
                return $this->unauthorized('User not authenticated');
            }

            $limit = $request->get('limit', 100);
            $grouped = $this->service->getGroupedNotifications($userId, $limit);

            return $this->success([
                'grouped_notifications' => $grouped,
                'total_groups' => count($grouped),
                'unread_count' => $this->service->getUnreadCount($userId)
            ], 'Grouped notifications retrieved');

        } catch (\Exception $e) {
            return $this->error('Failed to retrieve grouped notifications: ' . $e->getMessage());
        }
    }

    /**
     * GET /api/notifications/{id}
     * Get single notification detail
     */
    public function show(Request $request, $notificationId)
    {
        try {
            $userId = $this->userId($request);
            if (!$userId) {
                return $this->unauthorized('User not authenticated');
            }

            $notification = Notification::find($notificationId);

            if (!$notification) {
                return $this->error('Notification not found', 404);
            }

            // Verify ownership
            if ($notification->id_user != $userId) {
                return $this->unauthorized('You cannot view this notification');
            }

            // Auto-mark as read
            $notification->markAsRead();

            return $this->success($notification, 'Notification detail');

        } catch (\Exception $e) {
            return $this->error('Failed to retrieve notification: ' . $e->getMessage());
        }
    }

    /**
     * GET /api/notifications/unread/count
     * Get unread notification count
     */
    public function unreadCount(Request $request)
    {
        try {
            $userId = $this->userId($request);
            if (!$userId) {
                return $this->unauthorized('User not authenticated');
            }

            $count = $this->service->getUnreadCount($userId);
            $byType = $this->service->getUnreadCountByType($userId);

            return $this->success([
                'unread_count' => $count,
                'has_unread' => $count > 0,
                'by_type' => $byType
            ], 'Unread count');

        } catch (\Exception $e) {
            return $this->error('Failed to retrieve unread count: ' . $e->getMessage());
        }
    }

    // ============================================================
    // UPDATE/MODIFY ENDPOINTS
    // ============================================================

    /**
     * POST /api/notifications/{id}/mark-read
     * Mark notification as read
     */
    public function markAsRead(Request $request, $notificationId)
    {
        try {
            $userId = $this->userId($request);
            if (!$userId) {
                return $this->unauthorized('User not authenticated');
            }

            $success = $this->service->markAsRead($notificationId, $userId);

            if (!$success) {
                return $this->error('Notification not found or access denied', 404);
            }

            $notification = Notification::find($notificationId);

            return $this->success($notification, 'Notification marked as read');

        } catch (\Exception $e) {
            return $this->error('Failed to mark notification: ' . $e->getMessage());
        }
    }

    /**
     * POST /api/notifications/mark-all-read
     * Mark all notifications as read for user
     */
    public function markAllAsRead(Request $request)
    {
        try {
            $userId = $this->userId($request);
            if (!$userId) {
                return $this->unauthorized('User not authenticated');
            }

            $count = $this->service->markAllAsRead($userId);

            return $this->success([
                'marked_count' => $count
            ], "Marked $count notifications as read");

        } catch (\Exception $e) {
            return $this->error('Failed to mark all: ' . $e->getMessage());
        }
    }

    /**
     * POST /api/notifications/mark-category-read
     * Mark all notifications from a category as read
     */
    public function markCategoryAsRead(Request $request)
    {
        try {
            $userId = $this->userId($request);
            if (!$userId) {
                return $this->unauthorized('User not authenticated');
            }

            $request->validate([
                'category' => 'required|in:approval,return,reminder,system'
            ]);

            $count = $this->service->markCategoryAsRead($userId, $request->category);

            return $this->success([
                'marked_count' => $count
            ], "Marked $count notifications from {$request->category} as read");

        } catch (\Exception $e) {
            return $this->error('Failed to mark category as read: ' . $e->getMessage());
        }
    }

    /**
     * POST /api/notifications/{id}/archive
     * Archive notification (hide from inbox)
     */
    public function archive(Request $request, $notificationId)
    {
        try {
            $userId = $this->userId($request);
            if (!$userId) {
                return $this->unauthorized('User not authenticated');
            }

            $success = $this->service->archive($notificationId, $userId);

            if (!$success) {
                return $this->error('Notification not found or access denied', 404);
            }

            $notification = Notification::find($notificationId);

            return $this->success($notification, 'Notification archived');

        } catch (\Exception $e) {
            return $this->error('Failed to archive notification: ' . $e->getMessage());
        }
    }

    /**
     * POST /api/notifications/archive-category
     * Archive all notifications from a category
     */
    public function archiveCategory(Request $request)
    {
        try {
            $userId = $this->userId($request);
            if (!$userId) {
                return $this->unauthorized('User not authenticated');
            }

            $request->validate([
                'category' => 'required|in:approval,return,reminder,system'
            ]);

            $count = $this->service->archiveCategory($userId, $request->category);

            return $this->success([
                'archived_count' => $count
            ], "Archived $count notifications from {$request->category}");

        } catch (\Exception $e) {
            return $this->error('Failed to archive category: ' . $e->getMessage());
        }
    }

    /**
     * DELETE /api/notifications/{id}
     * Delete (soft delete) notification
     */
    public function destroy(Request $request, $notificationId)
    {
        try {
            $userId = $this->userId($request);
            if (!$userId) {
                return $this->unauthorized('User not authenticated');
            }

            $success = $this->service->delete($notificationId, $userId);

            if (!$success) {
                return $this->error('Notification not found or access denied', 404);
            }

            return $this->success(null, 'Notification deleted');

        } catch (\Exception $e) {
            return $this->error('Failed to delete notification: ' . $e->getMessage());
        }
    }

    /**
     * DELETE /api/notifications/clear-all
     * Delete all notifications for user
     */
    public function clearAll(Request $request)
    {
        try {
            $userId = $this->userId($request);
            if (!$userId) {
                return $this->unauthorized('User not authenticated');
            }

            $count = $this->service->deleteAllForUser($userId);

            return $this->success([
                'deleted_count' => $count
            ], "Deleted $count notifications");

        } catch (\Exception $e) {
            return $this->error('Failed to clear notifications: ' . $e->getMessage());
        }
    }

    /**
     * POST /api/notifications/{id}/restore
     * Restore soft-deleted notification
     */
    public function restore(Request $request, $notificationId)
    {
        try {
            $userId = $this->userId($request);
            if (!$userId) {
                return $this->unauthorized('User not authenticated');
            }

            $success = $this->service->restore($notificationId, $userId);

            if (!$success) {
                return $this->error('Notification not found or access denied', 404);
            }

            $notification = Notification::find($notificationId);

            return $this->success($notification, 'Notification restored');

        } catch (\Exception $e) {
            return $this->error('Failed to restore notification: ' . $e->getMessage());
        }
    }

    // ============================================================
    // PREFERENCES ENDPOINTS
    // ============================================================

    /**
     * GET /api/notifications/preferences
     * Get user's notification preferences
     */
    public function preferences(Request $request)
    {
        try {
            $userId = $this->userId($request);
            if (!$userId) {
                return $this->unauthorized('User not authenticated');
            }

            $prefs = NotificationPreference::firstOrCreate(
                ['id_user' => $userId],
                [
                    'in_app_enabled' => true,
                    'email_enabled' => true,
                    'sms_enabled' => false,
                    'push_enabled' => false,
                    'approval_notifications' => true,
                    'return_notifications' => true,
                    'reminder_notifications' => true,
                    'system_announcements' => true
                ]
            );

            return $this->success($prefs, 'Preferences retrieved');

        } catch (\Exception $e) {
            return $this->error('Failed to retrieve preferences: ' . $e->getMessage());
        }
    }

    /**
     * PUT /api/notifications/preferences
     * Update notification preferences
     */
    public function updatePreferences(Request $request)
    {
        try {
            $userId = $this->userId($request);
            if (!$userId) {
                return $this->unauthorized('User not authenticated');
            }

            $validated = $request->validate([
                'in_app_enabled' => 'boolean',
                'email_enabled' => 'boolean',
                'sms_enabled' => 'boolean',
                'push_enabled' => 'boolean',
                'approval_notifications' => 'boolean',
                'return_notifications' => 'boolean',
                'reminder_notifications' => 'boolean',
                'system_announcements' => 'boolean',
                'email_address' => 'email',
                'phone_number' => 'regex:/^(\+)?(\d{10,15})$/',
                'email_digest' => 'boolean',
                'sms_urgent_only' => 'boolean',
                'quiet_hours_enabled' => 'boolean',
                'quiet_hours_start' => 'date_format:H:i',
                'quiet_hours_end' => 'date_format:H:i'
            ]);

            $prefs = NotificationPreference::updateOrCreate(
                ['id_user' => $userId],
                $validated
            );

            return $this->success($prefs, 'Preferences updated');

        } catch (\Exception $e) {
            return $this->error('Failed to update preferences: ' . $e->getMessage());
        }
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    /**
     * Get user ID from request
     */
    private function userId(Request $request)
    {
        // Dari authenticated user
        if (auth()->check()) {
            return auth()->user()->id_user ?? auth()->user()->id;
        }

        // Development: dari query parameter
        if (config('app.debug')) {
            return $request->query('user_id');
        }

        return null;
    }

    /**
     * Success response
     */
    private function success($data = null, $message = 'Success', $code = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message
        ], $code);
    }

    /**
     * Error response
     */
    private function error($message = 'Error', $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }

    /**
     * Unauthorized response
     */
    private function unauthorized($message = 'Unauthorized')
    {
        return $this->error($message, 401);
    }
}
