<?php

namespace App\Actions\Fasting;

use App\Models\User;
use App\Models\FastingLog;
use App\Enums\FastingLogStatus;

class ListFastingLogsAction
{
    public function execute(User $user, array $filters): array
    {
        $baseQuery = FastingLog::whereHas('userProtocol', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });

        if (!empty($filters['start_date'])) {
            $baseQuery->whereDate('planned_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $baseQuery->whereDate('planned_date', '<=', $filters['end_date']);
        }

        if (!empty($filters['status'])) {
            $baseQuery->where('status', $filters['status']);
        }

        // summary
        $total = (clone $baseQuery)->count();
        $completed = (clone $baseQuery)->where('status', FastingLogStatus::COMPLETED)->count();
        $skipped = (clone $baseQuery)->where('status', FastingLogStatus::SKIPPED)->count();

        // cursor pagination
        $query = clone $baseQuery;

        if (!empty($filters['cursor'])) {
            $query->where('id', '<', $filters['cursor']);
        }

        $limit = min($filters['limit'] ?? 20, 50);

        $logs = $query
            ->orderBy('planned_date', 'desc')
            ->orderBy('id', 'desc')
            ->limit($limit + 1)
            ->get();

        $hasNext = $logs->count() > $limit;
        $logs = $logs->take($limit);
        $nextCursor = $hasNext ? $logs->last()->id : null;

        return [
            'logs' => $logs,
            'summary' => [
                'total' => $total,
                'completed' => $completed,
                'skipped' => $skipped,
                'adherence_rate' => $total > 0 ? round($completed / $total, 3) : 0,
            ],
            'pagination' => [
                'has_next' => $hasNext,
                'next_cursor' => $nextCursor,
            ]
        ];
    }
}
