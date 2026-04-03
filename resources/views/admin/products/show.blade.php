@extends('admin.layouts.app')

@section('title', 'View Product')
@section('page-title', 'Product Details: ' . $product->getTranslation('name'))

@push('styles')
<style>
/* Product Show Page Styling */
.admin-header {
    background: linear-gradient(135deg, rgba(0, 127, 255, 0.08), rgba(35, 239, 255, 0.04));
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid rgba(0, 127, 255, 0.1);
    box-shadow: 0 8px 25px rgba(0, 127, 255, 0.08);
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

.info-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 127, 255, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
}

.card-header-modern {
    background: linear-gradient(135deg, #007fff, #23efff);
    color: white;
    padding: 1.5rem 2rem;
    border: none;
}

.card-title-modern {
    font-family: 'Orbitron', monospace;
    font-weight: 700;
    margin: 0;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.card-body-modern {
    padding: 2rem;
}

.info-item {
    margin-bottom: 1.25rem;
    padding: 1rem;
    background: rgba(0, 127, 255, 0.02);
    border-radius: 12px;
    border-left: 3px solid var(--voltronix-primary);
    transition: all 0.3s ease;
}

.info-item:hover {
    background: rgba(0, 127, 255, 0.04);
    border-left-color: var(--voltronix-secondary);
    transform: translateX(2px);
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-label {
    font-weight: 700;
    color: #2c3e50;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-label i {
    color: #007fff;
    font-size: 1rem;
    padding: 0.25rem;
    background: rgba(0, 127, 255, 0.1);
    border-radius: 6px;
}

.info-value {
    color: #495057;
    font-size: 1rem;
    line-height: 1.6;
    margin: 0;
}

.info-value code {
    background: rgba(0, 127, 255, 0.1);
    color: #007fff;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-family: 'Courier New', monospace;
}

/* Media Cards */
.media-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 127, 255, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: all 0.3s ease;
}

.media-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
}

.media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    padding: 1.5rem;
}

