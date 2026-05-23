<?php

namespace App\Http\Controllers\API\V1;

use App\Enums\FbgTrendPeriod;
use App\Enums\FastingLogStatus;
use App\Http\Controllers\Controller;
use App\Models\FastingLog;
use App\Models\FgbRecord;
use App\Models\SafetyAlert;
use App\Services\CsvExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rules\Enum as EnumRule;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $today = now()->toDateString();

        $activeProtocol = $user->activeProtocol()->with('protocol')->first();

        $todayLog = FastingLog::whereHas('userProtocol', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereDate('planned_date', $today)
            ->orderByDesc('planned_date')
            ->first();

        $fbgBaseQuery = FgbRecord::query()->where('user_id', $user->id);

        $lastRecord = (clone $fbgBaseQuery)
            ->latest('server_timestamp')
            ->first();

        $fbgSummary = [
            'weekly_avg' => $this->formatAverage((clone $fbgBaseQuery)->where('server_timestamp', '>=', now()->subDays(7))->avg('value_mg_dl')),
            'monthly_avg' => $this->formatAverage((clone $fbgBaseQuery)->where('server_timestamp', '>=', now()->subDays(30))->avg('value_mg_dl')),
            'last_value' => $lastRecord ? (float) $lastRecord->value_mg_dl : null,
            'last_measured_at' => $lastRecord ? Carbon::parse($lastRecord->server_timestamp)->toISOString() : null,
            'min_30d' => $this->formatAverage((clone $fbgBaseQuery)->where('server_timestamp', '>=', now()->subDays(30))->min('value_mg_dl')),
            'max_30d' => $this->formatAverage((clone $fbgBaseQuery)->where('server_timestamp', '>=', now()->subDays(30))->max('value_mg_dl')),
            'fasting_day_avg' => $this->formatAverage((clone $fbgBaseQuery)->where('server_timestamp', '>=', now()->subDays(30))->where('is_fasting_day', true)->avg('value_mg_dl')),
            'non_fasting_day_avg' => $this->formatAverage((clone $fbgBaseQuery)->where('server_timestamp', '>=', now()->subDays(30))->where('is_fasting_day', false)->avg('value_mg_dl')),
        ];

        $streak = $this->calculateStreak($user->id, $today);
        $adherence = $this->calculateAdherence($user->id, $today);

        return response()->json([
            'data' => [
                'today' => [
                    'is_fasting_day' => $todayLog !== null,
                    'fasting_status' => $this->resolveFastingStatus($todayLog),
                    'protocol_name' => $activeProtocol?->protocol?->name,
                ],
                'fbg_summary' => $fbgSummary,
                'streak' => $streak,
                'adherence' => $adherence,
                'motivation' => $this->resolveMotivation(),
            ],
        ]);
    }

    public function fbgTrend(Request $request)
    {
        $validated = $request->validate([
            'period' => ['nullable', new EnumRule(FbgTrendPeriod::class)],
        ]);

        $period = FbgTrendPeriod::from($validated['period'] ?? FbgTrendPeriod::THIRTY_DAYS->value);

        $records = FgbRecord::query()->where('user_id', $request->user()->id)
            ->where('server_timestamp', '>=', now()->subDays($period->days()))
            ->orderBy('server_timestamp', 'asc')
            ->get();

        return response()->json([
            'data' => $records->map(function (FgbRecord $record) {
                return [
                    'date' => Carbon::parse($record->server_timestamp)->toDateString(),
                    'value_mg_dl' => (float) $record->value_mg_dl,
                    'is_fasting_day' => (bool) $record->is_fasting_day,
                    'context_tag' => $record->context_tag,
                ];
            }),
        ]);
    }

    public function export(Request $request, CsvExportService $csvExportService)
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'include' => ['nullable', 'string'],
        ]);

        $includes = collect(explode(',', $validated['include'] ?? ''))
            ->map(fn($value) => trim($value))
            ->filter()
            ->unique()
            ->values();

        $allowedIncludes = ['fbg', 'fasting_logs', 'alerts'];
        $includes = $includes->intersect($allowedIncludes);

        if ($includes->isEmpty()) {
            $includes = collect($allowedIncludes);
        }

        $start = Carbon::parse($validated['start_date'])->startOfDay();
        $end = Carbon::parse($validated['end_date'])->endOfDay();

        $sections = [];
        $userId = $request->user()->id;

        if ($includes->contains('fbg')) {
            $sections[] = $this->makeFgbSection($userId, $start, $end);
        }

        if ($includes->contains('fasting_logs')) {
            $sections[] = $this->makeFastingLogsSection($userId, $start, $end);
        }

        if ($includes->contains('alerts')) {
            $sections[] = $this->makeAlertsSection($userId, $start, $end);
        }

        $filename = sprintf('dashboard-export-%s-%s.csv', $validated['start_date'], $validated['end_date']);

        return $csvExportService->streamSections($filename, $sections);
    }

    private function makeFgbSection(string $userId, Carbon $start, Carbon $end): array
    {
        $records = FgbRecord::query()
            ->where('user_id', $userId)
            ->where('server_timestamp', '>=', $start)
            ->where('server_timestamp', '<=', $end)
            ->orderBy('server_timestamp', 'asc')
            ->get([
                'id',
                'user_protocol_id',
                'fasting_log_id',
                'value_mg_dl',
                'context_tag',
                'is_fasting_day',
                'client_timestamp',
                'server_timestamp',
                'created_at',
            ]);

        return [
            'title' => 'FBG Records',
            'headers' => [
                'id',
                'user_protocol_id',
                'fasting_log_id',
                'value_mg_dl',
                'context_tag',
                'is_fasting_day',
                'client_timestamp',
                'server_timestamp',
                'created_at',
            ],
            'rows' => $records->map(fn(FgbRecord $record) => [
                $record->id,
                $record->user_protocol_id,
                $record->fasting_log_id,
                (string) $record->value_mg_dl,
                $record->context_tag,
                $record->is_fasting_day ? 'yes' : 'no',
                $record->client_timestamp ? Carbon::parse($record->client_timestamp)->toDateTimeString() : null,
                $record->server_timestamp ? Carbon::parse($record->server_timestamp)->toDateTimeString() : null,
                $record->created_at?->toDateTimeString(),
            ])->toArray(),
        ];
    }

    private function makeFastingLogsSection(string $userId, Carbon $start, Carbon $end): array
    {
        $records = FastingLog::query()
            ->whereHas('userProtocol', fn($query) => $query->where('user_id', $userId))
            ->whereBetween('planned_date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('planned_date', 'asc')
            ->get([
                'id',
                'user_protocol_id',
                'planned_date',
                'started_at',
                'ended_at',
                'status',
                'mood',
                'skip_reason',
                'actual_duration_min',
                'notes',
                'confirmed_at',
                'created_at',
            ]);

        return [
            'title' => 'Fasting Logs',
            'headers' => [
                'id',
                'user_protocol_id',
                'planned_date',
                'started_at',
                'ended_at',
                'status',
                'mood',
                'skip_reason',
                'actual_duration_min',
                'notes',
                'confirmed_at',
                'created_at',
            ],
            'rows' => $records->map(fn(FastingLog $record) => [
                $record->id,
                $record->user_protocol_id,
                $record->planned_date?->toDateString(),
                $record->started_at?->toDateTimeString(),
                $record->ended_at?->toDateTimeString(),
                $record->status?->value,
                $record->mood?->value,
                $record->skip_reason,
                $record->actual_duration_min,
                $record->notes,
                $record->confirmed_at?->toDateTimeString(),
                $record->created_at?->toDateTimeString(),
            ])->toArray(),
        ];
    }

    private function makeAlertsSection(string $userId, Carbon $start, Carbon $end): array
    {
        $records = SafetyAlert::query()
            ->where('user_id', $userId)
            ->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->orderBy('created_at', 'asc')
            ->get([
                'id',
                'fgb_record_id',
                'type',
                'message',
                'acknowledged_at',
                'action_taken',
                'created_at',
            ]);

        return [
            'title' => 'Safety Alerts',
            'headers' => [
                'id',
                'fgb_record_id',
                'type',
                'message',
                'acknowledged_at',
                'action_taken',
                'created_at',
            ],
            'rows' => $records->map(fn(SafetyAlert $record) => [
                $record->id,
                $record->fgb_record_id,
                $record->type,
                $record->message,
                $record->acknowledged_at ? Carbon::parse($record->acknowledged_at)->toDateTimeString() : null,
                $record->action_taken,
                $record->created_at?->toDateTimeString(),
            ])->toArray(),
        ];
    }

    private function resolveFastingStatus(?FastingLog $log): string
    {
        if (!$log) {
            return 'none';
        }

        if ($log->status === FastingLogStatus::COMPLETED) {
            return $log->ended_at ? 'completed' : 'in_progress';
        }

        return $log->status->value;
    }

    private function calculateStreak(string $userId, string $today): array
    {
        $logs = FastingLog::whereHas('userProtocol', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->whereDate('planned_date', '<=', $today)
            ->orderBy('planned_date')
            ->get(['planned_date', 'status']);

        $best = 0;
        $current = 0;
        $running = 0;

        foreach ($logs as $log) {
            if ($log->status === FastingLogStatus::COMPLETED) {
                $running++;
                $best = max($best, $running);
            } else {
                $running = 0;
            }
        }

        foreach ($logs->reverse() as $log) {
            if ($log->status === FastingLogStatus::COMPLETED) {
                $current++;
                continue;
            }
            break;
        }

        return [
            'current' => $current,
            'best' => $best,
            'total_fasting_days' => $logs->where('status', FastingLogStatus::COMPLETED)->count(),
        ];
    }

    private function calculateAdherence(string $userId, string $today): array
    {
        $baseQuery = FastingLog::whereHas('userProtocol', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });

        $thisWeekStart = Carbon::parse($today)->startOfWeek();
        $thisMonthStart = Carbon::parse($today)->startOfMonth();

        $thisWeekTotal = (clone $baseQuery)->whereDate('planned_date', '>=', $thisWeekStart)->count();
        $thisWeekDone = (clone $baseQuery)->whereDate('planned_date', '>=', $thisWeekStart)
            ->where('status', FastingLogStatus::COMPLETED)
            ->count();

        $thisMonthTotal = (clone $baseQuery)->whereDate('planned_date', '>=', $thisMonthStart)->count();
        $thisMonthDone = (clone $baseQuery)->whereDate('planned_date', '>=', $thisMonthStart)
            ->where('status', FastingLogStatus::COMPLETED)
            ->count();

        $overallTotal = (clone $baseQuery)->count();
        $overallDone = (clone $baseQuery)->where('status', FastingLogStatus::COMPLETED)->count();

        return [
            'this_week' => $thisWeekTotal > 0 ? round($thisWeekDone / $thisWeekTotal, 3) : 0.0,
            'this_month' => $thisMonthTotal > 0 ? round($thisMonthDone / $thisMonthTotal, 3) : 0.0,
            'overall' => $overallTotal > 0 ? round($overallDone / $overallTotal, 3) : 0.0,
        ];
    }

    private function resolveMotivation(): array
    {
        $dayName = Carbon::now()->locale('id')->translatedFormat('l');

        return [
            'title' => "Semangat hari {$dayName}!",
            'body' => 'Tetap konsisten, catat hasil puasa, dan teruskan perjalanan kesehatanmu hari ini.',
        ];
    }

    private function formatAverage(mixed $value): ?float
    {
        if ($value === null) {
            return null;
        }

        return round((float) $value, 1);
    }
}
