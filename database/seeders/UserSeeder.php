<?php

namespace Database\Seeders;

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

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🔥 ADMIN (web only)
        $admin = User::create([
            'id' => Str::uuid(),
            'email' => 'admin@app.com',
            'type' => UserType::ADMIN,
        ]);

        AdminProfile::create([
            'user_id' => $admin->id,
            'name' => 'Admin Main',
            'age' => 35,
            'gender' => Gender::MALE,
        ]);

        UserAuthProvider::create([
            'user_id' => $admin->id,
            'provider' => 'email',
            'provider_id' => 'admin@app.com',
            'password_hash' => Hash::make('Admin1234'),
        ]);

        // 🔥 MOBILE USER 1
        $user1 = User::create([
            'id' => Str::uuid(),
            'email' => 'user@app.com',
            'type' => UserType::MOBILE,
        ]);

        MobileProfile::create([
            'user_id' => $user1->id,
            'name' => 'Mobile User',
            'age' => 29,
            'gender' => Gender::FEMALE,
            'diabetes_status' => DiabetesStatus::PREDIABETES,
            'bmi' => 24.3,
            'disclaimer_accepted' => true,
        ]);

        UserAuthProvider::create([
            'user_id' => $user1->id,
            'provider' => 'email',
            'provider_id' => 'user@app.com',
            'password_hash' => Hash::make('User1234'),
        ]);

        // 🔥 MOBILE USER 2
        $user2 = User::create([
            'id' => Str::uuid(),
            'email' => 'user2@app.com',
            'type' => UserType::MOBILE,
        ]);

        MobileProfile::create([
            'user_id' => $user2->id,
            'name' => 'Test User 2',
            'age' => 42,
            'gender' => Gender::MALE,
            'diabetes_status' => DiabetesStatus::T2DM,
            'bmi' => 31.7,
            'disclaimer_accepted' => true,
        ]);

        UserAuthProvider::create([
            'user_id' => $user2->id,
            'provider' => 'email',
            'provider_id' => 'user2@app.com',
            'password_hash' => Hash::make('User1234'),
        ]);
    }
}
