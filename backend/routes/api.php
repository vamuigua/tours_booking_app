<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\V1\Auth;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\DestinationController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\TourController;

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

Route::post('auth/register', Auth\RegisterController::class);
Route::post('auth/login', Auth\LoginController::class);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('auth/logout', Auth\LogoutController::class);
    Route::apiResource('destinations', DestinationController::class);
    Route::apiResource('tours', TourController::class);
    Route::apiResource('bookings', BookingController::class)->except('update', 'destroy');
    Route::apiResource('tickets', TicketController::class)->only('index', 'store');
});
