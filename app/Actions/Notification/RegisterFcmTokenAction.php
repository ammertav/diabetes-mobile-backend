<?php

namespace App\Actions\Notification;

use App\Models\FcmDevice;

class RegisterFcmTokenAction
{
    public function execute(string $userId, array $data): FcmDevice
    {
        return FcmDevice::updateOrCreate(
            ['fcm_token' => $data['fcm_token']],
            [
                'user_id' => $userId,
                'platform' => $data['platform'] ?? null,
                'last_used_at' => now(),
            ]
        );
    }
}
