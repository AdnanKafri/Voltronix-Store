@extends('admin.layouts.app')

@section('title', 'Create Product')
@section('page-title', 'Create New Product')

@push('styles')
<style>
/* Modern Admin Create Form Styling */
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
    padding: 2.5rem;
}

.section-body .row {
    margin-bottom: 1.5rem;
}

.section-body .row:last-child {
    margin-bottom: 0;
}

.form-control, .form-select, .form-control-file {
    border-radius: 12px;
    border: 2px solid #e9ecef;
    padding: 1rem 1.25rem;
    transition: all 0.3s ease;
    font-size: 1rem;
    background: #ffffff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
}

.form-control:focus, .form-select:focus {
    border-color: #007fff;
    box-shadow: 0 0 0 0.25rem rgba(0, 127, 255, 0.15), 0 4px 12px rgba(0, 127, 255, 0.1);
    transform: translateY(-2px);
    background: #ffffff;
}

.form-control::placeholder {
    color: #95a5a6;
    font-style: italic;
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
}

.form-label {
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1rem;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.9rem;
}

.form-label i {
    color: #007fff;
    font-size: 1.1rem;
    padding: 0.25rem;
    background: rgba(0, 127, 255, 0.1);
    border-radius: 6px;
}

.form-label .text-danger {
    color: #e74c3c !important;
    font-weight: 900;
    margin-left: 0.25rem;
}

.btn-voltronix {
    background: linear-gradient(135deg, #007fff, #23efff);
    border: none;
    border-radius: 15px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    color: white;
    transition: all 0.3s ease;
}

.btn-voltronix:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 127, 255, 0.3);
    color: white;
}

/* Enhanced Language Tabs */
.nav-tabs {
    border: none;
    margin-bottom: 0;
    gap: 8px;
}

.nav-tabs .nav-link {
    border-radius: 15px 15px 0 0;
    border: 2px solid #e9ecef;
    background: #ffffff;
    font-weight: 600;
    padding: 1rem 2rem;
    color: #495057;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.nav-tabs .nav-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(0, 127, 255, 0.1), transparent);
    transition: left 0.5s;
}

.nav-tabs .nav-link:hover {
    background: rgba(0, 127, 255, 0.05);
    border-color: #007fff;
    color: #007fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 127, 255, 0.2);
}

.nav-tabs .nav-link:hover::before {
    left: 100%;
}

.nav-tabs .nav-link.active {
    background: linear-gradient(135deg, #007fff, #23efff);
    border-color: #007fff;
    color: white;
    box-shadow: 0 6px 20px rgba(0, 127, 255, 0.4);
}

.nav-tabs .nav-link.active::before {
    display: none;
}

.tab-content {
    background: white;
    border-radius: 0 0 20px 20px;
    padding: 2.5rem;
    border: 2px solid #e9ecef;
    border-top: none;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

/* Media Upload Areas */
.media-upload-area {
    border: 3px dashed #007fff;
    border-radius: 20px;
    padding: 3rem 2rem;
    text-align: center;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, rgba(0, 127, 255, 0.05), rgba(35, 239, 255, 0.03));
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.media-upload-area::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    transition: left 0.5s;
}

.media-upload-area:hover {
    border-color: #23efff;
    background: linear-gradient(135deg, rgba(0, 127, 255, 0.1), rgba(35, 239, 255, 0.05));
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 127, 255, 0.2);
}

.media-upload-area:hover::before {
    left: 100%;
}

.upload-icon {
    font-size: 3rem;
    color: #007fff;
    margin-bottom: 1rem;
    display: block;
}

.upload-text {
    font-size: 1.1rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.upload-hint {
    font-size: 0.9rem;
    color: #6c757d;
    margin: 0;
}

.media-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 1rem;
}

