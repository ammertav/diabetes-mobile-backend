<?php

namespace App\Enums;

enum FbgTrendPeriod: string
{
    case SEVEN_DAYS = '7d';
    case THIRTY_DAYS = '30d';
    case NINETY_DAYS = '90d';

    public function days(): int
    {
        return match ($this) {
            self::SEVEN_DAYS => 7,
            self::THIRTY_DAYS => 30,
            self::NINETY_DAYS => 90,
        };
    }
}
