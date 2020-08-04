<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Errors;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $internalCode; // With this code you can identify the controller and action that cause the error.

    /*
     * CLIENT -> API (Keys)
     */

    const HASH    = 'hash';
    const SECRET  = 'secret';

    const UUID          = 'uuid';
    const DEVICE_NUMBER = 'device_number';

	const MESSAGE        = 'message';
	const MASIVE_MESSAGE = 'masive_message';
	const TO             = 'to';
	const FROM           = 'from';
	const COMPANY        = 'company';
	const KEEP           = 'keep';
	const DEVICE_ID      = 'device_id';
	const LIMIT          = 'limit';
	const MESSAGE_ID     = 'message_id';

	/**/
	public function setInternalErrorCode ( $controller = null, $action = null ) 
	{
		
		if ( empty( $controller ) || empty( $action ) ) {

			$this->internalCode = null;

		}

		$this->internalCode = Errors::FIELD_CONTROLLER_CODE . ( string ) $controller . Errors::FIELD_ACTION_CODE . ( string ) $action;

	}

	/**/
	public function getInternalErrorCode () 
	{
		return $this->internalCode;
	}

	/**/
	public function response ( $data = [] ) 
	{

		$error = new Errors();
		return $error->goodResponse( $data );

	}

	/**/
	public function error ( $exception = null, $controller = null, $action = null ) 
	{

		$this->setInternalErrorCode( $controller, $action );

		$exception->{ Errors::FIELD_RESPONSE_INTERNAL_ERROR_CODE } = $this->getInternalErrorCode();
        throw $exception;

	}

}
