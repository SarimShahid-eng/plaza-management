<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('auth')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'index')->name('dashboard');
    });
});
Route::controller(LoginController::class)->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('login', 'authenticate')->name('login');
    Route::middleware('auth')->group(function () {
        Route::post('logout', 'logout')->name('logout');
    });
});
// Route::get('login',')


// Route::resource('plazas', App\Http\Controllers\PlazaController::class)->only('index', 'store');

// Route::resource('payments', App\Http\Controllers\PaymentController::class)->only('store', 'update');


// Route::resource('plazas', App\Http\Controllers\PlazaController::class)->only('index');


// Route::resource('plazas', App\Http\Controllers\PlazaController::class)->only('index', 'store');


// Route::resource('plazas', App\Http\Controllers\Api\PlazaController::class)->only('index', 'store');


// Route::resource('plazas', App\Http\Controllers\Api\PlazaController::class)->only('index', 'store');


// Route::resource('notifications', App\Http\Controllers\Api\NotificationController::class)->only('index', 'update');

// Route::resource('transaction-logs', App\Http\Controllers\Api\TransactionLogController::class)->only('index', 'show');

// Route::resource('audit-logs', App\Http\Controllers\Api\AuditLogController::class)->only('index');


// Route::resource('notifications', App\Http\Controllers\Api\NotificationController::class)->only('index', 'update');


// Route::resource('notifications', App\Http\Controllers\Api\NotificationController::class)->only('index', 'update');
