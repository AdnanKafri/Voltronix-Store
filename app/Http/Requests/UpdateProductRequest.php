<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check(); // Only authenticated users can update products
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = $this->route('product')->id ?? null;
        
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string|max:2000',
            'description_ar' => 'nullable|string|max:2000',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $productId,
            'price' => 'required|numeric|min:0|max:99999.99',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'status' => 'required|in:available,unavailable',
            'is_new' => 'boolean',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'media_type' => 'required|in:simple,gallery,before_after,video,mixed',
            'features_en' => 'nullable|array',
            'features_ar' => 'nullable|array',
            'sort_order' => 'integer|min:0',
        ];

        // Add media-specific validation rules
        $mediaType = $this->input('media_type', 'simple');
        switch ($mediaType) {
            case 'gallery':
                $rules['gallery_images'] = 'nullable|array|max:10';
                $rules['gallery_images.*'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
                $rules['gallery_alt'] = 'nullable|array';
                break;
                
            case 'before_after':
                $rules['before_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
                $rules['after_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
                break;
                
            case 'video':
                $rules['video_file'] = 'nullable|mimes:mp4,avi,mov,wmv|max:51200';
                $rules['youtube_url'] = 'nullable|url';
                $rules['video_poster'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
                break;
                
            case 'mixed':
                $rules['mixed_images'] = 'nullable|array|max:5';
                $rules['mixed_images.*'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
                $rules['mixed_video'] = 'nullable|mimes:mp4,avi,mov,wmv|max:51200';
                $rules['mixed_youtube'] = 'nullable|url';
                break;
        }

        // Add delivery automation rules
        $rules['auto_delivery_enabled'] = 'boolean';
        $rules['delivery_type'] = 'required_if:auto_delivery_enabled,true|in:manual,file,credentials,license';
        $rules['delivery_file'] = 'required_if:delivery_type,file|file|max:102400'; // 100MB max
        $rules['default_expiration_days'] = 'nullable|integer|min:1|max:365';
        $rules['default_max_downloads'] = 'nullable|integer|min:1';
        $rules['default_max_views'] = 'nullable|integer|min:1';
        
        // Credentials Delivery
        $rules['default_username'] = 'required_if:delivery_type,credentials|string|max:255';
        $rules['default_password'] = 'required_if:delivery_type,credentials|string|max:255';
        $rules['credential_notes'] = 'nullable|string|max:1000';
        
        // License Delivery
        $rules['default_license_key'] = 'required_if:delivery_type,license|string|max:255';
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
