<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePassRequest extends FormRequest
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
            'password_current'=>'required:users',
            'password' => 'required',
            'confirm_password' => 'required:same:password',
        ];
    }

    public function messages()
    {
        return [
            'password_current'=>'Can not be empty',
            'password.required' => 'Can not be empty',
            'confirm_password.same' => 'Confirm password not match password',
            'confirm_password' => 'Can not be empty'
        ];
    }
}