.media-preview-item {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.media-preview-item img {
    width: 100px;
    height: 100px;
    object-fit: cover;
}

.media-preview-remove {
    position: absolute;
    top: 5px;
    right: 5px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.progress-container {
    display: none;
    margin-top: 1rem;
}

.progress {
    height: 8px;
    border-radius: 10px;
    background: #e9ecef;
}

.progress-bar {
    background: linear-gradient(135deg, #007fff, #23efff);
    border-radius: 10px;
}

/* Form Validation */
.field-error {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: none;
    padding: 0.5rem 0.75rem;
    background: rgba(220, 53, 69, 0.1);
    border-radius: 8px;
    border-left: 4px solid #dc3545;
}

.field-success {
    color: #28a745;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: none;
    padding: 0.5rem 0.75rem;
    background: rgba(40, 167, 69, 0.1);
    border-radius: 8px;
    border-left: 4px solid #28a745;
}

.form-control.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
}

.form-control.is-valid {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
}

/* Media Type Checkboxes */
.media-type-checkbox {
    position: relative;
    margin-bottom: 1.5rem;
}

.media-type-checkbox input[type="checkbox"] {
    display: none;
}

.media-type-label {
    display: flex;
    align-items: center;
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    gap: 1rem;
}

.media-type-label:hover {
    background: rgba(0, 127, 255, 0.05);
    border-color: #007fff;
    transform: translateY(-1px);
}

.media-type-checkbox input[type="checkbox"]:checked + .media-type-label {
    background: linear-gradient(135deg, rgba(0, 127, 255, 0.1), rgba(35, 239, 255, 0.05));
    border-color: #007fff;
    color: #007fff;
}

.media-type-icon {
    font-size: 1.5rem;
    color: #007fff;
}

.media-section {
    display: none;
    margin-top: 1.5rem;
    padding: 1.5rem;
    background: rgba(0, 127, 255, 0.02);
    border-radius: 15px;
    border: 1px solid rgba(0, 127, 255, 0.1);
}

.media-section.active {
    display: block;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Action Buttons */
.form-actions {
    background: linear-gradient(135deg, rgba(0, 127, 255, 0.05), rgba(35, 239, 255, 0.03));
    padding: 2rem;
    border-radius: 20px;
    border: 1px solid rgba(0, 127, 255, 0.1);
    text-align: center;
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

/* Responsive Design */
@media (max-width: 768px) {
    .admin-header {
        padding: 1.5rem;
        text-align: center;
    }
    
    .section-body {
        padding: 1.5rem;
    }
    
    .media-upload-area {
        padding: 2rem 1rem;
    }
    
    .upload-icon {
        font-size: 2.5rem;
    }
    
    .form-actions {
        padding: 1.5rem;
    }
    
    .btn-voltronix, .btn-secondary {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}

/* Features Container Styling */
.features-container {
    background: rgba(0, 127, 255, 0.02);
    border-radius: 15px;
    padding: 1.5rem;
    border: 1px solid rgba(0, 127, 255, 0.1);
    margin-bottom: 1rem;
}

.feature-item {
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.input-group-text {
    background: linear-gradient(135deg, rgba(0, 127, 255, 0.1), rgba(35, 239, 255, 0.05));
    border: 2px solid #e9ecef;
    border-right: none;
    color: #495057;
    font-weight: 600;
}

.input-group .form-control {
    border-left: none;
    border-right: none;
}

.input-group .btn {
    border-left: none;
}

/* Enhanced Error Styling */
.field-error {
    background: linear-gradient(135deg, rgba(231, 76, 60, 0.1), rgba(231, 76, 60, 0.05));
    border-left: 4px solid #e74c3c;
    color: #c0392b;
    font-weight: 600;
}

.field-success {
    background: linear-gradient(135deg, rgba(39, 174, 96, 0.1), rgba(39, 174, 96, 0.05));
    border-left: 4px solid #27ae60;
    color: #229954;
    font-weight: 600;
}

/* Price and Status Fields */
.price-fields {
    background: rgba(0, 127, 255, 0.02);
    border-radius: 15px;
    padding: 2rem;
    border: 1px solid rgba(0, 127, 255, 0.1);
}

.status-field {
    background: rgba(46, 204, 113, 0.05);
    border-radius: 15px;
    padding: 1.5rem;
    border: 1px solid rgba(46, 204, 113, 0.2);
}

/* RTL Support */
[dir="rtl"] .nav-tabs {
    direction: rtl;
}

[dir="rtl"] .section-title {
    flex-direction: row-reverse;
}

[dir="rtl"] .form-label {
    flex-direction: row-reverse;
}

[dir="rtl"] .input-group-text {
    border-right: 2px solid #e9ecef;
    border-left: none;
}

[dir="rtl"] .input-group .form-control {
    border-right: none;
    border-left: none;
}

[dir="rtl"] .input-group .btn {
    border-right: none;
}

[dir="rtl"] .media-type-label {
    flex-direction: row-reverse;
}

.form-control.is-invalid, .form-select.is-invalid {
    border-color: #dc3545;
}

.form-control.is-valid, .form-select.is-valid {
    border-color: #28a745;
}

.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.loading-spinner {
    width: 50px;
    height: 50px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #007fff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="admin-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h4 class="mb-1">{{ __('admin.product.create') }}</h4>
            <p class="mb-0">Create a new digital product with advanced media options</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
            {{ __('admin.back') }}
        </a>
    </div>
</div>
<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="text-center text-white">
        <div class="loading-spinner mb-3"></div>
        <h5>Creating Product...</h5>
        <p>Please wait while we process your request</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" id="productForm" novalidate>
            @csrf
            
            {{-- Global Error Display --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div>
                            <strong>{{ __('admin.validation.errors_found') }}</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>{{ session('error') }}</strong>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
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
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                   value="{{ old('slug') }}" placeholder="Auto-generated from English name">
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
                                <span>English</span>
                                <i class="bi bi-check-circle {{ app()->getLocale() == 'ar' ? 'me-2' : 'ms-2' }}" style="opacity: 0.7;"></i>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="arabic-tab" data-bs-toggle="tab" 
                                    data-bs-target="#arabic" type="button" role="tab">
                                <i class="bi bi-flag {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                <span>العربية</span>
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
                                           value="{{ old('name_en') }}" required id="nameEn" 
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
                                              placeholder="Enter detailed product description in English">{{ old('description_en') }}</textarea>
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
                                        <div class="feature-item mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-star text-warning"></i>
                                                </span>
                                                <input type="text" name="features_en[]" class="form-control" 
                                                       placeholder="Enter product feature in English">
                                                <button type="button" class="btn btn-outline-danger" onclick="removeFeature(this)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary" onclick="addFeature('featuresEn', 'features_en[]')">
                                        <i class="bi bi-plus-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                        Add Feature
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Arabic Content -->
                        <div class="tab-pane fade" id="arabic" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="bi bi-type"></i>
                                        Arabic Name
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" 
                                           value="{{ old('name_ar') }}" required dir="rtl"
                                           placeholder="أدخل اسم المنتج بالعربية">
                                    @error('name_ar')
                                        <div class="field-error" style="display: block;">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="bi bi-card-text"></i>
                                        Arabic Description
                                    </label>
                                    <textarea name="description_ar" rows="5" 
                                              class="form-control @error('description_ar') is-invalid @enderror" 
                                              dir="rtl"
                                              placeholder="أدخل وصف المنتج التفصيلي بالعربية">{{ old('description_ar') }}</textarea>
                                    @error('description_ar')
                                        <div class="field-error" style="display: block;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="bi bi-list-stars"></i>
                                        Arabic Features
                                    </label>
                                    <div id="featuresAr" class="features-container">
                                        <div class="feature-item mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-star text-warning"></i>
                                                </span>
                                                <input type="text" name="features_ar[]" class="form-control" 
                                                       placeholder="أدخل ميزة المنتج بالعربية" dir="rtl">
                                                <button type="button" class="btn btn-outline-danger" onclick="removeFeature(this)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary" onclick="addFeature('featuresAr', 'features_ar[]')">
                                        <i class="bi bi-plus-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                        إضافة ميزة
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
                                       value="{{ old('price') }}" required>
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
                                       value="{{ old('discount_price') }}">
                            </div>
                            @error('discount_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">{{ __('admin.product.status') }} <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>
                                    {{ __('admin.product.available') }}
                                </option>
                                <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>
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
                                               id="isFeatured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isFeatured">
                                            <i class="bi bi-star me-1"></i> Featured Product
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_new" 
                                               id="isNew" value="1" {{ old('is_new') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isNew">
                                            <i class="bi bi-lightning me-1"></i> New Product
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Sort Order</label>
                                    <input type="number" name="sort_order" min="0" 
                                           class="form-control @error('sort_order') is-invalid @enderror" 
                                           value="{{ old('sort_order', 0) }}">
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
                    <!-- Thumbnail -->
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label">Product Thumbnail <span class="text-danger">*</span></label>
                            <div class="media-upload-area" onclick="document.getElementById('thumbnail').click()">
                                <i class="bi bi-cloud-upload text-primary" style="font-size: 2rem;"></i>
                                <h6 class="mt-2">Click to upload thumbnail</h6>
                                <p class="text-muted mb-0">JPG, PNG, GIF up to 2MB</p>
                            </div>
                            <input type="file" name="thumbnail" id="thumbnail" accept=".jpg,.jpeg,.png,.gif" 
                                   class="d-none @error('thumbnail') is-invalid @enderror">
                            <div class="field-error" id="thumbnail-error"></div>
                            @error('thumbnail')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="thumbnailPreview" class="media-preview"></div>
                        </div>
                    </div>

                    <!-- Media Type Selection -->
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label">Select Media Types to Add</label>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="enableGallery" name="enable_gallery">
                                        <label class="form-check-label" for="enableGallery">
                                            <i class="bi bi-images me-2"></i>Image Gallery
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="enableBeforeAfter" name="enable_before_after">
                                        <label class="form-check-label" for="enableBeforeAfter">
                                            <i class="bi bi-arrow-left-right me-2"></i>Before/After
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="enableVideo" name="enable_video">
                                        <label class="form-check-label" for="enableVideo">
                                            <i class="bi bi-play-circle me-2"></i>Video Content
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="enableYoutube" name="enable_youtube">
                                        <label class="form-check-label" for="enableYoutube">
                                            <i class="bi bi-youtube me-2"></i>YouTube Video
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gallery Images Section -->
                    <div id="gallerySection" class="media-section" style="display: none;">
                        <h6 class="text-primary mb-3"><i class="bi bi-images me-2"></i>Image Gallery</h6>
                        <div class="media-upload-area" onclick="document.getElementById('galleryImages').click()">
                            <i class="bi bi-images text-primary" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Upload Gallery Images</h6>
                            <p class="text-muted mb-0">Select multiple images (up to 10)</p>
                        </div>
                        <input type="file" name="gallery_images[]" id="galleryImages" multiple accept=".jpg,.jpeg,.png,.gif" class="d-none">
                        <div class="field-error" id="gallery-error"></div>
                        <div id="galleryPreview" class="media-preview"></div>
                        <div class="progress-container" id="galleryProgress">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Before/After Images Section -->
                    <div id="beforeAfterSection" class="media-section" style="display: none;">
                        <h6 class="text-primary mb-3"><i class="bi bi-arrow-left-right me-2"></i>Before/After Images</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Before Image</label>
                                <div class="media-upload-area" onclick="document.getElementById('beforeImage').click()">
                                    <i class="bi bi-arrow-left text-warning" style="font-size: 1.5rem;"></i>
                                    <p class="mb-0 mt-2">Upload Before Image</p>
                                </div>
                                <input type="file" name="before_image" id="beforeImage" accept=".jpg,.jpeg,.png,.gif" class="d-none">
                                <div id="beforePreview" class="media-preview"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">After Image</label>
                                <div class="media-upload-area" onclick="document.getElementById('afterImage').click()">
                                    <i class="bi bi-arrow-right text-success" style="font-size: 1.5rem;"></i>
                                    <p class="mb-0 mt-2">Upload After Image</p>
                                </div>
                                <input type="file" name="after_image" id="afterImage" accept=".jpg,.jpeg,.png,.gif" class="d-none">
                                <div id="afterPreview" class="media-preview"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Video Upload Section -->
                    <div id="videoSection" class="media-section" style="display: none;">
                        <h6 class="text-primary mb-3"><i class="bi bi-play-circle me-2"></i>Video Upload</h6>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <div class="media-upload-area" onclick="document.getElementById('videoFile').click()">
                                    <i class="bi bi-camera-video text-primary" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Upload Video File</h6>
                                    <p class="text-muted mb-0">MP4, AVI, MOV up to 50MB</p>
                                </div>
                                <input type="file" name="video_file" id="videoFile" accept="video/*" class="d-none">
                                <div class="field-error" id="video-error"></div>
                                <div id="videoPreview" class="media-preview"></div>
                                <div class="progress-container" id="videoProgress">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Video Poster (Optional)</label>
                                <div class="media-upload-area" onclick="document.getElementById('videoPoster').click()">
                                    <i class="bi bi-image text-info" style="font-size: 1.5rem;"></i>
                                    <p class="mb-0 mt-2">Upload Poster</p>
                                </div>
                                <input type="file" name="video_poster" id="videoPoster" accept=".jpg,.jpeg,.png,.gif" class="d-none">
                                <div id="posterPreview" class="media-preview"></div>
                            </div>
                        </div>
                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label class="form-label">Video Title</label>
                                <input type="text" name="video_title" class="form-control" placeholder="Enter video title">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Video Description</label>
                                <input type="text" name="video_description" class="form-control" placeholder="Enter video description">
                            </div>
                        </div>
                    </div>

                    <!-- YouTube Video Section -->
                    <div id="youtubeSection" class="media-section" style="display: none;">
                        <h6 class="text-primary mb-3"><i class="bi bi-youtube me-2"></i>YouTube Video</h6>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">YouTube URL</label>
                                <input type="url" name="youtube_url" id="youtubeUrl" class="form-control" 
                                       placeholder="https://www.youtube.com/watch?v=...">
                                <div class="field-error" id="youtube-error"></div>
                            </div>
                        </div>
                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label class="form-label">Video Title</label>
                                <input type="text" name="youtube_title" class="form-control" placeholder="Enter video title">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Video Description</label>
                                <input type="text" name="youtube_description" class="form-control" placeholder="Enter video description">
                            </div>
                        </div>
                        <div id="youtubePreview" class="mt-3"></div>
                    </div>
                </div>
            </div>


            <!-- Delivery Configuration -->
            <div class="form-section">
                <div class="section-header">
                    <h5 class="section-title">
                        <i class="bi bi-send"></i>
                        {{ __('admin.product.delivery_settings') }}
                    </h5>
                </div>
                <div class="section-body">
                    {{-- Automation Enable Toggle --}}
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       id="auto_delivery_enabled" name="auto_delivery_enabled" 
                                       value="1" {{ old('auto_delivery_enabled', false) ? 'checked' : '' }}
                                       onchange="toggleDeliveryConfig()">
                                <label class="form-check-label" for="auto_delivery_enabled">
                                    {{ __('admin.product.enable_auto_delivery') }}
                                </label>
                                <small class="form-text text-muted d-block">
                                    {{ __('admin.product.auto_delivery_help') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- Delivery Configuration --}}
                    <div id="delivery-config" style="display: {{ old('auto_delivery_enabled', false) ? 'block' : 'none' }};">
                        
                        {{-- Delivery Type Info --}}
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label">{{ __('admin.product.delivery_type') }}</label>
                            <div class="col-md-9">
                                <div class="alert alert-info d-flex align-items-center mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <div>
                                        <strong>{{ __('admin.product.file_delivery') }}</strong>
                                        <small class="d-block text-muted">
                                            {{ __('admin.product.auto_delivery_file_only') }}
                                        </small>
                                    </div>
                                </div>
                                {{-- Hidden input to ensure the value is sent --}}
                                <input type="hidden" name="delivery_type" value="file">
                            </div>
                        </div>

                        {{-- File Delivery Fields --}}
                        <div id="file-delivery-fields">
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">{{ __('admin.product.delivery_file') }} <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="file" class="form-control" name="delivery_file" accept=".zip,.rar,.pdf,.exe,.dmg" required>
                                    <small class="form-text text-muted">
                                        {{ __('admin.product.delivery_file_help') }}
                                    </small>
                                    @error('delivery_file')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">{{ __('admin.product.max_downloads') }}</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" name="default_max_downloads" 
                                           value="{{ old('default_max_downloads', 5) }}" 
                                           min="1" max="100">
                                    <small class="form-text text-muted">{{ __('admin.product.max_downloads_help') }}</small>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Credentials Delivery Fields --}}
                        <div id="credentials-delivery-fields" style="display: {{ old('delivery_type') == 'credentials' ? 'block' : 'none' }};">
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">{{ __('admin.product.username') }} <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="default_username" 
                                           value="{{ old('default_username') }}" placeholder="{{ __('admin.product.username_placeholder') }}">
                                    @error('default_username')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">{{ __('admin.product.password') }} <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="default_password" 
                                           value="{{ old('default_password') }}" placeholder="{{ __('admin.product.password_placeholder') }}">
                                    @error('default_password')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">{{ __('admin.product.notes') }}</label>
                                <div class="col-md-9">
                                    <textarea class="form-control" name="credential_notes" rows="3" 
                                              placeholder="{{ __('admin.product.notes_placeholder') }}">{{ old('credential_notes') }}</textarea>
                                    <small class="form-text text-muted">{{ __('admin.product.notes_help') }}</small>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">{{ __('admin.product.max_views') }}</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" name="default_max_views" 
                                           value="{{ old('default_max_views', 10) }}" 
                                           min="1" max="100">
                                    <small class="form-text text-muted">{{ __('admin.product.max_views_help') }}</small>
                                </div>
                            </div>
                        </div>
                        
                        {{-- License Delivery Fields --}}
                        <div id="license-delivery-fields" style="display: {{ old('delivery_type') == 'license' ? 'block' : 'none' }};">
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">{{ __('admin.product.license_key') }} <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="default_license_key" 
                                           value="{{ old('default_license_key') }}" 
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
                                              placeholder="{{ __('admin.product.license_notes_placeholder') }}">{{ old('license_instructions') }}</textarea>
                                    <small class="form-text text-muted">{{ __('admin.product.license_notes_help') }}</small>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Common Settings --}}
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label">{{ __('admin.product.expiration_days') }}</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" name="default_expiration_days" 
                                       value="{{ old('default_expiration_days', 30) }}" 
                                       min="1" max="365">
                                <small class="form-text text-muted">{{ __('admin.product.expiration_days_help') }}</small>
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
                    <button type="button" class="btn btn-outline-primary me-2" onclick="previewProduct()">
                        <i class="bi bi-eye me-2"></i>
                        Preview
                    </button>
                    <button type="submit" class="btn btn-voltronix">
                        <i class="bi bi-check-circle me-2"></i>
                        Create Product
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Modern Voltronix Loading Overlay -->
    <div id="loadingOverlay" class="voltronix-loading-overlay">
        <div class="loading-content">
            <div class="loading-spinner">
                <div class="spinner-ring"></div>
                <div class="spinner-ring"></div>
                <div class="spinner-ring"></div>
            </div>
            <div class="loading-text">
                <h4 class="loading-title">{{ __('admin.product.creating_product') }}</h4>
                <p class="loading-subtitle">{{ __('admin.product.please_wait') }}</p>
            </div>
        </div>
    </div>

    <style>
    .voltronix-loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(26, 26, 26, 0.85);
        backdrop-filter: blur(10px);
        z-index: 10000;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease-out;
    }

    .loading-content {
        background: linear-gradient(145deg, #ffffff, #f8f9fa);
        border: 2px solid rgba(0, 127, 255, 0.2);
        border-radius: 25px;
        padding: 3rem 2.5rem;
        text-align: center;
        box-shadow: 
            0 20px 60px rgba(0, 127, 255, 0.15),
            0 10px 30px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(20px);
        max-width: 400px;
        width: 90%;
        position: relative;
        overflow: hidden;
    }

    .loading-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(0, 127, 255, 0.1), transparent);
        animation: shimmer 2s infinite;
    }

    .loading-spinner {
        position: relative;
        width: 80px;
        height: 80px;
        margin: 0 auto 2rem;
    }

    .spinner-ring {
        position: absolute;
        width: 100%;
        height: 100%;
        border: 3px solid transparent;
        border-radius: 50%;
        animation: spin 1.5s linear infinite;
    }

    .spinner-ring:nth-child(1) {
        border-top-color: #007fff;
        animation-delay: 0s;
    }

    .spinner-ring:nth-child(2) {
        border-right-color: #23efff;
        animation-delay: -0.5s;
        width: 70%;
        height: 70%;
        top: 15%;
        left: 15%;
    }

    .spinner-ring:nth-child(3) {
        border-bottom-color: #007fff;
        animation-delay: -1s;
        width: 40%;
        height: 40%;
        top: 30%;
        left: 30%;
    }

    .loading-title {
        font-family: 'Orbitron', sans-serif;
        font-weight: 700;
        font-size: 1.5rem;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, #007fff, #23efff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .loading-subtitle {
        color: #6c757d;
        font-size: 1rem;
        margin: 0;
        font-weight: 400;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    /* RTL Support */
    [dir="rtl"] .loading-content::before {
        animation: shimmerRTL 2s infinite;
    }

    @keyframes shimmerRTL {
        0% { right: -100%; left: auto; }
        100% { right: 100%; left: auto; }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .loading-content {
            padding: 2rem 1.5rem;
            border-radius: 20px;
        }
        
        .loading-spinner {
            width: 60px;
            height: 60px;
            margin-bottom: 1.5rem;
        }
        
        .loading-title {
            font-size: 1.25rem;
        }
        
        .loading-subtitle {
            font-size: 0.9rem;
        }
    }
    </style>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize advanced media system
    initializeMediaSystem();
    
    // Auto-generate slug from English name
    const nameEn = document.getElementById('nameEn');
    const slugField = document.querySelector('input[name="slug"]');
    
    nameEn.addEventListener('input', function() {
        if (!slugField.dataset.manual) {
            slugField.value = generateSlug(this.value);
        }
        validateField('name_en', this.value);
    });
    
    slugField.addEventListener('input', function() {
        this.dataset.manual = 'true';
        validateField('slug', this.value);
    });
    
    // Real-time validation for critical fields
    document.querySelector('input[name="name_ar"]').addEventListener('input', function() {
        validateField('name_ar', this.value);
    });
    
    document.querySelector('input[name="price"]').addEventListener('input', function() {
        validateField('price', this.value);
    });
    
    // Initialize image format validation
    initializeImageValidation();
    
    // Form submission with enhanced validation
    document.getElementById('productForm').addEventListener('submit', function(e) {
        e.preventDefault();
        handleFormSubmission();
    });
    
    // Hide loading overlay on page unload (in case of redirect or navigation)
    window.addEventListener('beforeunload', function() {
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) {
            loadingOverlay.style.display = 'none';
        }
    });
});

function initializeImageValidation() {
    // Add event listeners to all image upload inputs
    const imageInputs = [
        'thumbnail',
        'galleryImages', 
        'beforeImage',
        'afterImage',
        'videoPoster'
    ];
    
    imageInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('change', function() {
                validateImageFormat(this);
            });
        }
    });
}

function initializeMediaSystem() {
    // Media type toggles
    const toggles = ['enableGallery', 'enableBeforeAfter', 'enableVideo', 'enableYoutube'];
    
    toggles.forEach(toggleId => {
        document.getElementById(toggleId).addEventListener('change', function() {
            toggleMediaSection(toggleId, this.checked);
        });
    });
    
    // File upload handlers
    setupFileUpload('thumbnail', 'thumbnailPreview', false);
    setupFileUpload('galleryImages', 'galleryPreview', true);
    setupFileUpload('beforeImage', 'beforePreview', false);
    setupFileUpload('afterImage', 'afterPreview', false);
    setupFileUpload('videoFile', 'videoPreview', false);
    setupFileUpload('videoPoster', 'posterPreview', false);
    
    // YouTube URL validation
    document.getElementById('youtubeUrl').addEventListener('input', function() {
        validateYouTubeUrl(this.value);
    });
}

function toggleMediaSection(toggleId, enabled) {
    const sectionMap = {
        'enableGallery': 'gallerySection',
        'enableBeforeAfter': 'beforeAfterSection',
        'enableVideo': 'videoSection',
        'enableYoutube': 'youtubeSection'
    };
    
    const section = document.getElementById(sectionMap[toggleId]);
    if (section) {
        section.style.display = enabled ? 'block' : 'none';
    }
}

function setupFileUpload(inputId, previewId, multiple = false) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    
    if (!input || !preview) return;
    
    input.addEventListener('change', function(e) {
        const files = multiple ? Array.from(e.target.files) : [e.target.files[0]];
        
        if (files[0]) {
            preview.innerHTML = '';
            
            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    createImagePreview(file, preview, inputId, index);
                } else if (file.type.startsWith('video/')) {
                    createVideoPreview(file, preview, inputId);
                }
            });
        }
    });
}

