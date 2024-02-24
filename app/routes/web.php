<?php

use TurFramework\Http\Request;
use TurFramework\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\DashboardController;


Route::get('/', [HomeController::class, 'index'])->middleware('auth')->name('homePage');

Route::get('/about', function () {

    return view('pages.aboutPage');
})->name('aboutPage');



Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::controller(UserController::class)->group(function () {

    Route::get('/users',  'index')->name('users.list');

    Route::get('/users/create',  'create')->name('users.create');

    Route::get('/users/{id}/edit',  'edit')->name('users.edit');

    Route::post('/users/store',  'store')->name('users.store');

    Route::post('/users/update/{id}',  'update')->name('users.update');

    Route::delete('/users/{id}/delete',  'delete')->name('users.delete');
});
