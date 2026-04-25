<?php

namespace Database\Seeders;

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
            'name' => 'Admin Main',
            'email' => 'admin@app.com',
            'role' => 'admin',

            'age' => 35,
            'gender' => 'male',
            'diabetes_status' => 't2dm',
            'bmi' => 28.5,
            'disclaimer_accepted' => true,
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
            'name' => 'Mobile User',
            'email' => 'user@app.com',
            'role' => 'user',

            'age' => 29,
            'gender' => 'female',
            'diabetes_status' => 'prediabetes',
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
            'name' => 'Test User 2',
            'email' => 'user2@app.com',
            'role' => 'user',

            'age' => 42,
            'gender' => 'male',
            'diabetes_status' => 't2dm',
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
