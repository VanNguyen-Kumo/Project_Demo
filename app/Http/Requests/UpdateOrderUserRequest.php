<?php

namespace App\Http\Requests;

use App\Enums\OrderStatusType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderUserRequest extends FormRequest
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
            'status_id' => ['required', new EnumValue(OrderStatusType::class)],
        ];
    }
    public function messages()
    {
        return [
            'status_id.required' => 'can not be empty',
        ];
    }
}
