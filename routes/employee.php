<?php

use App\Http\Controllers\BookingsReportController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Employee\EmployeeBookingController;
use App\Http\Controllers\PropertiesReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Route::middleware(['auth', 'role:admin|employee'])
    ->prefix('dashboard')
    ->name('employee.')
    ->group(function () {



        // Bookings List
        Route::get('/bookings', [EmployeeBookingController::class, 'index'])
            ->name('bookings.index');

Route::middleware(['auth', 'role:admin'])
    ->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard.index');





        // Actions
        // reschedual
        Route::get('/bookings/{booking}/reschedule',
            [EmployeeBookingController::class, 'rescheduleForm']
        )->name('reschedule.form');
          // apprve
        Route::patch('/bookings/{id}/approve', [EmployeeBookingController::class, 'approve'])
            ->name('bookings.approve');
         // cancel
        Route::patch('/bookings/{id}/cancel', [EmployeeBookingController::class, 'cancel'])
            ->name('bookings.cancel');

        Route::patch('/bookings/{id}/reschedule', [EmployeeBookingController::class, 'reschedule'])
            ->name('bookings.reschedule');

        Route::patch('/bookings/{id}/complete', [EmployeeBookingController::class, 'complete'])
            ->name('bookings.complete');

        Route::patch('/bookings/{id}/reject', [EmployeeBookingController::class, 'reject'])
            ->name('bookings.reject');

        // My Bookings
      Route::get('/bookings/my', [EmployeeBookingController::class, 'myBookings'])
    ->name('bookings.my');


        // Pending Bookings
        Route::get('/bookings/pending', [EmployeeBookingController::class, 'pending'])
            ->name('bookings.pending');

             // Booking Details
        Route::get('/bookings/{id}', [EmployeeBookingController::class, 'show'])
            ->name('bookings.show');


    });

