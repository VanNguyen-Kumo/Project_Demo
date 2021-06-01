<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckOutRequest extends FormRequest
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
            'total_price'=>'required',
            'delivery_address'=>'required',
            'phone'=>[
                'required',
                'regex:/(0)[0-9]{9}/',
            ],

        ];
    }
    public function messages()
    {
        return[
            'delivery_address.required'=>'Can not be empty',
            'delivery_date.required'=>'Can not be empty',
            'total_price.required'=>'Can not be empty',
            'phone.required'=>'Can not be empty',
            'phone.regex'=>'Starting with 0....',
        ];
    }
}
