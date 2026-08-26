<?php

namespace Database\Seeders;

use App\Enums\FastingLogMood;
use App\Enums\FastingLogStatus;
use App\Models\FastingLog;
use App\Models\UserProtocol;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class FastingLogSeeder extends Seeder
{
    public function run(): void
    {
        $userProtocols = UserProtocol::with('protocol.days')->get();

        foreach ($userProtocols as $userProtocol) {
            $startDate = Carbon::parse($userProtocol->start_date);
            $endDate = Carbon::now();
            $protocol = $userProtocol->protocol;
            $fastingDays = $protocol->days->pluck('day')->toArray();
            $durationHours = $protocol->duration_hours ?? 16;

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $dayOfWeek = $date->dayOfWeekIso; // 1 (Mon) - 7 (Sun)
                
                // Only create a log if it's a fasting day according to the protocol
                if (in_array($dayOfWeek, $fastingDays)) {
                    $rand = rand(1, 100);
                    if ($rand <= 85) {
                        $status = FastingLogStatus::COMPLETED;
                        $startedAt = $date->copy()->setTime(8, 0, 0);
                        $endedAt = $startedAt->copy()->addHours($durationHours);
                        $actualDuration = ($durationHours * 60) + rand(-30, 30);
                        $mood = rand(1, 100) > 30 ? FastingLogMood::GOOD : FastingLogMood::NEUTRAL;
                        $confirmedAt = $endedAt->copy()->addMinutes(rand(5, 60));
                        $skipReason = null;
                        $notes = 'Fasting completed smoothly.';
                    } elseif ($rand <= 95) {
                        $status = FastingLogStatus::SKIPPED;
                        $startedAt = null;
                        $endedAt = null;
                        $actualDuration = null;
                        $mood = null;
                        $confirmedAt = null;
                        $skipReason = rand(1, 100) > 50 ? 'Merasa kurang sehat' : 'Acara keluarga';
                        $notes = 'Skipped fasting today.';
                    } else {
                        $status = FastingLogStatus::MISSED;
                        $startedAt = null;
                        $endedAt = null;
                        $actualDuration = null;
                        $mood = null;
                        $confirmedAt = null;
                        $skipReason = null;
                        $notes = 'Missed fasting.';
                    }

                    FastingLog::updateOrCreate(
                        [
                            'user_protocol_id' => $userProtocol->id,
                            'planned_date' => $date->toDateString(),
                        ],
                        [
                            'id' => (string) Str::uuid(),
                            'started_at' => $startedAt,
                            'ended_at' => $endedAt,
                            'status' => $status,
                            'mood' => $mood,
                            'skip_reason' => $skipReason,
                            'notes' => $notes,
                            'actual_duration_min' => $actualDuration,
                            'confirmed_at' => $confirmedAt,
                        ]
                    );
                }
            }
        }
    }
}
