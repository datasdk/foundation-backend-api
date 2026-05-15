<?php

use App\Http\Controllers\Api\RoleController;
use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;

Route::name('api.')->middleware('auth.both:api')->group(function () {
    Orion::resource('roles', RoleController::class);
});
