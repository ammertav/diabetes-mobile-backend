<?php

namespace App\Actions\Patient;

use App\DTO\PatientFilterData;
use App\Enums\UserType;
use App\Models\User;

class ListPatientsAction
{
    public function execute(PatientFilterData $dto): array
    {
        $query = User::query()
            ->where('type', UserType::MOBILE->value)
            ->with([
                'mobileProfile',
                'activeProtocol.protocol',
                'latestCheckin',
                'safetyAlerts'
            ])
            ->search($dto->search)
            ->filterByRisk($dto->risk)
            ->filterByProtocol($dto->protocol)
            ->filterByCheckinDate($dto->date);

        $patients = $query->latest()->paginate(10, ['*'], 'page', $dto->page);

        // Stats (overall division)
        $statsQuery = User::query()->where('type', UserType::MOBILE->value);
        $totalPatients = (clone $statsQuery)->count();
        $protocolPatients = (clone $statsQuery)->whereHas('activeProtocol')->count();
        $highRiskPatients = (clone $statsQuery)->whereHas('safetyAlerts', function ($q) {
            $q->whereNull('acknowledged_at')
              ->whereIn('type', ['hypo_severe', 'hyper_severe']);
        })->count();

        return [
            'stats' => [
                'total_patients' => $totalPatients,
                'protocol_patients' => $protocolPatients,
                'high_risk_patients' => $highRiskPatients,
            ],
            'patients' => $patients,
        ];
    }
}
