<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Errors extends Model
{

	// Constants
	const FIELD_RESPONSE_GOOD   = "ok";
	const FIELD_RESPONSE_BAD    = "error";
	const FIELD_CONTROLLER_CODE = 'C';
	const FIELD_ACTION_CODE     = 'A';
	const FIELD_RESPONSE_RETURNED_DATA = "data";
	const FIELD_RESPONSE_INTERNAL_ERROR_CODE = "internal_code";
	const FIELD_RESPONSE_LINE    = 'line';
	const FIELD_RESPONSE_PATH    = 'path';
	const FIELD_RESPONSE_MESSAGE = 'message';

	const ERROR_NOTFOUND   = 404;
	const ERROR_BADREQUEST = 400;
	const ERROR_SERVER     = 500;

	public function __construct () {}

	/*
	 * Functions
	 *
	 */

    public function goodResponse ( $data = [] ) 
    {

        $result = [

            self::FIELD_RESPONSE_GOOD => [ self::FIELD_RESPONSE_RETURNED_DATA => $data ]
            
        ];

        return $result;

    }

    public function badResponse ( $exception = null ) 
    {

        $internalErrorCode = ( !empty( $exception->{ self::FIELD_RESPONSE_INTERNAL_ERROR_CODE } ) ) ? $exception->{ self::FIELD_RESPONSE_INTERNAL_ERROR_CODE } : null;

        $result = [

            self::FIELD_RESPONSE_BAD => [

				self::FIELD_RESPONSE_INTERNAL_ERROR_CODE => $internalErrorCode,
                self::FIELD_RESPONSE_LINE    => $exception->getLine(),
				self::FIELD_RESPONSE_PATH    => $exception->getFile(),
				self::FIELD_RESPONSE_MESSAGE => $exception->getMessage()

            ]

        ];

		return $result;

    }

}
