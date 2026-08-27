<?php

namespace Database\Seeders;

use App\Enums\AuthProvider;
use App\Enums\DiabetesStatus;
use App\Enums\Gender;
use App\Enums\UserType;
use App\Models\AdminProfile;
use App\Models\MobileProfile;
use App\Models\User;
use App\Models\UserAuthProvider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Enums\UserProtocolStatus;
use App\Models\FastingProtocol;
use App\Models\UserAlertSetting;
use App\Models\UserNotificationSetting;
use App\Models\UserProtocol;
use App\Models\UserNotification;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🔥 ADMIN (web only)
        $admin = User::firstOrCreate(
            ['email' => 'admin@app.com'],
            [
                'id' => (string) Str::uuid(),
                'type' => UserType::ADMIN,
            ]
        );

        AdminProfile::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'name' => 'Admin Main',
                'age' => 35,
                'gender' => Gender::MALE,
            ]
        );

        UserAuthProvider::updateOrCreate(
            ['user_id' => $admin->id, 'provider' => AuthProvider::EMAIL],
            [
                'provider_id' => 'admin@app.com',
                'password_hash' => Hash::make('Admin1234'),
            ]
        );

        // 🔥 MOBILE USER 1 (Primary Mobile Test Account)
        $user1 = User::firstOrCreate(
            ['email' => 'user@app.com'],
            [
                'id' => (string) Str::uuid(),
                'type' => UserType::MOBILE,
            ]
        );

        MobileProfile::updateOrCreate(
            ['user_id' => $user1->id],
            [
                'name' => 'Mobile User',
                'age' => 29,
                'gender' => Gender::FEMALE,
                'diabetes_status' => DiabetesStatus::PREDIABETES,
                'bmi' => 24.3,
                'disclaimer_accepted' => true,
            ]
        );

        UserAuthProvider::updateOrCreate(
            ['user_id' => $user1->id, 'provider' => AuthProvider::EMAIL],
            [
                'provider_id' => 'user@app.com',
                'password_hash' => Hash::make('User1234'),
            ]
        );

        UserAlertSetting::updateOrCreate(
            ['user_id' => $user1->id],
            [
                'hypo_severe' => 70,
                'hypo_mild' => 80,
                'hyper_mild' => 180,
                'hyper_severe' => 250,
            ]
        );

        UserNotificationSetting::updateOrCreate(
            ['user_id' => $user1->id],
            [
                'niat_puasa_enabled' => true,
                'niat_puasa_time' => '20:00',
                'sahur_enabled' => true,
                'sahur_time' => '03:30',
                'fbg_reminder_enabled' => true,
                'fbg_reminder_time' => '17:45',
                'motivation_enabled' => true,
            ]
        );

        // Assign default active protocol if available
        $protocol = FastingProtocol::query()->where('name', 'Puasa Senin-Kamis')->first() ?? FastingProtocol::query()->first();
        if ($protocol) {
            UserProtocol::firstOrCreate(
                ['user_id' => $user1->id, 'status' => UserProtocolStatus::ACTIVE],
                [
                    'fasting_protocol_id' => $protocol->id,
                    'start_date' => now()->subDays(14)->toDateString(),
                ]
            );
        }

        // Seed initial notifications history for testing /api/v1/notifications/history
        UserNotification::firstOrCreate(
            ['user_id' => $user1->id, 'title' => 'Pengingat Niat Puasa'],
            [
                'type' => 'niat',
                'body' => 'Jangan lupa niat puasa untuk besok hari Senin.',
                'read_at' => null,
                'created_at' => now()->subHours(2),
            ]
        );

        UserNotification::firstOrCreate(
            ['user_id' => $user1->id, 'title' => 'Safety Alert Gula Darah'],
            [
                'type' => 'safety',
                'body' => 'Kadar FBG Anda terdeteksi di atas normal (185 mg/dL).',
                'read_at' => now()->subHours(5),
                'created_at' => now()->subHours(6),
            ]
        );

        // 🔥 MOBILE USER 2
        $user2 = User::firstOrCreate(
            ['email' => 'user2@app.com'],
            [
                'id' => (string) Str::uuid(),
                'type' => UserType::MOBILE,
            ]
        );

        MobileProfile::updateOrCreate(
            ['user_id' => $user2->id],
            [
                'name' => 'Test User 2',
                'age' => 42,
                'gender' => Gender::MALE,
                'diabetes_status' => DiabetesStatus::T2DM,
                'bmi' => 31.7,
                'disclaimer_accepted' => true,
            ]
        );

        UserAuthProvider::updateOrCreate(
            ['user_id' => $user2->id, 'provider' => AuthProvider::EMAIL],
            [
                'provider_id' => 'user2@app.com',
                'password_hash' => Hash::make('User1234'),
            ]
        );

        UserAlertSetting::updateOrCreate(
            ['user_id' => $user2->id],
            [
                'hypo_severe' => 70,
                'hypo_mild' => 80,
                'hyper_mild' => 180,
                'hyper_severe' => 250,
            ]
        );

        UserNotificationSetting::updateOrCreate(
            ['user_id' => $user2->id],
            [
                'niat_puasa_enabled' => true,
                'niat_puasa_time' => '20:00',
                'sahur_enabled' => true,
                'sahur_time' => '03:30',
                'fbg_reminder_enabled' => true,
                'fbg_reminder_time' => '17:45',
                'motivation_enabled' => true,
            ]
        );
    }
}
