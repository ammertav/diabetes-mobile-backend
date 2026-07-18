<?php

namespace App\Actions\Fasting;

use App\DTO\FastingLogWebFilterData;
use App\Enums\FastingLogStatus;
use App\Models\FastingLog;

class ListAdminFastingLogsAction
{
    public function execute(FastingLogWebFilterData $dto): array
    {
        $query = FastingLog::with([
            'userProtocol.user.mobileProfile',
            'userProtocol.protocol',
        ]);

        if (!empty($dto->search)) {
            $query->where(function ($q) use ($dto) {
                $q->whereHas('userProtocol.user.mobileProfile', function ($p) use ($dto) {
                    $p->where('name', 'like', "%{$dto->search}%");
                })->orWhereHas('userProtocol.user', function ($u) use ($dto) {
                    $u->where('email', 'like', "%{$dto->search}%");
                })->orWhereHas('userProtocol.protocol', function ($pr) use ($dto) {
                    $pr->where('name', 'like', "%{$dto->search}%");
                });
            });
        }

        if (!empty($dto->status) && $dto->status !== 'all') {
            $query->where('status', $dto->status);
        }

        if (!empty($dto->date)) {
            $query->whereDate('planned_date', $dto->date);
        }

        $logs = $query->orderBy('planned_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10, ['*'], 'page', $dto->page);

        $totalLogs = FastingLog::count();
        $completedLogs = FastingLog::where('status', FastingLogStatus::COMPLETED->value)->count();
        $skippedLogs = FastingLog::where('status', FastingLogStatus::SKIPPED->value)->count();
        $missedLogs = FastingLog::where('status', FastingLogStatus::MISSED->value)->count();
        $adherenceRate = $totalLogs > 0 ? round(($completedLogs / $totalLogs) * 100, 1) : 0;

        return [
            'stats' => [
                'total_logs' => $totalLogs,
                'completed_logs' => $completedLogs,
                'skipped_logs' => $skippedLogs,
                'missed_logs' => $missedLogs,
                'adherence_rate' => $adherenceRate,
            ],
            'logs' => $logs,
        ];
    }
}
