<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\PropertyImageController;

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
Route::post('/logout', [AuthController::class, 'logout']);


Route::middleware(['auth:sanctum', 'check.active'])->group(function () {

    Route::middleware(['role:admin'])->get('/dashboard', function() {
        return response()->json([
            'message' => 'Welcome Admin to Dashboard'
        ]);
    });
});


// Public Property endpoints (visitor – no auth)

    Route::get('/properties', [PropertyController::class, 'index']);
    Route::get('/properties/{property}', [PropertyController::class, 'show']);


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
    });


require __DIR__ . '/customer.php';


