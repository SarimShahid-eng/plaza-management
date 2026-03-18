<?php

use App\Http\Controllers\Api\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(LoginController::class)->group(function () {
    Route::post('login', 'login');
});
Route::get('hh', function (Request $request) {
    dd('ss');
    return $request->user();
})->middleware('auth:sanctum');
