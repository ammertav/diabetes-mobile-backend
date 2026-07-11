<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Models\RefreshToken;
use App\Utilities\JwtUtility;
use Firebase\JWT\ExpiredException;

class RotateRefreshTokenAction
{
    public function execute(string $token): array
    {
        try {
            $payload = JwtUtility::decode($token);
        } catch (ExpiredException $e) {
            throw new \Exception('Refresh token expired');
        } catch (\Exception $e) {
            throw new \Exception('Invalid token');
        }

        if (($payload->type ?? null) !== 'refresh') {
            throw new \Exception('Invalid token type');
        }

        $tokenDb = RefreshToken::query()->where('jti', $payload->jti)->first();

        if (!$tokenDb) {
            throw new \Exception('Token tidak valid');
        }

        if ($tokenDb->is_revoked) {
            throw new \Exception('Token sudah revoked');
        }

        if ($tokenDb->expired_at < now()) {
            throw new \Exception('Token expired');
        }

        $user = User::query()->where('id', $payload->sub)->first();

        if (!$user) {
            throw new \Exception('User not found');
        }

        $tokenDb->update(['is_revoked' => true]);

        $newAccessToken = JwtUtility::generateAccessToken($user);
        $newRefreshToken = JwtUtility::generateRefreshToken($user);

        RefreshToken::create([
            'user_id' => $user->id,
            'jti' => $newRefreshToken['jti'],
            'expired_at' => $newRefreshToken['expired_at'],
            'is_revoked' => false,
        ]);

        return [
            'token' => $newAccessToken,
            'refresh_token' => $newRefreshToken['token'],
        ];
    }
}
