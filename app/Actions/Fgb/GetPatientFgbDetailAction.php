<?php

namespace App\Actions\Fgb;

use App\Models\User;
use App\Models\FgbRecord;
use Illuminate\Support\Carbon;

class GetPatientFgbDetailAction
{
    public function execute(string $userId): array
    {
        $user = User::query()->where('id', $userId)->with('mobileProfile')->firstOrFail();

        // Get recent 10 FGB records for chart and list
        $records = FgbRecord::query()
            ->where('user_id', $userId)
            ->latest('server_timestamp')
            ->limit(10)
            ->get();

        // Prepare chart data (chronological order)
        $chartData = [];
        $previousDateString = null;
        $chronologicalRecords = $records->reverse()->values();

        foreach ($chronologicalRecords as $index => $record) {
            $timestamp = Carbon::parse($record->server_timestamp);
            $dateString = $timestamp->toDateString();
            
            $showSeparator = false;
            if ($index > 0 && $previousDateString !== null && $previousDateString !== $dateString) {
                $showSeparator = true;
            }

            $val = $record->value_mg_dl;
            if ($val < 70) {
                $barClass = 'bg-blue-500/25 hover:bg-blue-500/45';
            } elseif ($val >= 70 && $val < 100) {
                $barClass = 'bg-emerald-500/25 hover:bg-emerald-500/45';
            } elseif ($val >= 100 && $val <= 140) {
                $barClass = 'bg-orange-500/25 hover:bg-orange-500/45';
            } else {
                $barClass = 'bg-red-500/25 hover:bg-red-500/45';
            }

            $chartData[] = [
                'label' => $timestamp->format('H:i'),
                'date_label' => $timestamp->format('d M'),
                'value' => $record->value_mg_dl,
                'show_separator' => $showSeparator,
                'is_first' => ($index === 0),
                'bar_class' => $barClass,
            ];

            $previousDateString = $dateString;
        }

        return [
            'patient' => [
                'id' => 'PX-' . strtoupper(substr($user->id, 0, 4)),
                'name' => $user->mobileProfile?->name ?? 'Patient',
                'photo' => 'https://ui-avatars.com/api/?name=' . urlencode($user->mobileProfile?->name ?? 'Patient') . '&background=random&color=fff&size=128',
                'diabetes_status' => strtoupper($user->mobileProfile?->diabetes_status?->value ?? $user->mobileProfile?->diabetes_status ?? 'HEALTHY'),
                'email' => $user->email,
            ],
            'chart_data' => $chartData,
            'records' => $records->map(function ($record) {
                $val = $record->value_mg_dl;
                if ($val < 70) {
                    $status = 'Low';
                    $badge = 'bg-blue-100 text-blue-700';
                } elseif ($val >= 70 && $val < 100) {
                    $status = 'Normal';
                    $badge = 'bg-secondary-container text-on-secondary-container';
                } elseif ($val >= 100 && $val <= 140) {
                    $status = 'Elevated';
                    $badge = 'bg-orange-100 text-orange-700';
                } else {
                    $status = 'High';
                    $badge = 'bg-tertiary-container text-on-tertiary-container';
                }

                return [
                    'id' => $record->id,
                    'value' => $val,
                    'status' => $status,
                    'badge' => $badge,
                    'timestamp' => Carbon::parse($record->server_timestamp)->format('M d, h:i A'),
                ];
            })->values()->toArray(),
        ];
    }
}
