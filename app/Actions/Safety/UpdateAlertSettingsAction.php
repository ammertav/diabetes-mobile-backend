<?php

namespace App\Actions\Safety;

use App\Models\User;
use App\Models\UserAlertSetting;

class UpdateAlertSettingsAction
{
    public function execute(User $user, array $data): UserAlertSetting
    {
        return $user->alertSetting()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );
    }
}
