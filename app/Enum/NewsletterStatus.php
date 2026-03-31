<?php

namespace App\Enum;

enum NewsletterStatus: string
{
    case SUBSCRIBED = 'SUBSCRIBED';
    case UNSUBSCRIBED = 'UNSUBSCRIBED';
}
