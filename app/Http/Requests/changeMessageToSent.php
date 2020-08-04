<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\Controller;

use Illuminate\Validation\Validator;

class changeMessageToSent extends baseFormRequest
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

            Controller::HASH       => 'required',
            Controller::SECRET     => 'required',
            Controller::DEVICE_ID  => 'required|integer', // Not necessary but you can make it required if you want.
            Controller::MESSAGE_ID => 'required|integer',

        ];

    }
}
