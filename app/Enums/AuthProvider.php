<?php

namespace App\Enums;

enum AuthProvider: string
{
    case EMAIL = 'email';
    case GOOGLE = 'google';
    case APPLE = 'apple';
}
