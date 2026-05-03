<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\FastingLogController;
use App\Http\Controllers\API\V1\FastingProtocolController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('refresh-token', [AuthController::class, 'refreshToken']);
});

Route::middleware(\App\Http\Middleware\API\V1\AuthMiddleware::class)->group(function () {
    Route::get('users/profile', [AuthController::class, 'me']);
    Route::put('users/profile', [AuthController::class, 'update']);

    // Fasting Protocols
    Route::get('protocols', [FastingProtocolController::class, 'index']);
    Route::post('protocols/select', [FastingProtocolController::class, 'selectProtocol']);
    Route::get('protocols/active', [FastingProtocolController::class, 'active']);

    // Fasting Logs
    Route::post('fasting-logs/confirm', [FastingLogController::class, 'confirm']);
    Route::patch('fasting-logs/{id}/end', [FastingLogController::class, 'endFasting']);
    Route::get('fasting-logs', [FastingLogController::class, 'index']);

    // FGB Records
    Route::post('fgb-records', [\App\Http\Controllers\API\V1\FgbController::class, 'store']);
});
