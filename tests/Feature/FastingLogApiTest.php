<?php

use App\Enums\FastingLogMood;
use App\Enums\FastingLogStatus;
use App\Enums\UserProtocolStatus;
use App\Enums\UserType;
use App\Models\FastingLog;
use App\Models\FastingProtocol;
use App\Models\User;
use App\Models\UserProtocol;
use App\Utilities\JwtUtility;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function setupFastingUserWithLog(string $status = 'planned', ?string $plannedDate = null)
{
    $user = User::factory()->create(['type' => UserType::MOBILE]);
    $protocol = FastingProtocol::create([
        'name' => 'Intermittent Fasting',
        'type' => 'intermittent',
        'duration_hours' => 16,
        'description' => '16/8 Protocol',
    ]);
    $userProtocol = UserProtocol::create([
        'user_id' => $user->id,
        'fasting_protocol_id' => $protocol->id,
        'start_date' => now()->subDays(5)->toDateString(),
        'status' => UserProtocolStatus::ACTIVE,
    ]);
    $log = FastingLog::create([
        'user_protocol_id' => $userProtocol->id,
        'planned_date' => $plannedDate ?? now()->toDateString(),
        'status' => $status,
        'started_at' => $status === 'completed' ? now()->subHours(16) : null,
    ]);
    $token = JwtUtility::generateAccessToken($user);

    return [$user, $token, $log];
}

test('guest cannot access fasting log endpoints', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $this->getJson('/api/v1/fasting-logs')->assertStatus(401);
    $this->postJson('/api/v1/fasting-logs/confirm', [])->assertStatus(401);
    $this->patchJson('/api/v1/fasting-logs/dummy-id/end')->assertStatus(401);
});

test('user can confirm fasting log as completed', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    [$user, $token, $log] = setupFastingUserWithLog('planned');

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/v1/fasting-logs/confirm', [
            'planned_date' => now()->toDateString(),
            'is_completed' => true,
            'mood' => 'good',
            'notes' => 'Feeling energetic',
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Fasting log confirmed',
            'data' => [
                'id' => $log->id,
                'planned_date' => now()->toDateString(),
                'is_completed' => true,
                'mood' => 'good',
                'notes' => 'Feeling energetic',
            ],
        ]);

    $log->refresh();
    expect($log->status)->toBe(FastingLogStatus::COMPLETED);
    expect($log->confirmed_at)->not->toBeNull();
});

test('user can confirm fasting log as skipped with reason', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    [$user, $token, $log] = setupFastingUserWithLog('planned');

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/v1/fasting-logs/confirm', [
            'planned_date' => now()->toDateString(),
            'is_completed' => false,
            'skip_reason' => 'Traveling for work',
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Fasting log confirmed',
            'data' => [
                'id' => $log->id,
                'is_completed' => false,
            ],
        ]);

    $log->refresh();
    expect($log->status)->toBe(FastingLogStatus::SKIPPED);
    expect($log->skip_reason)->toBe('Traveling for work');
});

test('confirm returns 409 when log is already confirmed', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    [$user, $token, $log] = setupFastingUserWithLog('completed');

    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/v1/fasting-logs/confirm', [
            'planned_date' => now()->toDateString(),
            'is_completed' => true,
        ])
        ->assertStatus(409)
        ->assertJson(['message' => 'Already confirmed']);
});

test('user can end fasting session and calculate actual duration', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    [$user, $token, $log] = setupFastingUserWithLog('completed');

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->patchJson("/api/v1/fasting-logs/{$log->id}/end");

    $response->assertStatus(200)
        ->assertJson(['message' => 'Fasting ended']);

    $log->refresh();
    expect($log->ended_at)->not->toBeNull();
    expect($log->actual_duration_min)->toBeGreaterThan(900);
});

test('user can fetch fasting logs list and summary statistics', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    [$user, $token, $log] = setupFastingUserWithLog('completed', now()->subDay()->toDateString());

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/v1/fasting-logs');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                ['id', 'planned_date', 'is_completed', 'actual_duration_min', 'mood', 'server_timestamp'],
            ],
            'summary' => ['total', 'completed', 'skipped', 'adherence_rate'],
            'pagination' => ['has_next', 'next_cursor'],
        ])
        ->assertJson([
            'summary' => [
                'total' => 1,
                'completed' => 1,
                'skipped' => 0,
                'adherence_rate' => 1.0,
            ],
        ]);
});
