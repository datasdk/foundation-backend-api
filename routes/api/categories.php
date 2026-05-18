<?php

use App\Http\Controllers\Api\CategoriesController;
use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;

Route::name('api.')->group(function () {
    Route::middleware('auth.both:api')->group(function () {
        Orion::resource('categories', CategoriesController::class, ['only' => ['index', 'show', 'search']]);
        Route::post('categories/{type}/search', [CategoriesController::class, 'children']);
        Route::post('categories/{type}/tree', [CategoriesController::class, 'tree'])->name('categories.tree');
    });

    Route::middleware('auth:api')->group(function () {
        Orion::resource('categories', CategoriesController::class, ['except' => ['index', 'show', 'search']]);
        Route::post('options/categories/add/{model}', [CategoriesController::class, 'addCategories'])->name('options.categories.set');
    });
});
