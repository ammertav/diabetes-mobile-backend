<?php

namespace App\Actions\Notification;

use App\Models\UserNotification;
use Illuminate\Contracts\Pagination\CursorPaginator;

class GetNotificationHistoryAction
{
    public function execute(string $userId, array $filters): CursorPaginator
    {
        $limit = $filters['limit'] ?? 20;

        $query = UserNotification::query()
            ->where('user_id', $userId)
            ->when(isset($filters['type']), function ($q) use ($filters) {
                $q->where('type', $filters['type']);
            })
            ->when(isset($filters['read']), function ($q) use ($filters) {
                if ($filters['read']) {
                    $q->whereNotNull('read_at');
                } else {
                    $q->whereNull('read_at');
                }
            })
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');

        return $query->cursorPaginate($limit);
    }
}
