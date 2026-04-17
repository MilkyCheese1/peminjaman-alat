<?php

namespace App\Listeners;

use App\Services\NotificationService;
use App\Events\BorrowingRejected;

class SendBorrowingRejectedNotification
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(BorrowingRejected $event)
    {
        try {
            $this->notificationService->notifyBorrowingRejected($event->borrowing);
        } catch (\Exception $e) {
            \Log::error('Error sending borrowing rejected notification: ' . $e->getMessage(), [
                'borrowing_id' => $event->borrowing->id_borrowing ?? null,
                'error' => $e
            ]);
        }
    }
}
