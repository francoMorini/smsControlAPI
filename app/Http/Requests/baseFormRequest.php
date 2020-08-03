<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

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

                dd('Test');

                throw new Exception( $validator->errors()->getMessages() );

            }

        } catch ( Exception $e ) { throw $e; }

    }

}
