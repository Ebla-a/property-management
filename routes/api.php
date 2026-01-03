<?php

use App\Http\Controllers\Admin\AdminController as AdminAdminController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

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
Route::post('/register', [AdminAuthController::class, 'register']);
Route::post('/login', [AdminAuthController::class, 'login']);

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

    /*
    |--------------------------------------------------------------------------
    | Admin Routes (protected)
    |--------------------------------------------------------------------------
    */



        
    });


require __DIR__ . '/customer.php';


