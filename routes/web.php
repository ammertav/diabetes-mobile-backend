<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CmsContentController;
use App\Http\Controllers\FastingProtocolController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login-page');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login');
});
Route::get('/register', [AuthController::class, 'showRegister'])->name('register-page');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::get('/patients', [PatientController::class, 'index'])->name('patients-management');
    Route::get('/patients/data', [PatientController::class, 'loadPatients']);

    Route::get('/fgb-monitoring', function () {
        return view('fgb-monitoring.index');
    })->name('fgb-monitoring');

    Route::get('/cms', [CmsContentController::class, 'index'])->name('cms');
    Route::post('/cms/create', [CmsContentController::class, 'store'])->name('cms-store');
    Route::get('/cms/create', [CmsContentController::class, 'create'])->name('cms-create');

    Route::get('/fasting-protocols', [FastingProtocolController::class, 'index'])->name('fasting-protocols');
});
