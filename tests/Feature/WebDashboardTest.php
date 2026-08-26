<?php

use App\Models\User;
use App\Models\AdminProfile;
use App\Enums\UserType;
use App\Enums\Gender;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
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

test('guest cannot access dashboard and is redirected to login', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->get('/');

    $response->assertRedirect('/login');
});

test('authenticated admin can access dashboard and see dynamic stats', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->actingAs($this->admin)->get('/');

    $response->assertStatus(200)
        ->assertSee('Average FGB Trends')
        ->assertSee('Total Patients')
        ->assertSee('At-Risk Patients')
        ->assertSee('Avg. Fasting Compliance');
});
