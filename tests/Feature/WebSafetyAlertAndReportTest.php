<?php

use App\Models\User;
use App\Models\AdminProfile;
use App\Models\MobileProfile;
use App\Models\SafetyAlert;
use App\Models\FgbRecord;
use App\Models\FcmDevice;
use App\Enums\UserType;
use App\Enums\Gender;
use App\Enums\DiabetesStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

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

    // Create a mobile user (patient)
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

    $this->fgb = FgbRecord::create([
        'user_id' => $this->patient->id,
        'value_mg_dl' => 60.0, // Hypo severe triggers alert
        'context_tag' => 'morning',
        'is_fasting_day' => true,
        'server_timestamp' => now(),
        'client_timestamp' => now(),
    ]);

    $this->alert = SafetyAlert::create([
        'id' => (string) \Illuminate\Support\Str::uuid(),
        'user_id' => $this->patient->id,
        'fgb_record_id' => $this->fgb->id,
        'type' => 'hypo_severe',
        'message' => 'Gula darah terdeteksi sangat rendah',
    ]);
});

test('guest cannot access safety alerts and reports', function () {
    /** @var \Tests\TestCase $this */
    $this->get('/safety-alerts')->assertRedirect('/login');
    $this->get('/safety-alerts/data')->assertRedirect('/login');
    $this->get('/reports')->assertRedirect('/login');
    $this->get("/reports/patient/{$this->patient->id}")->assertRedirect('/login');
});

test('admin can access safety alerts web portal', function () {
    /** @var \Tests\TestCase $this */
    $response = $this->actingAs($this->admin)->get('/safety-alerts');
    $response->assertStatus(200)
        ->assertSee('Pusat Kontrol Alert Darurat');
});

test('admin can load safety alerts data json', function () {
    /** @var \Tests\TestCase $this */
    $response = $this->actingAs($this->admin)->getJson('/safety-alerts/data?status=all');
    $response->assertStatus(200)
        ->assertJsonStructure([
            'stats' => ['total', 'unresolved', 'severe', 'resolved'],
            'alerts' => [
                ['id', 'patient_name', 'type', 'message', 'glucose_value', 'status', 'action_taken', 'created_at']
            ],
            'pagination' => ['current_page', 'last_page', 'total']
        ]);
});

test('admin can notify patient via fcm mock notifyUser', function () {
    /** @var \Tests\TestCase $this */
    FcmDevice::create([
        'user_id' => $this->patient->id,
        'fcm_token' => 'fcm-dummy-patient-token',
        'platform' => 'ios',
    ]);

    $response = $this->actingAs($this->admin)->postJson("/safety-alerts/{$this->alert->id}/notify");
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Notifikasi darurat berhasil dikirim ke perangkat user.',
        ]);

    $this->assertDatabaseHas('user_notifications', [
        'user_id' => $this->patient->id,
        'type' => 'safety',
    ]);
});

test('admin can access reports index configuration', function () {
    /** @var \Tests\TestCase $this */
    $response = $this->actingAs($this->admin)->get('/reports');
    $response->assertStatus(200)
        ->assertSee('Laporan Kesehatan Pasien');
});

test('admin can access clinical patient report', function () {
    /** @var \Tests\TestCase $this */
    $response = $this->actingAs($this->admin)->get("/reports/patient/{$this->patient->id}");
    $response->assertStatus(200)
        ->assertSee('Laporan Kesehatan Pasien')
        ->assertSee('Patient John')
        ->assertSee('Gula darah terdeteksi sangat rendah');
});
