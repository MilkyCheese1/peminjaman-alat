<?php

namespace App\Listeners;

use App\Services\NotificationService;
use App\Events\ReturnSubmitted;

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
