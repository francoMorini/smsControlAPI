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

            $consumerModel = new Consumer();
            $exists        = $consumerModel->thisConsumerExists( $data[ Controller::SECRET ] );

            if ( !$exists ) { throw new Exception( 'Unauthorized.' ); }

            return $next( $request );

        } catch ( Exception $e ) { throw $e; }

    }
}
