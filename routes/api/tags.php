<?php

use DataSDK\Categories\Http\Controllers\Api\TagsController;
use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;

Route::name('api.')->middleware('auth.both:api')->group(function () {
    Orion::resource('tags', TagsController::class);
    Route::get('tags/type/{type}', [TagsController::class, 'findByType'])->name('tags.type');
});
