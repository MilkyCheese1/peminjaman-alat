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
        try {
            $this->notificationService->notifyReturnReady($event->borrowing);
        } catch (\Exception $e) {
            \Log::error('Error sending return ready notification: ' . $e->getMessage(), [
                'borrowing_id' => $event->borrowing->id_borrowing ?? null,
                'error' => $e
            ]);
        }
    }
}
