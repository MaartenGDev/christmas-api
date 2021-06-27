<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GiftReservationController;
use App\Http\Controllers\Api\UserGiftController;
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

Route::group(['prefix' => 'v1', 'as' => 'api.'], function () {
    Route::resource('gifts', UserGiftController::class);

    Route::get('gift-reservations', [GiftReservationController::class, 'index']);

    Route::patch('gift-reservations/{gift}', [GiftReservationController::class, 'patch']);

    Route::post('sessions/', [AuthController::class, 'login'])->name('auth.login');
    Route::delete('sessions/', [AuthController::class, 'logout'])->name('auth.logout');
    Route::patch('sessions/refresh', [AuthController::class, 'refresh'])->name('auth.refresh');
    Route::get('sessions/me', [AuthController::class, 'me'])->name('auth.me');
});
