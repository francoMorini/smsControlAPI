<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Exceptions\SmsApiException;
use App\Errors;
use App\Http\Controllers\Controller;
use App\Consumer;

class ConsumersAuth
{

    /*
    |--------------------------------------------------------------------------
    | Hash and secret for testing
    |--------------------------------------------------------------------------
    |
    | Here is the encrypted hash that you can use for the consumers testing
    | with secret keys.
    | 
    | Consumer Hash:   04c524dfc09c7e96853b6f48e154ea69
    | Consumer Secret: s?v.m9H}^Em]ZBu)toGt12b0tf6Cmd
    |
    | Devices Hash:   4951706773b1ddbd451f01ef6a477da4
    | Devices Secret: .Em~@@lN4!TNI,s9pB^x4-~aCKvb-J
    |
    */

    private $hash;
    private $salt = 'W{`A_C+dVvP$s5%]/E"g:]K3?Zm%`K'; // Your API salt for the consumers

    private function setHash ( $value = null ) 
    {
        $this->hash = md5( $this->salt . $value . $this->salt );
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle( $request, Closure $next )
    {

        try {

            $data = $request->only( Controller::HASH, Controller::SECRET );

            if ( !array_key_exists( Controller::HASH, $data ) || empty( $data[ Controller::HASH ] ) || !array_key_exists( Controller::SECRET, $data ) || empty( $data[ Controller::SECRET ] ) ) {

                throw new Exception( 'You need credentials to proceed.' );

            }

            $this->setHash( $data[ Controller::SECRET ] );

            if ( $this->hash != $data[ Controller::HASH ] ) {

                throw new Exception( 'Incorrect credentials.' );

            }

            $consumer = new Consumer();
            $exists   = $consumer->thisConsumerExists( $data[ Controller::SECRET ] );

            if ( !$exists ) { throw new Exception( 'Unauthorized.' ); }

            return $next( $request );

        } catch ( Exception $e ) { throw $e; }

    }
}
