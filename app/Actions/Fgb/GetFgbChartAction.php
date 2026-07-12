<?php

namespace App\Actions\Fgb;

use App\Models\FgbRecord;
use Illuminate\Support\Carbon;

class GetFgbChartAction
{
    public function execute(string $period, string $group): array
    {
        $query = FgbRecord::query();

        // Filter by group (diabetes status)
        if ($group !== 'all') {
            $query->whereHas('user.mobileProfile', function ($q) use ($group) {
                $q->where('diabetes_status', $group);
            });
        }

        $data = [];

        if ($period === 'weekly') {
            // Last 4 weeks
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
                ];
            }
        } elseif ($period === 'monthly') {
            // Last 6 months
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
                ];
            }
        } else {
            // Daily: last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);

                $avg = $query->clone()
                    ->whereDate('server_timestamp', $date->toDateString())
                    ->avg('value_mg_dl') ?? 100;

                $data[] = [
                    'label' => $date->format('D'),
                    'value' => round($avg, 1),
                    'is_current' => $date->isToday(),
                ];
            }
        }

        return $data;
    }
}
