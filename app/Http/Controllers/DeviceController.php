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

    		$deviceModel  = new Device();
    		$exists = $deviceModel->thisDeviceExists( $data[ self::UUID ], $data[ self::DEVICE_NUMBER ] );

    		if( !$exists ) {

    			$deviceModel->{ $deviceModel::FIELD_UUID }          = $data[ self::UUID ];
    			$deviceModel->{ $deviceModel::FIELD_DEVICE_NUMBER } = $data[ self::DEVICE_NUMBER ];
    			$deviceModel->{ $deviceModel::FIELD_COMPANY }       = $data[ self::COMPANY ];
    			$deviceModel->save();

    			$newDeviceID = $deviceModel->id;

    			unset( $deviceModel );

                return $this->response( $newDeviceID );

    		}

    		return $this->response( 'Ya existe' );

    	} catch ( Exception $e ) { $this->error( $e, $this->controller, '01' ); }

    }

}
