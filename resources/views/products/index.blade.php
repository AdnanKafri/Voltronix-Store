@extends('layouts.app')

@section('title', __('app.products.all_products') . ' - ' . __('app.hero.title'))
@section('description', __('app.products.title') . ' - ' . __('app.footer.description'))

@section('content')
<!-- Products Header -->
<section class="products-header">
    <div class="volt-container">
        <div class="header-content">
            {{-- Removed <br> --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-voltronix">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">{{ __('app.nav.home') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __('app.products.title') }}
                    </li>
                </ol>
            </nav>
            
            <div class="volt-container">
                <div class="header-content text-center">
                    <h1 class="display-4 fw-bold mb-3">{{ __('app.products.all_products') }}</h1>
                    <p class="lead mb-0">{{ __('app.products.subtitle') }}</p>
                </div>
            </div>
        </div>
</section>

<!-- Products Content -->
<section class="py-5" style="background: var(--voltronix-light);">
    <div class="volt-container">
        <!-- Advanced Filters -->
        <div class="filters-section">
            <h3 class="filter-title">
                <div class="filter-icon">
                    <i class="bi bi-funnel"></i>
                </div>
                {{ __('app.common.filter') }} & {{ __('app.common.search') }}
            </h3>
            
            <form method="GET" action="{{ route('products.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('app.common.search') }}</label>
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="{{ __('app.products.search_placeholder') }}"
                           value="{{ request('search') }}">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">{{ __('app.products.filters.category') }}</label>
                    <select name="category" class="form-select">
                        <option value="">{{ __('app.common.all') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->getTranslation('name') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">{{ __('app.products.filters.price_range') }}</label>
                    <div class="price-range-inputs">
                        <input type="number" 
                               name="min_price" 
                               class="form-control" 
                               placeholder="{{ __('app.products.filters.min_price') }}"
                               value="{{ request('min_price') }}">
                        <span class="price-separator">-</span>
                        <input type="number" 
                               name="max_price" 
                               class="form-control" 
                               placeholder="{{ __('app.products.filters.max_price') }}"
                               value="{{ request('max_price') }}">
                    </div>
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-voltronix w-100">
                        <i class="bi bi-search {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                        {{ __('app.common.search') }}
                    </button>
                </div>
            </form>
            
            <!-- Quick Filters -->
            <div class="quick-filters mt-3">
                <a href="{{ route('products.index') }}" 
                   class="quick-filter-btn {{ !request()->hasAny(['search', 'category', 'min_price', 'max_price', 'sort']) ? 'active' : '' }}">
                    {{ __('app.common.all') }}
                </a>
                <a href="{{ route('products.index', ['sort' => 'price_low']) }}" 
                   class="quick-filter-btn {{ request('sort') == 'price_low' ? 'active' : '' }}">
                    {{ __('app.products.sort.price_low') }}
                </a>
                <a href="{{ route('products.index', ['sort' => 'newest']) }}" 
                   class="quick-filter-btn {{ request('sort') == 'newest' ? 'active' : '' }}">
                    {{ __('app.products.sort.newest') }}
                </a>
                @if(request()->hasAny(['search', 'category', 'min_price', 'max_price', 'sort']))
                    <a href="{{ route('products.index') }}" class="quick-filter-btn">
                        <i class="bi bi-x-circle me-1"></i>
                        {{ __('app.products.filters.clear_filters') }}
                    </a>
                @endif
            </div>
        </div>

        <!-- Results Summary -->
        <div class="results-summary">
            <div class="results-count">
                {{ $products->total() }} {{ __('app.common.products') }} {{ __('app.common.found') }}
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <div class="view-toggle">
                    <button class="view-btn active" onclick="toggleView('grid')" id="gridBtn">
                        <i class="bi bi-grid-3x3-gap"></i>
                    </button>
                    <button class="view-btn" onclick="toggleView('list')" id="listBtn">
                        <i class="bi bi-list"></i>
                    </button>
                </div>
                
                <select name="sort" class="form-select" style="width: auto;" onchange="updateSort(this.value)">
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
        </div>

        <!-- Products Grid/List -->
        @if($products->count() > 0)
            <div id="productsContainer">
                <!-- Grid View -->
                <div class="products-grid" id="gridView">
                    @foreach($products as $product)
                        <div class="product-card">
                            <div class="product-image">
                                @if($product->thumbnail)
                                    <img src="{{ $product->thumbnail_url }}" 
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
                                <a href="{{ route('categories.show', $product->category->slug) }}" 
                                   class="product-category z-raised">
                                    {{ $product->category->getTranslation('name') }}
                                </a>
                                
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
                    @endforeach
                </div>
                
                <!-- List View -->
                <div class="products-list" id="listView" style="display: none;">
                    @foreach($products as $product)
                        <div class="product-card-list">
                            <div class="product-image-list">
                                @if($product->thumbnail)
                                    <img src="{{ $product->thumbnail_url }}" 
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
                            
                            <div class="product-info-list">
                                <h3 class="product-title-list">{{ $product->getTranslation('name') }}</h3>
                                <div class="product-category-list">{{ $product->category->getTranslation('name') }}</div>
                                
                                @if($product->getTranslation('description'))
                                    <p class="product-description-list">
                                        {{ Str::limit($product->getTranslation('description'), 150) }}
                                    </p>
                                @endif
                                
                                <div class="product-actions-list">
                                    <div class="product-price">
                                        @if($product->hasDiscount())
                                            <span class="price-current">{{ currency_format($product->discount_price) }}</span>
                                            <span class="price-original">{{ currency_format($product->price) }}</span>
                                            <span class="price-discount">{{ __('app.common.save') }} {{ safe_subtract($product->price, $product->discount_price) }}</span>
                                        @else
                                            <span class="price-current">{{ currency_format($product->price) }}</span>
                                        @endif
                                    </div>
                                    <div class="d-flex gap-2">
                                        @if($product->isAvailable())
                                            <button class="btn-add-to-cart" 
                                                    onclick="addToCart({{ $product->id }})">
                                                <i class="bi bi-cart-plus {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                                {{ __('app.common.add_to_cart') }}
                                            </button>
                                        @else
                                            <button class="btn btn-secondary" disabled>
                                                {{ __('app.common.out_of_stock') }}
                                            </button>
                                        @endif
                                        
                                        <a href="{{ route('products.show', $product->slug) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="bi bi-eye {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                            {{ __('app.common.view') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
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
                <i class="bi bi-search"></i>
                <h3>{{ __('app.products.no_products') }}</h3>
                <p class="mb-4">{{ __('app.common.try_different_search') }}</p>
                <a href="{{ route('products.index') }}" class="btn btn-voltronix">
                    {{ __('app.products.filters.clear_filters') }}
                </a>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    // View toggle functionality
    function toggleView(viewType) {
        const gridView = document.getElementById('gridView');
        const listView = document.getElementById('listView');
        const gridBtn = document.getElementById('gridBtn');
        const listBtn = document.getElementById('listBtn');
        
        if (viewType === 'grid') {
            gridView.style.display = 'grid';
            listView.style.display = 'none';
            gridBtn.classList.add('active');
            listBtn.classList.remove('active');
        } else {
            gridView.style.display = 'none';
            listView.style.display = 'flex';
            listBtn.classList.add('active');
            gridBtn.classList.remove('active');
        }
        
        // Save preference
        localStorage.setItem('productsView', viewType);
    }
    
    // Load saved view preference
    document.addEventListener('DOMContentLoaded', function() {
        const savedView = localStorage.getItem('productsView');
        if (savedView) {
            toggleView(savedView);
        }
    });
    
    // Sort functionality
    function updateSort(sortValue) {
        const url = new URL(window.location);
        url.searchParams.set('sort', sortValue);
        window.location.href = url.toString();
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

    // Observe all product cards
    document.querySelectorAll('.product-card, .product-card-list').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
</script>
@endpush

