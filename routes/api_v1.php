<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\ContentController;
use App\Http\Controllers\API\V1\DashboardController;
use App\Http\Controllers\API\V1\FastingLogController;
use App\Http\Controllers\API\V1\FastingProtocolController;
use App\Http\Controllers\API\V1\FgbController;
use App\Http\Controllers\API\V1\NotificationController;
use App\Http\Controllers\API\V1\SafetyAlertController;
use App\Http\Controllers\API\V1\StreakController;
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
    Route::post('fgb', [FgbController::class, 'store']);
    Route::get('fgb', [FgbController::class, 'index']);

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::get('dashboard/export', [DashboardController::class, 'export']);
    Route::get('dashboard/fbg-trend', [DashboardController::class, 'fbgTrend']);

    // Safety Alerts
    Route::get('safety-alerts', [SafetyAlertController::class, 'index']);
    Route::patch('safety-alerts/{id}/acknowledge', [SafetyAlertController::class, 'acknowledge']);
    Route::get('safety-alerts/settings', [SafetyAlertController::class, 'settings']);
    Route::put('safety-alerts/settings', [SafetyAlertController::class, 'updateSettings']);

    // Content (Edu & Spiritual)
    Route::get('content', [ContentController::class, 'index']);

    // Streaks
    Route::get('streaks', [StreakController::class, 'show']);

    // Notifications
    Route::get('notifications/settings', [NotificationController::class, 'settings']);
    Route::put('notifications/settings', [NotificationController::class, 'updateSettings']);
    Route::patch('notifications/fcm-token', [NotificationController::class, 'registerFcmToken']);
    Route::get('notifications/history', [NotificationController::class, 'history']);
    Route::patch('notifications/history/{id}/read', [NotificationController::class, 'markAsRead']);
});

