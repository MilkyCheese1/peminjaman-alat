<?php

namespace App\Traits;

use App\Events\BorrowingCreated;
use App\Events\BorrowingApproved;
use App\Events\BorrowingRejected;
use App\Events\ReturnReady;
use App\Events\ReturnVerified;
use App\Events\ReturnOverdue;

/**
 * Trait untuk mengeluarkan notification events dari Borrowing model
 * 
 * Gunakan di model Borrowing:
 * use TriggerNotifications;
 * 
 * Kemudian di controller:
 * $borrowing->triggerApprovedEvent();
 * $borrowing->triggerRejectedEvent('Alasan ditolak');
 * dll
 */
trait TriggerNotifications
{
    /**
     * Trigger event ketika borrowing baru dibuat
     */
    public function triggerCreatedEvent()
    {
        event(new BorrowingCreated($this));
        return $this;
    }

    /**
     * Trigger event ketika borrowing disetujui
     */
    public function triggerApprovedEvent()
    {
        event(new BorrowingApproved($this));
        return $this;
    }

    /**
     * Trigger event ketika borrowing ditolak
     */
    public function triggerRejectedEvent(?string $reason = null)
    {
        event(new BorrowingRejected($this, $reason));
        return $this;
    }

    /**
     * Trigger event ketika item siap untuk diambil
     */
    public function triggerReturnReadyEvent()
    {
        event(new ReturnReady($this));
        return $this;
    }

    /**
     * Trigger event ketika return diverifikasi
     */
    public function triggerReturnVerifiedEvent()
    {
        event(new ReturnVerified($this));
        return $this;
    }

    /**
     * Trigger event ketika return overdue
     */
    public function triggerReturnOverdueEvent()
    {
        event(new ReturnOverdue($this));
        return $this;
    }
}
