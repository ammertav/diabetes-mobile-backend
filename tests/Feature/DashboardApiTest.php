<?php

use App\Enums\DiabetesStatus;
use App\Enums\Gender;
use App\Enums\UserType;
use App\Models\FgbRecord;
use App\Models\MobileProfile;
use App\Models\User;
use App\Utilities\JwtUtility;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest cannot access dashboard and export endpoints', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $this->getJson('/api/v1/dashboard')->assertStatus(401);
    $this->getJson('/api/v1/dashboard/export')->assertStatus(401);
});

test('authenticated user can fetch dashboard summary successfully', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $user = User::factory()->create(['type' => UserType::MOBILE]);
    MobileProfile::create([
        'user_id' => $user->id,
        'name' => 'Dashboard User',
        'age' => 35,
        'gender' => Gender::MALE,
        'diabetes_status' => DiabetesStatus::T2DM,
        'bmi' => 24.5,
        'disclaimer_accepted' => true,
    ]);

    FgbRecord::create([
        'user_id' => $user->id,
        'value_mg_dl' => 115.0,
        'context_tag' => 'morning',
        'is_fasting_day' => false,
        'server_timestamp' => now(),
        'client_timestamp' => now(),
    ]);

    $token = JwtUtility::generateAccessToken($user);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/v1/dashboard');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'today' => ['is_fasting_day', 'fasting_status', 'protocol_name'],
                'fbg_summary' => [
                    'weekly_avg',
                    'monthly_avg',
                    'last_value',
                    'last_measured_at',
                    'min_30d',
                    'max_30d',
                    'fasting_day_avg',
                    'non_fasting_day_avg',
                ],
                'streak' => ['current', 'best', 'total_fasting_days'],
                'adherence' => ['this_week', 'this_month', 'overall'],
                'motivation',
            ],
        ]);

    expect((float) $response->json('data.fbg_summary.last_value'))->toBe(115.0);
});

test('export endpoint validates date range', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $user = User::factory()->create(['type' => UserType::MOBILE]);
    $token = JwtUtility::generateAccessToken($user);

    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/v1/dashboard/export?start_date=2026-09-10&end_date=2026-09-01')
        ->assertStatus(422)
        ->assertJsonValidationErrors(['end_date']);
});

test('authenticated user can stream export csv file', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $user = User::factory()->create(['type' => UserType::MOBILE]);
    $token = JwtUtility::generateAccessToken($user);

    $startDate = now()->subDays(7)->toDateString();
    $endDate = now()->toDateString();

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->get("/api/v1/dashboard/export?start_date={$startDate}&end_date={$endDate}");

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    expect($response->headers->get('content-disposition'))->toContain('.csv');
});
