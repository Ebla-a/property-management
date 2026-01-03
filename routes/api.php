<?php

use App\Http\Controllers\Admin\AdminController as AdminAdminController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\Admin\AdminController;
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
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);


Route::middleware(['auth:sanctum', 'check.active'])->group(function () {

    Route::middleware(['role:admin'])->get('/dashboard', function() {
        return response()->json([
            'message' => 'Welcome Admin to Dashboard'
        ]);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware([
    'auth:sanctum',
    'check.active',
    'role:admin'
])->prefix('admin')->group(function () {

    // Create employee
    Route::post('/employees', [AdminController::class, 'store'])
        ->name('admin.employees.store');

    // Change user role
    Route::patch('/users/{id}/role', [AdminController::class, 'changeRole'])
        ->name('admin.users.change-role');

    // Activate / Deactivate user
    Route::patch('/users/{userId}/status', [AdminController::class, 'toggleUserStatus'])
        ->name('admin.users.toggle-status');

    // Change admin password
    Route::patch('/change-password', [AdminController::class, 'changePassword'])
        ->name('admin.change-password');

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

    /*
    |--------------------------------------------------------------------------
    | Admin Routes (protected)
    |--------------------------------------------------------------------------
    */




    });


require __DIR__ . '/customer.php';


