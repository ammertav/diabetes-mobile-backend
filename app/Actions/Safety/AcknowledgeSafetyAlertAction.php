<?php

namespace App\Actions\Safety;

use App\Models\User;
use App\Models\SafetyAlert;

class AcknowledgeSafetyAlertAction
{
    public function execute(User $user, string $id, ?string $actionTaken): SafetyAlert
    {
        $alert = $user->safetyAlert($id);

        if ($alert->acknowledged_at !== null) {
            throw new \Exception('Alert already acknowledged', 422);
        }

        $alert->update([
            'acknowledged_at' => now(),
            'action_taken' => $actionTaken,
        ]);

        return $alert->fresh();
    }
}
