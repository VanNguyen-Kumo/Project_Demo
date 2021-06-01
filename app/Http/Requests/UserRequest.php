<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'email_address'=>'required:unique:users',
            'password'=>'required',
        ];
    }
    public function messages()
    {
        return [
            'email_address.required' => 'Cannot be empty',
            'email_address.unique'=>'Email is existed',
            'password.required' => 'Cannot be empty',
        ];
    }
}
