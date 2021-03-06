<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Validator;

use Exception;

class prepareSmsToSend extends baseFormRequest
{
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [

            Controller::HASH    => 'required',
            Controller::SECRET  => 'required',
            Controller::TO      => 'required',
            Controller::MESSAGE => 'required'

        ];

    }

}
