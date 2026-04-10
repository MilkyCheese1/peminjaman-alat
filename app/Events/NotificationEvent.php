<?php

namespace App\Events;

use App\Models\Borrowing;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;

/**
 * Base event class untuk semua notification events
 */
abstract class NotificationEvent
{
    use SerializesModels;

    public Borrowing $borrowing;
    public ?string $reason = null;

    public function __construct(Borrowing $borrowing, ?string $reason = null)
    {
        $this->borrowing = $borrowing;
        $this->reason = $reason;
    }
}

/**
 * Triggered when a new borrowing request is created
 */
class BorrowingCreated extends NotificationEvent
{
    public function __construct(Borrowing $borrowing)
    {
        parent::__construct($borrowing);
    }
}

/**
 * Triggered when borrowing is approved by admin
 */
class BorrowingApproved extends NotificationEvent
{
    public function __construct(Borrowing $borrowing)
    {
        parent::__construct($borrowing);
    }
}

/**
 * Triggered when borrowing is rejected by admin
 */
class BorrowingRejected extends NotificationEvent
{
    public function __construct(Borrowing $borrowing, string $reason = null)
    {
        parent::__construct($borrowing, $reason);
    }
}

/**
 * Triggered when item is ready for pickup
 */
class ReturnReady extends NotificationEvent
{
    public function __construct(Borrowing $borrowing)
    {
        parent::__construct($borrowing);
    }
}

/**
 * Triggered when return is submitted
 */
class ReturnSubmitted extends NotificationEvent
{
    public function __construct(Borrowing $borrowing)
    {
        parent::__construct($borrowing);
    }
}

/**
 * Triggered when return is verified by admin
 */
class ReturnVerified extends NotificationEvent
{
    public function __construct(Borrowing $borrowing)
    {
        parent::__construct($borrowing);
    }
}

/**
 * Triggered when return is overdue
 */
class ReturnOverdue extends NotificationEvent
{
    public function __construct(Borrowing $borrowing)
    {
        parent::__construct($borrowing);
    }
}
