<?php

use App\Models\User;
use App\Models\AdminProfile;
use App\Models\FgbRecord;
use App\Models\MobileProfile;
use App\Enums\UserType;
use App\Enums\Gender;
use App\Enums\DiabetesStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

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

test('guest cannot access chart details', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->getJson('/fgb-monitoring/chart-details');
    $response->assertStatus(401);
});

test('admin can access chart details with correct parameters', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    
    // Create patient and log
    $patient = User::create([
        'email' => 'patient@example.com',
        'type' => UserType::MOBILE,
    ]);

    MobileProfile::create([
        'user_id' => $patient->id,
        'name' => 'John Doe',
        'age' => 45,
        'gender' => Gender::MALE,
        'diabetes_status' => DiabetesStatus::PREDIABETES,
        'bmi' => 26.5,
        'disclaimer_accepted' => true,
    ]);

    $log = FgbRecord::create([
        'user_id' => $patient->id,
        'value_mg_dl' => 112.5,
        'context_tag' => 'morning',
        'is_fasting_day' => true,
        'client_timestamp' => Carbon::now(),
        'server_timestamp' => Carbon::now(),
    ]);

    $response = $this->actingAs($this->admin)->getJson('/fgb-monitoring/chart-details?period=daily&group=all&index=0');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'title',
            'avg_fgb',
            'count',
            'records' => [
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'patient_id',
                        'patient_name',
                        'patient_photo',
                        'value_mg_dl',
                        'status',
                        'badge_class',
                        'timestamp',
                    ]
                ]
            ]
        ]);

    $data = $response->json('records.data');
    $this->assertCount(1, $data);
    $this->assertEquals(112.5, $data[0]['value_mg_dl']);
    $this->assertEquals('John Doe', $data[0]['patient_name']);
});

test('admin can fetch logs and dynamic stats contains avg_fgb_diff', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    
    // Create patient and logs for this week and last week
    $patient = User::create([
        'email' => 'patient2@example.com',
        'type' => UserType::MOBILE,
    ]);

    MobileProfile::create([
        'user_id' => $patient->id,
        'name' => 'Jane Doe',
        'age' => 45,
        'gender' => Gender::FEMALE,
        'diabetes_status' => DiabetesStatus::PREDIABETES,
        'bmi' => 26.5,
        'disclaimer_accepted' => true,
    ]);

    // Log this week: 120
    FgbRecord::create([
        'user_id' => $patient->id,
        'value_mg_dl' => 120.0,
        'context_tag' => 'morning',
        'is_fasting_day' => true,
        'client_timestamp' => Carbon::now(),
        'server_timestamp' => Carbon::now(),
    ]);

    // Log last week (10 days ago): 100
    FgbRecord::create([
        'user_id' => $patient->id,
        'value_mg_dl' => 100.0,
        'context_tag' => 'morning',
        'is_fasting_day' => true,
        'client_timestamp' => Carbon::now()->subDays(10),
        'server_timestamp' => Carbon::now()->subDays(10),
    ]);

    $response = $this->actingAs($this->admin)->getJson('/fgb-monitoring/data');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'stats' => [
                'avg_fgb',
                'avg_fgb_diff',
                'target_range_percent',
                'abnormal_alerts',
            ],
            'logs'
        ]);

    $stats = $response->json('stats');
    $this->assertEquals(120, $stats['avg_fgb']);
    // Diff should be ((120 - 100) / 100) * 100 = +20.0%
    $this->assertEquals(20.0, $stats['avg_fgb_diff']);
});
