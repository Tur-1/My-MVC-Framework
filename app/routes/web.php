<?php

use App\Http\Controllers\HomeController;
use TurFramework\Core\Router\Route;

Route::group()
    ->controller()
    ->get('/', [HomeController::class, 'index']);
