<?php

use App\Http\Controllers\AuthController;
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
    Route::get('/patient-management', function () {
        return view('patient-management.index');
    })->name('patient-management');
    Route::get('/fgb-monitoring', function () {
        return view('fgb-monitoring.index');
    })->name('fgb-monitoring');
});
