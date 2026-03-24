<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PlazaController;
use Illuminate\Support\Facades\Route;

Route::controller(LoginController::class)->group(function () {
    Route::post('login', 'login');
});
// Route::get('hh', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('plaza')->name('plaza')->controller(PlazaController::class)->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('store/{plaza}', 'store')->name('store');
        Route::get('show/{plaza}', 'show')->name('show');          // api/plaza/1
        Route::put('update/{plaza}', 'update')->name('update');      // api/plaza/1 (PUT)
        Route::delete('destroy/{plaza}', 'destroy')->name('destroy'); // a

    });
});
// Route::apiResource('users', App\Http\Controllers\Api\UserController::class);

// Route::apiResource('units', App\Http\Controllers\Api\UnitController::class);

// Route::apiResource('payments', App\Http\Controllers\Api\PaymentController::class);

// Route::apiResource('maintenance-posts', App\Http\Controllers\Api\MaintenancePostController::class);

// Route::apiResource('tickets', App\Http\Controllers\Api\TicketController::class);

// Route::apiResource('special-assessments', App\Http\Controllers\Api\SpecialAssessmentController::class);

// Route::apiResource('broadcasts', App\Http\Controllers\Api\BroadcastController::class);

// Route::apiResource('transaction-logs', App\Http\Controllers\Api\TransactionLogController::class);

// Route::apiResource('audit-logs', App\Http\Controllers\Api\AuditLogController::class);
