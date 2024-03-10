<?php

use TurFramework\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/login', [AuthenticatedController::class, 'index'])
    ->middleware('guest')
    ->name('login');


Route::post('/login/store', [AuthenticatedController::class, 'store'])
    ->middleware('guest')
    ->name('login.store');

Route::post('/logout', [AuthenticatedController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/register', [RegisterController::class, 'index'])
    ->middleware('guest')
    ->name('register');

Route::post('/register/store', [RegisterController::class, 'store'])
    ->middleware('guest')
    ->name('register.store');
