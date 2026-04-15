<?php

namespace App\Listeners;

use App\Services\NotificationService;
use App\Events\ReturnReady;

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
