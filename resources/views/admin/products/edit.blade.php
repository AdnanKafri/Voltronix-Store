@extends('admin.layouts.app')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product: ' . $product->getTranslation('name'))

@push('styles')
<style>
/* Use same styling as create form */
.admin-header {
    background: linear-gradient(135deg, rgba(0, 127, 255, 0.05), rgba(35, 239, 255, 0.03));
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid rgba(0, 127, 255, 0.1);
}

.admin-header h4 {
    font-family: 'Orbitron', monospace;
    font-weight: 700;
    background: linear-gradient(135deg, #007fff, #23efff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
}

.admin-header p {
    color: #6c757d;
    font-size: 1rem;
    margin: 0;
}

.form-section {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 127, 255, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: all 0.3s ease;
}

.form-section:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
}

.section-header {
    background: linear-gradient(135deg, #007fff, #23efff);
    color: white;
    padding: 1.5rem 2rem;
    border: none;
}

.section-title {
    font-family: 'Orbitron', monospace;
    font-weight: 700;
    margin: 0;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-body {
    padding: 2rem;
}

.form-control, .form-select {
    border-radius: 12px;
    border: 2px solid #e9ecef;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.form-control:focus, .form-select:focus {
    border-color: #007fff;
    box-shadow: 0 0 0 0.2rem rgba(0, 127, 255, 0.15);
    transform: translateY(-1px);
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-label i {
    color: #007fff;
    font-size: 1rem;
}

.btn-voltronix {
    background: linear-gradient(135deg, #007fff, #23efff);
    border: none;
    border-radius: 15px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    color: white;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 127, 255, 0.3);
}

.btn-voltronix:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 127, 255, 0.4);
    color: white;
}

.btn-secondary {
    background: #6c757d;
    border: none;
    border-radius: 12px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
}

.form-actions {
    background: linear-gradient(135deg, rgba(0, 127, 255, 0.05), rgba(35, 239, 255, 0.03));
    padding: 2rem;
    border-radius: 20px;
    border: 1px solid rgba(0, 127, 255, 0.1);
    text-align: center;
}

/* Responsive Design */
@media (max-width: 768px) {
    .admin-header {
        padding: 1.5rem;
        text-align: center;
    }
    
    .section-body {
        padding: 1.5rem;
    }
    
    .form-actions {
        padding: 1.5rem;
    }
    
    .btn-voltronix, .btn-secondary {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}

/* RTL Support */
[dir="rtl"] .section-title {
    flex-direction: row-reverse;
}

[dir="rtl"] .form-label {
    flex-direction: row-reverse;
}

/* Enhanced Media Management Styles */
.media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.media-item-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border: 2px solid rgba(0, 127, 255, 0.1);
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
}

.media-item-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 127, 255, 0.2);
    border-color: #007fff;
}

.media-preview {
    position: relative;
    height: 150px;
    overflow: hidden;
}

.media-thumbnail {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.3s ease;
}

.video-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    color: #6c757d;
    text-align: center;
    padding: 1rem;
}

.media-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.media-item-card:hover .media-overlay {
    opacity: 1;
}

.media-actions {
    display: flex;
    gap: 0.5rem;
}

.media-actions .btn {
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.media-actions .btn:hover {
    transform: scale(1.1);
}

.media-info {
    padding: 0.75rem;
    text-align: center;
    background: rgba(0, 127, 255, 0.02);
}

/* Media Management Modals */
.media-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.media-modal.show {
    display: flex;
}

.media-modal-content {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    max-width: 600px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
    position: relative;
}

.media-modal-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid rgba(0, 127, 255, 0.1);
}

.media-modal-title {
    font-family: 'Orbitron', monospace;
    font-weight: 700;
    color: #007fff;
    margin: 0;
}

.media-modal-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #6c757d;
    cursor: pointer;
    transition: color 0.3s ease;
}

.media-modal-close:hover {
    color: #dc3545;
}

