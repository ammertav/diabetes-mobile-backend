<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind different classes or traits.
|
*/

pest()->extend(TestCase::class)
 // ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function createAuthenticatedMobileUser(array $attributes = []): array
{
    $user = \App\Models\User::factory()->create(array_merge([
        'email' => 'mobile.' . \Illuminate\Support\Str::random(8) . '@example.com',
        'type' => \App\Enums\UserType::MOBILE,
    ], $attributes));

    \App\Models\MobileProfile::create([
        'user_id' => $user->id,
        'name' => 'Mobile User',
        'age' => 28,
        'gender' => \App\Enums\Gender::MALE,
        'diabetes_status' => \App\Enums\DiabetesStatus::T2DM,
        'bmi' => 23.5,
        'disclaimer_accepted' => true,
    ]);

    $token = \App\Utilities\JwtUtility::generateAccessToken($user);

    return [$user, $token];
}

function createFastingProtocolWithDays(array $days = [1, 3, 5], array $attributes = []): \App\Models\FastingProtocol
{
    $protocol = \App\Models\FastingProtocol::create(array_merge([
        'id' => (string) \Illuminate\Support\Str::uuid(),
        'name' => 'Puasa Test ' . \Illuminate\Support\Str::random(5),
        'type' => \App\Enums\ProtocolType::SUNNAH->value,
        'start_time' => '18:00',
        'end_time' => '07:00',
        'duration_hours' => 13,
        'description' => 'Deskripsi test protokol',
    ], $attributes));

    foreach ($days as $day) {
        $protocol->days()->create(['day' => $day]);
    }

    return $protocol;
}