function createImagePreview(file, container, inputId, index = 0) {
    const reader = new FileReader();
    
    reader.onload = function(e) {
        const div = document.createElement('div');
        div.className = 'media-preview-item';
        div.innerHTML = `
            <img src="${e.target.result}" alt="Preview">
            <button type="button" class="media-preview-remove" onclick="removePreview(this, '${inputId}', ${index})">
                <i class="bi bi-x"></i>
            </button>
        `;
        container.appendChild(div);
    };
    
    reader.readAsDataURL(file);
}

function createVideoPreview(file, container, inputId) {
    const div = document.createElement('div');
    div.className = 'media-preview-item';
    div.innerHTML = `
        <div class="video-preview">
            <i class="bi bi-play-circle" style="font-size: 3rem; color: #007fff;"></i>
            <p class="mb-0 mt-2">${file.name}</p>
            <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
        </div>
        <button type="button" class="media-preview-remove" onclick="removePreview(this, '${inputId}')">
            <i class="bi bi-x"></i>
        </button>
    `;
    container.appendChild(div);
}

function removePreview(button, inputId, index = null) {
    const input = document.getElementById(inputId);
    const previewItem = button.closest('.media-preview-item');
    
    previewItem.remove();
    
    // Clear the input
    input.value = '';
}

function validateYouTubeUrl(url) {
    const errorDiv = document.getElementById('youtube-error');
    const previewDiv = document.getElementById('youtubePreview');
    
    if (!url) {
        errorDiv.style.display = 'none';
        previewDiv.innerHTML = '';
        return;
    }
    
    const youtubeRegex = /^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+/;
    
    if (!youtubeRegex.test(url)) {
        showFieldError('youtube-error', 'Please enter a valid YouTube URL');
        previewDiv.innerHTML = '';
        return;
    }
    
    hideFieldError('youtube-error');
    
    // Extract video ID and show preview
    const videoId = extractYouTubeId(url);
    if (videoId) {
        previewDiv.innerHTML = `
            <div class="youtube-preview">
                <iframe width="100%" height="200" src="https://www.youtube.com/embed/${videoId}" 
                        frameborder="0" allowfullscreen></iframe>
            </div>
        `;
    }
}

