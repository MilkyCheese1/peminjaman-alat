<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

// Notification Events
use App\Events\BorrowingCreated;
use App\Events\BorrowingApproved;
use App\Events\BorrowingRejected;
use App\Events\ReturnReady;
use App\Events\ReturnSubmitted;
use App\Events\ReturnVerified;
use App\Events\ReturnOverdue;

// Notification Listeners
use App\Listeners\SendBorrowingCreatedNotification;
use App\Listeners\SendBorrowingApprovedNotification;
use App\Listeners\SendBorrowingRejectedNotification;
use App\Listeners\SendReturnReadyNotification;
use App\Listeners\SendReturnSubmittedNotification;
use App\Listeners\SendReturnVerifiedNotification;
use App\Listeners\SendReturnOverdueNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        
        // Notification Events
        BorrowingCreated::class => [
            SendBorrowingCreatedNotification::class,
        ],
        BorrowingApproved::class => [
            SendBorrowingApprovedNotification::class,
        ],
        BorrowingRejected::class => [
            SendBorrowingRejectedNotification::class,
        ],
        ReturnReady::class => [
            SendReturnReadyNotification::class,
        ],
        ReturnSubmitted::class => [
            SendReturnSubmittedNotification::class,
        ],
        ReturnVerified::class => [
            SendReturnVerifiedNotification::class,
        ],
        ReturnOverdue::class => [
            SendReturnOverdueNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
