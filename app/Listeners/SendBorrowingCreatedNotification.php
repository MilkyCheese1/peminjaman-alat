<?php

namespace App\Listeners;

use App\Services\NotificationService;
use App\Events\BorrowingCreated;

class SendBorrowingCreatedNotification
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(BorrowingCreated $event)
    {
        try {
            $this->notificationService->notifyBorrowingCreated($event->borrowing);
        } catch (\Exception $e) {
            \Log::error('Error sending borrowing created notification: ' . $e->getMessage(), [
                'borrowing_id' => $event->borrowing->id_borrowing ?? null,
                'error' => $e
            ]);
            // Continue silently - don't crash the event
        }
    }
}
