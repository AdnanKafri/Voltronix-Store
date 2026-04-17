<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check(); // Only authenticated users can create products
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string|max:2000',
            'description_ar' => 'nullable|string|max:2000',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'price' => 'required|numeric|min:0|max:99999.99',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'status' => 'required|in:available,unavailable',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'features_ar' => 'nullable|array',
            'sort_order' => 'integer|min:0',
            
            // Advanced Media System
            'gallery_images' => 'nullable|array|max:10',
            // Media files - restricted to safe formats only
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'before_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'after_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_file' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:51200', // 50MB
            'video_poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_title' => 'nullable|string|max:255',
            'video_description' => 'nullable|string|max:500',
            
            'youtube_url' => 'nullable|url',
            'youtube_title' => 'nullable|string|max:255',
            
            // Delivery Automation
            'auto_delivery_enabled' => 'boolean',
            'default_expiration_days' => 'nullable|integer|min:1|max:365',
            'default_max_downloads' => 'nullable|integer|min:1',
            'default_max_views' => 'nullable|integer|min:1',
        ];

        // Add conditional delivery validation rules
        $autoDeliveryEnabled = $this->boolean('auto_delivery_enabled');

        if ($autoDeliveryEnabled) {
            // Auto delivery enabled - only file delivery is supported
            $rules['delivery_type'] = 'nullable|in:file'; // Will be auto-set to 'file' in controller
            $rules['delivery_file'] = 'required|file|max:102400'; // 100MB max - required for auto delivery
            
            // Other delivery fields are not used for auto delivery
            $rules['default_username'] = 'nullable|string|max:255';
            $rules['default_password'] = 'nullable|string|max:255';
            $rules['default_license_key'] = 'nullable|string|max:255';
        } else {
            // Auto delivery disabled - all delivery fields are nullable
            $rules['delivery_type'] = 'nullable|in:manual,file,credentials,license';
            $rules['delivery_file'] = 'nullable|file|max:102400';
            $rules['default_username'] = 'nullable|string|max:255';
            $rules['default_password'] = 'nullable|string|max:255';
            $rules['default_license_key'] = 'nullable|string|max:255';
        }

        // Always add these as nullable since they're optional
        $rules['credential_notes'] = 'nullable|string|max:1000';
        $rules['license_instructions'] = 'nullable|string|max:1000';

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'category_id' => __('admin.product.category'),
            'name_en' => __('admin.product.name_en'),
            'name_ar' => __('admin.product.name_ar'),
            'description_en' => __('admin.product.description_en'),
            'description_ar' => __('admin.product.description_ar'),
            'slug' => __('admin.product.slug'),
            'price' => __('admin.product.price'),
            'status' => __('admin.product.status'),
            'thumbnail' => __('admin.product.thumbnail'),
            'features_en' => __('admin.product.features_en'),
            'features_ar' => __('admin.product.features_ar'),
            'download_link' => __('admin.product.download_link'),
            'sort_order' => __('admin.product.sort_order'),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $normalized = [
            'sort_order' => $this->integer('sort_order') ?? 0,
        ];

        foreach (['default_expiration_days', 'default_max_downloads', 'default_max_views'] as $field) {
            if ($this->filled($field)) {
                $normalized[$field] = $this->integer($field);
            }
        }

        $this->merge($normalized);
    }
}
