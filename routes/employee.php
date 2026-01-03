<?php

use App\Http\Controllers\BookingsReportController;
use App\Http\Controllers\Employee\EmployeeBookingController;
use App\Http\Controllers\PropertiesReportController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'role:employee|admin'])
    ->prefix('dashboard')
    ->name('employee.')
    ->group(function () {

        // Employee Dashboard
        Route::view('/dashboard', 'dashboard.employee.dashboard')
            ->name('dashboard');

        // Bookings List
        Route::get('/bookings', [EmployeeBookingController::class, 'index'])
            ->name('bookings.index');

        // Booking Details
        Route::get('/bookings/{id}', [EmployeeBookingController::class, 'show'])
            ->name('bookings.show');

        // Actions
        Route::patch('/bookings/{id}/approve', [EmployeeBookingController::class, 'approve'])
            ->name('bookings.approve');

        Route::patch('/bookings/{id}/cancel', [EmployeeBookingController::class, 'cancel'])
            ->name('bookings.cancel');

        Route::patch('/bookings/{id}/reschedule', [EmployeeBookingController::class, 'reschedule'])
            ->name('bookings.reschedule');

        Route::patch('/bookings/{id}/complete', [EmployeeBookingController::class, 'complete'])
            ->name('bookings.complete');

        Route::patch('/bookings/{id}/reject', [EmployeeBookingController::class, 'reject'])
            ->name('bookings.reject');


    });
