<?php

use App\Models\User;
use App\Models\AdminProfile;
use App\Models\MobileProfile;
use App\Models\FgbRecord;
use App\Models\UserAlertSetting;
use App\Enums\UserType;
use App\Enums\Gender;
use App\Enums\DiabetesStatus;
use App\Notifications\AdminNotification;
use App\Actions\Fgb\StoreFgbRecordAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

use Illuminate\Notifications\DatabaseNotification;

beforeEach(function () {
    /** @var \Tests\TestCase $this */
    $this->admin = User::create([
        'email' => 'admin@example.com',
        'type' => UserType::ADMIN,
    ]);

    AdminProfile::create([
        'user_id' => $this->admin->id,
        'name' => 'Admin Test',
        'age' => 30,
        'gender' => Gender::MALE,
    ]);

    $this->patient = User::create([
        'email' => 'patient@example.com',
        'type' => UserType::MOBILE,
    ]);

    MobileProfile::create([
        'user_id' => $this->patient->id,
        'name' => 'Patient John',
        'age' => 45,
        'gender' => Gender::MALE,
        'diabetes_status' => DiabetesStatus::T2DM,
        'bmi' => 24.5,
        'disclaimer_accepted' => true,
    ]);
});

test('guest cannot access admin notifications API', function () {
    /** @var \Tests\TestCase $this */
    $this->getJson('/admin-notifications')->assertStatus(401);
    $this->postJson('/admin-notifications/some-id/read')->assertStatus(401);
    $this->postJson('/admin-notifications/read-all')->assertStatus(401);
});

test('admin can fetch notifications list and unread count', function () {
    /** @var \Tests\TestCase $this */
    DatabaseNotification::create([
        'id' => (string) \Illuminate\Support\Str::uuid(),
        'type' => AdminNotification::class,
        'notifiable_type' => User::class,
        'notifiable_id' => $this->admin->id,
        'data' => [
            'title' => 'Title test',
            'message' => 'Message test',
            'type' => 'critical',
            'action_url' => '/link',
        ],
        'read_at' => null,
    ]);

    $response = $this->actingAs($this->admin)->getJson('/admin-notifications');

    $response->assertStatus(200)
        ->assertJsonFragment([
            'unread_count' => 1,
            'title' => 'Title test',
            'message' => 'Message test',
            'type' => 'critical',
            'action_url' => '/link',
        ]);
});

test('admin can mark single notification as read', function () {
    /** @var \Tests\TestCase $this */
    $notification = DatabaseNotification::create([
        'id' => (string) \Illuminate\Support\Str::uuid(),
        'type' => AdminNotification::class,
        'notifiable_type' => User::class,
        'notifiable_id' => $this->admin->id,
        'data' => [
            'title' => 'Title test',
            'message' => 'Message text',
            'type' => 'info',
            'action_url' => null,
        ],
        'read_at' => null,
    ]);

    $response = $this->actingAs($this->admin)->postJson("/admin-notifications/{$notification->id}/read");
    $response->assertStatus(200)->assertJson(['success' => true]);

    $unreadCount = DatabaseNotification::where('notifiable_id', $this->admin->id)->whereNull('read_at')->count();
    $this->assertEquals(0, $unreadCount);
});

test('admin can mark all notifications as read', function () {
    /** @var \Tests\TestCase $this */
    DatabaseNotification::create([
        'id' => (string) \Illuminate\Support\Str::uuid(),
        'type' => AdminNotification::class,
        'notifiable_type' => User::class,
        'notifiable_id' => $this->admin->id,
        'data' => [
            'title' => 'Title 1',
            'message' => 'Message 1',
            'type' => 'info',
            'action_url' => null,
        ],
        'read_at' => null,
    ]);
    DatabaseNotification::create([
        'id' => (string) \Illuminate\Support\Str::uuid(),
        'type' => AdminNotification::class,
        'notifiable_type' => User::class,
        'notifiable_id' => $this->admin->id,
        'data' => [
            'title' => 'Title 2',
            'message' => 'Message 2',
            'type' => 'info',
            'action_url' => null,
        ],
        'read_at' => null,
    ]);

    $response = $this->actingAs($this->admin)->postJson('/admin-notifications/read-all');
    $response->assertStatus(200)->assertJson(['success' => true]);

    $unreadCount = DatabaseNotification::where('notifiable_id', $this->admin->id)->whereNull('read_at')->count();
    $this->assertEquals(0, $unreadCount);
});

test('storing critical fgb triggers admin notification automatically', function () {
    /** @var \Tests\TestCase $this */
    UserAlertSetting::create([
        'user_id' => $this->patient->id,
        'hypo_severe' => 70,
        'hypo_mild' => 80,
        'hyper_mild' => 180,
        'hyper_severe' => 250,
    ]);

    // FBG value 60 is severe hypo
    $action = app(StoreFgbRecordAction::class);
    $action->execute($this->patient, [
        'value_mg_dl' => 60.0,
        'context_tag' => 'morning',
        'client_timestamp' => now()->toDateTimeString(),
    ]);

    $unreadCount = DatabaseNotification::where('notifiable_id', $this->admin->id)->whereNull('read_at')->count();
    $this->assertEquals(1, $unreadCount);
    
    $notification = DatabaseNotification::where('notifiable_id', $this->admin->id)->whereNull('read_at')->first();
    $this->assertStringContainsString('Patient John', $notification->data['title']);
    $this->assertStringContainsString('60 mg/dL', $notification->data['message']);
});
