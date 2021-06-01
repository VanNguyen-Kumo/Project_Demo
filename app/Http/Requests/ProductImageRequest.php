<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductImageRequest extends FormRequest
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
            'image_url'=>'mimes:jpg,png,jpeg:max:5048',
            'product_id'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'product_id.required'=>'Cannot be empty',
            'image_url.mimes'=>'Format jpg,png,jpeg',
        ];
    }

}
