<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Models\MobileProfile;
use App\Models\UserAuthProvider;
use App\Models\RefreshToken;
use App\Enums\UserType;
use App\Enums\AuthProvider;
use App\Utilities\JwtUtility;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    public function execute(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'email' => $data['email'],
                'type' => UserType::MOBILE,
            ]);

            MobileProfile::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'age' => $data['age'],
                'gender' => $data['gender'],
                'diabetes_status' => $data['diabetes_status'],
                'bmi' => $data['bmi'],
                'disclaimer_accepted' => $data['disclaimer_accepted'],
            ]);

            $user->load('mobileProfile');

            UserAuthProvider::create([
                'user_id' => $user->id,
                'provider' => AuthProvider::EMAIL,
                'provider_id' => $data['email'],
                'password_hash' => Hash::make($data['password']),
            ]);

            $refreshToken = JwtUtility::generateRefreshToken($user);
            $token = JwtUtility::generateAccessToken($user);

            RefreshToken::create([
                'user_id' => $user->id,
                'jti' => $refreshToken['jti'],
                'expired_at' => $refreshToken['expired_at'],
                'is_revoked' => false,
            ]);

            return [
                'user' => $user,
                'token' => $token,
                'refresh_token' => $refreshToken['token'],
            ];
        });
    }
}
