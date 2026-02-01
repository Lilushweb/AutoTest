<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Car\CarController;
use App\Http\Controllers\ComfortCategoryController;
use App\Http\Controllers\PositionsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('cars', CarController::class)->except(['show']);
Route::apiResource('positions', PositionsController::class)->except(['show']);
Route::apiResource('comfortCategory', ComfortCategoryController::class)->except(['show']);
