<?php

namespace Database\Seeders;

use App\Enums\AuthProvider;
use App\Enums\DiabetesStatus;
use App\Enums\Gender;
use App\Enums\UserType;
use App\Enums\UserProtocolStatus;
use App\Models\FastingProtocol;
use App\Models\MobileProfile;
use App\Models\User;
use App\Models\UserAuthProvider;
use App\Models\UserProtocol;
use App\Models\FgbRecord;
use App\Models\SafetyAlert;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class FakePatientsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $protocols = FastingProtocol::all();
        $passwordHash = Hash::make('User1234');

        // We will seed 50 fake patients
        for ($i = 1; $i <= 50; $i++) {
            $email = "patient{$i}@example.com";
            
            // 1. Create User
            $user = User::create([
                'id' => Str::uuid(),
                'email' => $email,
                'type' => UserType::MOBILE,
            ]);

            // 2. Create Auth Provider
            UserAuthProvider::create([
                'user_id' => $user->id,
                'provider' => AuthProvider::EMAIL,
                'provider_id' => $email,
                'password_hash' => $passwordHash,
            ]);

            // 3. Create Mobile Profile
            $gender = $faker->randomElement([Gender::MALE, Gender::FEMALE]);
            $firstName = ($gender === Gender::MALE) ? $faker->firstNameMale : $faker->firstNameFemale;
            $lastName = $faker->lastName;
            $name = "{$firstName} {$lastName}";

            MobileProfile::create([
                'user_id' => $user->id,
                'name' => $name,
                'age' => $faker->numberBetween(18, 75),
                'gender' => $gender,
                'diabetes_status' => $faker->randomElement([
                    DiabetesStatus::HEALTHY,
                    DiabetesStatus::PREDIABETES,
                    DiabetesStatus::T2DM
                ]),
                'bmi' => $faker->randomFloat(1, 18.5, 34.9),
                'disclaimer_accepted' => true,
            ]);

            // 4. Assign Fasting Protocol (approx 70% of patients have active protocol)
            if ($faker->boolean(70) && $protocols->isNotEmpty()) {
                $protocol = $protocols->random();
                UserProtocol::create([
                    'user_id' => $user->id,
                    'fasting_protocol_id' => $protocol->id,
                    'status' => UserProtocolStatus::ACTIVE,
                    'start_date' => now()->subDays($faker->numberBetween(1, 10)),
                ]);
            }

            // 5. Seed FGB Records (Check-ins) (between 1 and 7 records)
            $fgbCount = $faker->numberBetween(1, 7);
            $latestRecord = null;

            for ($j = 0; $j < $fgbCount; $j++) {
                $value = $faker->numberBetween(60, 260); // some severe, some normal
                $timestamp = now()->subDays($j)->subHours($faker->numberBetween(1, 12));

                $fgb = FgbRecord::create([
                    'user_id' => $user->id,
                    'value_mg_dl' => $value,
                    'context_tag' => $faker->randomElement(['morning', 'before_meal', 'after_meal', 'bedtime']),
                    'client_timestamp' => $timestamp,
                    'server_timestamp' => $timestamp,
                ]);

                if (!$latestRecord || $timestamp->gt($latestRecord->server_timestamp)) {
                    $latestRecord = $fgb;
                }

                // 6. Generate Safety Alerts dynamically based on FBG values (approx 20% of severe readings)
                if ($value < 70 || $value > 200) {
                    $isSevere = ($value < 55 || $value > 250);
                    $alertType = '';
                    
                    if ($value < 70) {
                        $alertType = $isSevere ? 'hypo_severe' : 'hypo_mild';
                        $message = "Kadar gula darah sangat rendah ({$value} mg/dL). Pasien disarankan mengonsumsi karbohidrat cepat serap.";
                    } else {
                        $alertType = $isSevere ? 'hyper_severe' : 'hyper_mild';
                        $message = "Kadar gula darah sangat tinggi ({$value} mg/dL). Pasien disarankan memantau gejala dan beristirahat.";
                    }

                    // 50% chance the alert is unacknowledged (which determines Risk level)
                    $acknowledgedAt = $faker->boolean(50) ? now()->subMinutes($faker->numberBetween(10, 200)) : null;

                    SafetyAlert::create([
                        'user_id' => $user->id,
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
