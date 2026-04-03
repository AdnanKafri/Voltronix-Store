<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check(); // Require authentication for checkout
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:bank_transfer,crypto_usdt,crypto_btc,mtn_cash,syriatel_cash',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:5120', // 5MB max
            'payment_details' => 'nullable|json',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'customer_name' => __('app.checkout.customer_name'),
            'customer_email' => __('app.checkout.customer_email'),
            'customer_phone' => __('app.checkout.customer_phone'),
            'notes' => __('app.checkout.notes'),
            'payment_method' => __('app.checkout.payment_method'),
            'payment_proof' => __('app.checkout.payment_proof'),
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'payment_proof.required' => __('app.checkout.payment_proof_required'),
            'payment_proof.mimes' => __('app.checkout.payment_proof_format'),
            'payment_proof.max' => __('app.checkout.payment_proof_size'),
            'payment_method.required' => __('app.checkout.payment_method_required'),
            'payment_method.in' => __('app.checkout.payment_method_invalid'),
        ];
    }
}
