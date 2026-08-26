<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Enums\DiabetesStatus;
use App\Models\User;
use App\Models\FgbRecord;
use App\Models\SafetyAlert;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class FgbRecordSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $patients = User::where('type', UserType::MOBILE->value)
            ->with(['mobileProfile', 'activeProtocol.protocol'])
            ->get();

        if ($patients->isEmpty()) {
            $this->command->info('No mobile patients found. Run UserSeeder or FakePatientsSeeder first.');
            return;
        }

        foreach ($patients as $patient) {
            $diabetesStatus = $patient->mobileProfile?->diabetes_status ?? DiabetesStatus::HEALTHY->value;

            // Generate 20 readings for each patient spread over the last 30 days
            for ($i = 0; $i < 20; $i++) {
                $daysAgo = $i * 1.5; // roughly 20 records in 30 days
                $timestamp = Carbon::now()
                    ->subDays($daysAgo)
                    ->subHours($faker->numberBetween(0, 23))
                    ->subMinutes($faker->numberBetween(0, 59));

                // Determine realistic blood sugar range based on diabetes status
                if ($diabetesStatus === DiabetesStatus::HEALTHY->value) {
                    $value = $faker->randomElement([
                        $faker->numberBetween(70, 99), // normal fasting
                        $faker->numberBetween(100, 120), // slightly elevated post-meal
                    ]);
                } elseif ($diabetesStatus === DiabetesStatus::PREDIABETES->value) {
                    $value = $faker->randomElement([
                        $faker->numberBetween(100, 125), // prediabetes fasting
                        $faker->numberBetween(120, 160), // post-meal
                    ]);
                } else { // T2DM
                    $value = $faker->randomElement([
                        $faker->numberBetween(130, 180), // diabetic fasting
                        $faker->numberBetween(160, 260), // high readings
                        $faker->numberBetween(55, 69),   // occasional hypo
                    ]);
                }

                // Determine if it was a fasting day
                $isFastingDay = false;
                if ($patient->activeProtocol) {
                    $dayOfWeek = $timestamp->dayOfWeekIso; // 1 (Mon) - 7 (Sun)
                    $fastingDays = $patient->activeProtocol->protocol?->days ?? collect();
                    $isFastingDay = $fastingDays->contains('day', $dayOfWeek);
                }

                $contextTag = $faker->randomElement(['morning', 'before_meal', 'after_meal', 'bedtime', 'end_of_fast']);

                $fgb = FgbRecord::create([
                    'user_id' => $patient->id,
                    'value_mg_dl' => $value,
                    'context_tag' => $contextTag,
                    'is_fasting_day' => $isFastingDay,
                    'client_timestamp' => $timestamp,
                    'server_timestamp' => $timestamp,
                ]);

                // Create safety alerts for critical readings
                if ($value < 70 || $value > 200) {
                    $isSevere = ($value < 55 || $value > 250);
                    $alertType = '';
                    
                    if ($value < 70) {
                        $alertType = $isSevere ? 'hypo_severe' : 'hypo_mild';
                        $message = "Kadar gula darah rendah ({$value} mg/dL) terdeteksi pada konteks {$contextTag}.";
                    } else {
                        $alertType = $isSevere ? 'hyper_severe' : 'hyper_mild';
                        $message = "Kadar gula darah tinggi ({$value} mg/dL) terdeteksi pada konteks {$contextTag}.";
                    }

                    $acknowledgedAt = $faker->boolean(60) 
                        ? Carbon::parse($timestamp)->addMinutes($faker->numberBetween(15, 120)) 
                        : null;

                    SafetyAlert::create([
                        'user_id' => $patient->id,
                        'fgb_record_id' => $fgb->id,
                        'type' => $alertType,
                        'message' => $message,
                        'acknowledged_at' => $acknowledgedAt,
                    ]);
                }
            }
        }
    }
}
