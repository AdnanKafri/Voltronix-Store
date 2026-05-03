@extends('layouts.app')

@section('title', $product->getTranslation('name') . ' - ' . __('products.product_details'))
@section('description', $product->getTranslation('description') ?: __('products.product_details'))

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/css/glightbox.min.css" rel="stylesheet">
<style>
    .media-gallery {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }
    
    .gallery-main {
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 1rem;
        background: #f8f9fa;
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    
    .gallery-main:hover {
        transform: scale(1.02);
        box-shadow: 0 10px 25px rgba(0, 127, 255, 0.15);
        transition: all 0.3s ease;
    }
    
    .zoom-indicator {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 0.5rem;
        border-radius: 50%;
        font-size: 1.2rem;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
        z-index: 10;
    }
    
    .gallery-main:hover .zoom-indicator {
        opacity: 1;
    }
    
    [dir="rtl"] .zoom-indicator {
        right: auto;
        left: 15px;
    }
    
    /* Mobile optimizations for image gallery */
    @media (max-width: 768px) {
        .gallery-main {
            min-height: 300px;
        }
        
        .gallery-main img {
            height: 300px;
        }
        
        .zoom-indicator {
            top: 10px;
            right: 10px;
            padding: 0.4rem;
            font-size: 1rem;
        }
        
        [dir="rtl"] .zoom-indicator {
            right: auto;
            left: 10px;
        }
        
        .gallery-thumbnails {
            justify-content: flex-start;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }
        
        .gallery-thumb {
            flex-shrink: 0;
            width: 60px;
            height: 60px;
        }
    }
    
    /* GLightbox customizations */
    .glightbox-clean .gslide-description {
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 1rem;
        border-radius: 8px;
    }
    
    /* Enhanced GLightbox zoom and navigation */
    .glightbox-clean .gslide-image img {
        cursor: zoom-in;
        transition: transform 0.3s ease;
    }
    
    .glightbox-clean .gslide-image img.zoomed {
        cursor: zoom-out;
    }
    
    .glightbox-clean .gnext,
    .glightbox-clean .gprev {
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .glightbox-clean .gnext:hover,
    .glightbox-clean .gprev:hover {
        background: rgba(0, 127, 255, 0.8);
        transform: scale(1.1);
    }
    
    .glightbox-clean .gclose {
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .glightbox-clean .gclose:hover {
        background: rgba(220, 53, 69, 0.8);
        transform: scale(1.1);
    }
    
    /* Ensure zoom indicator appears on hover */
    .gallery-main:hover .zoom-indicator,
    .comparison-image:hover .zoom-indicator {
        opacity: 1;
    }
    
    /* Prevent default link behavior for glightbox elements */
    .glightbox {
        cursor: pointer;
        text-decoration: none !important;
    }
    
    .glightbox:hover {
        text-decoration: none !important;
    }
    
    .gallery-main img, .gallery-main video {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 15px;
    }
    
    .gallery-thumbnails {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .gallery-thumb {
        width: 80px;
        height: 80px;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.3s ease;
    }
    
    .gallery-thumb:hover, .gallery-thumb.active {
        border-color: var(--voltronix-primary);
        transform: scale(1.05);
    }
    
    .gallery-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .before-after-container {
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        height: 400px;
        background: #f8f9fa;
    }
    
    .before-after-slider {
        position: relative;
        width: 100%;
        height: 100%;
    }
    
    .before-image, .after-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .after-image {
        clip-path: polygon(50% 0%, 100% 0%, 100% 100%, 50% 100%);
        transition: clip-path 0.3s ease;
    }
    
    .slider-handle {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 40px;
        height: 40px;
        background: white;
        border: 3px solid var(--voltronix-primary);
        border-radius: 50%;
        cursor: ew-resize;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        z-index: 10;
    }
    
    .slider-line {
        position: absolute;
        top: 0;
        left: 50%;
        width: 3px;
        height: 100%;
        background: var(--voltronix-primary);
        transform: translateX(-50%);
        z-index: 5;
    }
    
    .video-container {
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        background: #000;
    }
    
    .video-container video {
        width: 100%;
        height: auto;
        min-height: 300px;
    }
    
    .youtube-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: 15px;
    }
    
    .youtube-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 15px;
    }
    
    .media-tabs {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .media-tab {
        padding: 0.75rem 1.5rem;
        background: #f8f9fa;
        border: none;
        border-radius: 25px;
        font-weight: 600;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .media-tab.active {
        background: linear-gradient(135deg, var(--voltronix-primary), #23efff);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);
    }
    
    .media-content {
        display: none;
    }
    
    .media-content.active {
        display: block;
    }
    
    .product-info {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }
    
    .product-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--voltronix-accent);
        margin-bottom: 1rem;
        line-height: 1.2;
    }
    
    .product-category {
        display: inline-block;
        background: var(--voltronix-primary);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .product-category:hover {
        background: #0056b3;
        color: white;
        transform: translateY(-2px);
    }
    
    .product-price {
        font-size: 3rem;
        font-weight: 700;
        color: var(--voltronix-primary);
        margin-bottom: 1.5rem;
    }
    
    .price-original {
        text-decoration: line-through;
        color: #6c757d;
        font-size: 1.5rem;
        margin-right: 1rem;
    }
    
    .discount-badge {
        background: #dc3545;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-left: 1rem;
    }
    
    .product-status {
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        margin-bottom: 2rem;
        display: inline-block;
    }
    
    .status-available {
        background: #d4edda;
        color: #155724;
        border: 2px solid #c3e6cb;
    }
    
    .status-unavailable {
        background: #f8d7da;
        color: #721c24;
        border: 2px solid #f5c6cb;
    }
    
    .product-description {
        font-size: 1.1rem;
        line-height: 1.6;
        color: #495057;
        margin-bottom: 2rem;
    }
    
    .product-features {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid rgba(0, 127, 255, 0.16);
        border-radius: 16px;
        padding: 1.25rem 1.25rem 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 6px 18px rgba(0, 127, 255, 0.08);
    }

    .features-title {
        display: flex;
        align-items: center;
        font-family: 'Orbitron', monospace;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--voltronix-accent);
        margin-bottom: 1rem;
    }
    
    .feature-item {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        margin-bottom: 0.7rem;
        padding: 0.45rem 0.25rem;
        border-radius: 10px;
        color: #1f2a37;
        font-size: 1rem;
        line-height: 1.45;
        transition: background-color 0.2s ease;
    }

    .feature-item:hover {
        background: rgba(0, 127, 255, 0.06);
    }
    
    .feature-item:last-child {
        margin-bottom: 0;
    }
    
    .feature-icon {
        color: var(--voltronix-primary);
        font-size: 1.05rem;
        margin-top: 0.1rem;
        flex-shrink: 0;
    }
    
    .btn-add-cart {
        background: linear-gradient(45deg, var(--voltronix-primary), #00d4ff);
        border: none;
        color: white;
        font-weight: 600;
        padding: 1rem 2rem;
        border-radius: 30px;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        position: relative;
        overflow: hidden;
        margin-right: 1rem;
    }
    
    .btn-add-cart:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(13, 110, 253, 0.4);
        color: white;
    }
    
    .btn-add-cart::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn-add-cart:hover::before {
        left: 100%;
    }
    
    .btn-download {
        background: var(--voltronix-accent);
        border: none;
        color: white;
        font-weight: 600;
        padding: 1rem 2rem;
        border-radius: 30px;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-download:hover {
        background: #000000;
        color: white;
        transform: translateY(-3px);
    }
    
    .product-header {
        background: linear-gradient(135deg, var(--voltronix-accent), #000000);
        color: white;
        padding: 2rem 0 1rem;
        margin-top: 0;
    }
    
    .related-products {
        background: var(--voltronix-light);
    }
    
    .related-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .related-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(13, 110, 253, 0.15);
    }
    
    .related-image {
        height: 180px;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .related-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .related-content {
        padding: 1.25rem;
    }
    
    .related-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--voltronix-accent);
        margin-bottom: 0.5rem;
    }
    
    .related-price {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--voltronix-primary);
        margin-bottom: 1rem;
    }
    
    /* Reviews Section Styling */
    .reviews-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0, 127, 255, 0.08);
        border: 1px solid rgba(0, 127, 255, 0.1);
        overflow: hidden;
    }
    
    .reviews-header {
        background: linear-gradient(135deg, rgba(0, 127, 255, 0.05), rgba(35, 239, 255, 0.03));
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(0, 127, 255, 0.1);
    }
    
    .reviews-title {
        font-family: 'Orbitron', monospace;
        font-weight: 700;
        color: var(--voltronix-accent);
        font-size: 1.5rem;
    }
    
    .rating-summary-compact {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .rating-stars-compact {
        font-size: 1.1rem;
    }
    
    .rating-text-compact {
        color: var(--voltronix-primary);
        font-weight: 600;
        font-size: 0.95rem;
    }
    
    .reviews-content {
        padding: 0.25rem 0;
    }

    .product-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    /* Before/After Comparison Styles */
    .before-after-comparison {
        padding: 1rem;
    }
    
    .comparison-image-container {
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .comparison-image-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    .comparison-label {
        position: absolute;
        top: 15px;
        {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 15px;
        z-index: 2;
    }
    
    .comparison-label .badge {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }
    
    .comparison-image img {
        width: 100%;
        height: 300px;
        object-fit: cover;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .comparison-image img:hover {
        transform: scale(1.02);
    }
    
    .comparison-description {
        background: rgba(248, 249, 250, 0.8);
        border-radius: 10px;
        padding: 1rem;
        backdrop-filter: blur(10px);
        margin-top: 1rem;
    }

    @media (max-width: 768px) {
        .product-features {
            padding: 1rem;
        }

        .features-title {
            font-size: 1rem;
        }

        .feature-item {
            font-size: 0.95rem;
        }

        .reviews-header {
            padding: 1.25rem 1.5rem;
        }
        
        .reviews-title {
            font-size: 1.25rem;
        }
        
        .rating-summary-compact {
            flex-direction: column;
            align-items: flex-end;
            gap: 0.25rem;
        }

        .product-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .comparison-image img {
            height: 250px;
        }
        
        .comparison-label {
            top: 10px;
            {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 10px;
        }
        
        .comparison-label .badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Product Header -->
<section class="product-header">
    <div class="volt-container">
        <br>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-voltronix">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">{{ __('app.nav.home') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('categories.index') }}">{{ __('app.categories.title') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('categories.show', $product->category->slug) }}">
                        {{ $product->category->getTranslation('name') }}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $product->getTranslation('name') }}
                </li>
            </ol>
        </nav>
    </div>
</section>

<!-- Product Details -->
<section class="py-5" style="background: var(--voltronix-light);">
    <div class="volt-container">
        <div class="row g-5">
            <!-- Product Media -->
            <div class="col-lg-6">
                @include('products.partials.media')
            </div>
            
            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="product-info">
                    <a href="{{ route('categories.show', $product->category->slug) }}" class="product-category">
                        <i class="bi bi-tag {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                        {{ $product->category->getTranslation('name') }}
                    </a>
                    
                    <h1 class="product-title">{{ $product->getTranslation('name') }}</h1>
                    
                    <x-product-price :product="$product" size="large" />
                    
                    <div class="product-status {{ $product->isAvailable() ? 'status-available' : 'status-unavailable' }}">
                        <i class="bi bi-{{ $product->isAvailable() ? 'check-circle' : 'x-circle' }} {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                        {{ $product->isAvailable() ? __('products.in_stock') : __('products.out_of_stock') }}
                    </div>
                    
                    @if($product->getTranslation('description'))
                        <div class="product-description">
                            {!! nl2br(e($product->getTranslation('description'))) !!}
                        </div>
                    @endif
                    
                    {{-- Product Features --}}
                    @php($localizedFeatures = $product->getTranslationArray('features', app()->getLocale()))
                    @if(!empty($localizedFeatures))
                        <div class="product-features">
                            <h5 class="features-title">
                                <i class="bi bi-star {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('products.features') }}
                            </h5>
                            @foreach($localizedFeatures as $feature)
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill feature-icon"></i>
                                    {{ $feature }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    {{-- Action Buttons --}}
                    <div class="product-actions">
                        @if($product->isAvailable())
                            <button class="btn-add-to-cart" onclick="addToCart({{ $product->id }})">
                                <i class="bi bi-cart-plus {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('products.add_to_cart') }}
                            </button>
                        @endif
                        
                        @if($product->download_link)
                            <a href="{{ $product->download_link }}" 
                               class="btn-download" 
                               target="_blank"
                               rel="noopener">
                                <i class="bi bi-download {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('products.download') }}
                            </a>
                        @endif
                    </div>
                    
                    {{-- Product Meta --}}
                    <div class="product-meta mt-4 pt-4 border-top">
                        <div class="row g-3">
                            <div class="col-6">
                                <small class="text-muted d-block">{{ __('app.categories.title') }}</small>
                                <strong>{{ $product->category->getTranslation('name') }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">{{ __('products.digital_product') }}</small>
                                <strong>{{ __('products.instant_delivery') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Product Reviews & Ratings -->
<section class="py-4" style="background: var(--voltronix-light);">
    <div class="volt-container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="reviews-card">
                    <div class="reviews-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h3 class="reviews-title mb-0">
                                {{ __('products.reviews_ratings') }}
                            </h3>
                            @if($product->reviews_count > 0)
                            <div class="rating-summary-compact">
                                <div class="rating-stars-compact">{!! $product->stars_html !!}</div>
                                <span class="rating-text-compact">{{ number_format($product->average_rating, 1) }} ({{ $product->reviews_count }})</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="reviews-content">
                        @include('products.partials.reviews')
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
@if($relatedProducts->count() > 0)
<section class="related-products py-5">
    <div class="volt-container">
        <h2 class="section-title">{{ __('products.related_products') }}</h2>
        <div class="row g-4">
            @foreach($relatedProducts as $related)
                <div class="col-lg-3 col-md-6">
                    <div class="related-card">
                        <div class="related-image">
                            @if($related->thumbnail)
                                <img src="{{ $related->thumbnail_url }}" 
                                     alt="{{ $related->getTranslation('name') }}" 
                                     loading="lazy">
                            @else
                                <i class="bi bi-box-seam product-icon"></i>
                            @endif
                        </div>
                        
                        <div class="related-content">
                            <h4 class="related-title">{{ Str::limit($related->getTranslation('name'), 50) }}</h4>
                            <div class="related-price">{{ currency_format($related->price) }}</div>
                            <a href="{{ route('products.show', $related->slug) }}" 
                               class="btn btn-outline-primary btn-sm">
                                {{ __('app.common.view') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/js/glightbox.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wait for GLightbox to be available
    function waitForGLightbox() {
        if (typeof GLightbox !== 'undefined') {
            initializeProductPage();
        } else {
            setTimeout(waitForGLightbox, 100);
        }
    }
    
    waitForGLightbox();
});

function initializeProductPage() {
    initializeMediaSystem();
    initializeBeforeAfterSlider();
    initializeScrollAnimations();
    initializeGLightbox();
}

// GLightbox initialization
function initializeGLightbox() {
    // Destroy existing instance if it exists
    if (window.glightboxInstance) {
        window.glightboxInstance.destroy();
    }
    
    // Initialize GLightbox with enhanced zoom and navigation settings
    window.glightboxInstance = GLightbox({
        selector: '.glightbox',
        touchNavigation: true,
        loop: true,
        zoomable: true,
        draggable: true,
        closeOnOutsideClick: true,
        keyboardNavigation: true,
        svg: {
            close: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>',
            next: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9,18 15,12 9,6"></polyline></svg>',
            prev: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15,18 9,12 15,6"></polyline></svg>'
        },
        skin: 'clean',
        moreText: 'See more',
        moreLength: 60,
        slideEffect: 'slide',
        openEffect: 'zoom',
        closeEffect: 'zoom',
        startAt: 0,
        width: '90vw',
        height: '80vh',
        videosWidth: '960px',
        beforeSlideChange: function(prev, current) {
            // Optional: Add any custom behavior before slide change
        },
        afterSlideChange: function(prev, current) {
            // Optional: Add any custom behavior after slide change
        }
    });
    
    // Add manual event listeners to prevent default anchor behavior
    document.querySelectorAll('.glightbox').forEach(function(element) {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        });
    });
}

// Media tab switching
function switchMediaTab(tabName, tabElement) {
    // Remove active class from all tabs and content
    document.querySelectorAll('.media-tab').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.media-content').forEach(content => content.classList.remove('active'));
    
    // Add active class to clicked tab and corresponding content
    tabElement.classList.add('active');
    document.getElementById(tabName + '-content').classList.add('active');
    
    // Reinitialize GLightbox for new content
    setTimeout(() => {
        if (typeof GLightbox !== 'undefined') {
            initializeGLightbox();
        }
    }, 150);
}

// Gallery image switching with improved error handling
function showGalleryImage(imageSrc, title, thumbElement) {
    const mainImageLink = document.querySelector('#galleryMain .glightbox');
    const mainImage = document.querySelector('#galleryMain .main-image');
    
    // Error handling - ensure elements exist
    if (!mainImageLink || !mainImage) {
        console.error('Gallery elements not found:', {
            mainImageLink: !!mainImageLink,
            mainImage: !!mainImage
        });
        return;
    }
    
    // Add loading state with smooth transition
    mainImage.style.transition = 'opacity 0.3s ease';
    mainImage.style.opacity = '0.5';
    
    // Update the link href and data attributes
    mainImageLink.href = imageSrc;
    mainImageLink.setAttribute('data-title', title);
    
    // Create new image to preload
    const tempImage = new Image();
    tempImage.onload = function() {
        // Update the image src and alt
        mainImage.src = imageSrc;
        mainImage.alt = title;
        
        // Restore opacity
        mainImage.style.opacity = '1';
    };
    
    tempImage.onerror = function() {
        console.error('Failed to load image:', imageSrc);
        mainImage.style.opacity = '1';
    };
    
    // Start loading
    tempImage.src = imageSrc;
    
    // Update active thumbnail
    document.querySelectorAll('.gallery-thumb').forEach(thumb => {
        thumb.classList.remove('active');
    });
    
    if (thumbElement) {
        thumbElement.classList.add('active');
    }
    
    // Reinitialize GLightbox to update the gallery
    if (typeof GLightbox !== 'undefined') {
        setTimeout(() => {
            initializeGLightbox();
        }, 150);
    }
}

// Initialize media system
function initializeMediaSystem() {
    // Media system initialized
}

// Before/After comparison functionality (side-by-side layout)
function initializeBeforeAfterSlider() {
    // Before/After comparison initialized
}

// Scroll animations for related products
function initializeScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe related product cards
    document.querySelectorAll('.related-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
}

// Legacy function for backward compatibility
function showMainImage(imageSrc, thumbElement) {
    showGalleryImage(imageSrc, 'Product Image', thumbElement);
}
</script>
@endpush

