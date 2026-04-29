<?php

namespace App\Enums;

enum ProtocolType: string
{
    case SUNNAH = 'sunnah';
    case INTERMITTENT = 'intermittent';
    case CUSTOM = 'custom';
}
