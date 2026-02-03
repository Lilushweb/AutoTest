<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Car\CarController;
use App\Http\Controllers\Car\CarBookingController;
use App\Http\Controllers\ComfortCategoryController;
use App\Http\Controllers\PositionsController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);

    Route::get('cars/available', [CarController::class, 'availableCars']);
    Route::apiResource('cars', CarController::class)->except(['show']);
    Route::apiResource('carBookings', CarBookingController::class)->except(['show']);
    Route::apiResource('positions', PositionsController::class)->except(['show']);
    Route::apiResource('comfortCategory', ComfortCategoryController::class)->except(['show']);
});
