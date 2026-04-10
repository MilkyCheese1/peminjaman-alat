<?php

namespace App\Listeners;

use App\Services\NotificationService;
use App\Events\BorrowingCreated;
use App\Events\BorrowingApproved;
use App\Events\BorrowingRejected;
use App\Events\ReturnReady;
use App\Events\ReturnSubmitted;
use App\Events\ReturnVerified;
use App\Events\ReturnOverdue;

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

class SendReturnReadyNotification
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(ReturnReady $event)
    {
        $this->notificationService->notifyReturnReady($event->borrowing);
    }
}

class SendReturnSubmittedNotification
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(ReturnSubmitted $event)
    {
        // Broadcast to admins/operators that return was submitted
        // Not implemented yet
    }
}

class SendReturnVerifiedNotification
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(ReturnVerified $event)
    {
        $this->notificationService->notifyReturnVerified($event->borrowing);
    }
}

class SendReturnOverdueNotification
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(ReturnOverdue $event)
    {
        $this->notificationService->notifyReturnOverdue($event->borrowing);
    }
}
