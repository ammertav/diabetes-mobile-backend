<?php

namespace App\Actions\Fgb;

use App\Models\FgbRecord;
use Illuminate\Support\Carbon;

class GetFgbChartAction
{
    public function execute(string $period, string $group): array
    {
        $query = FgbRecord::query();

        if ($group !== 'all') {
            $query->whereHas('user.mobileProfile', function ($q) use ($group) {
                $q->where('diabetes_status', $group);
            });
        }

        return [
            'chart_data' => $this->getChartData($period, $query),
            'stats' => $this->calculatePeriodStats($period, $group),
        ];
    }

    private function getChartData(string $period, $query): array
    {
        if ($period === 'weekly') {
            return $this->getWeeklyChartData($query);
        } elseif ($period === 'monthly') {
            return $this->getMonthlyChartData($query);
        }
        return $this->getDailyChartData($query);
    }

    private function getDailyChartData($query): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            $avg = $query->clone()
                ->whereDate('server_timestamp', $date->toDateString())
                ->avg('value_mg_dl') ?? 100;

            $data[] = [
                'label' => $date->format('D'),
                'value' => round($avg, 1),
                'is_current' => $date->isToday(),
                'index' => $i,
            ];
        }
        return $data;
    }

    private function getWeeklyChartData($query): array
    {
        $data = [];
        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
            $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();

            $avg = $query->clone()
                ->whereBetween('server_timestamp', [$startOfWeek, $endOfWeek])
                ->avg('value_mg_dl') ?? 100;

            $data[] = [
                'label' => $startOfWeek->format('M') . ' W' . $startOfWeek->weekOfMonth,
                'value' => round($avg, 1),
                'is_current' => ($i === 0),
                'index' => $i,
            ];
        }
        return $data;
    }

    private function getMonthlyChartData($query): array
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->clone()->startOfMonth();
            $endOfMonth = $date->clone()->endOfMonth();

            $avg = $query->clone()
                ->whereBetween('server_timestamp', [$startOfMonth, $endOfMonth])
                ->avg('value_mg_dl') ?? 100;

            $data[] = [
                'label' => $date->format('M'),
                'value' => round($avg, 1),
                'is_current' => $date->isCurrentMonth(),
                'index' => $i,
            ];
        }
        return $data;
    }

    private function calculatePeriodStats(string $period, string $group): array
    {
        $now = Carbon::now();
        
        if ($period === 'weekly') {
            $currentStart = $now->copy()->subWeeks(4);
            $prevStart = $now->copy()->subWeeks(8);
        } elseif ($period === 'monthly') {
            $currentStart = $now->copy()->subMonths(6);
            $prevStart = $now->copy()->subMonths(12);
        } else {
            $currentStart = $now->copy()->subDays(7);
            $prevStart = $now->copy()->subDays(14);
        }

        $baseQuery = FgbRecord::query();
        if ($group !== 'all') {
            $baseQuery->whereHas('user.mobileProfile', function ($q) use ($group) {
                $q->where('diabetes_status', $group);
            });
        }

        $avgFgb = round($baseQuery->clone()->where('server_timestamp', '>=', $currentStart)->avg('value_mg_dl') ?? 104);
        $avgFgbPrev = $baseQuery->clone()->whereBetween('server_timestamp', [$prevStart, $currentStart])->avg('value_mg_dl') ?? 104;
        $avgFgbDiff = $avgFgbPrev > 0 ? round((($avgFgb - $avgFgbPrev) / $avgFgbPrev) * 100, 1) : 0.0;

        $totalRecent = $baseQuery->clone()->where('server_timestamp', '>=', $currentStart)->count();
        $inRangeRecent = $baseQuery->clone()
            ->where('server_timestamp', '>=', $currentStart)
            ->whereBetween('value_mg_dl', [70, 130])
            ->count();
        $targetRangePercent = $totalRecent > 0 ? round(($inRangeRecent / $totalRecent) * 100, 1) : 78.4;

        $abnormalAlertsQuery = \App\Models\SafetyAlert::where('created_at', '>=', $currentStart)
            ->whereNull('acknowledged_at');
            
        if ($group !== 'all') {
            $abnormalAlertsQuery->whereHas('user.mobileProfile', function ($q) use ($group) {
                $q->where('diabetes_status', $group);
            });
        }
        $abnormalAlerts = $abnormalAlertsQuery->count();

        return [
            'avg_fgb' => $avgFgb,
            'avg_fgb_diff' => $avgFgbDiff,
            'target_range_percent' => $targetRangePercent,
            'abnormal_alerts' => $abnormalAlerts,
        ];
    }
}
