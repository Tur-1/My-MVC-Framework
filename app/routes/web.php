<?php

use App\Http\Controllers\HomeController;
use TurFramework\Core\Router\Route;

Route::get('/', [HomeController::class, 'index']);
Route::post('/store', [HomeController::class, 'store']);
