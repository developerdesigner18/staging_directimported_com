<?php

namespace App\Enum;

enum BookingStatus: string
{
    case PROCESSING = 'processing';
    case APPROVED = 'approved';
    case CANCELLED = 'cancelled';
    case CONFIRMED = 'confirmed';
    case FINISHED = 'finished';

    public function label(): string
    {
        return match($this) {
            self::PROCESSING => 'Processing - Approval pending',
            self::APPROVED => 'Approved - Payment remaining',
            self::CANCELLED => 'Not approved / Cancelled',
            self::CONFIRMED => 'Confirmed & Paid',
            self::FINISHED => 'Finished & Returned',
        };
    }
}
