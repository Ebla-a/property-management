<?php

use App\Http\Controllers\Employee\EmployeeBookingController;
use App\Http\Controllers\Employee\EmployeeDashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin|employee'])
    ->prefix('dashboard')
    ->name('employee.')
    ->group(function () {

        Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])
            ->name('dashboard.employee');

        // Bookings List employee
        Route::get('/my-own-bookings', [EmployeeBookingController::class, 'myBookings'])
            ->name('bookings.my');

        // Bookings List admin
        Route::get('/bookings', [EmployeeBookingController::class, 'index'])
            ->name('bookings.index');
        // Pending Bookings
        Route::get('/bookings/pending', [EmployeeBookingController::class, 'pending'])
            ->name('bookings.pending');

        Route::get('/bookings/{booking}', [EmployeeBookingController::class, 'show'])->name('bookings.show');

        // Actions
        // reschedual
        Route::get('/bookings/{booking}/reschedule',
            [EmployeeBookingController::class, 'rescheduleForm']
        )->name('reschedule.form');
        // apprve
        Route::patch('/bookings/{booking}/approve', [EmployeeBookingController::class, 'approve'])
            ->name('bookings.approve');
        // cancel
        Route::patch('/bookings/{booking}/cancel', [EmployeeBookingController::class, 'cancel'])
            ->name('bookings.cancel');

        Route::patch('/bookings/{booking}/reschedule', [EmployeeBookingController::class, 'reschedule'])
            ->name('bookings.reschedule');

        Route::patch('/bookings/{booking}/complete', [EmployeeBookingController::class, 'complete'])
            ->name('bookings.complete');

        Route::patch('/bookings/{booking}/reject', [EmployeeBookingController::class, 'reject'])
            ->name('bookings.reject');

        // Booking Details
        Route::get('/bookings/{booking}', [EmployeeBookingController::class, 'show'])
            ->name('bookings.show');

    });
