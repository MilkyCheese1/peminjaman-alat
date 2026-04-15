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
        $this->notificationService->notifyBorrowingApproved($event->borrowing);
    }
}
