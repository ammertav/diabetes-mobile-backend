<?php

namespace App\Enums;

enum CmsDayContext: string
{
    case Monday = 'monday';
    case Thursday = 'thursday';
    case General = 'general';

    public function label(): string
    {
        return match ($this) {
            self::Monday => 'Monday',
            self::Thursday => 'Thursday',
            self::General => 'General',
        };
    }
}
