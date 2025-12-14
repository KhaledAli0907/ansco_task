<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
        return [
            'subscription_id' => 'required|uuid|exists:subscriptions,id',
            'amount_cents' => 'required|integer|min:1',
            'currency' => 'required|string|in:EGP,USD,EUR',
            'shipping_data' => 'required|array',
            'shipping_data.first_name' => 'required|string|max:255',
            'shipping_data.last_name' => 'required|string|max:255',
            'shipping_data.email' => 'required|email|max:255',
            'shipping_data.phone_number' => 'required|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'subscription_id.required' => 'The subscription_id field is required.',
            'subscription_id.uuid' => 'The subscription_id must be a valid UUID.',
            'subscription_id.exists' => 'The selected subscription does not exist.',
            'amount_cents.required' => 'The amount_cents field is required.',
            'amount_cents.integer' => 'The amount_cents must be an integer.',
            'amount_cents.min' => 'The amount_cents must be at least 1.',
            'currency.required' => 'The currency field is required.',
            'currency.string' => 'The currency field must be a string.',
            'currency.in' => 'The currency must be one of: EGP, USD, EUR.',
            'shipping_data.required' => 'The shipping_data field is required.',
            'shipping_data.array' => 'The shipping_data must be an array.',
            'shipping_data.first_name.required' => 'The first_name field in shipping_data is required.',
            'shipping_data.first_name.string' => 'The first_name field in shipping_data must be a string.',
            'shipping_data.first_name.max' => 'The first_name field in shipping_data must not exceed 255 characters.',
            'shipping_data.last_name.required' => 'The last_name field in shipping_data is required.',
            'shipping_data.last_name.string' => 'The last_name field in shipping_data must be a string.',
            'shipping_data.last_name.max' => 'The last_name field in shipping_data must not exceed 255 characters.',
            'shipping_data.email.required' => 'The email field in shipping_data is required.',
            'shipping_data.email.email' => 'The email field in shipping_data must be a valid email address.',
            'shipping_data.email.max' => 'The email field in shipping_data must not exceed 255 characters.',
            'shipping_data.phone_number.required' => 'The phone_number field in shipping_data is required.',
            'shipping_data.phone_number.string' => 'The phone_number field in shipping_data must be a string.',
            'shipping_data.phone_number.max' => 'The phone_number field in shipping_data must not exceed 20 characters.',
        ];
    }

    public function attributes(): array
    {
        return [
            'subscription_id' => 'Subscription',
            'amount_cents' => 'Amount (cents)',
            'currency' => 'Currency',
            'shipping_data' => 'Shipping Data',
            'shipping_data.first_name' => 'First Name',
            'shipping_data.last_name' => 'Last Name',
            'shipping_data.email' => 'Email',
            'shipping_data.phone_number' => 'Phone Number',
        ];
    }
}

