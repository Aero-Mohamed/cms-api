<?php

use App\Http\Controllers\Auth\AuthenticateController;
use App\Http\Controllers\Operator\OperatorController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->as('api.')->group(function () {

    Route::post('login', [AuthenticateController::class, 'login'])->name('login');

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthenticateController::class, 'logout'])->name('logout');
    });
});

Route::prefix('admin/operators')
    ->as('api.')
    ->middleware('auth:api')
    ->group(function(){

    Route::post('/', [OperatorController::class, 'store']);
    Route::get('/', [OperatorController::class, 'index']);
    Route::get('/{operator}', [OperatorController::class, 'show']);
    Route::delete('/{operator}', [OperatorController::class, 'destroy']);
});
