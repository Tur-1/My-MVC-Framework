<?php

use TurFramework\Core\Facades\Route;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\HomeController;

Route::get('/about', [AboutController::class, 'about'])->name('aboutPage');

Route::get('/sd', function () {
    echo '/sd';
});

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('homePage');
});
