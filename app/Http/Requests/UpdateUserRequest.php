<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'first_name'=>'required',
            'last_name'=>'required',
            'display_name'=>'required:unique:users',
            'email_address'=>'required:unique:users:email',


        ];
    }
    public function messages()
    {
        return [
            'first_name.required'=>'Cannot be empty',
            'last_name.required'=>'Cannot be empty',
            'display_name.required'=>'Cannot be empty',
            'display_name.unique'=>'Display Name is existed',
            'email_address.required' => 'Cannot be empty',
            'email_address.unique'=>'Email is existed',

        ];
    }
}
