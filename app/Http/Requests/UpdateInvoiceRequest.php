<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
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
            'session_ids' => ['required', 'array', 'min:1'],
            'session_ids.*' => ['integer', 'exists:client_sessions,id'],
            'hourly_rate' => ['required', 'numeric', 'min:0'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'payment_status' => ['required', 'in:unpaid,paid,partial'],
            'payment_date' => ['nullable', 'date', 'required_if:payment_status,paid'],
            'issued_at' => ['required', 'date', 'before_or_equal:today'],
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
            'session_ids.required' => 'Please select at least one session.',
            'session_ids.min' => 'Please select at least one session.',
            'session_ids.*.exists' => 'One or more selected sessions do not exist.',
            'hourly_rate.required' => 'The hourly rate is required.',
            'hourly_rate.min' => 'Hourly rate must be greater than or equal to 0.',
            'tax_rate.min' => 'Tax rate must be greater than or equal to 0.',
            'tax_rate.max' => 'Tax rate cannot exceed 100%.',
            'payment_status.required' => 'Please select a payment status.',
            'payment_status.in' => 'Invalid payment status selected.',
            'payment_date.required_if' => 'Payment date is required when status is paid.',
            'issued_at.required' => 'The invoice date is required.',
            'issued_at.before_or_equal' => 'Invoice date cannot be in the future.',
        ];
    }
}
