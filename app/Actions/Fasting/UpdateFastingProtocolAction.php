<?php

namespace App\Actions\Fasting;

use App\Models\FastingProtocol;
use App\Models\FastingProtocolDay;
use Illuminate\Support\Facades\DB;

class UpdateFastingProtocolAction
{
    public function execute(FastingProtocol $protocol, array $data): FastingProtocol
    {
        return DB::transaction(function () use ($protocol, $data) {
            $protocol->update([
                'name' => $data['name'],
                'type' => $data['type'],
                'start_time' => $data['start_time'] ?? $protocol->start_time ?? '18:00',
                'end_time' => $data['end_time'] ?? $protocol->end_time ?? '10:00',
                'duration_hours' => $data['duration_hours'],
                'description' => $data['description'] ?? null,
            ]);

            if (isset($data['days'])) {
                $protocol->days()->delete();
                foreach ($data['days'] as $day) {
                    FastingProtocolDay::create([
                        'fasting_protocol_id' => $protocol->id,
                        'day' => $day,
                    ]);
                }
            }

            return $protocol->fresh(['days']);
        });
    }
}
