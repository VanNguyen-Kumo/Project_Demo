<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeAddressRequest extends FormRequest
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
            'phone' => [
                'required',
                'regex:/(0)[0-9]{9}/',
                'unique:users'
            ],
            'address' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'Cannot be empty',
            'phone.regex' => 'Starting with 0....',
            'phone.unique' => 'Phone is existed',
            'address.required' => 'Cannot be empty',
        ];
    }
}
