<?php

namespace App\Actions\Notification;

use App\Models\UserNotification;

class MarkNotificationAsReadAction
{
    public function execute(string $userId, int $id): UserNotification
    {
        /** @var UserNotification $notification */
        $notification = UserNotification::query()
            ->where('user_id', $userId)
            ->where('id', $id)
            ->firstOrFail();

        $notification->update(['read_at' => now()]);

        return $notification->fresh();
    }
}
