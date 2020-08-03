<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Exception;

class Consumer extends Model
{
    
	const FIELD_NAME            = 'name';
	const FIELD_SECRET_KEY      = 'secret_key';
	const FIELD_USAGE           = 'api_usage';
	const FIELD_LAST_USAGE_DATE = 'last_api_usage';

	public function __construct () {}

	public function thisConsumerExists ( $secret_key = null ) 
	{

		try {

			$consumer = DB::table( $this->getTable() )
			->select( self::FIELD_NAME )
			->where( self::FIELD_SECRET_KEY, '=', $secret_key )
			->first();

			if( empty( $consumer->{ self::FIELD_NAME } ) ) { return false; }

			return true;

		} catch ( Exception $e ) { throw $e; }

		return false;

	}

}
