<?php

use App\Http\Controllers\API\V1\AuthController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('refresh-token', [AuthController::class, 'refreshToken']);
});

Route::middleware(\App\Http\Middleware\API\V1\AuthMiddleware::class)->group(function () {

    Route::get('users/profile', [AuthController::class, 'me']);
});
