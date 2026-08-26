<?php

namespace App\Actions\Notification;

use App\Models\UserNotificationSetting;

class GetNotificationSettingsAction
{
    public function execute(string $userId): UserNotificationSetting
    {
        return UserNotificationSetting::firstOrCreate(
            ['user_id' => $userId],
            [
                'niat_puasa_enabled' => true,
                'niat_puasa_time' => '20:00',
                'sahur_enabled' => true,
                'sahur_time' => '03:30',
                'fbg_reminder_enabled' => true,
                'fbg_reminder_time' => '17:45',
                'motivation_enabled' => true,
            ]
        );
    }
}
