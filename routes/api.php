<?php

use App\Http\Controllers\Api\GenericEntityController;
use App\Http\Controllers\Auth\AuthenticateController;
use App\Http\Controllers\Operator\OperatorController;
use App\Http\Controllers\Schema\AttributeController;
use App\Http\Controllers\Schema\EntityController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->as('api.')->group(function () {

    Route::post('login', [AuthenticateController::class, 'login'])->name('login');

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthenticateController::class, 'logout'])->name('logout');
    });
});

Route::prefix('admin')
    ->as('api.')
    ->middleware('auth:api')
    ->group(function () {
        Route::apiResource('operators', OperatorController::class);

        // Entity routes
        Route::apiResource('entities', EntityController::class);

        // Attribute routes
        Route::apiResource('attributes', AttributeController::class);

        // Entity-Attribute relationship routes
        Route::get('entities/{entity}/attributes', [AttributeController::class, 'getAttributesForEntity'])
            ->name('entities.attributes');
        Route::post('attributes/attach', [AttributeController::class, 'attachToEntity'])
            ->name('attributes.attach');
        Route::post('attributes/detach', [AttributeController::class, 'detachFromEntity'])
            ->name('attributes.detach');

        // Entity-Entity relationship routes
        Route::post('entities/relationships', [EntityController::class, 'createRelationship'])
            ->name('entities.relationships.create');
        Route::delete('entities/relationships/{relationship}', [EntityController::class, 'deleteRelationship'])
            ->name('entities.relationships.delete');
        Route::get('entities/{entity}/relationships', [EntityController::class, 'getRelationships'])
            ->name('entities.relationships');
    });

// Content API routes
Route::prefix('content')
    ->as('api.content.')
    ->middleware('auth:api')
    ->group(function () {
        // Entity record creation
        Route::post('{entitySlug}', [GenericEntityController::class, 'store'])
            ->name('store');

        // Get entity record with relations
        Route::get('{entitySlug}/{recordId}', [GenericEntityController::class, 'show'])
            ->name('show')
            ->where('recordId', '[0-9]+');
    });
