<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
            'name'=>'required:unique:categories',
            'image_url'=>'required:mimes:jpg,png,jpeg:max:5048',
        ];
    }
    public function messages()
    {
        return [
            'name.required'=>'Cannot be empty',
            'name.unique'=>'Name is existed',
            'image_url.required'=>'Cannot be empty',
            'image_url.mimes'=>'Format jpg,png,jpeg',
        ];
    }
}