.media-item {
    position: relative;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.media-item:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.media-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    cursor: pointer;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.media-item img:hover {
    transform: scale(1.02);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.media-placeholder {
    width: 100%;
    height: 200px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 2rem;
    border-radius: 15px;
}

/* Before/After Comparison */
.before-after-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    padding: 1.5rem;
}

.comparison-item {
    position: relative;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.comparison-label {
    position: absolute;
    top: 15px;
    left: 15px;
    z-index: 2;
}

.comparison-badge {
    background: linear-gradient(135deg, #007fff, #23efff);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.comparison-badge.before {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.comparison-badge.after {
    background: linear-gradient(135deg, #27ae60, #229954);
}

.comparison-item img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    cursor: pointer;
    transition: all 0.3s ease;
}

.comparison-item:hover img {
    transform: scale(1.02);
}

/* Features List */
.features-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.features-list li {
    display: flex;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(0, 127, 255, 0.1);
    font-size: 1rem;
    color: #495057;
}

.features-list li:last-child {
    border-bottom: none;
}

.features-list li i {
    color: #007fff;
    margin-right: 0.75rem;
    font-size: 1.1rem;
    width: 20px;
    text-align: center;
}

/* Status Badges */
.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.status-available {
    background: linear-gradient(135deg, #27ae60, #229954);
    color: white;
}

.status-unavailable {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
}

.price-display {
    font-size: 2rem;
    font-weight: 700;
    color: #007fff;
    margin: 0;
}

.price-original {
    text-decoration: line-through;
    color: #6c757d;
    font-size: 1.2rem;
    margin-right: 1rem;
}

.discount-badge {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    margin-left: 1rem;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-top: 2rem;
}

.btn-voltronix {
    background: linear-gradient(135deg, #007fff, #23efff);
    border: none;
    color: white;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    border-radius: 15px;
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
    color: white;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    border-radius: 15px;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .admin-header {
        padding: 1.5rem;
        text-align: center;
    }
    
    .card-body-modern {
        padding: 1.5rem;
    }
    
    .before-after-container {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .comparison-item img {
        height: 250px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn-voltronix, .btn-secondary {
        width: 100%;
    }
}

/* RTL Support */
[dir="rtl"] .card-title-modern {
    flex-direction: row-reverse;
}

[dir="rtl"] .info-label {
    flex-direction: row-reverse;
}

[dir="rtl"] .features-list li i {
    margin-right: 0;
    margin-left: 0.75rem;
}

[dir="rtl"] .comparison-label {
    left: auto;
    right: 15px;
}
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="admin-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h4 class="mb-1">{{ __('admin.product.view') }}</h4>
            <p class="mb-0">{{ $product->getTranslation('name') }}</p>
        </div>
        <div class="action-buttons">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-voltronix">
                <i class="bi bi-pencil {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.edit') }}
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.back') }}
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-8">
        <!-- Product Information -->
        <div class="info-card">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="bi bi-info-circle"></i>
                    {{ __('admin.product.information') }}
                </h5>
            </div>
            <div class="card-body-modern">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-type"></i>
                                {{ __('admin.product.name_en') }}
                            </div>
                            <p class="info-value">{{ $product->getTranslation('name', 'en') ?: 'Not provided' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-type"></i>
                                {{ __('admin.product.name_ar') }}
                            </div>
                            <p class="info-value" dir="rtl">{{ $product->getTranslation('name', 'ar') ?: 'غير متوفر' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-folder"></i>
                                {{ __('admin.product.category') }}
                            </div>
                            <p class="info-value">{{ $product->category->getTranslation('name') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-link-45deg"></i>
                                {{ __('admin.product.slug') }}
                            </div>
                            <p class="info-value"><code>{{ $product->slug }}</code></p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-card-text"></i>
                                {{ __('admin.product.description_en') }}
                            </div>
                            <p class="info-value">{{ $product->getTranslation('description', 'en') ?: 'No description provided' }}</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-card-text"></i>
                                {{ __('admin.product.description_ar') }}
                            </div>
                            <p class="info-value" dir="rtl">{{ $product->getTranslation('description', 'ar') ?: 'لا يوجد وصف' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features -->
        @if($product->features)
            <div class="info-card">
                <div class="card-header-modern">
                    <h5 class="card-title-modern">
                        <i class="bi bi-list-stars"></i>
                        {{ __('admin.product.features') }}
                    </h5>
                </div>
                <div class="card-body-modern">
                    <div class="row">
                        @php
                            $featuresEn = $product->getTranslationArray('features', 'en');
                            $featuresAr = $product->getTranslationArray('features', 'ar');
                        @endphp
                        
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-flag"></i>
                                    English Features
                                </div>
                                @if($featuresEn && count($featuresEn) > 0)
                                    <ul class="features-list">
                                        @foreach($featuresEn as $feature)
                                            <li><i class="bi bi-check-circle"></i>{{ $feature }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">No English features provided</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-flag"></i>
                                    Arabic Features
                                </div>
                                @if($featuresAr && count($featuresAr) > 0)
                                    <ul class="features-list" dir="rtl">
                                        @foreach($featuresAr as $feature)
                                            <li><i class="bi bi-check-circle"></i>{{ $feature }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted" dir="rtl">لا توجد ميزات عربية</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Media Information -->
        <div class="media-card">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="bi bi-images"></i>
                    Product Media
                </h5>
            </div>
            
            <!-- Main Thumbnail -->
            @if($product->thumbnail)
                <div class="card-body-modern">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-image"></i>
                            Main Thumbnail
                        </div>
                        <div class="media-item" style="max-width: 300px;">
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                 alt="{{ $product->getTranslation('name') }}"
                                 onclick="openImageModal(this.src)">
                        </div>
                    </div>
                </div>
            @endif

            <!-- Media Type Display -->
            <div class="card-body-modern">
                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-camera"></i>
                        Media Type
                    </div>
                    <span class="status-badge" style="background: linear-gradient(135deg, #6c757d, #495057); color: white;">
                        <i class="bi bi-camera"></i>
                        {{ ucfirst(str_replace('_', ' ', $product->media_type)) }}
                    </span>
                </div>
            </div>

            <!-- Dynamic Media Display -->
            @if($product->media_data)
                @if($product->media_type === 'gallery' && isset($product->media_data['images']))
                    <!-- Gallery Images -->
                    <div class="card-body-modern">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-images"></i>
                                Gallery Images ({{ count($product->media_data['images']) }} images)
                            </div>
                            <div class="media-grid">
                                @foreach($product->media_data['images'] as $image)
                                    <div class="media-item">
                                        <img src="{{ asset('storage/' . $image['path']) }}" 
                                             alt="Gallery Image"
                                             onclick="openImageModal(this.src)">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @elseif($product->media_type === 'before_after')
                    <!-- Before/After Images -->
                    <div class="before-after-container">
                        @if(isset($product->media_data['before_image']))
                            <div class="comparison-item">
                                <div class="comparison-label">
                                    <span class="comparison-badge before">Before</span>
                                </div>
                                <img src="{{ asset('storage/' . $product->media_data['before_image']) }}" 
                                     alt="Before Image"
                                     onclick="openImageModal(this.src)">
                            </div>
                        @endif
                        @if(isset($product->media_data['after_image']))
                            <div class="comparison-item">
                                <div class="comparison-label">
                                    <span class="comparison-badge after">After</span>
                                </div>
                                <img src="{{ asset('storage/' . $product->media_data['after_image']) }}" 
                                     alt="After Image"
                                     onclick="openImageModal(this.src)">
                            </div>
                        @endif
                    </div>
                @elseif($product->media_type === 'video')
                    <!-- Video Information -->
                    <div class="card-body-modern">
                        @if(isset($product->media_data['youtube_url']))
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-youtube"></i>
                                    YouTube Video
                                </div>
                                <p class="info-value">
                                    <a href="{{ $product->media_data['youtube_url'] }}" target="_blank" class="text-primary">
                                        {{ $product->media_data['youtube_url'] }}
                                        <i class="bi bi-box-arrow-up-right ms-1"></i>
                                    </a>
                                </p>
                            </div>
                        @endif
                        @if(isset($product->media_data['video_file']))
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-file-play"></i>
                                    Video File
                                </div>
                                <p class="info-value">
                                    <code>{{ basename($product->media_data['video_file']) }}</code>
                                </p>
                            </div>
                        @endif
                    </div>
                @endif
            @else
                @php
                    $hasGalleryImages = isset($product->media_data['images']) && count($product->media_data['images']) > 0;
                    $hasBeforeAfter = (isset($product->media_data['before_image']) || isset($product->media_data['after_image']));
                    $hasVideoContent = (isset($product->media_data['youtube_url']) || isset($product->media_data['video_file']));
                    $hasAnyMedia = $hasGalleryImages || $hasBeforeAfter || $hasVideoContent;
                @endphp
                @if(!$hasAnyMedia)
                    <div class="card-body-modern text-center py-5">
                        <div class="media-placeholder">
                            <i class="bi bi-image"></i>
                        </div>
                        <p class="text-muted mt-3">No additional media available</p>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Product Status & Pricing -->
        <div class="info-card">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="bi bi-tag"></i>
                    {{ __('admin.product.pricing_flags') }}
                </h5>
            </div>
            <div class="card-body-modern">
                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-check-circle"></i>
                        Product Status
                    </div>
                    @if($product->status === 'available')
                        <span class="status-badge status-available">
                            <i class="bi bi-check-circle"></i>
                            Available
                        </span>
                    @else
                        <span class="status-badge status-unavailable">
                            <i class="bi bi-x-circle"></i>
                            Unavailable
                        </span>
                    @endif
                </div>
                
                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-currency-dollar"></i>
                        Price Information
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        @if($product->discount_price)
                            <span class="price-original">${{ number_format($product->price, 2) }}</span>
                            <span class="price-display">${{ number_format($product->discount_price, 2) }}</span>
                            <span class="discount-badge">{{ number_format((($product->price - $product->discount_price) / $product->price) * 100) }}% OFF</span>
                        @else
                            <span class="price-display">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-flag"></i>
                        Product Flags
                    </div>
                    <div class="d-flex flex-wrap gap-1">
                        @if($product->is_featured)
                            <span class="status-badge" style="background: linear-gradient(135deg, #ffc107, #ff8c00); color: white;">
                                <i class="bi bi-star-fill"></i>
                                Featured
                            </span>
                        @endif
                        @if($product->is_new)
                            <span class="status-badge" style="background: linear-gradient(135deg, #28a745, #20c997); color: white;">
                                <i class="bi bi-lightning-fill"></i>
                                New
                            </span>
                        @endif
                        @if($product->discount_price)
                            <span class="status-badge" style="background: linear-gradient(135deg, #dc3545, #e91e63); color: white;">
                                <i class="bi bi-percent"></i>
                                Sale
                            </span>
                        @endif
                        @if(!$product->is_featured && !$product->is_new && !$product->discount_price)
                            <span class="text-muted">No flags set</span>
                        @endif
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-sort-numeric-up"></i>
                        Sort Order
                    </div>
                    <span class="status-badge" style="background: linear-gradient(135deg, #17a2b8, #138496); color: white;">
                        <i class="bi bi-hash"></i>
                        {{ $product->sort_order }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Download Link -->
        @if($product->download_link)
            <div class="info-card">
                <div class="card-header-modern">
                    <h5 class="card-title-modern">
                        <i class="bi bi-download"></i>
                        {{ __('admin.product.digital_product') }}
                    </h5>
                </div>
                <div class="card-body-modern">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-link-45deg"></i>
                            Product Download
                        </div>
                        <a href="{{ $product->download_link }}" target="_blank" class="btn btn-voltronix w-100">
                            <i class="bi bi-cloud-download {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            Open Download Link
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="info-card">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="bi bi-lightning"></i>
                    {{ __('admin.common.actions') }}
                </h5>
            </div>
            <div class="card-body-modern">
                <div class="d-grid gap-3">
                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-voltronix" target="_blank">
                        <i class="bi bi-eye {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        View on Site
                    </a>
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary">
                        <i class="bi bi-pencil {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        Edit Product
                    </a>
                    <button type="button" class="btn btn-outline-danger" onclick="deleteProduct()">
                        <i class="bi bi-trash {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        Delete Product
                    </button>
                </div>
            </div>
        </div>

        <!-- Metadata -->
        <div class="info-card">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="bi bi-info-square"></i>
                    {{ __('admin.info') }}
                </h5>
            </div>
            <div class="card-body-modern">
                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-calendar-plus"></i>
                        Created
                    </div>
                    <p class="info-value">{{ $product->created_at->format('M d, Y H:i') }}</p>
                </div>
                
                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-calendar-check"></i>
                        Last Updated
                    </div>
                    <p class="info-value">{{ $product->updated_at->format('M d, Y H:i') }}</p>
                </div>
                
                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-hash"></i>
                        Product ID
                    </div>
                    <p class="info-value"><code>{{ $product->id }}</code></p>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <img id="modalImage" src="" class="img-fluid w-100" alt="Product Image">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

function deleteProduct() {
    Swal.fire({
        title: 'Delete Product?',
        text: 'This action cannot be undone. The product and all its media will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.products.destroy", $product) }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
