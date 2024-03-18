<?php

use TurFramework\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AboutController;


Route::get('/', [HomeController::class, 'index'])->name('homePage');

Route::get('/about', [AboutController::class, 'index'])->name('aboutPage');



Route::controller(UserController::class)->group(function () {

    Route::get('/users',  'index')->name('users.list');

    Route::get('/users/create',  'create')->name('users.create');

    Route::get('/users/{user}/edit',  'edit')->name('users.edit');

    Route::post('/users/store',  'store')->name('users.store');

    Route::post('/users/update/{id}',  'update')->name('users.update');

    Route::delete('/users/{id}/delete',  'delete')->name('users.delete');

    Route::get('/profile', 'profile')->middleware('auth')->name('user.profile');
});
