<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use function Symfony\Component\String\u;
use App\Enums\OrderStatusType;
use BenSampo\Enum\Rules\EnumValue;
class UpdateOrderAdminRequest extends FormRequest
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
            'status' => ['required', new EnumValue(OrderStatusType::class)],
        ];
    }
    public function messages()
    {
        return [
            'status.required' => 'can not be empty',
        ];
    }

}