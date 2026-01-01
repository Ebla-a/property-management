<?php

use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home page
Route::get('/', function () {
    return view('welcome');
});

// Admin dashboard routes (Properties Views)
// Protected by auth + role:admin
Route::middleware(['auth', 'checkRole:admin'])
    ->prefix('dashboard/admin')
    ->name('admin.')
    ->group(function () {

        // CRUD Views for Properties
        // index, create, store, show, edit, update, destroy
        Route::resource('properties', AdminPropertyController::class);

        // Property types management page
        Route::get('properties/types', [AdminPropertyController::class, 'types'])
            ->name('properties.types');
    });
