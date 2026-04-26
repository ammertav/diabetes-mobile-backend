<?php

use App\Enums\DiabetesStatus;
use App\Enums\Gender;
use App\Enums\UserType;
use App\Models\User;
use App\Models\MobileProfile;
use App\Models\UserAuthProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('user can register with mobile profile', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $data = [
        'email' => 'test@example.com',
        'password' => 'Password123',
        'name' => 'Test User',
        'age' => 30,
        'gender' => Gender::MALE->value,
        'diabetes_status' => DiabetesStatus::HEALTHY->value,
        'bmi' => 25.0,
        'disclaimer_accepted' => true,
    ];

    $response = $this->postJson('/api/v1/auth/register', $data);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'user' => [
                    'id',
                    'email',
                    'name',
                    'age',
                    'gender',
                    'diabetes_status',
                    'bmi',
                    'auth_provider',
                    'created_at',
                    'updated_at',
                ],
                'refresh_token',
                'token',
            ],
            'message',
        ]);

    // Verify user created
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'type' => UserType::MOBILE->value,
    ]);

    // Verify mobile profile created
    $user = User::where('email', 'test@example.com')->first();
    $this->assertDatabaseHas('mobile_profiles', [
        'user_id' => $user->id,
        'name' => 'Test User',
        'age' => 30,
        'gender' => Gender::MALE->value,
        'diabetes_status' => DiabetesStatus::HEALTHY->value,
        'bmi' => 25.0,
    ]);

    // Verify auth provider created
    $this->assertDatabaseHas('user_auth_providers', [
        'user_id' => $user->id,
        'provider' => 'email',
        'provider_id' => 'test@example.com',
    ]);
});

test('mobile user can login', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    // Create a mobile user
    $user = User::factory()->create([
        'email' => 'login@example.com',
        'type' => UserType::MOBILE,
    ]);

    MobileProfile::create([
        'user_id' => $user->id,
        'name' => 'Login User',
        'age' => 25,
        'gender' => Gender::FEMALE,
        'diabetes_status' => DiabetesStatus::PREDIABETES,
        'bmi' => 22.5,
        'disclaimer_accepted' => true,
    ]);

    UserAuthProvider::create([
        'user_id' => $user->id,
        'provider' => 'email',
        'provider_id' => 'login@example.com',
        'password_hash' => Hash::make('Password123'),
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'login@example.com',
        'password' => 'Password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'user' => [
                    'id',
                    'email',
                    'name',
                    'age',
                    'gender',
                    'diabetes_status',
                    'bmi',
                    'auth_provider',
                    'created_at',
                    'updated_at',
                ],
                'token',
                'refresh_token',
            ],
        ]);

    // Verify response contains mobile profile data
    $response->assertJson([
        'data' => [
            'user' => [
                'email' => 'login@example.com',
                'name' => 'Login User',
                'age' => 25,
                'gender' => Gender::FEMALE->value,
                'diabetes_status' => DiabetesStatus::PREDIABETES->value,
                'bmi' => 22.5,
            ],
        ],
    ]);
});

test('admin user cannot login', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    // Create an admin user
    $user = User::factory()->create([
        'email' => 'admin@example.com',
        'type' => UserType::ADMIN,
    ]);

    UserAuthProvider::create([
        'user_id' => $user->id,
        'provider' => 'email',
        'provider_id' => 'admin@example.com',
        'password_hash' => Hash::make('Password123'),
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'admin@example.com',
        'password' => 'Password123',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'message' => 'Invalid credentials',
        ]);
});

test('authenticated user can get profile', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    // Create a mobile user
    $user = User::factory()->create([
        'email' => 'profile@example.com',
        'type' => UserType::MOBILE,
    ]);

    MobileProfile::create([
        'user_id' => $user->id,
        'name' => 'Profile User',
        'age' => 28,
        'gender' => Gender::MALE,
        'diabetes_status' => DiabetesStatus::T2DM,
        'bmi' => 28.0,
        'disclaimer_accepted' => true,
    ]);

    UserAuthProvider::create([
        'user_id' => $user->id,
        'provider' => 'email',
        'provider_id' => 'profile@example.com',
        'password_hash' => Hash::make('Password123'),
    ]);

    // Login to get token
    $loginResponse = $this->postJson('/api/v1/auth/login', [
        'email' => 'profile@example.com',
        'password' => 'Password123',
    ]);

    $token = $loginResponse->json('data.token');

    // Get profile
    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/v1/users/profile');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'email',
                'name',
                'age',
                'gender',
                'diabetes_status',
                'bmi',
                'auth_provider',
                'created_at',
                'updated_at',
            ],
        ]);

    // Verify response contains mobile profile data
    $response->assertJson([
        'data' => [
            'email' => 'profile@example.com',
            'name' => 'Profile User',
            'age' => 28,
            'gender' => Gender::MALE->value,
            'diabetes_status' => DiabetesStatus::T2DM->value,
            'bmi' => 28.0,
        ],
    ]);
});
