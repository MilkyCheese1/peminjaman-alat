<?php

namespace App\Listeners;

use App\Services\NotificationService;
use App\Events\BorrowingApproved;

class SendBorrowingApprovedNotification
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(BorrowingApproved $event)
    {
        try {
            $this->notificationService->notifyBorrowingApproved($event->borrowing);
        } catch (\Exception $e) {
            \Log::error('Error sending borrowing approved notification: ' . $e->getMessage(), [
                'borrowing_id' => $event->borrowing->id_borrowing ?? null,
                'error' => $e
            ]);
        }
    }
}
