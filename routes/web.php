<?php

use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\ProfileController;
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
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
