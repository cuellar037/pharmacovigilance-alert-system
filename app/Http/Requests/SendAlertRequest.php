<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SendAlertRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'alerts' => ['required', 'array', 'min:1'], 
            'alerts.*.customer_id' => ['required', 'exists:customers,id'],
            'alerts.*.order_id' => ['required', 'exists:orders,id']
        ];
    }
}
