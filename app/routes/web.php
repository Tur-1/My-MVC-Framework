<?php

use App\Http\Controllers\HomeController;
use TurFramework\src\Router\Route;

Route::get('/', [HomeController::class, 'index']);
