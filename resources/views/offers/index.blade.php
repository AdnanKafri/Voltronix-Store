@extends('layouts.app')

@section('title', __('app.offers.title') . ' - ' . __('app.hero.title'))
@section('description', __('app.offers.description'))



@section('content')
<!-- Offers Header -->
<section class="offers-header">
    <div class="volt-container">
        {{-- Removed <br> --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-voltronix">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">{{ __('app.nav.home') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ __('app.offers.title') }}
                </li>
            </ol>
        </nav>
        
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="bi bi-fire me-3"></i>{{ __('app.offers.title') }}
                </h1>
                <p class="lead mb-0">{{ __('app.offers.subtitle') }}</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="offer-badge">
                    {{ __('app.offers.limited_time') }}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Offers Content -->
<section class="py-5" style="background: var(--voltronix-light);">
    <div class="volt-container">
        <!-- Special Offers Banner -->
        <div class="special-offers-banner">
            <div class="banner-content">
                <h4 class="mb-2">
                    <i class="bi bi-lightning-charge-fill me-2"></i>
                    {{ __('app.offers.flash_sale') }}
                </h4>
                <p class="mb-0">{{ __('app.offers.flash_sale_description') }}</p>
            </div>
        </div>


        <!-- Filters -->
        <div class="filters-section">
            <form method="GET" action="{{ route('offers.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="filter-label">{{ __('app.common.search') }}</label>
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="{{ __('app.products.search_placeholder') }}"
                           value="{{ request('search') }}">
                </div>
                
                <div class="col-md-3">
                    <label class="filter-label">{{ __('app.products.filters.category') }}</label>
                    <select name="category" class="form-select">
                        <option value="">{{ __('app.common.all') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->getTranslation('name') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label class="filter-label">{{ __('app.products.filters.max_price') }}</label>
                    <input type="number" 
                           name="max_price" 
                           class="form-control" 
                           placeholder="{{ __('app.products.filters.max_price_placeholder') }}"
                           value="{{ request('max_price') }}">
                </div>
                
                <div class="col-md-2">
                    <label class="filter-label">{{ __('app.common.sort_by') }}</label>
                    <select name="sort" class="form-select">
                        <option value="price_low" {{ $sortBy == 'price_low' ? 'selected' : '' }}>
                            {{ __('app.products.sort.price_low') }}
                        </option>
                        <option value="price_high" {{ $sortBy == 'price_high' ? 'selected' : '' }}>
                            {{ __('app.products.sort.price_high') }}
                        </option>
                        <option value="newest" {{ $sortBy == 'newest' ? 'selected' : '' }}>
                            {{ __('app.products.sort.newest') }}
                        </option>
                    </select>
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-voltronix w-100">
                        <i class="bi bi-funnel me-1"></i>
                        {{ __('app.common.filter') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Offers Grid -->
        @if($products->count() > 0)
            <div class="row g-4">
                @foreach($products as $product)
                    <div class="col-lg-4 col-md-6">
                        <div class="offer-card">
                            <div class="offer-image">
                                @if($product->thumbnail)
                                    <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                         alt="{{ $product->getTranslation('name') }}"
                                         loading="lazy">
                                @else
                                    <i class="bi bi-box-seam product-icon"></i>
                                @endif
                                
                                {{-- Always show sale badge for offers page --}}
                                @if($product->hasDiscount())
                                    <x-product-badge type="sale" :discount="$product->discount_percentage" />
                                @else
                                    {{-- Simulated sale badge for demo --}}
                                    <x-product-badge type="sale" :discount="30" />
                                @endif
                            </div>
                            
                            <div class="offer-content">
                                <a href="{{ route('categories.show', $product->category->slug) }}" 
                                   class="offer-category z-raised">
                                    {{ $product->category->getTranslation('name') }}
                                </a>
                                
                                <h3 class="offer-title">{{ $product->getTranslation('name') }}</h3>
                                
                                @if($product->getTranslation('description'))
                                    <p class="offer-description">
                                        {{ Str::limit($product->getTranslation('description'), 100) }}
                                    </p>
                                @endif
                                
                                @if($product->hasDiscount())
                                    <x-product-price :product="$product" />
                                @else
                                    {{-- Simulated discount pricing for demo --}}
                                    <div class="offer-price">
                                        <span class="price-original">{{ currency_format($product->price * 1.4) }}</span>
                                        <span class="price-current">{{ currency_format($product->price) }}</span>
                                        <span class="savings-badge">{{ __('app.offers.save') }} 30%</span>
                                    </div>
                                @endif
                                
                                <div class="d-flex gap-2 mt-3">
                                    @if($product->isAvailable())
                                        <button class="btn-add-to-cart flex-fill z-raised" 
                                                onclick="addToCart({{ $product->id }})">
                                            <i class="bi bi-cart-plus {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                            {{ __('app.offers.grab_deal') }}
                                        </button>
                                    @else
                                        <button class="btn btn-secondary flex-fill" disabled>
                                            {{ __('app.common.out_of_stock') }}
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('products.show', $product->slug) }}" 
                                       class="btn btn-outline-primary stretched-link">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="pagination-container d-flex flex-column align-items-center mt-5">
                    <!-- Controls -->
                    <div class="pagination-controls">
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>
                    
                    <!-- Results Text -->
                    <div class="pagination-info text-muted small mt-2">
                        {{ __('app.common.showing') }} <span class="fw-semibold">{{ $products->firstItem() }}</span> {{ __('app.common.to') }} <span class="fw-semibold">{{ $products->lastItem() }}</span> {{ __('app.common.of') }} <span class="fw-semibold">{{ $products->total() }}</span> {{ __('app.common.results') }}
                    </div>
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="bi bi-tag"></i>
                <h3>{{ __('app.offers.no_offers') }}</h3>
                <p class="mb-4">{{ __('app.offers.check_back_soon') }}</p>
                <a href="{{ route('products.index') }}" class="btn btn-voltronix">
                    {{ __('app.products.all_products') }}
                </a>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Apply coupon functionality
    function applyCoupon(event) {
        event.preventDefault();
        
        const form = event.target;
        const couponCode = form.coupon_code.value;
        
        // Placeholder for coupon application
        alert('{{ __("app.offers.coupon_system_coming_soon") }}\n' + 
              '{{ __("app.offers.coupon_code") }}: ' + couponCode);
        
        // Future implementation:
        // - Send AJAX request to validate and apply coupon
        // - Show success/error message
        // - Update prices on the page
    }

    // Scroll animations
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

    // Observe all offer cards
    document.querySelectorAll('.offer-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });

    // Auto-submit form on sort/filter change
    document.querySelector('select[name="sort"]').addEventListener('change', function() {
        this.closest('form').submit();
    });
    
    document.querySelector('select[name="category"]').addEventListener('change', function() {
        this.closest('form').submit();
    });
</script>
@endpush
