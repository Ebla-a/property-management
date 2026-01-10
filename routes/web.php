<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Admin Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\PropertyImageController;

/*
|--------------------------------------------------------------------------
| Reports Controllers
|--------------------------------------------------------------------------
*/
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
| Dashboard Routes (Admin Only)
| Prefix: /dashboard
| Name prefix: dashboard.*
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.active'])
    ->prefix('/dashboard')
    ->name('dashboard.')
    ->group(function () {


        // Home dashboard
Route::get('/', [DashboardController::class, 'index'])
    ->name('index');
    
   
 Route::middleware(['auth', 'check.active'])->get('/', [DashboardController::class, 'index'])->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Amenities CRUD
        |--------------------------------------------------------------------------
        */
        Route::resource('amenities', AmenityController::class)
            ->except(['show']);

        /*
        |--------------------------------------------------------------------------
        | Properties CRUD
        |--------------------------------------------------------------------------
        */
        Route::resource('properties', PropertyController::class);

        /*
        |--------------------------------------------------------------------------
        | Property Types
        |--------------------------------------------------------------------------
        */
        Route::get('properties/types', [PropertyController::class, 'types'])
            ->name('properties.types');

        /*
        |--------------------------------------------------------------------------
        | Property Images
        |--------------------------------------------------------------------------
        */
        Route::post('properties/{property}/images', [PropertyImageController::class, 'store'])
            ->name('properties.images.store');

        /*
        |--------------------------------------------------------------------------
        | Reports Pages
        |--------------------------------------------------------------------------
        */
        Route::view('reports', 'dashboard.reports.index')
            ->name('reports.index');

        Route::get('reports/properties', [PropertiesReportController::class, 'index'])
            ->name('reports.properties');

        Route::get('reports/bookings', [BookingsReportController::class, 'index'])
            ->name('reports.bookings');

        /*
        |--------------------------------------------------------------------------
        | Reports Export
        |--------------------------------------------------------------------------
        */
        Route::get('reports/bookings/export', [BookingsReportController::class, 'export'])
            ->name('reports.bookings.export');

        Route::get('reports/properties/export', [PropertiesReportController::class, 'export'])
            ->name('reports.properties.export');

        /*
        |--------------------------------------------------------------------------
        | Users & Employees Management (AdminController)
        |--------------------------------------------------------------------------
        */

        // List all users
        Route::get('users', [AdminController::class, 'index'])
            ->name('admin.employees.index');

        // Show create employee page
        Route::get('employees/create', [AdminController::class, 'create'])
            ->name('admin.employees.create');

        // Store new employee
        Route::post('employees', [AdminController::class, 'store'])
            ->name('admin.employees.store');

        // Show edit role page
        Route::get('users/{id}/role', [AdminController::class, 'editRole'])
            ->name('admin.users.edit-role');

        // Update user role
        Route::patch('users/{id}/role', [AdminController::class, 'changeRole'])
            ->name('admin.users.change-role');

        // Show account status page
        Route::get('users/{userId}/status', [AdminController::class, 'editAccount'])
            ->name('admin.users.edit-status');

        // Toggle user active / inactive
        Route::patch('users/{userId}/status', [AdminController::class, 'toggleUserStatus'])
            ->name('admin.users.toggle-status');

        // Delete a user
        Route::delete('users/{userId}', [AdminController::class, 'destroy'])
            ->name('admin.users.destroy');

        /*
        |--------------------------------------------------------------------------
        | Admin Password
        |--------------------------------------------------------------------------
        */
        Route::patch('change-password', [AdminController::class, 'changePassword'])
            ->name('admin.change-password');
    });

/*
|--------------------------------------------------------------------------
| Profile Routes (Authenticated Users)
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

/*
|--------------------------------------------------------------------------
| Auth & Employee Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
require __DIR__ . '/employee.php';
