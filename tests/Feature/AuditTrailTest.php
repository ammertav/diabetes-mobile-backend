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

test('guest cannot access audit trail page', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->get('/audit-trail');
    $response->assertRedirect('/login');
});

test('admin can access audit trail index page', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->actingAs($this->admin)->get('/audit-trail');

    $response->assertStatus(200);
    $response->assertSee('Audit Trail');
});

test('admin can fetch audit trail data via JSON endpoint', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->actingAs($this->admin)->get('/audit-trail/data');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'stats' => ['total_events', 'admin_actions', 'patient_actions', 'security_alerts'],
        'logs' => ['data'],
        'pagination' => ['current_page', 'last_page', 'total'],
    ]);
});
