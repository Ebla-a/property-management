<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\PropertyImageController;
use App\Http\Controllers\Admin\Reports\BookingsReportController;
use App\Http\Controllers\Admin\Reports\PropertiesReportController;
use App\Http\Controllers\Employee\BookingMessageController;
use App\Http\Controllers\Employee\EmployeeDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/dashboard/notifications/read', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return back();
})->name('notifications.read');

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->prefix('chat')->group(function () {
    Route::get('/bookings/{booking}/messages', [BookingMessageController::class, 'index']);
    Route::post('/bookings/{booking}/messages', [BookingMessageController::class, 'store']);
});

Route::view('/team', 'team')->name('team.index');

// Change Language
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'en'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
});

/*
|--------------------------------------------------------------------------
| Dashboard Routes (Admin & Employee)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.active', 'role:admin|employee'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {

        // Unified Dashboard Index logic
        Route::get('/', function () {
            $user = auth()->user();

            if ($user->hasRole('admin')) {
                return (new AdminDashboardController)->index(request());
            }

            if ($user->hasRole('employee')) {
                return (new EmployeeDashboardController)->index(request());
            }

            abort(403, 'Unauthorized');
        })->name('index');

        /*
        |--------------------------------------------------------------------------
        | Admin Specific Routes
        |--------------------------------------------------------------------------
        */
        Route::middleware(['role:admin'])->group(function () {
            
            Route::resource('amenities', AmenityController::class)->except(['show']);
            Route::resource('properties', PropertyController::class);

            Route::get('properties/types', [PropertyController::class, 'types'])->name('properties.types');

            // Property Images Management
            Route::prefix('properties/{property}/images')->name('properties.images.')->group(function () {
                Route::get('/', [PropertyImageController::class, 'index'])->name('index');
                Route::post('/', [PropertyImageController::class, 'store'])->name('store');
                Route::patch('{image}/main', [PropertyImageController::class, 'setMain'])->name('setMain');
                Route::delete('{image}', [PropertyImageController::class, 'destroy'])->name('destroy');
                Route::delete('{image}/force', [PropertyImageController::class, 'forceDestroy'])->name('forceDestroy');
                Route::get('trashed', [PropertyImageController::class, 'trashed'])->name('trashed');
                Route::patch('{image}/restore', [PropertyImageController::class, 'restore'])->name('restore');
            });

            // Reports
            Route::view('reports', 'dashboard.reports.index')->name('reports.index');
            Route::get('reports/properties', [PropertiesReportController::class, 'index'])->name('reports.properties');
            Route::get('reports/bookings', [BookingsReportController::class, 'index'])->name('reports.bookings');
            Route::get('reports/bookings/export', [BookingsReportController::class, 'export'])->name('reports.bookings.export');
            Route::get('reports/properties/export', [PropertiesReportController::class, 'export'])->name('reports.properties.export');

            // Users & Employees Management
            Route::get('users', [AdminController::class, 'index'])->name('admin.employees.index');
            Route::get('employees/create', [AdminController::class, 'create'])->name('admin.employees.create');
            Route::post('employees', [AdminController::class, 'store'])->name('admin.employees.store');
            Route::get('users/{id}/role', [AdminController::class, 'editRole'])->name('admin.users.edit-role');
            Route::patch('users/{id}/role', [AdminController::class, 'changeRole'])->name('admin.users.change-role');
            Route::get('users/{userId}/status', [AdminController::class, 'editAccount'])->name('admin.users.edit-status');
            Route::patch('users/{userId}/status', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle-status');
            Route::delete('users/{userId}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
            Route::patch('change-password', [AdminController::class, 'changePassword'])->name('admin.change-password');
        });

        /*
        |--------------------------------------------------------------------------
        | Profile Routes
        |--------------------------------------------------------------------------
        */
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    });

/*
|--------------------------------------------------------------------------
| Inclusion of Additional Route Files
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
require __DIR__ . '/employee.php';