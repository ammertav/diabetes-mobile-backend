<?php

namespace App\Utilities;

use App\Models\AuditTrail;
use App\Models\User;

class AuditLogger
{
    public static function log(
        ?User $user,
        string $action,
        string $actionLabel,
        string $description,
        ?string $ipAddress = null
    ): AuditTrail {
        $userName = $user ? ($user->adminProfile->name ?? $user->mobileProfile->name ?? $user->email) : 'System Admin';
        $userRole = $user ? ($user->isAdmin() ? 'Chief Admin' : 'Patient') : 'System Admin';

        return AuditTrail::create([
            'user_id' => $user?->id,
            'user_name' => $userName,
            'user_role' => $userRole,
            'action' => $action,
            'action_label' => $actionLabel,
            'description' => $description,
            'ip_address' => $ipAddress ?? request()->ip() ?? '127.0.0.1',
        ]);
    }
}
