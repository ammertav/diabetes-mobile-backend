<?php

use App\Enums\AlertType;
use App\Enums\FgbContextTag;
use App\Enums\UserType;
use App\Models\FgbRecord;
use App\Models\SafetyAlert;
use App\Models\User;
use App\Utilities\JwtUtility;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createAlertForUser(User $user, AlertType $type, ?string $acknowledgedAt = null): SafetyAlert
{
    $fgb = FgbRecord::create([
        'user_id' => $user->id,
        'value_mg_dl' => 50.0,
        'context_tag' => FgbContextTag::MORNING->value,
        'is_fasting_day' => false,
        'server_timestamp' => now(),
        'client_timestamp' => now(),
    ]);

    return SafetyAlert::create([
        'user_id' => $user->id,
        'fgb_record_id' => $fgb->id,
        'type' => $type->value,
        'message' => 'Peringatan glukosa',
        'acknowledged_at' => $acknowledgedAt,
    ]);
}

test('guest cannot access safety alert endpoints', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $this->getJson('/api/v1/safety-alerts')->assertStatus(401);
    $this->patchJson('/api/v1/safety-alerts/dummy-id/acknowledge')->assertStatus(401);
    $this->getJson('/api/v1/safety-alerts/settings')->assertStatus(401);
    $this->putJson('/api/v1/safety-alerts/settings', [])->assertStatus(401);
});

test('user can fetch their safety alerts list', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $user = User::factory()->create(['type' => UserType::MOBILE]);
    $token = JwtUtility::generateAccessToken($user);

    createAlertForUser($user, AlertType::HYPO_SEVERE);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/v1/safety-alerts');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                ['id', 'type', 'message', 'action', 'created_at'],
            ],
        ])
        ->assertJsonCount(1, 'data');
});

test('user can acknowledge safety alert with action taken', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $user = User::factory()->create(['type' => UserType::MOBILE]);
    $token = JwtUtility::generateAccessToken($user);

    $alert = createAlertForUser($user, AlertType::HYPO_MILD);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->patchJson("/api/v1/safety-alerts/{$alert->id}/acknowledge", [
            'action_taken' => 'Sudah meminum teh manis dan beristirahat',
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Alert acknowledged successfully',
        ]);

    $alert->refresh();
    expect($alert->acknowledged_at)->not->toBeNull();
    expect($alert->action_taken)->toBe('Sudah meminum teh manis dan beristirahat');
});

test('acknowledging already acknowledged alert returns error', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $user = User::factory()->create(['type' => UserType::MOBILE]);
    $token = JwtUtility::generateAccessToken($user);

    $alert = createAlertForUser($user, AlertType::HYPER_MILD, now()->toISOString());

    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->patchJson("/api/v1/safety-alerts/{$alert->id}/acknowledge", [
            'action_taken' => 'Sudah minum air',
        ])
        ->assertStatus(422);
});

test('user can fetch default and updated safety alert settings', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $user = User::factory()->create(['type' => UserType::MOBILE]);
    $token = JwtUtility::generateAccessToken($user);

    // 1. Fetch default settings when not yet set in DB
    $defaultResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/v1/safety-alerts/settings');

    $defaultResponse->assertStatus(200)
        ->assertJsonStructure([
            'data' => ['hypo_severe', 'hypo_mild', 'hyper_severe', 'hyper_mild'],
        ]);

    // 2. Update settings
    $updateResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson('/api/v1/safety-alerts/settings', [
            'hypo_severe' => 65,
            'hypo_mild' => 78,
            'hyper_mild' => 190,
            'hyper_severe' => 260,
        ]);

    $updateResponse->assertStatus(200)
        ->assertJson([
            'message' => 'Settings updated successfully',
            'data' => [
                'hypo_severe' => 65,
                'hypo_mild' => 78,
                'hyper_mild' => 190,
                'hyper_severe' => 260,
            ],
        ]);

    // 3. Verify settings persist
    $getUpdated = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/v1/safety-alerts/settings');

    $getUpdated->assertStatus(200)
        ->assertJson([
            'data' => [
                'hypo_severe' => 65,
                'hypo_mild' => 78,
                'hyper_mild' => 190,
                'hyper_severe' => 260,
            ],
        ]);
});
