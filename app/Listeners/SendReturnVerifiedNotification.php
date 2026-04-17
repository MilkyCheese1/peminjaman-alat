<?php

namespace App\Listeners;

use App\Services\NotificationService;
use App\Events\ReturnVerified;

class SendReturnVerifiedNotification
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(ReturnVerified $event)
    {
        try {
            $this->notificationService->notifyReturnVerified($event->borrowing);
        } catch (\Exception $e) {
            \Log::error('Error sending return verified notification: ' . $e->getMessage(), [
                'borrowing_id' => $event->borrowing->id_borrowing ?? null,
                'error' => $e
            ]);
        }
    }
}
