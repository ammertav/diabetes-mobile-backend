<?php

use App\Enums\DiabetesStatus;
use App\Enums\Gender;
use App\Enums\UserType;
use App\Models\MobileProfile;
use App\Models\RefreshToken;
use App\Models\User;
use App\Utilities\JwtUtility;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest cannot access refresh token endpoint without bearer token', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $this->getJson('/api/v1/auth/refresh-token')->assertStatus(401);
});

test('user can rotate refresh token successfully', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $user = User::factory()->create(['type' => UserType::MOBILE]);

    $refreshData = JwtUtility::generateRefreshToken($user);
    RefreshToken::create([
        'user_id' => $user->id,
        'jti' => $refreshData['jti'],
        'expired_at' => $refreshData['expired_at'],
        'is_revoked' => false,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $refreshData['token'])
        ->getJson('/api/v1/auth/refresh-token');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => ['token', 'refresh_token'],
        ]);

    $this->assertDatabaseHas('refresh_tokens', [
        'jti' => $refreshData['jti'],
        'is_revoked' => true,
    ]);
});

test('refresh token fails when token is revoked or expired', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $user = User::factory()->create(['type' => UserType::MOBILE]);

    $refreshData = JwtUtility::generateRefreshToken($user);
    RefreshToken::create([
        'user_id' => $user->id,
        'jti' => $refreshData['jti'],
        'expired_at' => now()->subMinute(),
        'is_revoked' => true,
    ]);

    $this->withHeader('Authorization', 'Bearer ' . $refreshData['token'])
        ->getJson('/api/v1/auth/refresh-token')
        ->assertStatus(401);
});

test('guest cannot update profile', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $this->putJson('/api/v1/users/profile', [])->assertStatus(401);
});

test('user receives 422 when updating profile with invalid data', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $user = User::factory()->create(['type' => UserType::MOBILE]);
    MobileProfile::create([
        'user_id' => $user->id,
        'name' => 'Old Name',
        'age' => 25,
        'gender' => Gender::MALE,
        'diabetes_status' => DiabetesStatus::HEALTHY,
        'bmi' => 22.0,
        'disclaimer_accepted' => true,
    ]);

    $token = JwtUtility::generateAccessToken($user);

    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson('/api/v1/users/profile', [
            'name' => 'A',
            'age' => 15,
            'diabetes_status' => 'invalid_status',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'age', 'diabetes_status']);
});

test('user can update profile successfully', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $user = User::factory()->create(['type' => UserType::MOBILE]);
    $profile = MobileProfile::create([
        'user_id' => $user->id,
        'name' => 'Original Name',
        'age' => 28,
        'gender' => Gender::FEMALE,
        'diabetes_status' => DiabetesStatus::HEALTHY,
        'bmi' => 21.0,
        'disclaimer_accepted' => true,
    ]);

    $token = JwtUtility::generateAccessToken($user);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson('/api/v1/users/profile', [
            'name' => 'Updated Profile Name',
            'age' => 29,
            'bmi' => 23.5,
            'diabetes_status' => DiabetesStatus::PREDIABETES->value,
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Profile updated successfully',
            'data' => [
                'name' => 'Updated Profile Name',
                'age' => 29,
                'bmi' => 23.5,
                'diabetes_status' => DiabetesStatus::PREDIABETES->value,
            ],
        ]);

    $profile->refresh();
    expect($profile->name)->toBe('Updated Profile Name');
    expect($profile->age)->toBe(29);
    expect($profile->bmi)->toBe(23.5);
    expect($profile->diabetes_status)->toBe(DiabetesStatus::PREDIABETES);
});
