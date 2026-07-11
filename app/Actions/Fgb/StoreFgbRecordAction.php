<?php

namespace App\Actions\Fgb;

use App\Models\User;
use App\Models\FgbRecord;
use App\Models\FastingLog;
use App\Models\UserAlertSetting;
use App\Models\SafetyAlert;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreFgbRecordAction
{
    public function execute(User $user, array $data): array
    {
        return DB::transaction(function () use ($user, $data) {
            $isFastingDay = FastingLog::whereHas('userProtocol', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
                ->whereDate('planned_date', now()->toDateString())
                ->exists();

            $activeProtocol = $user->userProtocol()->where('status', 'active')->first();

            $fgb = FgbRecord::create([
                'id' => (string) Str::uuid(),
                'user_id' => $user->id,
                'user_protocol_id' => $activeProtocol?->id,
                'value_mg_dl' => $data['value_mg_dl'],
                'context_tag' => $data['context_tag'],
                'fasting_log_id' => $data['fasting_log_id'] ?? null,
                'client_timestamp' => Carbon::parse($data['client_timestamp']),
                'server_timestamp' => now(),
                'is_fasting_day' => $isFastingDay,
            ]);

            $alertData = $this->checkAndCreateAlerts($user->id, $fgb);

            return [
                'fgb' => $fgb,
                'alert' => $alertData,
            ];
        });
    }

    private function checkAndCreateAlerts(string $userId, FgbRecord $fgb): array
    {
        $settings = UserAlertSetting::query()->where('user_id', $userId)->first();

        if (!$settings) {
            $settings = new UserAlertSetting([
                'hypo_severe' => 70,
                'hypo_mild' => 80,
                'hyper_mild' => 180,
                'hyper_severe' => 250,
            ]);
        }

        $value = $fgb->value_mg_dl;

        $type = null;
        $message = null;
        $action = null;

        if ($value < $settings->hypo_severe) {
            $type = 'hypo_severe';
            $message = "FBG Anda {$value} mg/dL — terlalu rendah (berbahaya).";
            $action = "Segera konsumsi gula dan hubungi tenaga medis.";
        } elseif ($value < $settings->hypo_mild) {
            $type = 'hypo_mild';
            $message = "FBG Anda {$value} mg/dL — di bawah normal.";
            $action = "Pertimbangkan konsumsi makanan manis.";
        } elseif ($value > $settings->hyper_severe) {
            $type = 'hyper_severe';
            $message = "FBG Anda {$value} mg/dL — sangat tinggi.";
            $action = "Segera konsultasi dokter.";
        } elseif ($value > $settings->hyper_mild) {
            $type = 'hyper_mild';
            $message = "FBG Anda {$value} mg/dL — di atas normal.";
            $action = "Pantau dan konsultasikan jika berlanjut.";
        }

        if (!$type) {
            return ['triggered' => false];
        }

        try {
            SafetyAlert::create([
                'user_id' => $userId,
                'fgb_record_id' => $fgb->id,
                'type' => $type,
                'message' => $message,
            ]);
        } catch (\Throwable $e) {
            // duplicate / race condition → ignore
        }

        return [
            'triggered' => true,
            'type' => $type,
            'message' => $message,
            'action' => $action,
        ];
    }
}
