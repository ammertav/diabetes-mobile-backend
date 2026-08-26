<?php

namespace App\Actions\Fasting;

use App\Models\User;
use App\Models\UserProtocol;
use App\Models\FastingLog;
use App\Enums\UserProtocolStatus;
use App\Enums\FastingLogStatus;
use App\Exceptions\AlreadyConfirmedException;
use Illuminate\Support\Facades\DB;

class ConfirmFastingLogAction
{
    public function execute(User $user, array $data): FastingLog
    {
        $userProtocol = UserProtocol::query()
            ->where('user_id', $user->id)
            ->where('status', UserProtocolStatus::ACTIVE)
            ->firstOrFail();

        $log = FastingLog::query()
            ->where('user_protocol_id', $userProtocol->id)
            ->where('planned_date', $data['planned_date'])
            ->first();

        if (!$log) {
            throw new \Exception('No planned fasting', 404);
        }

        if (!$log->status->isPlanned()) {
            throw new AlreadyConfirmedException($log);
        }

        $now = now();

        DB::transaction(function () use ($log, $data, $now) {
            if ($data['is_completed']) {
                $log->update([
                    'status' => FastingLogStatus::COMPLETED,
                    'started_at' => $now,
                    'mood' => $data['mood'] ?? null,
                    'notes' => $data['notes'] ?? null,
                    'confirmed_at' => $now,
                ]);
            } else {
                $log->update([
                    'status' => FastingLogStatus::SKIPPED,
                    'skip_reason' => $data['skip_reason'] ?? null,
                    'notes' => $data['notes'] ?? null,
                    'confirmed_at' => $now,
                ]);
            }
        });

        return $log->fresh();
    }
}
