<?php

use Illuminate\Support\Facades\Route;
use App\Services\RatingService;

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::post('/ratings', [RatingService::class, 'store'])
        ->name('customer.ratings.store');
});
