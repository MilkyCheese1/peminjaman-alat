<?php

namespace App\Listeners;

use App\Services\NotificationService;
use App\Events\ReturnSubmitted;

class SendReturnSubmittedNotification
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(ReturnSubmitted $event)
    {
        try {
            // Broadcast to admins/operators that return was submitted
            // TODO: Implement notification logic for return submission
        } catch (\Exception $e) {
            \Log::error('Error sending return submitted notification: ' . $e->getMessage(), [
                'borrowing_id' => $event->borrowing->id_borrowing ?? null,
                'error' => $e
            ]);
        }
    }
}
