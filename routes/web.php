<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CmsContentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FastingProtocolController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\FgbMonitoringController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login-page');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login');
});
Route::get('/register', [AuthController::class, 'showRegister'])->name('register-page');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/patients', [PatientController::class, 'index'])->name('patients-management');
    Route::get('/patients/data', [PatientController::class, 'loadPatients']);

    Route::get('/fgb-monitoring', [FgbMonitoringController::class, 'index'])->name('fgb-monitoring');
    Route::get('/fgb-monitoring/data', [FgbMonitoringController::class, 'loadLogs']);
    Route::get('/fgb-monitoring/chart', [FgbMonitoringController::class, 'chartData']);
    Route::get('/fgb-monitoring/chart-details', [FgbMonitoringController::class, 'chartDetails']);
    Route::get('/fgb-monitoring/patients/{userId}', [FgbMonitoringController::class, 'patientDetail']);

    Route::get('/cms', [CmsContentController::class, 'index'])->name('cms');
    Route::get('/cms/create', [CmsContentController::class, 'create'])->name('cms-create');
    Route::post('/cms', [CmsContentController::class, 'store'])->name('cms-store');
    Route::get('/cms/{id}/edit', [CmsContentController::class, 'edit'])->name('cms-edit');
    Route::put('/cms/{id}', [CmsContentController::class, 'update'])->name('cms-update');
    Route::delete('/cms/{id}', [CmsContentController::class, 'destroy'])->name('cms-destroy');

    Route::get('/fasting-protocols', [FastingProtocolController::class, 'index'])->name('fasting-protocols');
    Route::get('/fasting-protocols/create', [FastingProtocolController::class, 'create'])->name('fasting-protocols-create');
    Route::post('/fasting-protocols', [FastingProtocolController::class, 'store'])->name('fasting-protocols-store');
    Route::put('/fasting-protocols/{id}', [FastingProtocolController::class, 'update'])->name('fasting-protocols-update');
    Route::delete('/fasting-protocols/{id}', [FastingProtocolController::class, 'destroy'])->name('fasting-protocols-destroy');
});
