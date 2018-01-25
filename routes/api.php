<?php

use Illuminate\Http\Request;

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\\'], function () {
    Route::resource('gifts', 'UserGiftController');
    Route::get('gift-reservations', 'GiftReservationController@index');

    Route::patch('gift-reservations/{gift}', 'GiftReservationController@patch');

    Route::post('sessions/', 'AuthController@login')->name('auth.login');
    Route::delete('sessions/', 'AuthController@logout')->name('auth.logout');
    Route::patch('sessions/refresh', 'AuthController@refresh')->name('auth.refresh');
    Route::get('sessions/me', 'AuthController@me')->name('auth.me');
});