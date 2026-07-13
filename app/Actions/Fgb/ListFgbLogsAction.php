<?php

namespace App\Actions\Fgb;

use App\DTO\FgbLogFilterData;
use App\Models\FgbRecord;
use App\Models\SafetyAlert;
use Illuminate\Support\Carbon;

class ListFgbLogsAction
{
    public function execute(FgbLogFilterData $dto): array
    {
        $query = FgbRecord::query()
            ->with(['user.mobileProfile']);

        $this->applyFilters($query, $dto);

        $logs = $query->latest('server_timestamp')
            ->paginate(10, ['*'], 'page', $dto->page);

        return [
            'stats' => $this->calculateStats(),
            'logs' => $logs,
        ];
    }

    private function applyFilters($query, FgbLogFilterData $dto): void
    {
        if ($dto->search) {
            $search = '%' . $dto->search . '%';
            $query->whereHas('user', function ($qu) use ($search) {
                $qu->where('email', 'like', $search)
                   ->orWhereHas('mobileProfile', function ($qp) use ($search) {
                       $qp->where('name', 'like', $search);
                   });
            });
        }

        if ($dto->status && $dto->status !== 'all') {
            $this->applyStatusFilter($query, $dto->status);
        }
    }

    private function applyStatusFilter($query, string $status): void
    {
        switch ($status) {
            case 'normal':
                $query->whereBetween('value_mg_dl', [70, 99.9]);
                break;
            case 'elevated':
                $query->whereBetween('value_mg_dl', [100, 140]);
                break;
            case 'high':
                $query->where('value_mg_dl', '>', 140);
                break;
            case 'low':
                $query->where('value_mg_dl', '<', 70);
                break;
        }
    }

    private function calculateStats(): array
    {
        $now = Carbon::now();
        $sevenDaysAgo = $now->copy()->subDays(7);
        $fourteenDaysAgo = $now->copy()->subDays(14);

        $recentFgbQuery = FgbRecord::where('server_timestamp', '>=', $sevenDaysAgo);
        $avgFgb = round($recentFgbQuery->avg('value_mg_dl') ?? 104);

        $avgFgbPrev = FgbRecord::whereBetween('server_timestamp', [$fourteenDaysAgo, $sevenDaysAgo])
            ->avg('value_mg_dl') ?? 104;

        $avgFgbDiff = $avgFgbPrev > 0 ? round((($avgFgb - $avgFgbPrev) / $avgFgbPrev) * 100, 1) : 0.0;

        $totalRecent = $recentFgbQuery->count();
        $inRangeRecent = FgbRecord::where('server_timestamp', '>=', $sevenDaysAgo)
            ->whereBetween('value_mg_dl', [70, 130])
            ->count();
        $targetRangePercent = $totalRecent > 0 ? round(($inRangeRecent / $totalRecent) * 100, 1) : 78.4;

        $abnormalAlerts = SafetyAlert::where('created_at', '>=', $sevenDaysAgo)
            ->whereNull('acknowledged_at')
            ->count();

        return [
            'avg_fgb' => $avgFgb,
            'avg_fgb_diff' => $avgFgbDiff,
            'target_range_percent' => $targetRangePercent,
            'abnormal_alerts' => $abnormalAlerts,
        ];
    }
}
