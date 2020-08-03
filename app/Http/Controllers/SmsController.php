<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\prepareSmsToSend;
use App\Http\Requests\getPendingMessagesToSend;
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

			$messageModel = new Message();

			$result = $messageModel->saveSmsForSend(

				$data[ self::MESSAGE ], 
				$data[ self::TO ], 
				$request->post( self::FROM ),    // Optional
				$request->post( self::COMPANY ), // Optional
				$request->post( self::KEEP )     // Optional

			);

			unset( $messageModel );

			return $this->response( $result );

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

			$messageModel = new Message();
			$message      = $messageModel->getPendingMessages( $data[ self::DEVICE_ID ] );

			unset( $messageModel );

			return $this->response( $message );

		} catch ( Exception $e ) { $this->error( $e, $this->controller, '02' ); }

	}

}
