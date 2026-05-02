<?php

namespace App\Enums;

enum FastingLogStatus: string
{
    case PLANNED = 'planned';
    case COMPLETED = 'completed';
    case MISSED = 'missed';
    case SKIPPED = 'skipped';

    public function isPlanned(): bool
    {
        return $this === self::PLANNED;
    }
}
