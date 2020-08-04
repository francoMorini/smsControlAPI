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


/*
|--------------------------------------------------------------------------
| Consumers Routes
|--------------------------------------------------------------------------
|
| In this section the routes are exclusive for consumers who needs to 
| send a message.
|
*/
Route::group( [ 'middleware' => [ 'consumersAuth', ] ], function () {

	// Here the protected routes. This give you a better control of your API consumers.
	Route::post( '/send', 'SmsController@prepareSmsToSend');

} );

/*
|--------------------------------------------------------------------------
| Emmiter Devices Routes
|--------------------------------------------------------------------------
|
| This routes are exclusive for Devices that send and recieve messages.
|
*/
Route::group( [ 'middleware' => [ 'consumersAuth', ] ], function () {

	Route::post( '/pendingtosend', 'SmsController@getPendingMessagesToSend');
	Route::post( '/install', 'DeviceController@newDevice');
	Route::post( '/sent', 'SmsController@changeMessageToSent' );

} );