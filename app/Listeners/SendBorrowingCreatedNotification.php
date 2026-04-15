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
        $this->notificationService->notifyBorrowingCreated($event->borrowing);
    }
}
