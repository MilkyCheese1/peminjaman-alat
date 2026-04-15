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
        $this->notificationService->notifyBorrowingRejected($event->borrowing, $event->reason);
    }
}
