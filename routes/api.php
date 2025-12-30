use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PropertyImageController;

Route::prefix('admin')->group(function () {
    Route::post('/properties/{property}/images', [PropertyImageController::class, 'store']);
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\PropertyController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // current logged user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->prefix('admin')->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::post('/add-employee', [AdminController::class, 'addEmployee']);

        // Property CRUD
        Route::apiResource('properties', PropertyController::class);
    });
});