/* Responsive Design */
@media (max-width: 768px) {
    .media-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
    }
    
    .media-preview {
        height: 120px;
    }
    
    .media-actions .btn {
        width: 30px;
        height: 30px;
        font-size: 0.8rem;
    }
}
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="admin-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h4 class="mb-1">{{ __('admin.product.edit') }}</h4>
            <p class="mb-0">{{ __('admin.product.edit') }}: {{ $product->getTranslation('name') }}</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
            {{ __('admin.back') }}
        </a>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm" novalidate>
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="form-section">
                <div class="section-header">
                    <h5 class="section-title">
                        <i class="bi bi-info-circle"></i>
                        {{ __('admin.product.basic_info') }}
                    </h5>
                </div>
                <div class="section-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('admin.product.category') }} <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">{{ __('admin.product.select_category') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                                        {{ $category->getTranslation('name') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">{{ __('admin.product.slug') }}</label>
                            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" 
                                   value="{{ old('slug', $product->slug) }}" placeholder="Auto-generated from English name">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Multilingual Content -->
            <div class="form-section">
                <div class="section-header">
                    <h5 class="section-title">
                        <i class="bi bi-translate"></i>
                        {{ __('admin.product.multilingual_content') }}
                    </h5>
                </div>
                <div class="section-body">
                    <!-- Enhanced Language Tabs -->
                    <ul class="nav nav-tabs mb-0" id="languageTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="english-tab" data-bs-toggle="tab" 
                                    data-bs-target="#english" type="button" role="tab">
                                <i class="bi bi-flag {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                <span>{{ __('app.common.english') }}</span>
                                <i class="bi bi-check-circle {{ app()->getLocale() == 'ar' ? 'me-2' : 'ms-2' }}" style="opacity: 0.7;"></i>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="arabic-tab" data-bs-toggle="tab" 
                                    data-bs-target="#arabic" type="button" role="tab">
                                <i class="bi bi-flag {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                <span>{{ __('app.common.arabic') }}</span>
                                <i class="bi bi-check-circle {{ app()->getLocale() == 'ar' ? 'me-2' : 'ms-2' }}" style="opacity: 0.7;"></i>
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="languageTabContent">
                        <!-- English Content -->
                        <div class="tab-pane fade show active" id="english" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="bi bi-type"></i>
                                        {{ __('admin.product.name_en') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" 
                                           value="{{ old('name_en', $product->getTranslation('name', 'en')) }}" required id="nameEn"
                                           placeholder="Enter product name in English">
                                    @error('name_en')
                                        <div class="field-error" style="display: block;">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="bi bi-card-text"></i>
                                        {{ __('admin.product.description_en') }}
                                    </label>
                                    <textarea name="description_en" rows="5" 
                                              class="form-control @error('description_en') is-invalid @enderror"
                                              placeholder="Enter detailed product description in English">{{ old('description_en', $product->getTranslation('description', 'en')) }}</textarea>
                                    @error('description_en')
                                        <div class="field-error" style="display: block;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="bi bi-list-stars"></i>
                                        {{ __('admin.product.features_en') }}
                                    </label>
                                    <div id="featuresEn" class="features-container">
                                        @php
                                            $featuresEn = old('features_en', $product->getTranslationArray('features', 'en'));
                                        @endphp
                                        @if($featuresEn && count($featuresEn) > 0)
                                            @foreach($featuresEn as $feature)
                                                <div class="feature-item mb-3">
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="bi bi-star text-warning"></i>
                                                        </span>
                                                        <input type="text" name="features_en[]" class="form-control" 
                                                               value="{{ $feature }}" placeholder="{{ __('admin.product.features_en') }}">
                                                        <button type="button" class="btn btn-outline-danger" onclick="removeFeature(this)">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="feature-item mb-3">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="bi bi-star text-warning"></i>
                                                    </span>
                                                    <input type="text" name="features_en[]" class="form-control" 
                                                           placeholder="{{ __('admin.product.features_en') }}">
                                                    <button type="button" class="btn btn-outline-danger" onclick="removeFeature(this)">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-outline-primary" onclick="addFeature('featuresEn', 'features_en[]')">
                                        <i class="bi bi-plus-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                        {{ __('admin.product.add_feature') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Arabic Content -->
                        <div class="tab-pane fade" id="arabic" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">{{ __('admin.product.name_ar') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" 
                                           value="{{ old('name_ar', $product->getTranslation('name', 'ar')) }}" required dir="rtl">
                                    @error('name_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">{{ __('admin.product.description_ar') }}</label>
                                    <textarea name="description_ar" rows="4" 
                                              class="form-control @error('description_ar') is-invalid @enderror" 
                                              dir="rtl">{{ old('description_ar', $product->getTranslation('description', 'ar')) }}</textarea>
                                    @error('description_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="bi bi-list-stars"></i>
                                        {{ __('admin.product.features_ar') }}
                                    </label>
                                    <div id="featuresAr" class="features-container">
                                        @php
                                            $featuresAr = old('features_ar', $product->getTranslationArray('features', 'ar'));
                                        @endphp
                                        @if($featuresAr && count($featuresAr) > 0)
                                            @foreach($featuresAr as $feature)
                                                <div class="feature-item mb-3">
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="bi bi-star text-warning"></i>
                                                        </span>
                                                        <input type="text" name="features_ar[]" class="form-control" 
                                                               value="{{ $feature }}" placeholder="{{ __('admin.product.enter_feature_ar') }}" dir="rtl">
                                                        <button type="button" class="btn btn-outline-danger" onclick="removeFeature(this)">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="feature-item mb-3">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="bi bi-star text-warning"></i>
                                                    </span>
                                                    <input type="text" name="features_ar[]" class="form-control" 
                                                           placeholder="{{ __('admin.product.enter_feature_ar') }}" dir="rtl">
                                                    <button type="button" class="btn btn-outline-danger" onclick="removeFeature(this)">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-outline-primary" onclick="addFeature('featuresAr', 'features_ar[]')">
                                        <i class="bi bi-plus-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                        {{ __('admin.product.add_feature') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing & Flags -->
            <div class="form-section">
                <div class="section-header">
                    <h5 class="section-title">
                        <i class="bi bi-currency-dollar"></i>
                        {{ __('admin.product.pricing_flags') }}
                    </h5>
                </div>
                <div class="section-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('admin.product.price') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="price" step="0.01" min="0" 
                                       class="form-control @error('price') is-invalid @enderror" 
                                       value="{{ old('price', $product->price) }}" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Discount Price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="discount_price" step="0.01" min="0" 
                                       class="form-control @error('discount_price') is-invalid @enderror" 
                                       value="{{ old('discount_price', $product->discount_price) }}">
                            </div>
                            @error('discount_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">{{ __('admin.product.status') }} <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="available" {{ old('status', $product->status) == 'available' ? 'selected' : '' }}>
                                    {{ __('admin.product.available') }}
                                </option>
                                <option value="unavailable" {{ old('status', $product->status) == 'unavailable' ? 'selected' : '' }}>
                                    {{ __('admin.product.unavailable') }}
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_featured" 
                                               id="isFeatured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isFeatured">
                                            <i class="bi bi-star me-1"></i> Featured Product
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_new" 
                                               id="isNew" value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isNew">
                                            <i class="bi bi-lightning me-1"></i> New Product
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Sort Order</label>
                                    <input type="number" name="sort_order" min="0" 
                                           class="form-control @error('sort_order') is-invalid @enderror" 
                                           value="{{ old('sort_order', $product->sort_order) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advanced Media Management -->
            <div class="form-section">
                <div class="section-header">
                    <h5 class="section-title">
                        <i class="bi bi-images"></i>
                        {{ __('admin.product.advanced_media') }}
                    </h5>
                </div>
                <div class="section-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Media Type <span class="text-danger">*</span></label>
                            <select name="media_type" id="mediaType" class="form-select @error('media_type') is-invalid @enderror" required>
                                <option value="simple" {{ old('media_type', $product->media_type) == 'simple' ? 'selected' : '' }}>
                                    Simple Image
                                </option>
                                <option value="gallery" {{ old('media_type', $product->media_type) == 'gallery' ? 'selected' : '' }}>
                                    Image Gallery
                                </option>
                                <option value="before_after" {{ old('media_type', $product->media_type) == 'before_after' ? 'selected' : '' }}>
                                    Before/After Images
                                </option>
                                <option value="video" {{ old('media_type', $product->media_type) == 'video' ? 'selected' : '' }}>
                                    Video Content
                                </option>
                                <option value="mixed" {{ old('media_type', $product->media_type) == 'mixed' ? 'selected' : '' }}>
                                    Mixed Media
                                </option>
                            </select>
                            @error('media_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Thumbnail Image</label>
                            <input type="file" name="thumbnail" accept="image/*" 
                                   class="form-control @error('thumbnail') is-invalid @enderror">
                            @if($product->thumbnail)
                                <div class="mt-2">
                                    <img src="{{ $product->thumbnail_url }}" 
                                         alt="Current thumbnail" class="img-thumbnail" style="max-height: 100px;">
                                    <div class="form-text">Current thumbnail (leave empty to keep current)</div>
                                </div>
                            @endif
                            @error('thumbnail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Existing Media Display with Individual CRUD Operations -->
                    @if($product->media->count() > 0 || $product->media_data)
                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">
                                    <i class="bi bi-collection {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                    {{ __('admin.product.existing_media') }}
                                </h6>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addMediaItem()">
                                        <i class="bi bi-plus-circle {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                        {{ __('admin.product.add_media') }}
                                    </button>
                                </div>
                            </div>
                            
                            <div class="media-grid" id="existingMediaGrid">
                                <!-- ProductMedia Relationship Items -->
                                @foreach($product->media as $mediaItem)
                                    <div class="media-item-card" data-media-id="{{ $mediaItem->id }}" data-source="relationship">
                                        <div class="media-preview">
                                            @if($mediaItem->isImage())
                                                <img src="{{ $mediaItem->media_url }}" 
                                                     alt="{{ $mediaItem->title ?: 'Product Media' }}" 
                                                     class="media-thumbnail">
                                            @elseif($mediaItem->type === 'youtube')
                                                <div class="video-placeholder">
                                                    <i class="bi bi-youtube" style="font-size: 2rem; color: #ff0000;"></i>
                                                    <small class="d-block mt-1">{{ __('admin.product.youtube_url') }}</small>
                                                </div>
                                            @elseif($mediaItem->type === 'video')
                                                <div class="video-placeholder">
                                                    <i class="bi bi-play-circle" style="font-size: 2rem; color: #007fff;"></i>
                                                    <small class="d-block mt-1">{{ __('admin.product.video_title') }}</small>
                                                </div>
                                            @endif
                                            
                                            <div class="media-overlay">
                                                <div class="media-actions">
                                                    <button type="button" class="btn btn-sm btn-light" onclick="viewMediaItem({{ $mediaItem->id }})" title="{{ __('admin.product.view_media') }}">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-warning" onclick="editMediaItem({{ $mediaItem->id }})" title="{{ __('admin.product.edit_media') }}">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteMediaItem({{ $mediaItem->id }})" title="{{ __('admin.product.delete_media') }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="media-info">
                                            <small class="text-muted">
                                                {{ ucfirst($mediaItem->type) }}
                                                @if($mediaItem->title)
                                                    - {{ Str::limit($mediaItem->title, 20) }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Legacy media_data JSON Items -->
                                @if($product->media_data)
                                    @if($product->media_type === 'gallery' && isset($product->media_data['images']))
                                        @foreach($product->media_data['images'] as $index => $image)
                                            <div class="media-item-card" data-media-type="gallery" data-index="{{ $index }}" data-source="json">
                                                <div class="media-preview">
                                                    <img src="{{ $product->resolveMediaUrl($image['path'] ?? null) }}" 
                                                         alt="Gallery Image {{ $index + 1 }}" 
                                                         class="media-thumbnail">
                                                    <div class="media-overlay">
                                                        <div class="media-actions">
                                                            <button type="button" class="btn btn-sm btn-light" onclick="viewLegacyMedia('{{ $product->resolveMediaUrl($image['path'] ?? null) }}', 'Gallery Image {{ $index + 1 }}')" title="{{ __('admin.product.view_media') }}">
                                                                <i class="bi bi-eye"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeLegacyMedia(this, 'gallery', {{ $index }})" title="{{ __('admin.product.delete_media') }}">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="media-info">
                                                    <small class="text-muted">{{ __('admin.product.gallery_images') }} #{{ $index + 1 }} (Legacy)</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                    @if($product->media_type === 'before_after')
                                        @if(isset($product->media_data['before_image']))
                                            <div class="media-item-card" data-media-type="before_after" data-index="before" data-source="json">
                                                <div class="media-preview">
                                                    <img src="{{ $product->resolveMediaUrl($product->media_data['before_image'] ?? null) }}" 
                                                         alt="Before Image" class="media-thumbnail">
                                                    <div class="media-overlay">
                                                        <div class="media-actions">
                                                            <button type="button" class="btn btn-sm btn-light" onclick="viewLegacyMedia('{{ $product->resolveMediaUrl($product->media_data['before_image'] ?? null) }}', 'Before Image')" title="{{ __('admin.product.view_media') }}">
                                                                <i class="bi bi-eye"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeLegacyMedia(this, 'before_after', 'before')" title="{{ __('admin.product.delete_media') }}">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="media-info">
                                                    <small class="text-muted">{{ __('admin.product.before_image') }} (Legacy)</small>
                                                </div>
                                            </div>
                                        @endif
                                        @if(isset($product->media_data['after_image']))
                                            <div class="media-item-card" data-media-type="before_after" data-index="after" data-source="json">
                                                <div class="media-preview">
                                                    <img src="{{ $product->resolveMediaUrl($product->media_data['after_image'] ?? null) }}" 
                                                         alt="After Image" class="media-thumbnail">
                                                    <div class="media-overlay">
                                                        <div class="media-actions">
                                                            <button type="button" class="btn btn-sm btn-light" onclick="viewLegacyMedia('{{ $product->resolveMediaUrl($product->media_data['after_image'] ?? null) }}', 'After Image')" title="{{ __('admin.product.view_media') }}">
                                                                <i class="bi bi-eye"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeLegacyMedia(this, 'before_after', 'after')" title="{{ __('admin.product.delete_media') }}">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="media-info">
                                                    <small class="text-muted">{{ __('admin.product.after_image') }} (Legacy)</small>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="mt-4">
                            <div class="alert alert-info d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-info-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                    {{ __('admin.product.no_media') }}
                                </div>
                                <button type="button" class="btn btn-primary btn-sm" onclick="addMediaItem()">
                                    <i class="bi bi-plus-circle {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                    {{ __('admin.product.add_media') }}
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Dynamic Media Fields -->
                    <div id="mediaFields" class="mt-4"></div>
                </div>
            </div>

            <!-- Delivery Automation -->
            <div class="form-section">
                <div class="section-header">
                    <h5 class="section-title">
                        <i class="bi bi-gear"></i>
                        Delivery Automation
                    </h5>
                </div>
                <div class="section-body">
                    {{-- Automation Enable Toggle --}}
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       id="auto_delivery_enabled" name="auto_delivery_enabled" 
                                       value="1" {{ old('auto_delivery_enabled', $product->auto_delivery_enabled ?? false) ? 'checked' : '' }}
                                       onchange="toggleDeliveryConfig()">
                                <label class="form-check-label" for="auto_delivery_enabled">
                                    Enable Automatic Delivery
                                </label>
                                <small class="form-text text-muted d-block">
                                    When enabled, this product will be delivered automatically when orders are approved.
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- Delivery Configuration (Hidden by default) --}}
                    <div id="delivery-config" style="display: {{ old('auto_delivery_enabled', $product->auto_delivery_enabled ?? false) ? 'block' : 'none' }};">
                        
                        {{-- Delivery Type --}}
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label">{{ __('admin.product.delivery_type') }} <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-select" name="delivery_type" id="delivery_type" onchange="toggleDeliveryTypeFields()">
                                    <option value="manual" {{ old('delivery_type', $product->delivery_type ?? 'manual') == 'manual' ? 'selected' : '' }}>
                                        {{ __('admin.product.manual_delivery') }}
                                    </option>
                                    <option value="file" {{ old('delivery_type', $product->delivery_type) == 'file' ? 'selected' : '' }}>
                                        {{ __('admin.product.file_delivery') }}
                                    </option>
                                    <option value="credentials" {{ old('delivery_type', $product->delivery_type) == 'credentials' ? 'selected' : '' }}>
                                        {{ __('admin.product.credentials_delivery') }}
                                    </option>
                                    <option value="license" {{ old('delivery_type', $product->delivery_type) == 'license' ? 'selected' : '' }}>
                                        {{ __('admin.product.license_delivery') }}
                                    </option>
                                </select>
                                @error('delivery_type')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- File Delivery Fields --}}
                        <div id="file-delivery-fields" style="display: {{ old('delivery_type', $product->delivery_type) == 'file' ? 'block' : 'none' }};">
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Delivery File</label>
                                <div class="col-md-9">
                                    <input type="file" class="form-control" name="delivery_file" accept=".zip,.rar,.pdf,.exe,.dmg">
                                    @if(isset($product) && $product->delivery_file_path)
                                        <small class="text-success d-block mt-1">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Current file: {{ $product->delivery_file_name ?? basename($product->delivery_file_path) }}
                                        </small>
                                    @endif
                                    <small class="form-text text-muted">
                                        Upload the file that will be automatically delivered to customers.
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- Default Settings --}}
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Default Expiration (Days)</label>
                                <input type="number" class="form-control" name="default_expiration_days" 
                                       value="{{ old('default_expiration_days', $product->default_expiration_days ?? 30) }}" 
                                       min="1" max="365">
                                <small class="form-text text-muted">How many days the delivery remains accessible.</small>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Max Downloads</label>
                                <input type="number" class="form-control" name="default_max_downloads" 
                                       value="{{ old('default_max_downloads', $product->default_max_downloads) }}" 
                                       min="1" placeholder="Unlimited">
                                <small class="form-text text-muted">Maximum number of downloads allowed (leave empty for unlimited).</small>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Max Views</label>
                                <input type="number" class="form-control" name="default_max_views" 
                                       value="{{ old('default_max_views', $product->default_max_views) }}" 
                                       min="1" placeholder="Unlimited">
                                <small class="form-text text-muted">Maximum number of credential views allowed (leave empty for unlimited).</small>
                            </div>
                        </div>
                        
                        {{-- Credentials Delivery Fields --}}
                        <div id="credentials-delivery-fields" style="display: {{ old('delivery_type', $product->delivery_type) == 'credentials' ? 'block' : 'none' }};">
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Default Username</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="default_username" 
                                           value="{{ old('default_username', $product->delivery_config['default_username'] ?? '') }}" placeholder="Enter default username">
                                    <small class="form-text text-muted">
                                        Default username that will be used for automated credential deliveries.
                                    </small>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Default Password</label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" name="default_password" 
                                           value="{{ old('default_password', $product->delivery_config['default_password'] ?? '') }}" placeholder="Enter default password">
                                    <small class="form-text text-muted">
                                        Default password that will be used for automated credential deliveries.
                                    </small>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">{{ __('admin.product.notes') }}</label>
                                <div class="col-md-9">
                                    <textarea class="form-control" name="credential_notes" rows="3" 
                                              placeholder="{{ __('admin.product.notes_placeholder') }}">{{ old('credential_notes', $product->delivery_config['credential_notes'] ?? '') }}</textarea>
                                    <small class="form-text text-muted">{{ __('admin.product.notes_help') }}</small>
                                </div>
                            </div>
                        </div>
                        
                        {{-- License Delivery Fields --}}
                        <div id="license-delivery-fields" style="display: {{ old('delivery_type', $product->delivery_type) == 'license' ? 'block' : 'none' }};">
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">{{ __('admin.product.license_key') }} <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="default_license_key" 
                                           value="{{ old('default_license_key', $product->delivery_config['default_license_key'] ?? '') }}" 
                                           placeholder="{{ __('admin.product.license_key_placeholder') }}">
                                    @error('default_license_key')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">{{ __('admin.product.license_notes') }}</label>
                                <div class="col-md-9">
                                    <textarea class="form-control" name="license_instructions" rows="3" 
                                              placeholder="{{ __('admin.product.license_notes_placeholder') }}">{{ old('license_instructions', $product->delivery_config['license_instructions'] ?? '') }}</textarea>
                                    <small class="form-text text-muted">{{ __('admin.product.license_notes_help') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Products
                </a>
                <div>
                    <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="btn btn-outline-info me-2">
                        <i class="bi bi-eye me-2"></i>
                        View Product
                    </a>
                    <button type="submit" class="btn btn-voltronix">
                        <i class="bi bi-check-circle me-2"></i>
                        Update Product
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from English name
    const nameEn = document.getElementById('nameEn');
    const slugField = document.querySelector('input[name="slug"]');
    
    nameEn.addEventListener('input', function() {
        if (!slugField.dataset.manual) {
            slugField.value = generateSlug(this.value);
        }
    });
    
    slugField.addEventListener('input', function() {
        this.dataset.manual = 'true';
    });
    
    // Media type change handler
    const mediaType = document.getElementById('mediaType');
    const mediaFields = document.getElementById('mediaFields');
    
    mediaType.addEventListener('change', function() {
        updateMediaFields(this.value);
    });
    
    // Initialize media fields
    updateMediaFields(mediaType.value);
});

function generateSlug(text) {
    return text.toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

function updateMediaFields(mediaType) {
    const mediaFields = document.getElementById('mediaFields');
    let html = '';
    
    switch(mediaType) {
        case 'simple':
            html = '<p class="text-muted mb-0"><i class="bi bi-info-circle me-2"></i>Simple media uses only the thumbnail image above.</p>';
            break;
            
        case 'gallery':
            html = `
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Gallery Images</label>
                        <input type="file" name="gallery_images[]" multiple accept="image/*" 
                               class="form-control" id="galleryImages">
                        <div class="form-text">Select multiple images for the gallery (max 10). Leave empty to keep current images.</div>
                    </div>
                </div>
            `;
            break;
            
        case 'before_after':
            html = `
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Before Image</label>
                        <input type="file" name="before_image" accept="image/*" class="form-control">
                        <div class="form-text">Leave empty to keep current image</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">After Image</label>
                        <input type="file" name="after_image" accept="image/*" class="form-control">
                        <div class="form-text">Leave empty to keep current image</div>
                    </div>
                </div>
            `;
            break;
            
        case 'video':
            html = `
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('admin.product.video_upload') }}</label>
                        <input type="file" name="video_file" accept="video/*" class="form-control">
                        <div class="form-text">Upload video file (max 50MB). Leave empty to keep current.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">YouTube URL</label>
                        <input type="url" name="youtube_url" class="form-control" 
                               placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Video Poster Image</label>
                        <input type="file" name="video_poster" accept="image/*" class="form-control">
                        <div class="form-text">Thumbnail image for video player</div>
                    </div>
                </div>
            `;
            break;
            
        case 'mixed':
            html = `
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Mixed Images</label>
                        <input type="file" name="mixed_images[]" multiple accept="image/*" class="form-control">
                        <div class="form-text">Multiple images (max 5)</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('admin.product.video_upload') }}</label>
                        <input type="file" name="mixed_video" accept="video/*" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">YouTube URL</label>
                        <input type="url" name="mixed_youtube" class="form-control" 
                               placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                </div>
            `;
            break;
    }
    
    mediaFields.innerHTML = html;
}

function addFeature(containerId, inputName) {
    const container = document.getElementById(containerId);
    const div = document.createElement('div');
    div.className = 'feature-item mb-3';
    
    const isArabic = inputName.includes('_ar');
    const placeholder = isArabic ? '{{ __('admin.product.enter_feature_ar') }}' : '{{ __('admin.product.features_en') }}';
    const dirAttribute = isArabic ? ' dir="rtl"' : '';
    
    div.innerHTML = `
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-star text-warning"></i>
            </span>
            <input type="text" name="${inputName}" class="form-control" placeholder="${placeholder}"${dirAttribute}>
            <button type="button" class="btn btn-outline-danger" onclick="removeFeature(this)">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    
    // Add animation
    div.style.opacity = '0';
    div.style.transform = 'translateY(-10px)';
    container.appendChild(div);
    
    // Animate in
    setTimeout(() => {
        div.style.transition = 'all 0.3s ease';
        div.style.opacity = '1';
        div.style.transform = 'translateY(0)';
    }, 10);
}

function removeFeature(button) {
    const featureItem = button.closest('.feature-item');
    
    // Animate out
    featureItem.style.transition = 'all 0.3s ease';
    featureItem.style.opacity = '0';
    featureItem.style.transform = 'translateY(-10px)';
    
    // Remove after animation
    setTimeout(() => {
        featureItem.remove();
    }, 300);
}

// Form submission with loading state
document.getElementById('productForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>{{ __('admin.loading') }}...';
});

// Enhanced Media Management Functions
function addMediaItem() {
    showMediaUploadModal();
}

// Handle ProductMedia relationship items
function viewMediaItem(mediaId) {
    // AJAX call to get media details
    fetch(`/admin/products/media/${mediaId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.media.type === 'youtube') {
                    showYouTubeModal(data.media.url, data.media.title);
                } else if (data.media.path || data.media.url) {
                    showImageModal(data.media.media_url, data.media.title || 'Product Media');
                }
            }
        })
        .catch(error => {
            console.error('Error loading media:', error);
            Swal.fire('{{ __('admin.error') }}', '{{ __('admin.something_went_wrong') }}', 'error');
        });
}

function editMediaItem(mediaId) {
    showMediaEditModal(mediaId);
}

function deleteMediaItem(mediaId) {
    Swal.fire({
        title: '{{ __('admin.product.delete_media') }}?',
        text: '{{ __('admin.product.confirm_delete_media') }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __('admin.yes') }}, {{ __('admin.product.delete_media') }}',
        cancelButtonText: '{{ __('admin.cancel') }}',
        reverseButtons: {{ app()->getLocale() == 'ar' ? 'true' : 'false' }}
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: '{{ __('admin.loading') }}...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // AJAX call to delete media
            fetch(`/admin/products/media/${mediaId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the media card with animation
                    const mediaCard = document.querySelector(`[data-media-id="${mediaId}"]`);
                    if (mediaCard) {
                        mediaCard.style.transition = 'all 0.3s ease';
                        mediaCard.style.opacity = '0';
                        mediaCard.style.transform = 'scale(0.8)';
                        
                        setTimeout(() => {
                            mediaCard.remove();
                            
                            // Check if no media left
                            const remainingMedia = document.querySelectorAll('#existingMediaGrid .media-item-card');
                            if (remainingMedia.length === 0) {
                                location.reload(); // Reload to show "no media" state
                            }
                        }, 300);
                    }
                    
                    Swal.fire({
                        title: '{{ __('admin.success') }}!',
                        text: '{{ __('admin.product.media_deleted') }}',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                } else {
                    Swal.fire('{{ __('admin.error') }}', data.message || '{{ __('admin.something_went_wrong') }}', 'error');
                }
            })
            .catch(error => {
                console.error('Error deleting media:', error);
                Swal.fire('{{ __('admin.error') }}', '{{ __('admin.something_went_wrong') }}', 'error');
            });
        }
    });
}

// Handle legacy JSON media items
function viewLegacyMedia(src, alt) {
    showImageModal(src, alt);
}

function removeLegacyMedia(button, mediaType, index) {
    const mediaCard = button.closest('.media-item-card');
    const mediaInfo = mediaCard.querySelector('.media-info small').textContent;
    
    Swal.fire({
        title: '{{ __('admin.product.delete_media') }}?',
        text: `Remove "${mediaInfo}" from this product?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __('admin.yes') }}, {{ __('admin.product.delete_media') }}',
        cancelButtonText: '{{ __('admin.cancel') }}',
        reverseButtons: {{ app()->getLocale() == 'ar' ? 'true' : 'false' }}
    }).then((result) => {
        if (result.isConfirmed) {
            // Add removal animation
            mediaCard.style.transition = 'all 0.3s ease';
            mediaCard.style.opacity = '0';
            mediaCard.style.transform = 'scale(0.8)';
            
            setTimeout(() => {
                mediaCard.remove();
                
                // Show success message
                Swal.fire({
                    title: '{{ __('admin.success') }}!',
                    text: '{{ __('admin.product.media_deleted') }}',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }, 300);
        }
    });
}

function showImageModal(src, alt) {
    const modal = document.createElement('div');
    modal.className = 'media-modal show';
    modal.innerHTML = `
        <div class="media-modal-content" style="max-width: 800px;">
            <button class="media-modal-close" onclick="this.closest('.media-modal').remove()">&times;</button>
            <div class="media-modal-header">
                <h5 class="media-modal-title">{{ __('admin.product.media_preview') }}</h5>
            </div>
            <div class="text-center">
                <img src="${src}" alt="${alt}" class="img-fluid" style="max-height: 60vh; border-radius: 10px;">
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Close on outside click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

function showMediaUploadModal() {
    const modal = document.createElement('div');
    modal.className = 'media-modal show';
    modal.innerHTML = `
        <div class="media-modal-content">
            <button class="media-modal-close" onclick="this.closest('.media-modal').remove()">&times;</button>
            <div class="media-modal-header">
                <h5 class="media-modal-title">{{ __('admin.product.add_media') }}</h5>
            </div>
            <div class="media-modal-body">
                <form id="addMediaForm" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('admin.product.media_type') }} <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required onchange="toggleMediaFields(this.value)">
                                <option value="">{{ __('admin.product.media_type') }}</option>
                                <option value="image">{{ __('admin.product.gallery_images') }}</option>
                                <option value="before">{{ __('admin.product.before_image') }}</option>
                                <option value="after">{{ __('admin.product.after_image') }}</option>
                                <option value="video">{{ __('admin.product.video_upload') }}</option>
                                <option value="youtube">{{ __('admin.product.youtube_url') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('admin.product.media_title') }}</label>
                            <input type="text" name="title" class="form-control" placeholder="{{ __('admin.product.media_title') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('admin.product.media_description') }}</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="{{ __('admin.product.media_description') }}"></textarea>
                        </div>
                        
                        <!-- File Upload Fields -->
                        <div class="col-12" id="fileUploadField" style="display: none;">
                            <label class="form-label">{{ __('admin.product.file_path') }} <span class="text-danger">*</span></label>
                            <input type="file" name="media_file" class="form-control" accept="image/*,video/*">
                            <div class="form-text">{{ __('admin.product.file_size_limit') }} | {{ __('admin.product.supported_formats') }}: JPG, PNG, GIF, MP4, MOV</div>
                        </div>
                        
                        <!-- URL Field -->
                        <div class="col-12" id="urlField" style="display: none;">
                            <label class="form-label">{{ __('admin.product.external_url') }} <span class="text-danger">*</span></label>
                            <input type="url" name="url" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                            <div class="form-text">{{ __('admin.product.youtube_url') }}</div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="media-modal-footer mt-3 text-end">
                <button class="btn btn-secondary me-2" onclick="this.closest('.media-modal').remove()">{{ __('admin.cancel') }}</button>
                <button class="btn btn-voltronix" onclick="submitMediaForm()">{{ __('admin.product.add_media') }}</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
}

function showMediaEditModal(mediaId) {
    // Show loading first
    Swal.fire({
        title: '{{ __('admin.loading') }}...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Fetch media details
    fetch(`/admin/products/media/${mediaId}`)
        .then(response => response.json())
        .then(data => {
            Swal.close();
            
            if (data.success) {
                const media = data.media;
                const modal = document.createElement('div');
                modal.className = 'media-modal show';
                modal.innerHTML = `
                    <div class="media-modal-content">
                        <button class="media-modal-close" onclick="this.closest('.media-modal').remove()">&times;</button>
                        <div class="media-modal-header">
                            <h5 class="media-modal-title">{{ __('admin.product.edit_media') }} - ${media.type}</h5>
                        </div>
                        <div class="media-modal-body">
                            <form id="editMediaForm" enctype="multipart/form-data">
                                <input type="hidden" name="media_id" value="${mediaId}">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('admin.product.media_type') }}</label>
                                        <input type="text" class="form-control" value="${media.type}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('admin.product.media_title') }}</label>
                                        <input type="text" name="title" class="form-control" value="${media.title || ''}" placeholder="{{ __('admin.product.media_title') }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">{{ __('admin.product.media_description') }}</label>
                                        <textarea name="description" class="form-control" rows="2" placeholder="{{ __('admin.product.media_description') }}">${media.description || ''}</textarea>
                                    </div>
                                    
                                    ${media.type === 'youtube' ? `
                                        <div class="col-12">
                                            <label class="form-label">{{ __('admin.product.external_url') }}</label>
                                            <input type="url" name="url" class="form-control" value="${media.url || ''}" placeholder="https://www.youtube.com/watch?v=...">
                                        </div>
                                    ` : `
                                        <div class="col-12">
                                            <label class="form-label">{{ __('admin.product.replace_media') }}</label>
                                            <input type="file" name="media_file" class="form-control" accept="${media.type === 'video' ? 'video/*' : 'image/*'}">
                                            <div class="form-text">{{ __('admin.product.keep_current_file') }}</div>
                                        </div>
                                    `}
                                </div>
                            </form>
                        </div>
                        <div class="media-modal-footer mt-3 text-end">
                            <button class="btn btn-secondary me-2" onclick="this.closest('.media-modal').remove()">{{ __('admin.cancel') }}</button>
                            <button class="btn btn-voltronix" onclick="updateMediaForm()">{{ __('admin.save_changes') }}</button>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(modal);
            } else {
                Swal.fire('{{ __('admin.error') }}', '{{ __('admin.something_went_wrong') }}', 'error');
            }
        })
        .catch(error => {
            Swal.close();
            console.error('Error loading media:', error);
            Swal.fire('{{ __('admin.error') }}', '{{ __('admin.something_went_wrong') }}', 'error');
        });
}

function showYouTubeModal(url, title) {
    const videoId = extractYouTubeId(url);
    const modal = document.createElement('div');
    modal.className = 'media-modal show';
    modal.innerHTML = `
        <div class="media-modal-content" style="max-width: 800px;">
            <button class="media-modal-close" onclick="this.closest('.media-modal').remove()">&times;</button>
            <div class="media-modal-header">
                <h5 class="media-modal-title">${title || 'YouTube Video'}</h5>
            </div>
            <div class="text-center">
                ${videoId ? `
                    <iframe width="100%" height="400" src="https://www.youtube.com/embed/${videoId}" 
                            frameborder="0" allowfullscreen style="border-radius: 10px;"></iframe>
                ` : `
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ __('admin.product.invalid_youtube_url') }}
                    </div>
                `}
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Close on outside click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

function extractYouTubeId(url) {
    const regex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
    const match = url.match(regex);
    return match ? match[1] : null;
}

function toggleMediaFields(type) {
    const fileField = document.getElementById('fileUploadField');
    const urlField = document.getElementById('urlField');
    
    if (type === 'youtube') {
        fileField.style.display = 'none';
        urlField.style.display = 'block';
        urlField.querySelector('input').required = true;
        fileField.querySelector('input').required = false;
    } else if (type) {
        fileField.style.display = 'block';
        urlField.style.display = 'none';
        fileField.querySelector('input').required = true;
        urlField.querySelector('input').required = false;
        
        // Update file accept types
        const fileInput = fileField.querySelector('input[type="file"]');
        if (type === 'video') {
            fileInput.accept = 'video/*';
        } else {
            fileInput.accept = 'image/*';
        }
    } else {
        fileField.style.display = 'none';
        urlField.style.display = 'none';
        fileField.querySelector('input').required = false;
        urlField.querySelector('input').required = false;
    }
}

function submitMediaForm() {
    const form = document.getElementById('addMediaForm');
    const formData = new FormData(form);
    formData.append('product_id', {{ $product->id }});
    
    // Show loading
    Swal.fire({
        title: '{{ __('admin.product.upload_progress') }}...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch('/admin/products/media', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: '{{ __('admin.success') }}!',
                text: '{{ __('admin.product.media_updated') }}',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload(); // Reload to show new media
            });
        } else {
            Swal.fire('{{ __('admin.error') }}', data.message || '{{ __('admin.something_went_wrong') }}', 'error');
        }
    })
    .catch(error => {
        console.error('Error adding media:', error);
        Swal.fire('{{ __('admin.error') }}', '{{ __('admin.something_went_wrong') }}', 'error');
    });
}

function updateMediaForm() {
    const form = document.getElementById('editMediaForm');
    const formData = new FormData(form);
    const mediaId = formData.get('media_id');
    
    // Show loading
    Swal.fire({
        title: '{{ __('admin.product.processing') }}...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch(`/admin/products/media/${mediaId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: '{{ __('admin.success') }}!',
                text: '{{ __('admin.product.media_updated') }}',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload(); // Reload to show updated media
            });
        } else {
            Swal.fire('{{ __('admin.error') }}', data.message || '{{ __('admin.something_went_wrong') }}', 'error');
        }
    })
    .catch(error => {
        console.error('Error updating media:', error);
        Swal.fire('{{ __('admin.error') }}', '{{ __('admin.something_went_wrong') }}', 'error');
    });
}

function enableMediaEditMode() {
    const mediaGrid = document.getElementById('currentMediaGrid');
    const mediaCards = mediaGrid.querySelectorAll('.media-item-card');
    
    mediaCards.forEach(card => {
        card.style.border = '2px solid #ffc107';
        card.classList.add('edit-mode');
    });
    
    // Show edit mode indicator
    const indicator = document.createElement('div');
    indicator.className = 'alert alert-warning mt-2';
    indicator.innerHTML = '<i class="bi bi-pencil me-2"></i>Edit mode enabled. Click on any media item to modify it.';
    mediaGrid.parentNode.insertBefore(indicator, mediaGrid);
    
    setTimeout(() => {
        indicator.remove();
        mediaCards.forEach(card => {
            card.style.border = '';
            card.classList.remove('edit-mode');
        });
    }, 5000);
}

// Delivery Automation JavaScript
function toggleDeliveryConfig() {
    const enabled = document.getElementById('auto_delivery_enabled').checked;
    const config = document.getElementById('delivery-config');
    config.style.display = enabled ? 'block' : 'none';
    
    if (!enabled) {
        document.getElementById('delivery_type').value = 'manual';
        toggleDeliveryTypeFields();
    }
}

function toggleDeliveryTypeFields() {
    const deliveryType = document.getElementById('delivery_type').value;
    const fileFields = document.getElementById('file-delivery-fields');
    const credentialsFields = document.getElementById('credentials-delivery-fields');
    const licenseFields = document.getElementById('license-delivery-fields');
    
    // Hide all delivery type fields first
    fileFields.style.display = 'none';
    credentialsFields.style.display = 'none';
    licenseFields.style.display = 'none';
    
    // Show relevant fields based on delivery type
    if (deliveryType === 'file') {
        fileFields.style.display = 'block';
    } else if (deliveryType === 'credentials') {
        credentialsFields.style.display = 'block';
    } else if (deliveryType === 'license') {
        licenseFields.style.display = 'block';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleDeliveryConfig();
    toggleDeliveryTypeFields();
});
</script>
@endpush


