<?php

namespace App\Enum;

enum VehicleStatus: string
{
    case AVAILABLE = 'available';
    case SOLD = 'sold';

    public function label(): string
    {
        return match ($this) {
            self::AVAILABLE => 'Available',
            self::SOLD => 'Sold',
        };
    }
}
