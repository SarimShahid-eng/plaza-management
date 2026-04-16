<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PlazaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('auth')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'index')->name('dashboard');
    });
    Route::controller(UserController::class)->prefix('users')->group(function (){
        Route::get('/' , 'index')->name('users.index');
        Route::get('/create' , 'create')->name('users.create');
        Route::post('/store' , 'store')->name('users.store');
        Route::get('/edit/{user}' , 'edit')->name('users.edit');
        Route::post('/update/{user}' , 'update')->name('users.update');
        Route::get('/show/{user}' , 'show')->name('users.show');
        Route::post('/destroy/{user}' , 'destroy')->name('users.destroy');
    });
    Route::controller(PlazaController::class)->prefix('plaza')->group(function (){
        Route::get('/' , 'index')->name('users.index');
        Route::get('/create' , 'create')->name('users.create');
        Route::post('/store' , 'store')->name('users.store');
        Route::get('/edit/{user}' , 'edit')->name('users.edit');
        // Route::post('/destroy/{user}' , 'destroy')->name('users.destroy');
    });
});
Route::controller(LoginController::class)->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('login', 'authenticate')->name('login');
    Route::middleware('auth')->group(function () {
        Route::post('logout', 'logout')->name('logout');
    });
});
