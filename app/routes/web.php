<?php

use TurFramework\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AboutController;

Route::get('/', [HomeController::class, 'index'])->name('homePage');


Route::get('/about', [AboutController::class, 'index'])->name('aboutPage');


Route::controller(UserController::class)->group(function () {

    Route::get('/users',  'index')->name('usersList');

    Route::get('/users/create',  'create')->name('usersCreate');

    Route::get('/users/{id}/edit',  'edit')->name('usersEdit');

    Route::post('/users/store',  'store')->name('usersStore');

    Route::post('/users/update/{id}',  'update')->name('usersUpdate');

    Route::delete('/users/{id}/delete',  'delete')->name('usersDelete');
});
