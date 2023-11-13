<?php


use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use src\Router\Route;



Route::get('/', [HomeController::class, 'index']);
