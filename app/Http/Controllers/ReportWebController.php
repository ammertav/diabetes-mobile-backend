<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FgbRecord;
use App\Models\FastingLog;
use App\Models\SafetyAlert;
use App\Enums\UserType;
use App\Enums\FastingLogStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportWebController extends Controller
{
    public function index(Request $request)
    {
        $patients = User::query()->where('type', UserType::MOBILE)
            ->with('mobileProfile')
            ->get();

        return view('reports.index', compact('patients'));
    }

    public function patientReport(Request $request, string $userId)
    {
        $patient = User::query()->where('type', UserType::MOBILE)
            ->with(['mobileProfile', 'activeProtocol.protocol'])
            ->findOrFail($userId);

        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : now()->subDays(30)->startOfDay();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : now()->endOfDay();

        $fbgStats = $this->getFbgStats($userId, $startDate, $endDate);
        $fastingStats = $this->getFastingStats($userId, $startDate, $endDate);
        $alertStats = $this->getAlertStats($userId, $startDate, $endDate);

        return view('reports.patient-report', compact('patient', 'startDate', 'endDate', 'fbgStats', 'fastingStats', 'alertStats'));
    }

    private function getFbgStats(string $userId, Carbon $start, Carbon $end): array
    {
        $records = FgbRecord::query()->where('user_id', $userId)
            ->whereBetween('server_timestamp', [$start, $end], 'and')
            ->get();

        return [
            'avg' => $records->avg('value_mg_dl') ? round($records->avg('value_mg_dl'), 1) : null,
            'min' => $records->min('value_mg_dl'),
            'max' => $records->max('value_mg_dl'),
            'count' => $records->count(),
        ];
    }

    private function getFastingStats(string $userId, Carbon $start, Carbon $end): array
    {
        $logs = FastingLog::query()->whereHas('userProtocol', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
            ->whereBetween('planned_date', [$start->toDateString(), $end->toDateString()], 'and')
            ->get();

        $total = $logs->count();
        $completed = $logs->where('status', FastingLogStatus::COMPLETED)->count();

        return [
            'total' => $total,
            'completed' => $completed,
            'adherence' => $total > 0 ? round($completed / $total * 100, 1) : 0,
        ];
    }

    private function getAlertStats(string $userId, Carbon $start, Carbon $end): array
    {
        $alerts = SafetyAlert::query()->where('user_id', $userId)
            ->whereBetween('created_at', [$start, $end], 'and')
            ->get();

        return [
            'total' => $alerts->count(),
            'unresolved' => $alerts->whereNull('acknowledged_at')->count(),
            'list' => $alerts->take(10), // Limit last 10 alert entries
        ];
    }
}
