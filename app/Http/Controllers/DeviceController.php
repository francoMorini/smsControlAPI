<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\newDevice;
use App\Device;

use Exception;

class DeviceController extends Controller
{

    private $controller = '02';

    public function __construct () {}

    /**/
    public function newDevice ( newDevice $request ) 
    {

    	try {

    		$data = $request->only( self::UUID, self::DEVICE_NUMBER, self::COMPANY );

    		$device = new Device();
    		$exists = $device->thisDeviceExists( $data[ self::UUID ], $data[ self::DEVICE_NUMBER ] );

    		if( !$exists ) {

    			$device->{ $device::FIELD_UUID }          = $data[ self::UUID ];
    			$device->{ $device::FIELD_DEVICE_NUMBER } = $data[ self::DEVICE_NUMBER ];
    			$device->{ $device::FIELD_COMPANY }       = $data[ self::COMPANY ];
    			$device->save();

    			$newDeviceID = $device->id;

    			unset( $device );

                return $this->response( $newDeviceID );

    		}

    		return $this->response( 'Ya existe' );

    	} catch ( Exception $e ) { $this->error( $e, $this->controller, '01' ); }

    }

}
