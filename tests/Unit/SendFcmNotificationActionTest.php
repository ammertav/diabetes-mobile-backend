<?php

use App\Actions\Notification\SendFcmNotificationAction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

uses(Tests\TestCase::class);

test('SendFcmNotificationAction falls back to mock mode when no credentials file exists', function () {
    Config::set('services.firebase.credentials', '/non/existent/path/credentials.json');

    $action = new SendFcmNotificationAction();
    $result = $action->execute('target-fcm-token-123', 'Test Title', 'Test Body');

    expect($result)->toHaveKey('success', true);
    expect($result)->toHaveKey('mock', true);
});

test('SendFcmNotificationAction sends FCM message when Http is faked and credentials are provided', function () {
    // Fake OAuth2 token response and FCM send response
    Http::fake([
        'https://oauth2.googleapis.com/*' => Http::response([
            'access_token' => 'mock-google-oauth2-access-token',
        ], 200),
        'https://fcm.googleapis.com/*' => Http::response([
            'name' => 'projects/mock-project/messages/msg-12345',
        ], 200),
    ]);

    Config::set('services.firebase.project_id', 'mock-project');

    // Create temporary mock credentials file
    $tempFile = sys_get_temp_dir() . '/test-firebase-credentials.json';
    
    // Generate an RSA private key for testing JWT signing
    $res = openssl_pkey_new([
        "digest_alg" => "sha256",
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    ]);
    openssl_pkey_export($res, $privateKey);

    file_put_contents($tempFile, json_encode([
        'client_email' => 'test-service-account@mock-project.iam.gserviceaccount.com',
        'private_key' => $privateKey,
    ]));

    Config::set('services.firebase.credentials', $tempFile);

    $action = new SendFcmNotificationAction();
    $result = $action->execute('target-fcm-token-123', 'Emergency Alert', 'Blood sugar critical!', ['alert_id' => '101']);

    expect($result)->toHaveKey('success', true);
    expect($result['response'])->toHaveKey('name', 'projects/mock-project/messages/msg-12345');

    @unlink($tempFile);
});
