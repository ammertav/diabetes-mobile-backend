<?php

use App\Enums\UserType;
use App\Models\User;
use App\Models\SafetyAlert;
use App\Models\FgbRecord;
use App\Utilities\AuditLogger;
use App\Actions\Notification\MarkNotificationAsReadAction;
use App\Actions\Notification\RegisterFcmTokenAction;
use App\Models\UserNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('UserType enum correctly identifies admin type', function () {
    expect(UserType::ADMIN->isAdmin())->toBeTrue();
    expect(UserType::MOBILE->isAdmin())->toBeFalse();
});

test('User model isAdmin method operates correctly', function () {
    /** @var User $admin */
    $admin = User::factory()->create(['type' => UserType::ADMIN]);
    /** @var User $mobile */
    $mobile = User::factory()->create(['type' => UserType::MOBILE]);

    expect($admin->isAdmin())->toBeTrue();
    expect($mobile->isAdmin())->toBeFalse();
});

test('User model risk status attribute evaluates correctly based on unacknowledged safety alerts', function () {
    /** @var User $user */
    $user = User::factory()->create(['type' => UserType::MOBILE]);

    expect($user->risk_status)->toBe('Low');

    $recordMild = FgbRecord::create([
        'user_id' => $user->id,
        'value_mg_dl' => 65.0,
        'context_tag' => 'fasting',
        'client_timestamp' => now(),
        'server_timestamp' => now(),
    ]);

    SafetyAlert::create([
        'user_id' => $user->id,
        'fgb_record_id' => $recordMild->id,
        'type' => 'hypo_mild',
        'message' => 'Mild hypoglycemia alert',
        'triggered_at' => now(),
    ]);

    expect($user->fresh()->risk_status)->toBe('Medium');

    $recordSevere = FgbRecord::create([
        'user_id' => $user->id,
        'value_mg_dl' => 45.0,
        'context_tag' => 'fasting',
        'client_timestamp' => now(),
        'server_timestamp' => now(),
    ]);

    SafetyAlert::create([
        'user_id' => $user->id,
        'fgb_record_id' => $recordSevere->id,
        'type' => 'hypo_severe',
        'message' => 'Severe hypoglycemia alert',
        'triggered_at' => now(),
    ]);

    expect($user->fresh()->risk_status)->toBe('High');
});

test('AuditLogger utility creates audit trail entry', function () {
    /** @var \Tests\TestCase $this */
    /** @var User $admin */
    $admin = User::factory()->create(['type' => UserType::ADMIN]);

    AuditLogger::log(
        $admin,
        'CREATE',
        'FastingProtocol',
        'Created fasting protocol'
    );

    $this->assertDatabaseHas('audit_trails', [
        'user_id' => $admin->id,
        'action' => 'CREATE',
        'action_label' => 'FastingProtocol',
        'description' => 'Created fasting protocol',
    ]);
});

test('MarkNotificationAsReadAction marks notification as read', function () {
    /** @var \Tests\TestCase $this */
    /** @var User $user */
    $user = User::factory()->create(['type' => UserType::MOBILE]);
    /** @var UserNotification $notification */
    $notification = UserNotification::create([
        'user_id' => $user->id,
        'type' => 'niat',
        'title' => 'Pengingat Niat',
        'body' => 'Jangan lupa niat',
        'read_at' => null,
    ]);

    $action = app(MarkNotificationAsReadAction::class);
    $updatedNotification = $action->execute($user->id, (int) $notification->id);

    expect($updatedNotification->read_at)->not->toBeNull();
    $this->assertDatabaseHas('user_notifications', [
        'id' => $notification->id,
    ]);
});

test('RegisterFcmTokenAction registers or updates FCM device token', function () {
    /** @var \Tests\TestCase $this */
    /** @var User $user */
    $user = User::factory()->create(['type' => UserType::MOBILE]);

    $action = app(RegisterFcmTokenAction::class);
    $device = $action->execute($user->id, [
        'fcm_token' => 'sample_fcm_token_123',
        'platform' => 'android',
    ]);

    expect($device->fcm_token)->toBe('sample_fcm_token_123');
    $this->assertDatabaseHas('fcm_devices', [
        'user_id' => $user->id,
        'fcm_token' => 'sample_fcm_token_123',
        'platform' => 'android',
    ]);
});
