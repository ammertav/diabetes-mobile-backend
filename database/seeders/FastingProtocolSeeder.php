<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FastingProtocolSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // Helper biar gak repetitif
            $createProtocol = function ($data, $days = []) {
                $protocolId = Str::uuid();

                DB::table('fasting_protocols')->insert([
                    'id' => $protocolId,
                    'name' => $data['name'],
                    'type' => $data['type'],
                    'duration_hours' => $data['duration_hours'] ?? null,
                    'description' => $data['description'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($days as $day) {
                    DB::table('fasting_protocol_days')->insert([
                        'fasting_protocol_id' => $protocolId,
                        'day' => $day,
                    ]);
                }
            };

            /*
            ISO Day:
            1 = Senin
            2 = Selasa
            3 = Rabu
            4 = Kamis
            5 = Jumat
            6 = Sabtu
            7 = Minggu
            */

            // 🕌 Puasa Senin Kamis
            $createProtocol([
                'name' => 'Puasa Senin-Kamis',
                'type' => 'sunnah',
                'duration_hours' => 13,
                'description' => 'Puasa sunnah setiap hari Senin dan Kamis',
            ], [1, 4]);

            // 🕌 Puasa Daud (selang-seling → contoh: Senin, Rabu, Jumat)
            $createProtocol([
                'name' => 'Puasa Daud (Sample)',
                'type' => 'sunnah',
                'duration_hours' => 13,
                'description' => 'Puasa selang-seling (contoh hari tetap untuk simulasi)',
            ], [1, 3, 5]);

            // ⏱ Intermittent Fasting 16:8 (setiap hari)
            $createProtocol([
                'name' => 'Intermittent Fasting 16:8',
                'type' => 'intermittent',
                'duration_hours' => 16,
                'description' => 'Puasa 16 jam setiap hari',
            ], [1, 2, 3, 4, 5, 6, 7]);

            // ⏱ Intermittent 14:10 (weekday only)
            $createProtocol([
                'name' => 'Intermittent 14:10 (Weekdays)',
                'type' => 'intermittent',
                'duration_hours' => 14,
                'description' => 'Puasa hanya hari kerja',
            ], [1, 2, 3, 4, 5]);

            // 🎯 Custom: Weekend fasting
            $createProtocol([
                'name' => 'Weekend Fasting',
                'type' => 'custom',
                'duration_hours' => 12,
                'description' => 'Puasa hanya di akhir pekan',
            ], [6, 7]);
        });
    }
}
