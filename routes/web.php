<?php

use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\PropertyImageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\PropertyController;

Route::prefix('dashboard')
    ->name('dashboard.')
    ->middleware(['auth', 'checkRole:admin'])

    ->group(function () {

        Route::resource('amenities', AmenityController::class)->except(['show']);
        Route::resource('properties', PropertyController::class);
    });


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard (for admin and employee)
|--------------------------------------------------------------------------
|
|
|
*/

Route::middleware(['auth'])
    ->get('/dashboard', function () {
        return view('dashboard.index');
    })
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Admin dashboard routes (Properties Views)
| Protected by auth + role:admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'checkRole:admin'])
    ->prefix('dashboard')
    ->name('admin.')
    ->group(function () {
        // Reports
        Route::view('/reports', 'dashboard.reports.index')->name('reports.index');

        Route::view('/reports/properties', 'dashboard.reports.properties')
            ->name('reports.properties');

        Route::view('/reports/bookings', 'dashboard.reports.bookings')
            ->name('reports.bookings');

        // CRUD Views for Properties
        // index, create, store, show, edit, update, destroy
        Route::resource('properties', AdminPropertyController::class);

        // Property types management page
        Route::get('properties/types', [AdminPropertyController::class, 'types'])
            ->name('properties.types');

        // Property Images (protected)
        Route::post('/properties/{property}/images', [PropertyImageController::class, 'store']);
    });

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/employee.php';
