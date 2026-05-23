<?php

use App\Enums\DiabetesStatus;
use App\Enums\Gender;
use App\Enums\UserType;
use App\Models\FgbRecord;
use App\Models\MobileProfile;
use App\Models\User;
use App\Models\UserAuthProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('authenticated user can fetch fbg trend with default period', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $user = User::factory()->create([
        'email' => 'trend@example.com',
        'type' => UserType::MOBILE,
    ]);

    MobileProfile::create([
        'user_id' => $user->id,
        'name' => 'Trend User',
        'age' => 40,
        'gender' => Gender::MALE,
        'diabetes_status' => DiabetesStatus::T2DM,
        'bmi' => 24.0,
        'disclaimer_accepted' => true,
    ]);

    UserAuthProvider::create([
        'user_id' => $user->id,
        'provider' => 'email',
        'provider_id' => 'trend@example.com',
        'password_hash' => Hash::make('Password123'),
    ]);

    $loginResponse = $this->postJson('/api/v1/auth/login', [
        'email' => 'trend@example.com',
        'password' => 'Password123',
    ]);

    $token = $loginResponse->json('data.token');

    FgbRecord::create([
        'user_id' => $user->id,
        'value_mg_dl' => 185.0,
        'context_tag' => 'end_of_fast',
        'is_fasting_day' => true,
        'server_timestamp' => now()->subDays(3),
        'client_timestamp' => now()->subDays(3),
    ]);

    FgbRecord::create([
        'user_id' => $user->id,
        'value_mg_dl' => 170.0,
        'context_tag' => 'morning',
        'is_fasting_day' => false,
        'server_timestamp' => now()->subDays(9),
        'client_timestamp' => now()->subDays(9),
    ]);

    FgbRecord::create([
        'user_id' => $user->id,
        'value_mg_dl' => 200.0,
        'context_tag' => 'before_meal',
        'is_fasting_day' => false,
        'server_timestamp' => now()->subDays(35),
        'client_timestamp' => now()->subDays(35),
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/v1/dashboard/fbg-trend');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                ['date', 'value_mg_dl', 'is_fasting_day', 'context_tag'],
            ],
        ])
        ->assertJsonCount(2, 'data')
        ->assertJsonFragment([
            'value_mg_dl' => 185.0,
            'is_fasting_day' => true,
            'context_tag' => 'end_of_fast',
        ]);
});
