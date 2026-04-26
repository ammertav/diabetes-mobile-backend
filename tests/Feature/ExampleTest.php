<?php

test('the application returns a successful response', function () {
    $response = visit('/');

    $response->assertStatus(200);
});
