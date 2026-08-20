<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SearchOrdersRequest extends FormRequest
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
            'lot' => ['required', 'string', 'max:100'], 
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }

    public function messages(): array
    {
        return [
            'lot.required' => 'The lot field is required.',
            'lot.string' => 'The lot must be a string.',
            'lot.max' => 'The lot may not be greater than 100 characters.',
            'start_date.date' => 'The start date must be a valid date.',
            'end_date.date' => 'The end date must be a valid date.',
            'end_date.after_or_equal' => 'The end date must be a date after or equal to the start date.',
        ];
    }

    // This method returns the date range for the search, defaulting to the last 30 days if no dates are provided.
    public function dateRange(): array
    {
        return [
            'start' => $this->input('start_date')
                ? \Carbon\Carbon::parse($this->input('start_date'))->startOfDay()
                : now()->subDays(30)->startOfDay(),
            'end' => $this->input('end_date')
                ? \Carbon\Carbon::parse($this->input('end_date'))->endOfDay()
                : now()->endOfDay(),
        ];
    }
}
