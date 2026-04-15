<?php

namespace App\Listeners;

use App\Services\NotificationService;
use App\Events\ReturnOverdue;

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
