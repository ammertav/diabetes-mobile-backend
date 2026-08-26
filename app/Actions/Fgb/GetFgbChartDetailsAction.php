<?php

namespace App\Actions\Fgb;

use App\Models\FgbRecord;
use Illuminate\Support\Carbon;

class GetFgbChartDetailsAction
{
    public function execute(string $period, string $group, int $index): array
    {
        $query = FgbRecord::query()
            ->with(['user.mobileProfile']);

        // Filter by group (diabetes status)
        if ($group !== 'all') {
            $query->whereHas('user.mobileProfile', function ($q) use ($group) {
                $q->where('diabetes_status', $group);
            });
        }

        // Determine start and end dates
        if ($period === 'weekly') {
            $start = Carbon::now()->subWeeks($index)->startOfWeek();
            $end = Carbon::now()->subWeeks($index)->endOfWeek();
            $query->whereBetween('server_timestamp', [$start, $end]);
            $title = "Week of " . $start->format('M d, Y');
        } elseif ($period === 'monthly') {
            $date = Carbon::now()->subMonths($index);
            $start = $date->clone()->startOfMonth();
            $end = $date->clone()->endOfMonth();
            $query->whereBetween('server_timestamp', [$start, $end]);
            $title = $date->format('F Y');
        } else {
            // Daily
            $date = Carbon::now()->subDays($index);
            $start = $date->clone()->startOfDay();
            $end = $date->clone()->endOfDay();
            $query->whereBetween('server_timestamp', [$start, $end]);
            $title = $date->format('l, M d, Y');
        }

        $records = $query->latest('server_timestamp')->get();

        return [
            'title' => $title,
            'avg_fgb' => round($records->avg('value_mg_dl') ?? 0, 1),
            'count' => $records->count(),
            'records' => $records,
        ];
    }
}
