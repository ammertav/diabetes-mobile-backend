<?php

namespace App\Actions\Notification;

use App\Models\UserNotificationSetting;

class UpdateNotificationSettingsAction
{
    protected GetNotificationSettingsAction $getSettingsAction;

    public function __construct(GetNotificationSettingsAction $getSettingsAction)
    {
        $this->getSettingsAction = $getSettingsAction;
    }

    public function execute(string $userId, array $data): UserNotificationSetting
    {
        $settings = $this->getSettingsAction->execute($userId);
        $settings->update($data);

        return $settings;
    }
}
