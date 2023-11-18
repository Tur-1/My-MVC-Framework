<?php

use App\Http\Controllers\HomeController;
use TurFramework\Core\Router\Route;

Route::get('/', [HomeController::class, 'index']);
