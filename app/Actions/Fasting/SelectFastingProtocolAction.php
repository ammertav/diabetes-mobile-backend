<?php

namespace App\Actions\Fasting;

use App\Enums\FastingLogStatus;
use App\Enums\UserProtocolStatus;
use App\Exceptions\SameProtocolActiveException;
use App\Models\FastingLog;
use App\Models\FastingProtocol;
use App\Models\User;
use App\Models\UserProtocol;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SelectFastingProtocolAction
{
    public function execute(User $user, string $protocolId, string $startDate): UserProtocol
    {
        $protocol = FastingProtocol::with('days')->findOrFail($protocolId);

        if ($protocol->days->isEmpty()) {
            throw new \DomainException("Protokol '{$protocol->name}' belum memiliki jadwal hari puasa.");
        }

        $activeProtocol = $user->activeProtocol()->first();
        if ($activeProtocol && $activeProtocol->fasting_protocol_id === $protocol->id) {
            throw new SameProtocolActiveException($activeProtocol);
        }

        return DB::transaction(function () use ($user, $protocol, $startDate) {
            UserProtocol::query()
                ->where('user_id', $user->id)
                ->where('status', UserProtocolStatus::ACTIVE)
                ->update([
                    'status' => UserProtocolStatus::COMPLETED,
                    'end_date' => now()->toDateString(),
                ]);

            $userProtocol = UserProtocol::create([
                'user_id' => $user->id,
                'fasting_protocol_id' => $protocol->id,
                'start_date' => $startDate,
                'status' => UserProtocolStatus::ACTIVE,
            ]);

            $this->createPlannedLogs($userProtocol->id, $protocol->days->pluck('day')->all(), $startDate);

            return $userProtocol;
        });
    }

    private function createPlannedLogs(string $userProtocolId, array $days, string $startDate): void
    {
        $fastingDays = array_map('intval', $days);
        $start = Carbon::parse($startDate);
        $end = $start->copy()->addWeeks(4);
        $now = now();
        $logs = [];

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if (in_array($date->dayOfWeekIso, $fastingDays, true)) {
                $logs[] = [
                    'id' => (string) Str::uuid(),
                    'user_protocol_id' => $userProtocolId,
                    'planned_date' => $date->toDateString(),
                    'status' => FastingLogStatus::PLANNED->value,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (!empty($logs)) {
            DB::table('fasting_logs')->insert($logs);
        }
    }
}
