<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CarRentalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/home', [HomeController::class, 'index']);
Route::get('/cars/details/{id}', [HomeController::class, 'detailCar']);

Route::middleware('auth:api')->group(function () {
    Route::get('/cars', [CarController::class, 'index']);
    Route::post('/cars', [CarController::class, 'store']);
    Route::get('/cars/{id}', [CarController::class, 'show']);
    Route::post('/cars/{id}/update', [CarController::class, 'update']);
    Route::delete('/cars/{id}', [CarController::class, 'destroy']);

    Route::post('/rent-car', [CarRentalController::class, 'rentCar']);
    Route::get('/rental-history', [CarRentalController::class, 'rentalHistory']);
    Route::get('/rental/{id}/available-dates', [CarRentalController::class, 'availableDates']);
    Route::post('/rental/{id}/complete', [CarRentalController::class, 'completeRental']);

    Route::get('/profile', [UserController::class, 'showProfile']);
    Route::post('/profile/update', [UserController::class, 'updateProfile']);
});
