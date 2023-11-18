<?php


use App\Http\Controllers\ProductController;
use TurFramework\Core\Router\Route;




Route::get('/products', [ProductController::class, 'index']);
