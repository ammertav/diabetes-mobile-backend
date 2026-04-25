<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::get('/', function () {
    return view('dashboard.index');
})->name('dashboard');
Route::get('/patient-management', function () {
    return view('patient-management.index');
})->name('patient-management');
Route::get('/fgb-monitoring', function () {
    return view('fgb-monitoring.index');
})->name('fgb-monitoring');
