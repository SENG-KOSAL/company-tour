<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersContoller;

use App\Http\Controllers\TourContoller;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
///
Route::post('/users', [UsersContoller::class, 'createUser']);
// Route::get('/users', [UsersContoller::class, 'getUsers']); // Add this


Route::post('/tours', [TourContoller::class, 'store']);
Route::get('/tours', [TourContoller::class, 'getTours']);
Route::get('/tours/{id}', [TourContoller::class, 'getTourById']);
Route::get('/tours4', [TourContoller::class, 'getTours4']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    // Tour Management crud opeations
    Route::get('/admin/tours', [AdminController::class, 'getAllTours']);
    Route::post('/admin/tours', [AdminController::class, 'CreateTours']);
    Route::put('/admin/tours/{id}', [AdminController::class, 'updateTour']);
    Route::delete('/admin/tours/{id}', [AdminController::class, 'deleteTour']);

    // Booking Management
    // Route::get('/admin/bookings', [AdminController::class, 'getAllBookings']);
    // Route::post('/admin/bookings/{id}/confirm', [AdminController::class, 'confirmBooking']);
    // Route::post('/admin/bookings/{id}/cancel', [AdminController::class, 'cancelBooking']);

    // User Management
    Route::get('/admin/users', [AdminController::class, 'getAllUsers']);
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser']);
});


//booking contoller
Route::get('/bookings', [BookingController::class, 'getBookings']); // ⚠️ Now public

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UsersContoller::class, 'getUsers']); // Add this
    Route::post('/bookings', [BookingController::class, 'createBooking']);
    // Route::get('/bookings', [BookingController::class, 'getBookings']);
    Route::get('/bookings/{id}', [BookingController::class, 'getBookingById']);
    Route::put('/bookings/{id}', [BookingController::class, 'updateBooking']);
    Route::delete('/bookings/{id}/cancel', [BookingController::class, 'cancelBooking']);
    // Cancel booking (if you create a specific method)
    // Route::put('/bookings/{id}/cancel', [BookingController::class, 'cancel']);
});

//admin route
Route::post('/register-admin', [UsersContoller::class, 'registerAdmin']);

//Login
Route::post('/login', [UsersContoller::class, 'login']);

// 1|nCQA57ZuClXLCRTyazkIoZ7GxUMTXHWAiDpiv7HJ4fd88ee8