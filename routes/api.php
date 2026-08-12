<?php

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/cars', [CarController::class, 'index']);
Route::get('/cars/{slug}', [CarController::class, 'show']);
Route::post('/bookings', [BookingController::class, 'store']); // khách vãng lai vẫn đặt được
 
// Cần đăng nhập (Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::get('/user', fn (Illuminate\Http\Request $request) => $request->user());
});