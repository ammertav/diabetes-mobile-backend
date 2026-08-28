<?php

test('the application returns a successful response', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('login page does not contain credentials in debug mode for security', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    config(['app.debug' => true]);

    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertDontSee('admin@app.com');
    $response->assertDontSee('Admin1234');
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

test('non-existent route returns 404 page with custom layout', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->get('/non-existent-route-path-999');

    $response->assertStatus(404);
    $response->assertSee('ERROR 404');
    $response->assertSee('Page Not Found');
});
