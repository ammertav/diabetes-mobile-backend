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
            $createProtocol = function ($data, $days = []) {
                $protocolId = Str::uuid();

                DB::table('fasting_protocols')->insert([
                    'id' => $protocolId,
                    'name' => $data['name'],
                    'type' => $data['type'],
                    'start_time' => $data['start_time'] ?? '18:00',
                    'end_time' => $data['end_time'] ?? '10:00',
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
            ISO Day: 1=Senin, 2=Selasa, 3=Rabu, 4=Kamis, 5=Jumat, 6=Sabtu, 7=Minggu
            */

            // 1. Puasa Senin-Kamis
            $createProtocol([
                'name' => 'Puasa Senin-Kamis',
                'type' => 'sunnah',
                'start_time' => '18:00',
                'end_time' => '07:00',
                'duration_hours' => 13,
                'description' => 'Puasa sunnah rutin setiap hari Senin dan Kamis untuk pemeliharaan metabolik.',
            ], [1, 4]);

            // 2. Puasa Daud
            $createProtocol([
                'name' => 'Puasa Daud (Selang-Seling)',
                'type' => 'sunnah',
                'start_time' => '18:00',
                'end_time' => '07:00',
                'duration_hours' => 13,
                'description' => 'Puasa selang-seling sehari puasa sehari tidak (simulasi jadwal Senin, Rabu, Jumat, Minggu).',
            ], [1, 3, 5, 7]);

            // 3. Puasa Ayyamul Bidh
            $createProtocol([
                'name' => 'Puasa Ayyamul Bidh',
                'type' => 'sunnah',
                'start_time' => '18:00',
                'end_time' => '07:00',
                'duration_hours' => 13,
                'description' => 'Puasa sunnah pertengahan bulan Hijriah (simulasi hari Rabu, Kamis, Jumat).',
            ], [3, 4, 5]);

            // 4. Puasa Syawal
            $createProtocol([
                'name' => 'Puasa Syawal 6 Hari',
                'type' => 'sunnah',
                'start_time' => '18:00',
                'end_time' => '07:00',
                'duration_hours' => 13,
                'description' => 'Puasa sunnah 6 hari di bulan Syawal.',
            ], [1, 2, 3, 4, 5, 6]);

            // 5. Intermittent Fasting 16:8 (Daily)
            $createProtocol([
                'name' => 'Intermittent Fasting 16:8',
                'type' => 'intermittent',
                'start_time' => '20:00',
                'end_time' => '12:00',
                'duration_hours' => 16,
                'description' => 'Protokol populer puasa 16 jam dengan jendela makan 8 jam setiap hari.',
            ], [1, 2, 3, 4, 5, 6, 7]);

            // 6. Intermittent 14:10 (Weekdays)
            $createProtocol([
                'name' => 'Intermittent 14:10 (Weekdays)',
                'type' => 'intermittent',
                'start_time' => '20:00',
                'end_time' => '10:00',
                'duration_hours' => 14,
                'description' => 'Protokol puasa sedang 14 jam khusus hari kerja Senin hingga Jumat.',
            ], [1, 2, 3, 4, 5]);

            // 7. Intermittent Fasting 18:6 (Advanced)
            $createProtocol([
                'name' => 'Intermittent Fasting 18:6',
                'type' => 'intermittent',
                'start_time' => '19:00',
                'end_time' => '13:00',
                'duration_hours' => 18,
                'description' => 'Protokol puasa intensif 18 jam untuk meningkatkan sensitivitas insulin.',
            ], [1, 2, 3, 4, 5, 6, 7]);

            // 8. Warrior Fasting 20:4
            $createProtocol([
                'name' => 'Warrior Diet 20:4',
                'type' => 'intermittent',
                'start_time' => '20:00',
                'end_time' => '16:00',
                'duration_hours' => 20,
                'description' => 'Puasa 20 jam dengan jendela makan singkat 4 jam di malam hari.',
            ], [1, 2, 3, 4, 5, 6]);

            // 9. Circadian Rhythm Fasting 13:11
            $createProtocol([
                'name' => 'Circadian Rhythm Fasting 13:11',
                'type' => 'intermittent',
                'start_time' => '19:00',
                'end_time' => '08:00',
                'duration_hours' => 13,
                'description' => 'Menyelaraskan puasa dengan siklus tidur dan ritme sirkadian tubuh.',
            ], [1, 2, 3, 4, 5, 6, 7]);

            // 10. Weekend Fasting Rest
            $createProtocol([
                'name' => 'Weekend Fasting Rest',
                'type' => 'custom',
                'start_time' => '20:00',
                'end_time' => '08:00',
                'duration_hours' => 12,
                'description' => 'Puasa ringan 12 jam khusus di akhir pekan Sabtu dan Minggu.',
            ], [6, 7]);

            // 11. Overnight Recovery 12:12
            $createProtocol([
                'name' => 'Overnight Recovery 12:12',
                'type' => 'custom',
                'start_time' => '19:00',
                'end_time' => '07:00',
                'duration_hours' => 12,
                'description' => 'Puasa malam hari untuk memulihkan fungsi pencernaan dan mengontrol FGB.',
            ], [1, 2, 3, 4, 5, 6, 7]);

            // 12. Mid-Week Metabolic Boost
            $createProtocol([
                'name' => 'Mid-Week Metabolic Boost',
                'type' => 'custom',
                'start_time' => '18:00',
                'end_time' => '10:00',
                'duration_hours' => 16,
                'description' => 'Puasa pemacu metabolisme di pertengahan minggu (Selasa dan Kamis).',
            ], [2, 4]);

            // 13. Post-Holiday Reset Protocol
            $createProtocol([
                'name' => 'Post-Holiday Reset 16:8',
                'type' => 'custom',
                'start_time' => '20:00',
                'end_time' => '12:00',
                'duration_hours' => 16,
                'description' => 'Protokol pemulihan kadar gula darah pasca liburan selama 3 hari awal minggu.',
            ], [1, 2, 3]);

            // 14. Early Bird Fasting 15:9
            $createProtocol([
                'name' => 'Early Bird Fasting 15:9',
                'type' => 'intermittent',
                'start_time' => '17:00',
                'end_time' => '08:00',
                'duration_hours' => 15,
                'description' => 'Memulai puasa sore lebih awal pukul 17:00 hingga sarapan pukul 08:00.',
            ], [1, 2, 3, 4, 5]);

            // 15. Friday Blessing Fasting
            $createProtocol([
                'name' => 'Puasa Sunnah Jumat',
                'type' => 'sunnah',
                'start_time' => '18:00',
                'end_time' => '07:00',
                'duration_hours' => 13,
                'description' => 'Puasa sunnah khusus di hari Jumat (diiringi puasa Kamis/Sabtu).',
            ], [4, 5]);
        });
    }
}
