<?php

namespace App\Actions\Fasting;

use App\Models\FastingLog;
use App\Enums\FastingLogStatus;
use Illuminate\Support\Collection;

class GetStreakSummaryAction
{
    public function execute(string $userId, string $today): array
    {
        $logs = FastingLog::query()->whereHas('userProtocol', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->whereDate('planned_date', '<=', $today)
            ->orderBy('planned_date')
            ->get(['planned_date', 'status']);

        $best = $this->calculateBestStreak($logs);
        $current = $this->calculateCurrentStreak($logs);
        $lastCompletedLog = $logs->where('status', FastingLogStatus::COMPLETED)->last();

        return [
            'current_streak' => $current,
            'best_streak' => $best,
            'total_fasting_days' => $logs->where('status', FastingLogStatus::COMPLETED)->count(),
            'last_fasting_date' => $lastCompletedLog ? $lastCompletedLog->planned_date : null,
        ];
    }

    private function calculateBestStreak(Collection $logs): int
    {
        $best = 0;
        $running = 0;

        foreach ($logs as $log) {
            if ($log->status === FastingLogStatus::COMPLETED) {
                $running++;
                $best = max($best, $running);
            } else {
                $running = 0;
            }
        }

        return $best;
    }

    private function calculateCurrentStreak(Collection $logs): int
    {
        $current = 0;

        foreach ($logs->reverse() as $log) {
            if ($log->status === FastingLogStatus::COMPLETED) {
                $current++;
                continue;
            }
            break;
        }

        return $current;
    }
}
