<?php

namespace App\Actions\Fasting;

use App\Models\User;
use App\Models\UserProtocol;
use App\Models\FastingLog;
use App\Enums\UserProtocolStatus;
use App\Enums\FastingLogStatus;
use App\Exceptions\SameProtocolActiveException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SelectFastingProtocolAction
{
    public function execute(User $user, int $protocolId, string $startDate): void
    {
        $activeProtocol = $user->activeProtocol()->first();

        if ($activeProtocol && $activeProtocol->fasting_protocol_id === $protocolId) {
            throw new SameProtocolActiveException($activeProtocol);
        }

        DB::transaction(function () use ($user, $protocolId, $startDate) {
            UserProtocol::query()
                ->where('user_id', $user->id)
                ->where('status', UserProtocolStatus::ACTIVE)
                ->update([
                    'status' => UserProtocolStatus::COMPLETED,
                    'end_date' => now()->toDateString(),
                ]);

            $userProtocol = UserProtocol::create([
                'user_id' => $user->id,
                'fasting_protocol_id' => $protocolId,
                'start_date' => $startDate,
                'status' => UserProtocolStatus::ACTIVE,
            ]);

            $protocol = $userProtocol->protocol;

            $start = Carbon::parse($startDate);
            $end = $start->copy()->addWeeks(4);

            $fastingDays = $protocol->days->pluck('day')->values()->toArray();

            for ($date = $start; $date->lte($end); $date->addDay()) {
                if (in_array($date->dayOfWeekIso, $fastingDays)) {
                    FastingLog::create([
                        'user_protocol_id' => $userProtocol->id,
                        'planned_date' => $date->toDateString(),
                        'status' => FastingLogStatus::PLANNED,
                    ]);
                }
            }
        });
    }
}
