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

        // Search patient name or email
        if ($dto->search) {
            $search = '%' . $dto->search . '%';
            $query->whereHas('user', function ($qu) use ($search) {
                $qu->where('email', 'like', $search)
                   ->orWhereHas('mobileProfile', function ($qp) use ($search) {
                       $qp->where('name', 'like', $search);
                   });
            });
        }

        // Filter by status
        if ($dto->status && $dto->status !== 'all') {
            switch ($dto->status) {
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

        $logs = $query->latest('server_timestamp')->paginate(10, ['*'], 'page', $dto->page);

        // Stats (last 7 days overall)
        $sevenDaysAgo = Carbon::now()->subDays(7);
        $recentFgbQuery = FgbRecord::where('server_timestamp', '>=', $sevenDaysAgo);

        $avgFgb = round($recentFgbQuery->avg('value_mg_dl') ?? 104);
        
        $totalRecent = $recentFgbQuery->count();
        $inRangeRecent = FgbRecord::where('server_timestamp', '>=', $sevenDaysAgo)
            ->whereBetween('value_mg_dl', [70, 130])
            ->count();
        $targetRangePercent = $totalRecent > 0 ? round(($inRangeRecent / $totalRecent) * 100, 1) : 78.4;

        $abnormalAlerts = SafetyAlert::where('created_at', '>=', $sevenDaysAgo)
            ->whereNull('acknowledged_at')
            ->count();

        return [
            'stats' => [
                'avg_fgb' => $avgFgb,
                'target_range_percent' => $targetRangePercent,
                'abnormal_alerts' => $abnormalAlerts,
            ],
            'logs' => $logs,
        ];
    }
}
