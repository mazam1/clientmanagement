<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization handled by middleware
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'session_date' => ['required', 'date', 'before_or_equal:today'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:1440'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'client_id.required' => 'Please select a client.',
            'client_id.exists' => 'The selected client does not exist.',
            'session_date.required' => 'The session date is required.',
            'session_date.date' => 'Please provide a valid date.',
            'session_date.before_or_equal' => 'Session date cannot be in the future.',
            'duration_minutes.required' => 'The session duration is required.',
            'duration_minutes.integer' => 'Duration must be a number.',
            'duration_minutes.min' => 'Duration must be at least 1 minute.',
            'duration_minutes.max' => 'Duration cannot exceed 24 hours (1440 minutes).',
            'notes.max' => 'Notes cannot exceed 1000 characters.',
        ];
    }
}
