<?php

namespace App\Exceptions;

use Exception;
use App\Errors;

class SmsApiException extends Exception
{
    
	private $response;

	/**/
	public function setResponse ( $value = null ) 
	{
		$this->response = $value;
	}

	/**/
	public function getResponse () { return $this->response; }

	public function render ( $request, Exception $exception ) 
	{

		$this->setResponse( Errors::FIELD_RESPONSE_BAD );

		$internalCode = ( !empty( $exception[ Errors::FIELD_RESPONSE_INTERNAL_ERROR_CODE ] ) ) ? $exception[ Errors::FIELD_RESPONSE_INTERNAL_ERROR_CODE ] : null;

		$response = [

			$this->response => [

				Errors::FIELD_RESPONSE_INTERNAL_ERROR_CODE => $internalCode,
				Errors::FIELD_RESPONSE_PATH    => $exception->getFile(),
				Errors::FIELD_RESPONSE_MESSAGE => $exception->getMessage()

			]

		];

		return response( $response );

	}

}
