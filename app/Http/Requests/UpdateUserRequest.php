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
            'password'=>'required',
            'image_url'=>'required:mimes:jpg,png,jpeg:max:5048',
            'phone'=>[
                'required',
                'regex:/(0)[0-9]{9}/',
                'unique:users'
            ],
            'address'=>'required'
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
            'password.required' => 'Cannot be empty',
            'phone.required'=>'Cannot be empty',
            'phone.regex'=>'Starting with 0....',
            'phone.unique'=>'Phone is existed',
            'address.required'=>'Cannot be empty',
            'image_url.required'=>'Cannot be empty',
            'image_url.mimes'=>'Format jpg,png,jpeg',
        ];
    }
}
