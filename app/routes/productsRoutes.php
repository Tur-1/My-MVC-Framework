<?php


use App\Http\Controllers\ProductController;
use TurFramework\src\Router\Route;




Route::get('/products', [ProductController::class, 'index']);
