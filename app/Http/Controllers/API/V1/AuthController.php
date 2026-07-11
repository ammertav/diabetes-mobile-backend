<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Actions\Auth\RegisterUserAction;
use App\Actions\Auth\LoginUserAction;
use App\Actions\Auth\RotateRefreshTokenAction;
use App\Actions\Auth\UpdateUserProfileAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, RegisterUserAction $action)
    {
        $result = $action->execute($request->validated());

        return response()->json([
            'success' => true,
            'data' => [
                'user' => new UserResource($result['user']),
                'refresh_token' => $result['refresh_token'],
                'token' => $result['token'],
            ],
            'message' => 'Registration successful'
        ]);
    }

    public function login(Request $request, LoginUserAction $action)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $result = $action->execute($data['email'], $data['password']);

        if (!$result) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
            'data' => [
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
                'refresh_token' => $result['refresh_token'],
            ]
        ]);
    }

    public function refreshToken(Request $request, RotateRefreshTokenAction $action)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'error' => 'Refresh token required'
            ], 401);
        }

        try {
            $result = $action->execute($token);

            return response()->json([
                'data' => [
                    'token' => $result['token'],
                    'refresh_token' => $result['refresh_token'],
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 401);
        }
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

    public function update(UpdateUserRequest $request, UpdateUserProfileAction $action)
    {
        try {
            $user = $action->execute(Auth::user(), $request->validated());

            return response()->json([
                'data' => new UserResource($user),
                'message' => 'Profile updated successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to update profile',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
