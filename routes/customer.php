<?php

use App\Http\Controllers\Customer\ReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::post('/ratings', [ReviewController::class, 'store'])
        ->name('customer.ratings.store');
});
