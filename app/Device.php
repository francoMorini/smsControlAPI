<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Message;
use App\Company;

use Exception;
use App\Exceptions\SmsApiException;

class Device extends Model
{
    
    const FIELD_UUID = 'uuid';
    const FIELD_DEVICE_NUMBER  = 'number';
    const FIELD_COMPANY        = 'company_id';
	const FIELD_IS_ACTIVE      = 'is_active';
    const FIELD_LAST_USED_DATE = 'last_used_date';
	const FIELD_MAIL_SENT_BATTERY     = 'battery_mail_sent';
	const FIELD_MAIL_SENT_PLUGGED_OFF = 'plugged_off_mail_sent';
	const FIELD_MAIL_SENT_INACIVITY   = 'inactivity_mail_sent';
    const FIELD_BATTERY_LEVEL = 'battery_level';
    const FIELD_IS_PLUGGED_IN = 'is_plugged_in';
    const FIELD_NETWORK       = 'network_connection';
    const FIELD_LAST_REPORT   = 'last_device_report';
    const FIELD_DEVICE_PLUGGED_OFF = 'device_plugged_off';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $attributes = [

    	self::FIELD_IS_ACTIVE             => false,
    	self::FIELD_MAIL_SENT_BATTERY     => false,
    	self::FIELD_MAIL_SENT_PLUGGED_OFF => false,
    	self::FIELD_MAIL_SENT_INACIVITY   => false


    ];

    public function __construct () {}

    /**/
    public function getOneEmittingDevice ( $deviceId, $company, $excludedCompany ) 
    {

    	try {

    		$message = new Message();
            $company = new Company();

            $FIELD_DEVICE_IS_ACTIVE          = self::FIELD_IS_ACTIVE;
            $FIELD_DEVICE_LAST_USED_DATE     = self::FIELD_LAST_USED_DATE;
            $FIELD_DEVICE_COMPANY_ID         = self::FIELD_COMPANY;
            $FIELD_COMPANY_LAST_USED_DATE    = $company::FIELD_LAST_USED;
            $FIELD_COMPANY_NAME              = $company::FIELD_NAME;
            $FIELD_COMPANY_IS_ACTIVE         = $company::FIELD_IS_ACTIVE;
            $FIELD_MESSAGE_DEVICE_ID         = $message::FIELD_DEVICE_ID;
            $FIELD_MESSAGE_FROM_US_TO_CLIENT = $message::FIELD_FROM_US_TO_CLIENT;
            $FIELD_MESSAGE_STATUS            = $message::FIELD_STATUS;

    		$sql = "SELECT {$this->getKeyName()} FROM (
                    
                    SELECT 

                        device.{$this->getKeyName()},
                        company.{$FIELD_COMPANY_NAME},
                        company.{$FIELD_COMPANY_LAST_USED_DATE},
                        device.{$FIELD_DEVICE_LAST_USED_DATE}

                    FROM {$this->getTable()} as device 
                    INNER JOIN {$company->getTable()} as company ON device.{$FIELD_DEVICE_COMPANY_ID} = company.{$company->getKeyName()} 
                    WHERE device.{$FIELD_DEVICE_IS_ACTIVE} = 1 AND company.{$FIELD_COMPANY_IS_ACTIVE} = 1) AS TEMP ";

                $sqlOrder = " ORDER BY ";

                if ( !empty( $company ) ) {

                    $sqlOrder .= " CASE WHEN (TEMP.{$FIELD_COMPANY_NAME} = '{$company}' OR TEMP.{$company->getKeyName()} = '{$company}') THEN 1 ELSE 2 END ASC, ";

                }

                if ( !empty( $excludedCompany ) ) {

                    $sqlOrder .= " CASE WHEN (TEMP.{$FIELD_COMPANY_NAME} = '{$excludedCompany}' OR TEMP.{$company->getKeyName()} = '{$excludedCompany}') THEN 2 ELSE 1 END ASC, ";

                }

                unset($message);
                unset($company);

                $sqlOrder .= " TEMP.{$FIELD_COMPANY_LAST_USED_DATE} ASC, TEMP.{$FIELD_DEVICE_LAST_USED_DATE} ASC";
                $sql      .= $sqlOrder . ';';

                $device = collect( DB::select( $sql ) )->first();

                if ( empty( $device ) ) { throw new SmsApiException( 'No devices available.' ); }

                return $device->{$this->getKeyName()};

    	} catch ( Exception $e ) { throw $e; }

    	return null;

    }

    public function thisDeviceExists ( $uuid, $deviceNumber ) 
    {

        try {

            $device = self::where( self::FIELD_DEVICE_NUMBER, '=', $deviceNumber )->get();

            if ( !empty( $device ) ) { throw new SmsApiException( 'The Device number already exists.' ); }

            $result = DB::table( $this->getTable() )
            ->select( $this->getKeyName() )
            ->where( [

                [ self::FIELD_UUID, '=', $uuid ],
                [ self::FIELD_DEVICE_NUMBER, '=', $deviceNumber ],

            ] )
            ->first();

            if ( empty( $result ) ) {

                return false;

            }

        } catch ( Exception $e ) { throw $e; }

        return true;

    }

}
