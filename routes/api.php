<?php

use Illuminate\Http\Request;

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

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\\'], function () {
    Route::resource('gifts', 'GiftController');

    Route::post('sessions/', 'AuthController@login')->name('auth.login');
    Route::delete('sessions/', 'AuthController@logout')->name('auth.logout');
    Route::patch('sessions/refresh', 'AuthController@refresh')->name('auth.refresh');
    Route::post('me', 'AuthController@me')->name('auth.me');
});