<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Models\RefreshToken;
use App\Enums\UserType;
use App\Utilities\JwtUtility;
use Illuminate\Support\Facades\Hash;

class LoginUserAction
{
    public function execute(string $email, string $password): ?array
    {
        $user = User::query()->where('email', $email)->with('mobileProfile')->first();

        if (!$user || $user->type === UserType::ADMIN) {
            return null;
        }

        $auth = $user->authProviders()->where('provider', 'email')->first();

        if (!$auth || !Hash::check($password, $auth->password_hash)) {
            return null;
        }

        $refreshToken = JwtUtility::generateRefreshToken($user);
        $accessToken = JwtUtility::generateAccessToken($user);

        RefreshToken::create([
            'user_id' => $user->id,
            'jti' => $refreshToken['jti'],
            'expired_at' => $refreshToken['expired_at'],
            'is_revoked' => false,
        ]);

        return [
            'user' => $user,
            'token' => $accessToken,
            'refresh_token' => $refreshToken['token'],
        ];
    }
}
