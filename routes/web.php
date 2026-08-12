<?php
use App\Http\Controllers\CarPageController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/xe/{slug}', [CarPageController::class, 'show'])->name('cars.show');