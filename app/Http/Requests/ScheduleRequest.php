<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'day' => ['sometimes', 'string'],
                'start_time' => ['sometimes', 'date_format:g:i A'],
                'end_time' => ['sometimes', 'date_format:g:i A'],
                'is_attending' => ['required', 'boolean'],
            ];
        }
        return [
            'day' => ['required', 'string'],
            'start_time' => ['sometimes', 'date_format:g:i A'],
            'end_time' => ['sometimes', 'date_format:g:i A'],
            'is_attending' => ['required', 'boolean'],
        ];
    }
}
