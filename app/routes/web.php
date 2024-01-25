<?php

use TurFramework\Facades\Route;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('homePage');


Route::get('/about', [AboutController::class, 'index'])->name('aboutPage');

Route::get('/user/{id}/{name}/{age?}', [HomeController::class, 'user'])->name('user');
