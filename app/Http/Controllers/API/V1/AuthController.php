<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\MobileProfile;
use App\Models\RefreshToken;
use App\Models\User;
use App\Models\UserAuthProvider;
use App\Enums\UserType;
use App\Utilities\JwtUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
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
                'provider' => 'email',
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

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => new UserResource($user),
                    'refresh_token' => $refreshToken['token'],
                    'token' => $token,
                ],
                'message' => 'Registration successful'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $data['email'])->with('mobileProfile')->first();

        if (!$user) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        if ($user->type === UserType::ADMIN) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $auth = $user->authProviders()->where('provider', 'email')->first();

        if (!Hash::check($data['password'], $auth->password_hash)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }


        $refreshToken = JwtUtility::generateRefreshToken($user);
        $accessToken = JwtUtility::generateAccessToken($user);

        RefreshToken::create([
            'user_id' => $user->id,
            'jti' => $refreshToken['jti'],
            'expired_at' => $refreshToken['expired_at'],
            'is_revoked' => false,
        ]);

        return response()->json([
            'data' => [
                'user' => new UserResource($user),
                'token' => $accessToken,
                'refresh_token' => $refreshToken['token'],
            ]
        ]);
    }

    public function refreshToken(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'error' => 'Refresh token required'
            ], 401);
        }

        try {
            $payload = JwtUtility::decode($token);

            $tokenDb = RefreshToken::where('jti', $payload->jti)->first();

            if (!$tokenDb) {
                return response()->json(['error' => 'Token tidak valid'], 401);
            }

            if ($tokenDb->is_revoked) {
                return response()->json(['error' => 'Token sudah revoked'], 401);
            }

            if ($tokenDb->expired_at < now()) {
                return response()->json(['error' => 'Token expired'], 401);
            }
        } catch (\Firebase\JWT\ExpiredException $e) {
            return response()->json([
                'error' => 'Refresh token expired'
            ], 401);
        } catch (\Exception $e) {
            dd($e);
            return response()->json([
                'error' => 'Invalid token'
            ], 401);
        }

        if (($payload->type ?? null) !== 'refresh') {
            return response()->json([
                'error' => 'Invalid token type'
            ], 401);
        }

        $user = User::where('id', $payload->sub)->first();

        if (!$user) {
            return response()->json([
                'error' => 'User not found'
            ], 401);
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

        return response()->json([
            'data' => [
                'token' => $newAccessToken,
                'refresh_token' => $newRefreshToken['token'],
            ],

        ]);
    }

    public function me(Request $request)
    {
        $user = Auth::user()->load('mobileProfile');

        if (!$user) {
            return response()->json([
                'message' => 'User tidak ditemukan'
            ], 401);
        }

        return response()->json([
            'data' => new UserResource($user),
        ]);
    }
}
