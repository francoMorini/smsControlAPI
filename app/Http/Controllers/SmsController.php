<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\prepareSmsToSend;
use App\Http\Requests\getPendingMessagesToSend;
use App\Http\Requests\changeMessageToSent;
use App\Message;

use Exception;

class SmsController extends Controller
{
   
	private $controller = '01';

	public function __construct () {}

    /*
	 * Prepare the SMS to send
	 *
	 * Prepare all the information for your devices in the DB until it is sent.
	 *
     */
	public function prepareSmsToSend ( prepareSmsToSend $request ) 
	{

		try {

			$data = $request->only( self::TO, self::MESSAGE );

			$message = new Message();

			$result = $message->saveSmsForSend(

				$data[ self::MESSAGE ], 
				$data[ self::TO ], 
				$request->post( self::FROM ),    // Optional
				$request->post( self::COMPANY ), // Optional
				$request->post( self::KEEP )     // Optional

			);

			unset( $message );

			return $this->response( 'The message was successfully saved.' );

		} catch ( Exception $e ) { $this->error( $e, $this->controller, '01' ); }

	}

	/*
	 *
	 */
	public function getPendingMessagesToSend ( getPendingMessagesToSend $request ) 
	{

		try {

			$data = $request->only( self::DEVICE_ID );

			// Actualizar salud del dispositivo (TODO)

			$message        = new Message();
			$pendingMessage = $message->getPendingMessages( $data[ self::DEVICE_ID ] );

			unset( $message );

			return $this->response( $pendingMessage );

		} catch ( Exception $e ) { $this->error( $e, $this->controller, '02' ); }

	}

	/**/
	public function changeMessageToSent ( changeMessageToSent $request ) 
	{

		try {

			$data = $request->only( self::DEVICE_ID, self::MESSAGE_ID );

			$message = new Message();
			$result  = $message->changeMessageToSent( $data[ self::MESSAGE_ID ], $request->{ self::DEVICE_ID } );

			if ( !$result ) { throw new Exception( 'Something went wrong.' ); }

			return $this->response( 'The status was successfully changed.' );

		} catch ( Exception $e ) { $this->error( $e, $this->controller, '03' ); }

	}

}
