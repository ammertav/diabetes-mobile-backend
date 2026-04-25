<?php

namespace App\Utilities;

use App\Models\RefreshToken;
use Firebase\JWT\Key;
use Firebase\JWT\JWT;
use Illuminate\Support\Str;

class JwtUtility
{
    protected static function privateKey()
    {
        return file_get_contents(storage_path('app/private/keys/private.key'));
    }

    protected static function publicKey()
    {
        return file_get_contents(storage_path('app/public/keys/public.key'));
    }

    protected static function algorithm()
    {
        return 'RS256';
    }

    /**
     * ACCESS TOKEN (short lived)
     */
    public static function generateAccessToken($user): string
    {
        $payload = [
            'iss' => 'diabetes-admin',
            'sub' => (string) $user->id,
            'aud' => 'mobile-app',

            'type' => 'access',
            'ver' => $user->token_version ?? 1,
            'jti' => (string) Str::uuid(),

            'iat' => time(),
            'exp' => time() + (60 * 60), // 1 jam
        ];

        return JWT::encode($payload, self::privateKey(), self::algorithm());
    }

    /**
     * REFRESH TOKEN (long lived)
     */
    public static function generateRefreshToken($user): array
    {
        $jti = (string) Str::uuid();
        $exp = time() + (60 * 60 * 24 * 30);

        $payload = [
            'iss' => 'diabetes-admin',
            'sub' => (string) $user->id,
            'aud' => 'mobile-app',

            'type' => 'refresh',
            'ver' => $user->token_version ?? 1,
            'jti' => $jti,

            'iat' => time(),
            'exp' => $exp, // 30 hari
        ];

        return [
            'token' => JWT::encode($payload, self::privateKey(), self::algorithm()),
            'jti' => $jti,
            'expired_at' => date('Y-m-d H:i:s', $exp),
        ];
    }

    /**
     * Decode & verify token
     */
    public static function decode(string $token)
    {
        return JWT::decode(
            $token,
            new Key(self::publicKey(), self::algorithm())
        );
    }

    public static function isAccessToken($payload): bool
    {
        return ($payload->type ?? null) === 'access';
    }

    public static function isRefreshToken($payload): bool
    {
        return ($payload->type ?? null) === 'refresh';
    }
}
