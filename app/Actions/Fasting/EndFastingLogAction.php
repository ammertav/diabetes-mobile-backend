<?php

namespace App\Actions\Fasting;

use App\Models\FastingLog;
use App\Enums\FastingLogStatus;

class EndFastingLogAction
{
    public function execute(int $id): FastingLog
    {
        $log = FastingLog::findOrFail($id);

        if ($log->status !== FastingLogStatus::COMPLETED) {
            throw new \Exception('Invalid state', 400);
        }

        if ($log->ended_at) {
            throw new \Exception('Already ended', 409);
        }

        $end = now();

        $duration = $log->started_at
            ? $end->diffInMinutes($log->started_at)
            : null;

        $log->update([
            'ended_at' => $end,
            'actual_duration_min' => $duration,
        ]);

        return $log;
    }
}
