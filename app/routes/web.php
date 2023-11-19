<?php

use App\Http\Controllers\HomeController;
use TurFramework\Core\Router\Route;

Route::get('/', [HomeController::class, 'index']);
Route::post('/store/{user_id}', [HomeController::class, 'store']);
