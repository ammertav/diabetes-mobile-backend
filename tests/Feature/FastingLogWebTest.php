<?php

use App\Models\User;
use App\Models\AdminProfile;
use App\Enums\UserType;
use App\Enums\Gender;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class);

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
});

test('guest cannot access fasting logs page', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->get('/fasting-logs');
    $response->assertRedirect('/login');
});

test('admin can access fasting logs index page', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->actingAs($this->admin)->get('/fasting-logs');

    $response->assertStatus(200);
    $response->assertSee('Fasting Logs');
});

test('admin can fetch fasting logs data via JSON endpoint', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->actingAs($this->admin)->get('/fasting-logs/data');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'stats' => ['total_logs', 'completed_logs', 'skipped_logs', 'missed_logs', 'adherence_rate'],
        'logs' => ['data'],
        'pagination' => ['current_page', 'last_page', 'total'],
    ]);
});
