<?php

test('the application returns a successful response', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('login page contains credentials when debug is true', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    config(['app.debug' => true]);
    config(['app.test_username' => 'test-user@example.com']);
    config(['app.test_password' => 'secret-test-pwd']);

    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertSee('value="test-user@example.com"', false);
    $response->assertSee('value="secret-test-pwd"', false);
});

test('login page does not contain credentials when debug is false', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    config(['app.debug' => false]);
    config(['app.test_username' => 'test-user@example.com']);
    config(['app.test_password' => 'secret-test-pwd']);

    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertDontSee('value="test-user@example.com"');
    $response->assertDontSee('value="secret-test-pwd"');
});

test('login page displays errors when validation fails', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class)
        ->from('/login')
        ->followingRedirects()
        ->post('/login', [
            'email' => '',
            'password' => '',
        ]);

    $response->assertStatus(200);
    $response->assertSee('Sign In Failed');
    $response->assertSee('The email field is required.');
    $response->assertSee('The password field is required.');
});
