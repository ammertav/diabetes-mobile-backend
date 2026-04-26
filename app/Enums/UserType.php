<?php

namespace App\Enums;

enum UserType: string
{
    case ADMIN = 'admin';
    case MOBILE = 'mobile';

    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }
}
