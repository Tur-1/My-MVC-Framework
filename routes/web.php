<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use src\Router\Route;



Route::get('/', function () {
    echo 'home';
});

Route::get('/products', [ProductController::class, 'index']);
