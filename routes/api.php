<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\MaintenancePostController;
// use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PlazaController;
use App\Http\Controllers\Api\SpecialAssessmentController;
use App\Http\Controllers\Api\UnitController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(LoginController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout');
});
// Route::get('hh', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('plaza')->name('plaza')->controller(PlazaController::class)->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::get('show/{plaza}', 'show')->name('show');          // api/plaza/1
        Route::put('update/{plaza}', 'update')->name('update');      // api/plaza/1 (PUT)
        Route::delete('destroy/{plaza}', 'destroy')->name('destroy'); // a
    });
    Route::prefix('user')->name('user.')->controller(UserController::class)->group(function () {
        Route::middleware('role:chairman')->group(function () {
            Route::get('members', 'members');
            Route::post('store', 'store');
        });
        Route::middleware('role:chairman,member')->group(function () {
            Route::post('update', 'update');
        });
    });
    Route::middleware('role:chairman')->group(function () {
        Route::prefix('unit')->name('unit')->controller(UnitController::class)->group(function () {
            Route::get('index', 'index')->name('index');
            Route::post('store', 'store')->name('store');
            Route::get('show/{unit}', 'show')->name('show');
            // Route::post('update', 'update')->name('update');
            Route::post('assignMember', 'assignMember')->name('assignMember');
            Route::post('revokeMember', 'revokeMember')->name('revokeMember');
            Route::post('paidAmount', 'paidAmount');
        });
        Route::prefix('maintenance_post')->name('maintenance_post')->controller(MaintenancePostController::class)->group(function () {
            Route::get('index', 'index')->name('index');
            Route::post('store', 'store')->name('store');
            // Route::get('show/{unit}', 'show')->name('show');
            // Route::post('update', 'update')->name('update');
            // Route::delete('destroy/{unit}', 'destroy')->name('destroy');
        });
    });
    Route::prefix('special_assesment')->name('special_assesment')->controller(SpecialAssessmentController::class)->group(function () {
        Route::middleware('role:chairman,member')->group(function () {
            // for user and chairman both
            Route::get('index', 'index')->name('index');
        });
        // store can be hit by chairman only
        Route::post('store', 'store')->name('store');
    });

    // Route::prefix('payments')->name('payment')->controller(PaymentController::class)->group(function () {
    //     Route::get('show/{user}', 'show')->name('show');          // api/plaza/1
    //     Route::post('update/{unit}', 'update')->name('update');      // api/plaza/1 (PUT)
    //     Route::delete('destroy/{unit}', 'destroy')->name('destroy'); // a
    //     Route::patch('assignMember/{user}/{unit}', 'assignMember')->name('assignMember'); // a
    // });
});
