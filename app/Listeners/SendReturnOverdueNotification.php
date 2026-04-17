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
        try {
            $this->notificationService->notifyReturnOverdue($event->borrowing);
        } catch (\Exception $e) {
            \Log::error('Error sending return overdue notification: ' . $e->getMessage(), [
                'borrowing_id' => $event->borrowing->id_borrowing ?? null,
                'error' => $e
            ]);
        }
    }
}
