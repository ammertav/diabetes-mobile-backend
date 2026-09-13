<?php

use App\Enums\FastingLogStatus;
use App\Enums\UserProtocolStatus;
use App\Models\FastingLog;
use App\Models\UserProtocol;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest cannot access fasting protocols endpoints', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $this->getJson('/api/v1/protocols')->assertStatus(401);
    $this->getJson('/api/v1/protocols/active')->assertStatus(401);
});

test('user can fetch list of available fasting protocols', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    [, $token] = createAuthenticatedMobileUser();
    $protocol = createFastingProtocolWithDays([1, 4]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/v1/protocols');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'type',
                    'start_time',
                    'end_time',
                    'fasting_days',
                    'duration_hours',
                    'description',
                ],
            ],
        ]);

    $items = collect($response->json('data'));
    expect($items->contains('id', $protocol->id))->toBeTrue();
});

test('user without active protocol receives 404 on active endpoint', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    [, $token] = createAuthenticatedMobileUser();

    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/v1/protocols/active')
        ->assertStatus(404);
});

test('user with active protocol can fetch active protocol details and adherence', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    [$user, $token] = createAuthenticatedMobileUser();
    $protocol = createFastingProtocolWithDays([1, 4]);

    $userProtocol = UserProtocol::create([
        'user_id' => $user->id,
        'fasting_protocol_id' => $protocol->id,
        'start_date' => now()->subDays(7)->toDateString(),
        'status' => UserProtocolStatus::ACTIVE,
    ]);

    FastingLog::create([
        'user_protocol_id' => $userProtocol->id,
        'planned_date' => now()->subDays(4)->toDateString(),
        'status' => FastingLogStatus::COMPLETED,
    ]);
    FastingLog::create([
        'user_protocol_id' => $userProtocol->id,
        'planned_date' => now()->subDays(1)->toDateString(),
        'status' => FastingLogStatus::PLANNED,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/v1/protocols/active');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'user_protocol_id',
            'protocol' => ['id', 'name', 'type', 'start_time', 'end_time', 'duration_hours', 'description'],
            'start_date',
            'status',
            'adherence_rate',
        ])
        ->assertJson([
            'user_protocol_id' => $userProtocol->id,
            'status' => UserProtocolStatus::ACTIVE->value,
        ]);
});
