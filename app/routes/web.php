<?php

use TurFramework\Core\Facades\Route;
use App\Http\Controllers\HomeController;



Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('homePage');
    Route::get('/about', 'about')->name('aboutPage');
});