function extractYouTubeId(url) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);
    return (match && match[2].length === 11) ? match[2] : null;
}

async function validateField(field, value) {
    try {
        const response = await fetch('{{ route("admin.products.validate-field") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                field: field,
                value: value
            })
        });
        
        const result = await response.json();
        const input = document.querySelector(`input[name="${field}"]`);
        
        if (result.valid) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            hideFieldError(field + '-error');
        } else {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            showFieldError(field + '-error', result.message);
        }
    } catch (error) {
        console.error('Validation error:', error);
    }
}

function showFieldError(errorId, message) {
    const errorDiv = document.getElementById(errorId);
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    }
}

function hideFieldError(errorId) {
    const errorDiv = document.getElementById(errorId);
    if (errorDiv) {
        errorDiv.style.display = 'none';
    }
}

async function handleFormSubmission() {
    // Show loading overlay
    const loadingOverlay = document.getElementById('loadingOverlay');
    const submitButton = document.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;
    
    loadingOverlay.style.display = 'flex';
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> {{ __("admin.product.creating_product") }}...';
    
    // Clear previous validation errors
    clearValidationErrors();
    
    // Basic client-side validation
    const form = document.getElementById('productForm');
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    let validationErrors = [];
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('is-invalid');
            const label = field.closest('.row')?.querySelector('label')?.textContent?.replace('*', '').trim() || field.name;
            validationErrors.push(`${label} is required`);
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    // Delivery-specific validation - check current toggle state
    const autoDeliveryEnabled = document.getElementById('auto_delivery_enabled').checked;
    if (autoDeliveryEnabled) {
        // Auto delivery only supports file delivery - validate file upload
        const deliveryFile = document.querySelector('input[name="delivery_file"]');
        if (!deliveryFile || !deliveryFile.files.length) {
            isValid = false;
            validationErrors.push('Delivery file is required when auto delivery is enabled');
            if (deliveryFile) {
                deliveryFile.classList.add('is-invalid');
            }
        }
    } else {
        // Auto delivery is disabled - clear any delivery file validation errors
        const deliveryFile = document.querySelector('input[name="delivery_file"]');
        if (deliveryFile) {
            deliveryFile.classList.remove('is-invalid');
        }
    }
    
    if (!isValid) {
        hideLoadingAndRestore(loadingOverlay, submitButton, originalButtonText);
        
        Swal.fire({
            title: '{{ __("admin.validation.errors_found") }}',
            html: '<ul style="text-align: left; margin: 0;">' + validationErrors.map(error => `<li>${error}</li>`).join('') + '</ul>',
            icon: 'error',
            confirmButtonColor: '#007fff',
            width: '500px'
        });
        return;
    }
    
    // Submit form via AJAX
    try {
        const formData = new FormData(form);
        
        // Debug: Log form data
        console.log('Form action:', form.action);
        console.log('Form data entries:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (response.ok) {
            // Success - redirect to products index
            Swal.fire({
                title: '{{ __("admin.product.success") }}',
                text: '{{ __("admin.product.created_successfully") }}',
                icon: 'success',
                confirmButtonColor: '#007fff',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '{{ route("admin.products.index") }}';
            });
        } else {
            const errorData = await response.json();
            console.log('Error response data:', errorData);
            
            if (response.status === 422) {
                // Validation errors
                displayValidationErrors(errorData.errors);
                hideLoadingAndRestore(loadingOverlay, submitButton, originalButtonText);
                
                Swal.fire({
                    title: '{{ __("admin.product.validation_error") }}',
                    text: '{{ __("admin.product.check_errors_below") }}',
                    icon: 'error',
                    confirmButtonColor: '#007fff'
                });
            } else {
                // Other errors
                hideLoadingAndRestore(loadingOverlay, submitButton, originalButtonText);
                
                Swal.fire({
                    title: '{{ __("admin.product.error") }}',
                    text: errorData.message || '{{ __("admin.product.creation_failed") }}',
                    icon: 'error',
                    confirmButtonColor: '#007fff'
                });
            }
        }
    } catch (error) {
        console.error('Form submission error:', error);
        hideLoadingAndRestore(loadingOverlay, submitButton, originalButtonText);
        
        Swal.fire({
            title: '{{ __("admin.product.error") }}',
            text: '{{ __("admin.product.network_error") }}',
            icon: 'error',
            confirmButtonColor: '#007fff'
        });
    }
}

