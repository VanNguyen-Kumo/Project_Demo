<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name'=>'required:unique:products',
            'price'=>'required:numeric',
            'quantity'=>'required:numeric',
            'category_id'=>'required',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Cannot be empty',
            'name.unique' => 'Name is existed',
            'price.required' => 'Cannot be empty',
            'price.numeric' => 'Format is numeric',
            'quantity.required' => 'Cannot be empty',
            'quantity.numeric' => 'Format is numeric',
            'category_id.required'=>'Can not be empty'
        ];
    }
}
