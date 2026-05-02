<?php

namespace App\Enums;

enum UserProtocolStatus: string
{
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }
}
