<?php

namespace App\Actions\Notification;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendFcmNotificationAction
{
    public function execute(
        string $fcmToken,
        string $title,
        string $body,
        array $data = [],
        bool $validateOnly = false
    ): array {
        $credentials = $this->loadCredentials();
        if (!$credentials) {
            Log::info("FCM Mock Notice: Firebase credentials not found or incomplete. FCM token target: {$fcmToken}");
            return [
                'success' => true,
                'mock' => true,
                'message' => 'FCM mock notification logged (No credentials configured)',
            ];
        }

        $projectId = config('services.firebase.project_id') ?: ($credentials['project_id'] ?? null);
        if (!$projectId) {
            return ['success' => false, 'error' => 'Firebase Project ID is missing'];
        }

        $accessToken = $this->getAccessToken($credentials);
        if (!$accessToken) {
            return ['success' => false, 'error' => 'Failed to generate Google OAuth2 token for FCM'];
        }

        return $this->sendHttpRequest($projectId, $accessToken, $fcmToken, $title, $body, $data, $validateOnly);
    }

    private function loadCredentials(): ?array
    {
        $path = config('services.firebase.credentials');
        if (!$path || !file_exists($path)) {
            return null;
        }

        $content = file_get_contents($path);
        $json = json_decode($content, true);
        
        return (is_array($json) && isset($json['client_email'], $json['private_key'])) ? $json : null;
    }

    private function getAccessToken(array $credentials): ?string
    {
        return Cache::remember('fcm_access_token', 3000, function () use ($credentials) {
            try {
                $now = time();
                $payload = [
                    'iss' => $credentials['client_email'],
                    'sub' => $credentials['client_email'],
                    'aud' => 'https://oauth2.googleapis.com/token',
                    'iat' => $now,
                    'exp' => $now + 3600,
                    'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                ];

                $jwt = JWT::encode($payload, $credentials['private_key'], 'RS256');

                $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ]);

                return $response->successful() ? ($response->json('access_token') ?? null) : null;
            } catch (\Throwable $e) {
                Log::error('FCM Token Auth Exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    private function sendHttpRequest(
        string $projectId,
        string $accessToken,
        string $fcmToken,
        string $title,
        string $body,
        array $data,
        bool $validateOnly = false
    ): array {
        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";
        $payload = [
            'validate_only' => $validateOnly,
            'message' => [
                'token' => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
            ],
        ];

        if (!empty($data)) {
            $payload['message']['data'] = array_map('strval', $data);
        }

        try {
            $response = Http::withToken($accessToken)
                ->acceptJson()
                ->post($url, $payload);

            if ($response->successful()) {
                return ['success' => true, 'response' => $response->json()];
            }

            Log::error('FCM Send Error: ' . $response->body());
            return ['success' => false, 'error' => $response->body()];
        } catch (\Throwable $e) {
            Log::error('FCM Connection Exception: ' . $e->getMessage());
            return ['success' => false, 'error' => 'Gagal terhubung ke API FCM: ' . $e->getMessage()];
        }
    }
}
