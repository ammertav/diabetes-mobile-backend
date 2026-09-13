<?php

use App\Enums\UserProtocolStatus;
use App\Models\FastingProtocol;
use App\Models\UserProtocol;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

test('guest cannot select protocol without bearer token', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $this->postJson('/api/v1/protocols/select', [
        'protocol_id' => (string) Str::uuid(),
        'start_date' => now()->addDay()->toDateString(),
    ])->assertStatus(401);
});

test('user can select a fasting protocol with UUID successfully', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    [$user, $token] = createAuthenticatedMobileUser();
    $protocol = createFastingProtocolWithDays([1, 4]);
    $startDate = now()->addDay()->toDateString();

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/v1/protocols/select', [
            'protocol_id' => $protocol->id,
            'start_date' => $startDate,
        ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['message', 'data' => ['user_protocol_id', 'protocol_id', 'start_date', 'status']])
        ->assertJson(['message' => 'Protocol selected', 'data' => ['protocol_id' => $protocol->id]]);

    $this->assertDatabaseHas('user_protocols', [
        'user_id' => $user->id,
        'fasting_protocol_id' => $protocol->id,
        'status' => UserProtocolStatus::ACTIVE->value,
    ]);

    $userProtocol = UserProtocol::query()->where('user_id', $user->id)->first();
    expect($userProtocol?->logs()->count())->toBeGreaterThan(0);
});

test('user can switch active protocol to a different one', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    [$user, $token] = createAuthenticatedMobileUser();
    $oldProtocol = createFastingProtocolWithDays([1, 3]);
    $newProtocol = createFastingProtocolWithDays([2, 4]);

    $old = UserProtocol::create([
        'user_id' => $user->id,
        'fasting_protocol_id' => $oldProtocol->id,
        'start_date' => now()->subWeek()->toDateString(),
        'status' => UserProtocolStatus::ACTIVE,
    ]);

    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/v1/protocols/select', [
            'protocol_id' => $newProtocol->id,
            'start_date' => now()->addDay()->toDateString(),
        ])->assertStatus(200);

    $this->assertDatabaseHas('user_protocols', ['id' => $old->id, 'status' => UserProtocolStatus::COMPLETED->value]);
    $this->assertDatabaseHas('user_protocols', ['user_id' => $user->id, 'fasting_protocol_id' => $newProtocol->id, 'status' => UserProtocolStatus::ACTIVE->value]);
});

test('fails with 422 when protocol_id is missing, invalid format, or non-existent', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    [, $token] = createAuthenticatedMobileUser();
    $headers = ['Authorization' => 'Bearer ' . $token];
    $date = now()->addDay()->toDateString();

    $this->withHeaders($headers)->postJson('/api/v1/protocols/select', ['start_date' => $date])
        ->assertStatus(422)->assertJsonValidationErrors(['protocol_id']);

    $this->withHeaders($headers)->postJson('/api/v1/protocols/select', ['protocol_id' => 123, 'start_date' => $date])
        ->assertStatus(422)->assertJsonValidationErrors(['protocol_id']);

    $this->withHeaders($headers)->postJson('/api/v1/protocols/select', ['protocol_id' => (string) Str::uuid(), 'start_date' => $date])
        ->assertStatus(422)->assertJsonValidationErrors(['protocol_id']);
});

test('fails with 422 when start_date is past or invalid format', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    [, $token] = createAuthenticatedMobileUser();
    $protocol = createFastingProtocolWithDays();
    $headers = ['Authorization' => 'Bearer ' . $token];

    $this->withHeaders($headers)->postJson('/api/v1/protocols/select', ['protocol_id' => $protocol->id, 'start_date' => now()->subDay()->toDateString()])
        ->assertStatus(422)->assertJsonValidationErrors(['start_date']);

    $this->withHeaders($headers)->postJson('/api/v1/protocols/select', ['protocol_id' => $protocol->id, 'start_date' => 'invalid-date'])
        ->assertStatus(422)->assertJsonValidationErrors(['start_date']);
});

test('fails with 422 when same protocol is already active or has no days', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    [$user, $token] = createAuthenticatedMobileUser();
    $protocol = createFastingProtocolWithDays();

    UserProtocol::create([
        'user_id' => $user->id,
        'fasting_protocol_id' => $protocol->id,
        'start_date' => now()->toDateString(),
        'status' => UserProtocolStatus::ACTIVE,
    ]);

    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/v1/protocols/select', ['protocol_id' => $protocol->id, 'start_date' => now()->addDays(2)->toDateString()])
        ->assertStatus(422)->assertJsonFragment(['protocol_id' => $protocol->id]);

    $emptyProtocol = FastingProtocol::create(['name' => 'Empty', 'type' => 'custom', 'duration_hours' => 12]);
    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/v1/protocols/select', ['protocol_id' => $emptyProtocol->id, 'start_date' => now()->addDay()->toDateString()])
        ->assertStatus(422)->assertJsonFragment(['message' => "Protokol 'Empty' belum memiliki jadwal hari puasa."]);
});
