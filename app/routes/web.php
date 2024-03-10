<?php

use TurFramework\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;


Route::get('/', [HomeController::class, 'index'])->name('homePage');

Route::get('/about', [AboutController::class, 'index'])->name('aboutPage');


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::controller(UserController::class)->group(function () {

    Route::get('/users',  'index')->name('users.list');

    Route::get('/users/create',  'create')->name('users.create');

    Route::get('/users/{id}/edit',  'edit')->name('users.edit');

    Route::post('/users/store',  'store')->name('users.store');

    Route::post('/users/update/{id}',  'update')->name('users.update');

    Route::delete('/users/{id}/delete',  'delete')->name('users.delete');
});


Route::controller(AdminController::class)->group(function () {

    Route::get('/admin/dashboard',  'dashboard')->middleware('auth:admins')->name('admin.dashboard');

    Route::post('/admin/logout', 'logout')->middleware('auth:admins')->name('admin.logout');

    Route::get('/admin/login', 'login')->middleware('guest:admins')->name('admin.login');

    Route::post('/admin/login/store', 'store')->middleware('guest:admins')->name('admin.login.store');
});
