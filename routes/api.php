<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Visitor\PropertyController as VisitorPropertyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
|
| - register/login (public)
| - properties index & show (public for visitors)
|
*/

// Auth (public)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'check.active'])->group(function () {

    Route::middleware(['role:admin'])->get('/dashboard', function () {
        return response()->json([
            'message' => 'Welcome Admin to Dashboard',
        ]);
    });
});

// Public Property endpoints (visitor – no auth)

Route::get('/properties', [VisitorPropertyController::class, 'index']);
Route::get('/properties/{property}', [VisitorPropertyController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
|
| Protected by sanctum; admin-only routes kept as they were.
|
*/
Route::middleware('auth:sanctum')->group(function () {

    // current logged user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Add a new review / rating
    Route::post('/reviews', [ReviewController::class, 'store']);
});

require __DIR__.'/customer.php';
