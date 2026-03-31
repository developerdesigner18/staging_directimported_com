<?php

namespace App\Enum;

enum DocumentStatus: string
{
    case PENDING = 'PENDING';
    case VERIFIED = 'VERIFIED';
    case REJECTED='REJECTED';
}
