<?php

use TurFramework\Facades\Route;
use App\Http\Controllers\AdminController;


Route::controller(AdminController::class)->group(function () {

    Route::get('/admin/dashboard',  'dashboard')
        ->middleware('auth:admins')->name('admin.dashboard');

    Route::post('/admin/logout', 'logout')
        ->middleware('auth:admins')->name('admin.logout');

    Route::get('/admin/login', 'login')
        ->middleware('guest:admins')->name('admin.login');

    Route::get('/admin/register', 'register')
        ->middleware('guest:admins')->name('admin.register');

    Route::post('/admin/register/store', 'registerAdmin')
        ->middleware('guest:admins')->name('admin.register.store');

    Route::post('/admin/login/store', 'store')
        ->middleware('guest:admins')->name('admin.login.store');
});
