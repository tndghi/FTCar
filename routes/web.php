<?php
use App\Http\Controllers\CarPageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\CarCategoryController as AdminCarCategoryController;
use App\Http\Controllers\Admin\CarController as AdminCarController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::redirect('/home', '/');

Route::get('/xe/{slug}', [CarPageController::class, 'show'])->name('cars.show');

Route::get('/admin/login', [AuthController::class, 'create'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'store'])->middleware('throttle:6,1')->name('admin.login.store');
Route::post('/admin/logout', [AuthController::class, 'destroy'])->name('admin.logout');

Route::prefix('admin')->name('admin.')->middleware(AdminMiddleware::class)->group(function () {
    Route::redirect('/', '/admin/cars')->name('dashboard');
    Route::resource('cars', AdminCarController::class)->except('show');
    Route::resource('categories', AdminCarCategoryController::class)->except('show');
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::patch('/bookings/{booking}', [AdminBookingController::class, 'update'])->name('bookings.update');
});