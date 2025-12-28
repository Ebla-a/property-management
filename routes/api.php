<?php

use App\Http\Controllers\Admin\PropertyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::apiResource('properties', PropertyController::class);
});
