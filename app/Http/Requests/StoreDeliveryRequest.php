<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeliveryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check(); // Only authenticated users can create deliveries
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Basic Information
            'delivery_type' => 'required|in:file,credentials,license',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|in:active,expired,revoked',
            
            // File Delivery
            'file' => 'required_if:delivery_type,file|file|max:102400', // 100MB max
            'download_limit' => 'nullable|integer|min:1|max:999',
            
            // Credentials Delivery
            'username' => 'required_if:delivery_type,credentials|string|max:255',
            'password' => 'required_if:delivery_type,credentials|string|max:255',
            'additional_info' => 'nullable|string|max:1000',
            'view_limit' => 'nullable|integer|min:1|max:999',
            
            // License Delivery
            'license_key' => 'required_if:delivery_type,license|string|max:255',
            'license_instructions' => 'nullable|string|max:1000',
            
            // Access Control
            'expires_at' => 'nullable|date|after:now',
            'allowed_ips' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'delivery_type' => __('admin.deliveries.delivery_type'),
            'title' => __('admin.deliveries.title'),
            'description' => __('admin.deliveries.description'),
            'file' => __('admin.deliveries.upload_file'),
            'username' => __('admin.deliveries.username'),
            'password' => __('admin.deliveries.password'),
            'license_key' => __('admin.deliveries.license_key'),
            'expires_at' => __('admin.deliveries.expiry_date'),
            'download_limit' => __('admin.deliveries.download_limit'),
            'view_limit' => __('admin.deliveries.view_limit'),
            'allowed_ips' => __('admin.deliveries.ip_restrictions'),
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'delivery_type.required' => __('admin.deliveries.select_delivery_type'),
            'file.required_if' => __('admin.deliveries.file_required_for_file_type'),
            'username.required_if' => __('admin.deliveries.username_required_for_credentials'),
            'password.required_if' => __('admin.deliveries.password_required_for_credentials'),
            'license_key.required_if' => __('admin.deliveries.license_key_required_for_license'),
        ];
    }
}
