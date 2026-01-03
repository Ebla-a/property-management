<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\PropertyController;

Route::prefix('dashboard')
    ->name('dashboard.')
    ->middleware(['auth', 'can:admin'])
    ->group(function () {

        Route::resource('amenities', AmenityController::class)->except(['show']);
        Route::resource('properties', PropertyController::class);
});


