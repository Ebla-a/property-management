<?php

use App\Http\Controllers\BookingsReportController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Employee\EmployeeBookingController;
use App\Http\Controllers\PropertiesReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Employee\EmployeeDashboardController;

/*
|--------------------------------------------------------------------------
| Employee Routes (fixed)
|--------------------------------------------------------------------------
| - Prefix: /dashboard
| - Name prefix: employee.*
| - Notes:
|   * Use `role:employee` middleware to restrict these routes to employees only.
|   * Use consistent route-model-binding parameter `{booking}` (not `{id}`).
|   * Dashboard index should be `/` (because of the 'dashboard' prefix).
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:employee'])
    ->prefix('dashboard')
    ->name('employee.')
    ->group(function () {

    // Employee home dashboard: GET /dashboard
    Route::get('/', [EmployeeDashboardController::class, 'index'])
        ->name('dashboard');

    // Bookings List: GET /dashboard/bookings
    Route::get('bookings', [EmployeeBookingController::class, 'index'])
        ->name('bookings.index');

    // My Bookings: GET /dashboard/bookings/my
    Route::get('bookings/my', [EmployeeBookingController::class, 'myBookings'])
        ->name('bookings.my');

    // Pending Bookings: GET /dashboard/bookings/pending
    Route::get('bookings/pending', [EmployeeBookingController::class, 'pending'])
        ->name('bookings.pending');

    // Booking Details (use route-model-binding): GET /dashboard/bookings/{booking}
    Route::get('bookings/{booking}', [EmployeeBookingController::class, 'show'])
        ->name('bookings.show');

    // Reschedule form (GET): /dashboard/bookings/{booking}/reschedule
    Route::get('bookings/{booking}/reschedule', [EmployeeBookingController::class, 'rescheduleForm'])
        ->name('reschedule.form');

    // Actions (PATCH) - use {booking} for model binding
    Route::patch('bookings/{booking}/approve', [EmployeeBookingController::class, 'approve'])
        ->name('bookings.approve');

    Route::patch('bookings/{booking}/cancel', [EmployeeBookingController::class, 'cancel'])
        ->name('bookings.cancel');

    Route::patch('bookings/{booking}/reschedule', [EmployeeBookingController::class, 'reschedule'])
        ->name('bookings.reschedule');

    Route::patch('bookings/{booking}/complete', [EmployeeBookingController::class, 'complete'])
        ->name('bookings.complete');

    Route::patch('bookings/{booking}/reject', [EmployeeBookingController::class, 'reject'])
        ->name('bookings.reject');
});