function hideLoadingAndRestore(loadingOverlay, submitButton, originalButtonText) {
    loadingOverlay.style.display = 'none';
    submitButton.disabled = false;
    submitButton.innerHTML = originalButtonText;
}

function clearValidationErrors() {
    // Remove all validation error classes and messages
    document.querySelectorAll('.is-invalid').forEach(field => {
        field.classList.remove('is-invalid');
    });
    
    document.querySelectorAll('.invalid-feedback').forEach(feedback => {
        feedback.style.display = 'none';
    });
}

function displayValidationErrors(errors) {
    Object.keys(errors).forEach(fieldName => {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (field) {
            field.classList.add('is-invalid');
            
            // Find or create error message element
            let errorElement = field.parentNode.querySelector('.invalid-feedback');
            if (!errorElement) {
                errorElement = document.createElement('div');
                errorElement.className = 'invalid-feedback';
                field.parentNode.appendChild(errorElement);
            }
            
            errorElement.textContent = errors[fieldName][0];
            errorElement.style.display = 'block';
        }
    });
    
    // Scroll to first error
    const firstError = document.querySelector('.is-invalid');
    if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

function generateSlug(text) {
    return text.toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

function addFeature(containerId, inputName) {
    const container = document.getElementById(containerId);
    const div = document.createElement('div');
    div.className = 'feature-item mb-3';
    
    const isArabic = inputName.includes('_ar');
    const placeholder = isArabic ? 'أدخل ميزة المنتج بالعربية' : 'Enter product feature in English';
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

// Delivery Automation JavaScript
function toggleDeliveryConfig() {
    const enabled = document.getElementById('auto_delivery_enabled').checked;
    const config = document.getElementById('delivery-config');
    config.style.display = enabled ? 'block' : 'none';
    
    // Clear any validation errors when toggling
    clearDeliveryValidationErrors();
    
    // Update required state of delivery file based on toggle
    const deliveryFile = document.querySelector('input[name="delivery_file"]');
    if (deliveryFile) {
        if (enabled) {
            deliveryFile.setAttribute('required', 'required');
        } else {
            deliveryFile.removeAttribute('required');
        }
        // Remove any validation styling
        deliveryFile.classList.remove('is-invalid', 'is-valid');
    }
}

function clearDeliveryValidationErrors() {
    // Clear validation errors from delivery-related fields
    const deliveryFields = document.querySelectorAll('#delivery-config input, #delivery-config select');
    deliveryFields.forEach(field => {
        field.classList.remove('is-invalid', 'is-valid');
    });
    
    // Clear any error messages
    const errorMessages = document.querySelectorAll('#delivery-config .text-danger');
    errorMessages.forEach(error => {
        if (error.classList.contains('small')) {
            error.remove();
        }
    });
}

// Image format validation
function validateImageFormat(input) {
    const allowedFormats = ['jpg', 'jpeg', 'png', 'gif'];
    const files = input.files;
    
    if (!files || files.length === 0) return true;
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const fileName = file.name.toLowerCase();
        const fileExtension = fileName.split('.').pop();
        
        if (!allowedFormats.includes(fileExtension)) {
            // Show error message
            showImageFormatError(input, fileName, fileExtension);
            // Clear the input
            input.value = '';
            return false;
        }
    }
    
    // Clear any existing error messages
    clearImageFormatError(input);
    return true;
}

function showImageFormatError(input, fileName, extension) {
    // Remove existing error
    clearImageFormatError(input);
    
    // Create error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'text-danger small mt-1 image-format-error';
    errorDiv.innerHTML = `<i class="bi bi-exclamation-triangle me-1"></i>Invalid format: "${fileName}" (.${extension}). Only JPG, JPEG, PNG, and GIF files are allowed.`;
    
    // Insert after the input's parent
    const parent = input.closest('.media-upload-area') || input.parentElement;
    parent.parentElement.insertBefore(errorDiv, parent.nextSibling);
    
    // Add invalid styling
    input.classList.add('is-invalid');
    
    // Show SweetAlert notification
    Swal.fire({
        title: 'Invalid Image Format',
        text: `The file "${fileName}" has an unsupported format (.${extension}). Please use JPG, JPEG, PNG, or GIF files only.`,
        icon: 'error',
        confirmButtonColor: '#007fff',
        timer: 5000
    });
}

function clearImageFormatError(input) {
    // Remove error message
    const errorDiv = input.parentElement.parentElement.querySelector('.image-format-error');
    if (errorDiv) {
        errorDiv.remove();
    }
    
    // Remove invalid styling
    input.classList.remove('is-invalid');
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
    
    // Update required fields
    updateRequiredFields(deliveryType);
}

function updateRequiredFields(deliveryType) {
    // Remove all required attributes first
    document.querySelectorAll('[name="delivery_file"], [name="default_username"], [name="default_password"], [name="default_license_key"]').forEach(field => {
        field.removeAttribute('required');
    });
    
    // Add required attributes based on type
    if (deliveryType === 'file') {
        const fileField = document.querySelector('[name="delivery_file"]');
        if (fileField) fileField.setAttribute('required', 'required');
    } else if (deliveryType === 'credentials') {
        const usernameField = document.querySelector('[name="default_username"]');
        const passwordField = document.querySelector('[name="default_password"]');
        if (usernameField) usernameField.setAttribute('required', 'required');
        if (passwordField) passwordField.setAttribute('required', 'required');
    } else if (deliveryType === 'license') {
        const licenseField = document.querySelector('[name="default_license_key"]');
        if (licenseField) licenseField.setAttribute('required', 'required');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleDeliveryConfig();
    toggleDeliveryTypeFields();
});
</script>
@endpush
