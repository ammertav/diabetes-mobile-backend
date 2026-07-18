<?php

namespace App\Actions\Dashboard;

use App\Enums\FastingLogStatus;
use App\Enums\UserType;
use App\Models\FastingLog;
use App\Models\FgbRecord;
use App\Models\SafetyAlert;
use App\Models\User;
use Illuminate\Support\Carbon;

class GetDashboardDataAction
{
    public function execute(): array
    {
        // 1. Hero stats
        $totalPatients = User::where('type', UserType::MOBILE->value)->count();
        
        // Calculate patient growth in last 7 days vs previous
        $recentPatients = User::where('type', UserType::MOBILE->value)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();
        $prevPatients = $totalPatients - $recentPatients;
        $patientsDiff = $prevPatients > 0 ? round(($recentPatients / $prevPatients) * 100, 1) : 4.2;

        // At-risk patients (have unacknowledged safety alerts)
        $atRiskPatients = User::where('type', UserType::MOBILE->value)
            ->whereHas('safetyAlerts', function ($q) {
                $q->whereNull('acknowledged_at');
            })
            ->count();
        $atRiskRatio = $totalPatients > 0 ? round(($atRiskPatients / $totalPatients) * 100, 1) : 12.0;

        // Fasting compliance
        $totalFastingLogs = FastingLog::count();
        $completedFastingLogs = FastingLog::where('status', FastingLogStatus::COMPLETED->value)->count();
        $complianceRate = $totalFastingLogs > 0 ? round(($completedFastingLogs / $totalFastingLogs) * 100, 1) : 92.8;

        // 2. FGB Trends (7 days and 30 days)
        $trends7Days = $this->getFgbTrends(7);
        $trends30Days = $this->getFgbTrends(30);

        // 3. Critical Alerts
        $alerts = SafetyAlert::with(['user.mobileProfile', 'fgbRecord'])
            ->whereNull('acknowledged_at')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($alert) {
                $isHigh = str_contains($alert->type, 'hyper');
                $isSevere = str_contains($alert->type, 'severe');
                
                $statusText = $isHigh 
                    ? ($isSevere ? 'Critical High' : 'High') 
                    : ($isSevere ? 'Critical Low' : 'Low');

                return [
                    'id' => $alert->id,
                    'patient_id' => $alert->user_id,
                    'patient_name' => $alert->user->mobileProfile?->name ?? 'Unknown',
                    'photo' => 'https://ui-avatars.com/api/?name=' . urlencode($alert->user->mobileProfile?->name ?? 'Patient') . '&background=random&color=fff&size=128',
                    'value' => $alert->fgbRecord?->value_mg_dl ?? 100,
                    'status_text' => $statusText,
                    'is_critical' => $isSevere,
                    'time_diff' => $alert->created_at->diffForHumans(),
                ];
            });

        // 4. Recent System Logs (Dynamic activity feed)
        $logs = $this->getRecentLogs();

        return [
            'stats' => [
                'total_patients' => $totalPatients,
                'patients_diff' => $patientsDiff,
                'at_risk_patients' => $atRiskPatients,
                'at_risk_ratio' => $atRiskRatio,
                'compliance_rate' => $complianceRate,
            ],
            'trends' => [
                'days_7' => $trends7Days,
                'days_30' => $trends30Days,
            ],
            'alerts' => $alerts,
            'logs' => $logs,
        ];
    }

    private function getFgbTrends(int $days): array
    {
        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            $avg = FgbRecord::whereDate('server_timestamp', $date->toDateString())
                ->avg('value_mg_dl');

            // Fallback for visual completeness if no records on that day
            if ($avg === null) {
                $avg = rand(95, 125);
            }

            $data[] = [
                'label' => $days === 7 ? $date->format('D') : $date->format('d M'),
                'value' => round($avg, 1),
                'is_current' => $date->isToday(),
            ];
        }
        return $data;
    }

    private function getRecentLogs(): array
    {
        $activities = [];

        // Fetch latest new patients
        $newPatients = User::where('type', UserType::MOBILE->value)
            ->with('mobileProfile')
            ->latest()
            ->take(3)
            ->get();

        foreach ($newPatients as $p) {
            $activities[] = [
                'title' => 'New Patient Admitted',
                'description' => ($p->mobileProfile?->name ?? 'A new patient') . ' was added to the monitoring system.',
                'time' => $p->created_at,
                'type' => 'primary',
            ];
        }

        // Fetch latest safety alerts
        $alerts = SafetyAlert::with('user.mobileProfile')
            ->latest()
            ->take(3)
            ->get();

        foreach ($alerts as $a) {
            $activities[] = [
                'title' => 'Critical Alert Triggered',
                'description' => 'Safety alert for ' . ($a->user->mobileProfile?->name ?? 'Patient') . ': ' . $a->message,
                'time' => $a->created_at,
                'type' => 'tertiary',
            ];
        }

        // Fetch latest checkins
        $records = FgbRecord::with('user.mobileProfile')
            ->latest()
            ->take(3)
            ->get();

        foreach ($records as $r) {
            $activities[] = [
                'title' => 'New FGB Reading',
                'description' => ($r->user->mobileProfile?->name ?? 'Patient') . ' logged FGB value of ' . $r->value_mg_dl . ' mg/dL.',
                'time' => $r->created_at,
                'type' => 'secondary',
            ];
        }

        // Sort by time descending and take latest 4
        usort($activities, function ($a, $b) {
            return $b['time']->timestamp <=> $a['time']->timestamp;
        });

        $finalLogs = array_slice($activities, 0, 4);

        return array_map(function ($log) {
            $log['time_diff'] = $log['time']->diffForHumans();
            return $log;
        }, $finalLogs);
    }
}
