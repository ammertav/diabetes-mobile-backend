<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

Route::get('/', function () {
    return view('dashboard.index');
})->name('dashboard');
Route::get('/patient-management', function () {
    return view('patient-management.index');
})->name('patient-management');
Route::get('/fgb-monitoring', function () {
    return view('fgb-monitoring.index');
})->name('fgb-monitoring');
