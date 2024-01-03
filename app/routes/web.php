<?php

use TurFramework\Core\Facades\Route;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('homePage');
Route::controller(HomeController::class)->group(function () {
    Route::post('/user/{id}', 'user')->name('user');
});

Route::get('/about', [AboutController::class, 'about'])->name('aboutPage');
