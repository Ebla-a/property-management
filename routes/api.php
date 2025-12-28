<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::middleware(['auth:sanctum','role:admin'])->group(function () {
    // admin only
    Route::get('/dashboard', [AdminController::class, 'dashboard']);

    Route::post('/add-employee', [AdminController::class, 'addEmployee']);
});


Route::get('/create-token', [AuthController::class, 'createToken']);
