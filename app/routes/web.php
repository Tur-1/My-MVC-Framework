<?php

use TurFramework\Core\Facades\Route;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('homePage');
Route::get('/about', [AboutController::class, 'index'])->name('aboutPage');
