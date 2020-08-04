<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Device;

use Exception;
use App\Exceptions\SmsApiException;

class Message extends Model
{

	const FIELD_MESSAGE           = 'message';
	const FIELD_INTERNAL_SMS_ID   = 'internal_sms_id';
	const FIELD_CLIENT_NUMBER     = 'client_number';
	const FIELD_DEVICE_ID         = 'device_id';
	const FIELD_FROM_US_TO_CLIENT = 'from_us_to_client';
	const FIELD_STATUS            = 'status';
	const FIELD_SEND_DATE         = 'send_date';

	public function __construct () {}

	/**/
	protected function getLastClientUsedDevice ( $clientNumber = null ) 
	{

		try {

			if ( empty( $clientNumber ) ) { return null; }

			$result = DB::table( $this->getTable() )
			->select( self::FIELD_DEVICE_ID )
			->where( self::FIELD_CLIENT_NUMBER, '=', $clientNumber )
			->orderByDesc( self::FIELD_SEND_DATE )
			->first();

			if ( empty( $result ) ) { return null; }

			$lastDevice = $result->{ self::FIELD_DEVICE_ID };

			return $lastDevice;

		} catch ( Exception $e ) { throw $e; }

		return null;

	}

	/**/
	protected function getLastClientUsedCompany ( $clientNumber = null ) 
	{

		try {

			if ( empty( $clientNumber ) ) { return null; }

			$device = new Device();

			$result = DB::table( $this->getTable() . ' AS messages' )
			->select( $device::FIELD_COMPANY )
			->join( 
				$device->getTable() . ' AS devices', 
				'messages.' . self::FIELD_DEVICE_ID, 
				'=', 
				'devices.' . $device->getKeyName() 
			)
			->where( 'messages.' . self::FIELD_CLIENT_NUMBER, '=', $clientNumber )
			->where( self::FIELD_SEND_DATE, '>=', DB::raw( 'DATE_SUB(NOW(), INTERVAL 1 DAY)' ) )
			->orderByDesc( self::FIELD_SEND_DATE )
			->first();
			
			unset( $device );

			if ( empty( $result ) ) { 
				return null; 
			}

			return $result->{ Device::FIELD_COMPANY };

		} catch ( Exception $e ) { throw $e; }

		return null;

	} 

    /**/
	public function saveSmsForSend ( $messageToSend, $clientNumber, $emittingDevice = null, $company = null, $keep = false ) 
	{

		try {

			$excludedCompany = null;

			if ( $keep ) {

				$emittingDevice = $this->getLastClientUsedDevice( $clientNumber );

			} else {

				$excludedCompany = ( empty( $company ) ) ? $this->getLastClientUsedCompany( $clientNumber ) : $company;

			}

			$device    = new Device();
			$emittingDevice = ( empty( $emittingDevice ) ) ? $device->getOneEmittingDevice( $emittingDevice, $company, $excludedCompany ) : $emittingDevice;

			DB::beginTransaction();

			$this->{self::FIELD_MESSAGE}           = $messageToSend;
			$this->{self::FIELD_CLIENT_NUMBER}     = $clientNumber;
			$this->{self::FIELD_DEVICE_ID}         = $emittingDevice;
			$this->{self::FIELD_FROM_US_TO_CLIENT} = 1;
			$this->{self::FIELD_STATUS}            = 0;
			$this->{self::FIELD_SEND_DATE}         = date('Y-m-d H:i:s');

			$this->save();

			DB::commit();

			return true;

		} catch ( Exception $e ) { 

			DB::rollBack();
			throw $e; 

		}

		return false;

	}

	/**/
	public function getPendingMessages ( $device_id ) 
	{

		try {

			$pendingMessage = DB::table( $this->getTable() )
			->select( $this->getKeyName(), self::FIELD_MESSAGE, self::FIELD_CLIENT_NUMBER )
			->where( [

				[ self::FIELD_DEVICE_ID, '=', $device_id ],
				[ self::FIELD_FROM_US_TO_CLIENT, '=', 1 ],
				[ self::FIELD_STATUS, '=', 0 ],
				[ self::FIELD_SEND_DATE, '<=', date('Y-m-d H:i:s') ],

			] )
			->first();

			if ( empty( $pendingMessage ) ) { return null; }

			// Now we change the message status to "taken", 
			// to prevent it from being taken again.

			$message = self::find( $pendingMessage->{ $this->getKeyName() } );
			$message->{ self::FIELD_STATUS } = 2;
			$message->save();

			return $pendingMessage;

		} catch ( Exception $e ) { throw $e; }

		return null;

	}

	/**/
	public function changeMessageToSent ( $message_id, $device_id = null ) 
	{

		try {

			DB::beginTransaction();

			$message = self::find( $message_id );

			if ( empty( $message ) ) {
				throw new SmsApiException( 'The informed Message does not exists.' );
			}

			$message->{ self::FIELD_STATUS } = 1;
			$message->save();

			// If the device_id is a required field into Requests form.

			if ( !empty( $device_id ) && $message->{ self::FIELD_DEVICE_ID } != $device_id ) {
				throw new SmsApiException( 'The informed Device does not match with the message emitter Device.' );
			}

			// We change the last used date of the emitter device.

			$usedDevice = Device::find( $message->{ self::FIELD_DEVICE_ID } );

			$usedDevice->{ Device::FIELD_LAST_USED_DATE } = date( 'Y-m-d H:i:s' );
			$usedDevice->save();

			// If nothing wrong happend
			
			DB::commit();
			return true;

		} catch ( Exception $e ) { 

			DB::rollBack();
			throw $e; 

		}

		return false;

	}

}
