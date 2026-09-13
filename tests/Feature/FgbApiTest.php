<?php

use App\Enums\FgbContextTag;
use App\Enums\UserType;
use App\Models\FgbRecord;
use App\Models\SafetyAlert;
use App\Models\User;
use App\Utilities\JwtUtility;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest cannot access fgb endpoints', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $this->getJson('/api/v1/fgb')->assertStatus(401);
    $this->postJson('/api/v1/fgb', [])->assertStatus(401);
});

test('validation fails when storing fgb with invalid value or context tag', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $user = User::factory()->create(['type' => UserType::MOBILE]);
    $token = JwtUtility::generateAccessToken($user);

    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/v1/fgb', [
            'value_mg_dl' => 30, // below min 40
            'context_tag' => 'invalid_tag',
            'client_timestamp' => 'not-a-date',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['value_mg_dl', 'context_tag', 'client_timestamp']);
});

test('user can store normal fgb record successfully', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $user = User::factory()->create(['type' => UserType::MOBILE]);
    $token = JwtUtility::generateAccessToken($user);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/v1/fgb', [
            'value_mg_dl' => 110.0,
            'context_tag' => FgbContextTag::MORNING->value,
            'client_timestamp' => now()->toIso8601String(),
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => ['id', 'value_mg_dl', 'context_tag', 'is_fasting_day', 'server_timestamp'],
        ])
        ->assertJson([
            'data' => [
                'value_mg_dl' => 110.0,
                'context_tag' => FgbContextTag::MORNING->value,
            ],
        ]);

    $this->assertDatabaseHas('fgb_records', [
        'user_id' => $user->id,
        'value_mg_dl' => 110.0,
    ]);
});

test('user storing critical fgb value triggers safety alert', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $user = User::factory()->create(['type' => UserType::MOBILE]);
    $token = JwtUtility::generateAccessToken($user);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/v1/fgb', [
            'value_mg_dl' => 55.0, // severe hypoglycemia (< 70)
            'context_tag' => FgbContextTag::END_OF_FAST->value,
            'client_timestamp' => now()->toIso8601String(),
        ]);

    $response->assertStatus(201);
    expect((float) $response->json('data.value_mg_dl'))->toBe(55.0);

    $this->assertDatabaseHas('safety_alerts', [
        'user_id' => $user->id,
    ]);
});

test('user can fetch list of fgb records in descending order', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $user = User::factory()->create(['type' => UserType::MOBILE]);
    $token = JwtUtility::generateAccessToken($user);

    FgbRecord::create([
        'user_id' => $user->id,
        'value_mg_dl' => 120.0,
        'context_tag' => FgbContextTag::MORNING->value,
        'is_fasting_day' => false,
        'server_timestamp' => now()->subDay(),
        'client_timestamp' => now()->subDay(),
    ]);

    FgbRecord::create([
        'user_id' => $user->id,
        'value_mg_dl' => 135.0,
        'context_tag' => FgbContextTag::AFTER_MEAL->value,
        'is_fasting_day' => false,
        'server_timestamp' => now(),
        'client_timestamp' => now(),
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/v1/fgb');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                ['id', 'value_mg_dl', 'context_tag', 'is_fasting_day', 'server_timestamp'],
            ],
        ])
        ->assertJsonCount(2, 'data');

    $data = $response->json('data');
    expect((float) $data[0]['value_mg_dl'])->toBe(135.0);
    expect((float) $data[1]['value_mg_dl'])->toBe(120.0);
});
