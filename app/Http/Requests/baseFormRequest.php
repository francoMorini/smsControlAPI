<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

use Exception;
use App\Exceptions\SmsApiException;

abstract class baseFormRequest extends FormRequest
{

    abstract public function authorize();
    abstract public function rules();

    /*
     * Return error message for the client
     *
     */
    protected function withValidator( $validator )
    {
        
        try {

            if ( $validator->fails() ) {

                $errorsToRender = [];

                foreach ( $validator->errors()->getMessages() as $key => $value ) {

                    $errorsToRender[ $key ] = $value[0];

                }

                throw new SmsApiException( json_encode( $errorsToRender ) );

            }

        } catch ( Exception $e ) { throw $e; }

    }

}
