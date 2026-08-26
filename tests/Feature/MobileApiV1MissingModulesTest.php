<?php

use App\Enums\DiabetesStatus;
use App\Enums\Gender;
use App\Enums\UserType;
use App\Enums\CmsContentType;
use App\Enums\CmsDayContext;
use App\Enums\FastingLogStatus;
use App\Models\CmsContent;
use App\Models\FastingLog;
use App\Models\MobileProfile;
use App\Models\User;
use App\Models\UserProtocol;
use App\Models\UserNotification;
use App\Models\UserNotificationSetting;
use App\Utilities\JwtUtility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

function createAuthenticatedUser()
{
    $user = User::factory()->create([
        'email' => 'testmobile@example.com',
        'type' => UserType::MOBILE,
    ]);

    MobileProfile::create([
        'user_id' => $user->id,
        'name' => 'Mobile User',
        'age' => 25,
        'gender' => Gender::FEMALE,
        'diabetes_status' => DiabetesStatus::HEALTHY,
        'bmi' => 21.5,
        'disclaimer_accepted' => true,
    ]);

    $token = JwtUtility::generateAccessToken($user);

    return [$user, $token];
}

test('guest cannot access missing modules', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $this->getJson('/api/v1/content')->assertStatus(401);
    $this->getJson('/api/v1/streaks')->assertStatus(401);
    $this->getJson('/api/v1/notifications/settings')->assertStatus(401);
    $this->putJson('/api/v1/notifications/settings', [])->assertStatus(401);
    $this->patchJson('/api/v1/notifications/fcm-token', [])->assertStatus(401);
    $this->getJson('/api/v1/notifications/history')->assertStatus(401);
});

test('user can list content and filter by type and day context', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    [$user, $token] = createAuthenticatedUser();

    CmsContent::create([
        'content_type' => CmsContentType::Motivation,
        'day_context' => CmsDayContext::Monday,
        'title' => 'Senin Semangat',
        'body' => 'Isi motivasi Senin',
        'is_published' => true,
        'published_at' => now(),
    ]);

    CmsContent::create([
        'content_type' => CmsContentType::Education,
        'day_context' => CmsDayContext::General,
        'title' => 'Edukasi Diabetes',
        'body' => 'Isi edukasi umum',
        'is_published' => true,
        'published_at' => now(),
    ]);

    CmsContent::create([
        'content_type' => CmsContentType::Reflection,
        'day_context' => CmsDayContext::Thursday,
        'title' => 'Kamis Refleksi',
        'body' => 'Isi refleksi Kamis',
        'is_published' => false, // Not published
        'published_at' => now(),
    ]);

    // List all published
    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/v1/content');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');

    // Filter by type
    $responseType = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/v1/content?content_type=motivation');

    $responseType->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['title' => 'Senin Semangat']);

    // Filter by day context
    $responseDay = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/v1/content?day_context=general');

    $responseDay->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['title' => 'Edukasi Diabetes']);
});

test('user can fetch streak summary', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    [$user, $token] = createAuthenticatedUser();

    $fp = \App\Models\FastingProtocol::create([
        'id' => (string) \Illuminate\Support\Str::uuid(),
        'name' => 'Puasa Senin Kamis',
        'type' => \App\Enums\ProtocolType::SUNNAH->value,
    ]);

    $protocol = UserProtocol::create([
        'user_id' => $user->id,
        'fasting_protocol_id' => $fp->id,
        'start_date' => now()->subDays(10)->toDateString(),
        'status' => \App\Enums\UserProtocolStatus::ACTIVE,
    ]);

    // Create 3 consecutive completed fasting logs
    for ($i = 3; $i >= 1; $i--) {
        FastingLog::create([
            'user_protocol_id' => $protocol->id,
            'planned_date' => now()->subDays($i)->toDateString(),
            'status' => FastingLogStatus::COMPLETED,
            'started_at' => now()->subDays($i)->setTime(18, 0),
            'ended_at' => now()->subDays($i - 1)->setTime(10, 0),
        ]);
    }

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/v1/streaks');

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'current_streak' => 3,
                'best_streak' => 3,
                'total_fasting_days' => 3,
                'last_fasting_date' => now()->subDays(1)->toDateString(),
            ]
        ]);
});

test('user can manage notification settings', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    [$user, $token] = createAuthenticatedUser();

    // Fetch defaults
    $responseGet = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/v1/notifications/settings');

    $responseGet->assertSuccessful()
        ->assertJson([
            'data' => [
                'niat_puasa_enabled' => true,
                'niat_puasa_time' => '20:00',
                'sahur_enabled' => true,
                'sahur_time' => '03:30',
                'fbg_reminder_enabled' => true,
                'fbg_reminder_time' => '17:45',
                'motivation_enabled' => true,
            ]
        ]);

    // Update settings
    $responsePut = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson('/api/v1/notifications/settings', [
            'niat_puasa_enabled' => false,
            'niat_puasa_time' => '21:00',
        ]);

    $responsePut->assertStatus(200)
        ->assertJson([
            'data' => [
                'niat_puasa_enabled' => false,
                'niat_puasa_time' => '21:00',
                'sahur_enabled' => true,
            ]
        ]);

    $this->assertDatabaseHas('user_notification_settings', [
        'user_id' => $user->id,
        'niat_puasa_enabled' => false,
        'niat_puasa_time' => '21:00',
    ]);
});

test('user can register FCM token', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    [$user, $token] = createAuthenticatedUser();

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->patchJson('/api/v1/notifications/fcm-token', [
            'fcm_token' => 'fcm-dummy-token-123',
            'platform' => 'android',
        ]);

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('fcm_devices', [
        'user_id' => $user->id,
        'fcm_token' => 'fcm-dummy-token-123',
        'platform' => 'android',
    ]);
});

test('user can fetch notifications and mark as read', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    [$user, $token] = createAuthenticatedUser();

    $notif1 = UserNotification::create([
        'user_id' => $user->id,
        'type' => 'motivation',
        'title' => 'Niat Sahur',
        'body' => 'Jangan lupa sahur ya',
    ]);

    $notif2 = UserNotification::create([
        'user_id' => $user->id,
        'type' => 'safety',
        'title' => 'Gula Darah Rendah',
        'body' => 'Gula darah terdeteksi rendah',
        'read_at' => now(),
    ]);

    // Fetch history (unread only)
    $responseUnread = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/v1/notifications/history?read=false');

    $responseUnread->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['title' => 'Niat Sahur']);

    // Mark as read
    $responseReadAction = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->patchJson("/api/v1/notifications/history/{$notif1->id}/read");

    $responseReadAction->assertStatus(200)
        ->assertJsonPath('read_at', fn($val) => !is_null($val));

    $this->assertDatabaseHas('user_notifications', [
        'id' => $notif1->id,
        'read_at' => $notif1->fresh()->read_at,
    ]);
});
