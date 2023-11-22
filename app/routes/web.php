<?php

use App\Http\Controllers\HomeController;

use TurFramework\Core\Router\Route;


Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index');
});
