<?php

use TurFramework\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AboutController;

Route::get('/', [HomeController::class, 'index'])->name('homePage');


Route::get('/about', [AboutController::class, 'index'])->name('aboutPage');

Route::post('/user/store', [UserController::class, 'store'])->name('store');
