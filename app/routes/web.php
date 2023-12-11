<?php

use TurFramework\Core\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('homePage');
Route::controller(HomeController::class)->group(function () {
    Route::get('/about', 'about')->name('aboutPage');
    Route::post('/post', 'post')->name('post');
});
