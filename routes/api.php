<?php

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

Route::group( [ 'middleware' => [ 'consumersAuth', ] ], function () {

	// Here the protected routes. This give you a better control of your API consumers.
	Route::post( '/send', 'SmsController@prepareSmsToSend');

} );

// Sms
Route::post( '/pendingtosend', 'SmsController@getPendingMessagesToSend');

// Devices
Route::post( '/install', 'DeviceController@newDevice');