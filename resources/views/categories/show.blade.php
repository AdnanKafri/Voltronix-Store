@extends('layouts.app')

@section('title', $category->getTranslation('name') . ' - ' . __('app.categories.title'))
@section('description', $category->getTranslation('description') ?: __('app.categories.products_in_category'))

@push('styles')
<style>
    .product-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(13, 110, 253, 0.15);
        border-color: var(--voltronix-primary);
    }
    
    .product-image {
        height: 220px;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: all 0.3s ease;
    }
    
    .product-card:hover .product-image img {
        transform: scale(1.05);
    }
    
    .product-icon {
        font-size: 3rem;
        color: var(--voltronix-primary);
        opacity: 0.6;
    }
    
    .product-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #dc3545;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .product-content {
        padding: 1.25rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .product-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--voltronix-accent);
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }
    
    .product-description {
        color: #6c757d;
        font-size: 0.9rem;
        line-height: 1.4;
        margin-bottom: 1rem;
        flex-grow: 1;
    }
    
    .product-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--voltronix-primary);
        margin-bottom: 1rem;
    }
    
    .price-original {
        text-decoration: line-through;
        color: #6c757d;
        font-size: 0.9rem;
        margin-right: 0.5rem;
    }
    
    .btn-product {
        background: var(--voltronix-primary);
        border: none;
        color: white;
        font-weight: 500;
        padding: 0.6rem 1.25rem;
        border-radius: 20px;
        transition: all 0.3s ease;
        text-decoration: none;
        text-align: center;
        display: block;
    }
    
    .btn-product:hover {
        background: #0056b3;
        transform: translateY(-2px);
        color: white;
    }
    
    .filters-section {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .filter-group {
        margin-bottom: 1rem;
    }
    
    .filter-group:last-child {
        margin-bottom: 0;
    }
    
    .filter-label {
        font-weight: 600;
        color: var(--voltronix-accent);
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .category-header {
        background: linear-gradient(135deg, var(--voltronix-accent), #000000);
        color: white;
        padding: 4rem 0 2rem;
        /* Removed margin-top: 76px; which caused double spacing */
        /* margin-top: 76px; <-- REMOVED */
    }
    
    .category-info {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 1.5rem;
        margin-top: 2rem;
    }
    
    .results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .results-count {
        color: #6c757d;
        font-size: 0.95rem;
    }
    
    .sort-dropdown {
        min-width: 200px;
    }
</style>
@endpush

@section('content')
<!-- Category Header -->
<section class="category-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-voltronix">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">{{ __('app.nav.home') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('categories.index') }}">{{ __('app.categories.title') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $category->getTranslation('name') }}
                </li>
            </ol>
        </nav>
        
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">{{ $category->getTranslation('name') }}</h1>
                @if($category->getTranslation('description'))
                    <p class="lead mb-0">{{ $category->getTranslation('description') }}</p>
                @endif
            </div>
            <div class="col-lg-4 text-lg-end">
                @if($category->thumbnail)
                    <div style="width: 120px; height: 120px; margin: 0 auto; display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 15px; background: linear-gradient(135deg, var(--voltronix-primary), #00d4ff);">
                        <img src="{{ asset('storage/' . $category->thumbnail) }}" 
                             alt="{{ $category->getTranslation('name') }}"
                             style="width: 100%; height: 100%; object-fit: contain; object-position: center; padding: 10px; box-sizing: border-box;">
                    </div>
                @endif
            </div>
        </div>
        
        <div class="category-info">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="mb-2">{{ __('app.categories.products_in_category') }}</h6>
                    <span class="badge bg-primary fs-6">{{ $products->total() }} {{ __('app.products.title') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Products Section -->
<section class="py-5" style="background: var(--voltronix-light);">
    <div class="container">
        <!-- Filters and Search -->
        <div class="filters-section">
            <form method="GET" action="{{ route('categories.show', $category->slug) }}" class="row g-3">
                <div class="col-md-4">
                    <label class="filter-label">{{ __('app.common.search') }}</label>
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="{{ __('app.products.search_placeholder') }}"
                           value="{{ request('search') }}">
                </div>
                
                <div class="col-md-3">
                    <label class="filter-label">{{ __('app.common.sort_by') }}</label>
                    <select name="sort" class="form-select">
                        <option value="default" {{ $sortBy == 'default' ? 'selected' : '' }}>
                            {{ __('app.products.sort.default') }}
                        </option>
                        <option value="price_low" {{ $sortBy == 'price_low' ? 'selected' : '' }}>
                            {{ __('app.products.sort.price_low') }}
                        </option>
                        <option value="price_high" {{ $sortBy == 'price_high' ? 'selected' : '' }}>
                            {{ __('app.products.sort.price_high') }}
                        </option>
                        <option value="newest" {{ $sortBy == 'newest' ? 'selected' : '' }}>
                            {{ __('app.products.sort.newest') }}
                        </option>
                        <option value="name" {{ $sortBy == 'name' ? 'selected' : '' }}>
                            {{ __('app.products.sort.name') }}
                        </option>
                    </select>
                </div>
                
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-voltronix me-2">
                        <i class="bi bi-funnel {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                        {{ __('app.common.filter') }}
                    </button>
                    @if(request()->hasAny(['search', 'sort']))
                        <a href="{{ route('categories.show', $category->slug) }}" class="btn btn-outline-secondary">
                            {{ __('app.products.filters.clear_filters') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Results Header -->
        <div class="results-header">
            <div class="results-count">
                {{ $products->total() }} {{ __('app.common.products') }} {{ __('app.common.found') }}
            </div>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="row g-4">
                @foreach($products as $product)
                    <div class="col-lg-4 col-md-6">
                        <div class="product-card">
                            <div class="product-image">
                                @if($product->thumbnail)
                                    <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                         alt="{{ $product->getTranslation('name') }}"
                                         loading="lazy">
                                @else
                                    <i class="bi bi-box-seam product-icon"></i>
                                @endif
                                
                                {{-- Product Badges --}}
                                @if($product->hasDiscount())
                                    <div class="product-badge sale">
                                        {{ $product->discount_percentage }}% {{ __('app.common.off') }}
                                    </div>
                                @elseif($product->is_new)
                                    <div class="product-badge new">
                                        {{ __('app.common.new') }}
                                    </div>
                                @elseif($product->is_featured)
                                    <div class="product-badge featured">
                                        {{ __('app.common.featured') }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="product-content">
                                <h3 class="product-title">{{ $product->getTranslation('name') }}</h3>
                                
                                @if($product->getTranslation('description'))
                                    <p class="product-description">
                                        {{ Str::limit($product->getTranslation('description'), 100) }}
                                    </p>
                                @endif
                                
                                <div class="product-price">
                                    @if($product->hasDiscount())
                                        <span class="price-current">{{ currency_format($product->discount_price) }}</span>
                                        <span class="price-original">{{ currency_format($product->price) }}</span>
                                        <span class="price-discount">{{ __('app.common.save') }} {{ safe_subtract($product->price, $product->discount_price) }}</span>
                                    @else
                                        <span class="price-current">{{ currency_format($product->price) }}</span>
                                    @endif
                                </div>
                                
                                <div class="product-actions d-flex gap-2 mt-3">
                                    @if($product->isAvailable())
                                        <button class="btn-add-to-cart btn-sm flex-fill z-raised" 
                                                onclick="addToCart({{ $product->id }})">
                                            <i class="bi bi-cart-plus {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                            {{ __('app.common.add_to_cart') }}
                                        </button>
                                    @else
                                        <button class="btn btn-secondary btn-sm flex-fill" disabled>
                                            {{ __('app.common.out_of_stock') }}
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('products.show', $product->slug) }}" 
                                       class="btn btn-outline-primary btn-sm stretched-link">
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
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="bi bi-search"></i>
                <h3>{{ __('app.products.no_products') }}</h3>
                <p class="mb-4">{{ __('app.common.try_different_search') }}</p>
                <a href="{{ route('categories.show', $category->slug) }}" class="btn btn-voltronix">
                    {{ __('app.products.filters.clear_filters') }}
                </a>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Add scroll animations for product cards
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

    // Observe all product cards
    document.querySelectorAll('.product-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });

    // Auto-submit form on sort change
    document.querySelector('select[name="sort"]').addEventListener('change', function() {
        this.closest('form').submit();
    });
</script>
@endpush
