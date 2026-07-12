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

test('header displays active navigation name for dashboard', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->actingAs($this->admin)->get('/');

    $response->assertStatus(200);
    $response->assertSee('Dashboard');
    $response->assertDontSee('The Clinical Sanctuary');
});

test('header displays active navigation name for patient management', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->actingAs($this->admin)->get('/patients');

    $response->assertStatus(200);
    $response->assertSee('Patient Management');
    $response->assertDontSee('The Clinical Sanctuary');
});

test('header displays active navigation name for fgb monitoring', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->actingAs($this->admin)->get('/fgb-monitoring');

    $response->assertStatus(200);
    $response->assertSee('FGB Monitoring');
    $response->assertDontSee('The Clinical Sanctuary');
});

test('header displays active navigation name for content cms', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->actingAs($this->admin)->get('/cms');

    $response->assertStatus(200);
    $response->assertSee('Content CMS');
    $response->assertDontSee('The Clinical Sanctuary');
});

test('header displays active navigation name for fasting protocols', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->actingAs($this->admin)->get('/fasting-protocols');

    $response->assertStatus(200);
    $response->assertSee('Fasting Protocols');
    $response->assertDontSee('The Clinical Sanctuary');
});
