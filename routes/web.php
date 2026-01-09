<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PropertyImageController;
use App\Http\Controllers\Admin\DashboardController;


use App\Http\Controllers\Admin\Reports\BookingsReportController;
use App\Http\Controllers\Admin\Reports\PropertiesReportController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard Routes (Admin only)
| URL prefix: /dashboard
| Route name prefix: dashboard.*
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.active', 'role:admin'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {


        // Home dashboard
Route::get('/', [DashboardController::class, 'index'])
    ->name('index');


        /*
        | Amenities CRUD
        */
        Route::resource('amenities', AmenityController::class)
            ->except(['show']);

        /*
        | Properties CRUD
        */
        Route::resource('properties', PropertyController::class);

        /*
        | Property Types Page
        */
        Route::get('properties/types', [PropertyController::class, 'types'])
            ->name('properties.types');

        /*
        | Property Images
        */
        Route::post('properties/{property}/images', [PropertyImageController::class, 'store'])
            ->name('properties.images.store');

        /*
        | Reports Pages
        */
        Route::view('reports', 'dashboard.reports.index')
            ->name('reports.index');

        Route::view('reports/properties', 'dashboard.reports.properties')
            ->name('reports.properties');

        Route::view('reports/bookings', 'dashboard.reports.bookings')
            ->name('reports.bookings');


        Route::get('reports/bookings', [BookingsReportController::class, 'index'])
            ->name('reports.bookings');

      // Export PDf
    Route::get('reports/bookings/export',
    [BookingsReportController::class, 'export'])
    ->name('reports.bookings.export');
    Route::get('admin/reports/properties/export', [PropertiesReportController::class, 'export'])
     ->name('dashboard.reports.properties.export');


        Route::get('reports/properties', [PropertiesReportController::class, 'index'])
            ->name('reports.properties');

        // Create employee
        Route::post('/employees', [AdminController::class, 'store'])
            ->name(name: 'admin.employees.store');

        // Change user role
        Route::patch('/users/{id}/role', [AdminController::class, 'changeRole'])
            ->name('admin.users.change-role');

        // Activate / Deactivate user
        Route::patch('/users/{userId}/status', [AdminController::class, 'toggleUserStatus'])
            ->name('admin.users.toggle-status');

        // Change admin password
        Route::patch('/change-password', [AdminController::class, 'changePassword'])
            ->name('admin.change-password');

        // add employee
        Route::post('/add-employee', [AdminController::class, 'store']);
    });

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


require __DIR__ . '/auth.php';
require __DIR__ . '/employee.php';
