<?php


use App\Http\Controllers\ProductController;
use src\Router\Route;




Route::get('/products', [ProductController::class, 'index']);
