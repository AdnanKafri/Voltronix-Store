@extends('layouts.app', [
    'title' => $title,
    'description' => $description,
    'keywords' => 'search, products, categories, digital store, voltronix',
    'canonicalUrl' => request()->url()
])

@section('content')
<section class="search-header">
    <div class="volt-container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-voltronix">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">{{ __('app.nav.home') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ __('app.search.title') }}
                </li>
            </ol>
        </nav>
        
        <h1 class="display-4 fw-bold mb-3">
            <i class="bi bi-search {{ app()->getLocale() == 'ar' ? 'ms-3' : 'me-3' }}"></i>
            {{ !empty($query) ? __('app.search.results_for', ['query' => $query]) : __('app.search.title') }}
        </h1>
        <p class="lead mb-0">
            {{ !empty($query) && $totalResults > 0 ? __('app.search.results_description', ['query' => $query, 'count' => $totalResults]) : __('app.search.description') }}
        </p>
    </div>
</section>

<section class="search-results-section py-5">
    <div class="volt-container">
        @if(!empty($query))
            <!-- Search Summary -->
            <div class="search-summary mb-4">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h2 class="search-query-title">
                            {{ __('app.search.results_for', ['query' => $query]) }}
                        </h2>
                        <p class="search-results-count text-muted">
                            {{ __('app.search.results_found') }} {{ $totalResults }} {{ $totalResults == 1 ? __('app.search.results_count') : Str::plural(__('app.search.results_count'), $totalResults) }}
                        </p>
                    </div>
                </div>
            </div>

            @if($totalResults > 0)
                <!-- Categories Results -->
                @if($categories->count() > 0)
                    <div class="search-section mb-5">
                        <div class="section-header mb-4">
                            <h3 class="section-title">
                                <i class="bi bi-grid-3x3-gap {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('app.search.categories') }}
                                <span class="badge bg-primary {{ app()->getLocale() == 'ar' ? 'me-2' : 'ms-2' }}">{{ $categories->count() }}</span>
                            </h3>
                        </div>
                        
                        <div class="row g-4">
                            @foreach($categories as $category)
                                <div class="col-lg-4 col-md-6">
                                    <div class="card category-card h-100">
                                        @if($category->thumbnail)
                                            <div class="card-img-wrapper">
                                                <img src="{{ asset('storage/' . $category->thumbnail) }}" 
                                                     class="card-img-top" 
                                                     alt="{{ $category->getTranslation('name') }}">
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $category->getTranslation('name') }}</h5>
                                            <p class="card-text text-muted">
                                                {{ Str::limit($category->getTranslation('description'), 100) }}
                                            </p>
                                            <div class="category-meta">
                                                <span class="products-count">
                                                    <i class="bi bi-box-seam me-1"></i>
                                                    {{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="{{ route('categories.show', $category->slug) }}" 
                                               class="btn btn-outline-primary w-100">
                                                {{ __('app.categories.view_products') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Products Results -->
                @if($products->count() > 0)
                    <div class="search-section">
                        <div class="section-header mb-4">
                            <h3 class="section-title">
                                <i class="bi bi-box-seam {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('app.search.products') }}
                                <span class="badge bg-primary {{ app()->getLocale() == 'ar' ? 'me-2' : 'ms-2' }}">{{ $products->total() }}</span>
                            </h3>
                        </div>
                        
                        <div class="row g-4">
                            @foreach($products as $product)
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="card product-card h-100">
                                        <div class="card-img-wrapper">
                                            @if($product->thumbnail)
                                                <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                                     class="card-img-top" 
                                                     alt="{{ $product->getTranslation('name') }}">
                                            @else
                                                <div class="placeholder-img">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            @endif
                                            
                                            <!-- Product Badges -->
                                            @if($product->is_featured)
                                                <span class="badge badge-featured">{{ __('app.products.featured') }}</span>
                                            @endif
                                            @if($product->is_new)
                                                <span class="badge badge-new">{{ __('app.products.new') }}</span>
                                            @endif
                                            @if($product->hasDiscount())
                                                <span class="badge badge-sale">{{ $product->discount_percentage }}% {{ __('app.products.off') }}</span>
                                            @endif
                                        </div>
                                        
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $product->getTranslation('name') }}</h6>
                                            <p class="card-text text-muted small">
                                                {{ Str::limit($product->getTranslation('description'), 80) }}
                                            </p>
                                            
                                            @if($product->category)
                                                <div class="product-category mb-2">
                                                    <span class="badge bg-light text-dark">
                                                        {{ $product->category->getTranslation('name') }}
                                                    </span>
                                                </div>
                                            @endif
                                            
                                            <div class="product-price">
                                                @if($product->hasDiscount())
                                                    <span class="price-original text-decoration-line-through text-muted">
                                                        {{ $product->formatted_price }}
                                                    </span>
                                                    <span class="price-sale fw-bold text-primary">
                                                        ${{ number_format($product->effective_price, 2) }}
                                                    </span>
                                                @else
                                                    <span class="price-current fw-bold text-primary">
                                                        {{ $product->formatted_price }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="card-footer bg-transparent">
                                            <a href="{{ route('products.show', $product->slug) }}" 
                                               class="btn btn-primary btn-sm w-100">
                                                {{ __('app.products.view_details') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        @if($products->hasPages())
                            <div class="d-flex justify-content-center mt-5">
                                {{ $products->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                @endif
            @else
                <!-- Creative No Results State -->
                <div class="no-results-creative">
                    <div class="no-results-container">
                        <!-- Animated Search Icon -->
                        <div class="no-results-icon-wrapper">
                            <div class="search-pulse-ring"></div>
                            <div class="search-pulse-ring delay-1"></div>
                            <div class="search-pulse-ring delay-2"></div>
                            <i class="bi bi-search no-results-icon"></i>
                        </div>
                        
                        <!-- Main Message -->
                        <div class="no-results-content">
                            <h2 class="no-results-title">{{ __('app.search.no_results_title') }}</h2>
                            <p class="no-results-subtitle">{{ __('app.search.no_results_subtitle') }}</p>
                        </div>
                        
                        <!-- Smart Suggestions -->
                        <div class="search-suggestions-grid">
                            <h3 class="suggestions-title">{{ __('app.search.suggestions_title') }}</h3>
                            
                            <div class="row g-4 justify-content-center">
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <a href="{{ route('products.index') }}" class="suggestion-card">
                                        <div class="suggestion-icon">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                        <h4>{{ __('app.search.browse_all') }}</h4>
                                        <p>{{ __('app.nav.products') }}</p>
                                    </a>
                                </div>
                                
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <a href="{{ route('categories.index') }}" class="suggestion-card">
                                        <div class="suggestion-icon">
                                            <i class="bi bi-grid-3x3-gap"></i>
                                        </div>
                                        <h4>{{ __('app.search.popular_categories') }}</h4>
                                        <p>{{ __('app.nav.categories') }}</p>
                                    </a>
                                </div>
                                
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <a href="{{ route('offers.index') }}" class="suggestion-card">
                                        <div class="suggestion-icon">
                                            <i class="bi bi-percent"></i>
                                        </div>
                                        <h4>{{ __('app.search.special_offers') }}</h4>
                                        <p>{{ __('app.offers.title') }}</p>
                                    </a>
                                </div>
                                
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <a href="{{ route('products.index', ['featured' => 1]) }}" class="suggestion-card">
                                        <div class="suggestion-icon">
                                            <i class="bi bi-star-fill"></i>
                                        </div>
                                        <h4>{{ __('app.search.featured_products') }}</h4>
                                        <p>{{ __('app.products.featured') }}</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <!-- Search Landing -->
            <div class="search-landing text-center py-5">
                <div class="search-icon mb-4">
                    <i class="bi bi-search display-1 text-primary"></i>
                </div>
                <h2 class="search-title mb-3">{{ __('app.search.title') }}</h2>
                <p class="search-description text-muted mb-4">{{ __('app.search.description') }}</p>
                
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('search.index') }}" class="search-form-main">
                            <div class="input-group input-group-lg">
                                <input type="text" 
                                       name="q" 
                                       class="form-control" 
                                       placeholder="{{ __('app.search.placeholder') }}"
                                       autocomplete="off"
                                       autofocus>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i>
                                    {{ __('app.search.title') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
/* Search Results Styles */
.search-header {
    background: linear-gradient(135deg, var(--voltronix-primary), var(--voltronix-secondary));
    color: white;
    padding: 4rem 0 2rem;
    margin-top: 0;
    position: relative;
    overflow: hidden;
    margin-bottom: 3rem;
}

.search-results-section {
    min-height: 60vh;
}

.search-query-title {
    font-family: 'Orbitron', sans-serif;
    font-weight: 700;
    color: var(--voltronix-primary);
    margin-bottom: 0.5rem;
}

.search-results-count {
    font-size: 0.9rem;
}

.section-title {
    font-family: 'Orbitron', sans-serif;
    font-weight: 600;
    color: var(--voltronix-dark);
    display: flex;
    align-items: center;
}

.category-card,
.product-card {
    border: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
    background: white;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.category-card:hover,
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 127, 255, 0.15);
    border: 1px solid rgba(0, 127, 255, 0.2);
}

.card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 1.25rem;
}

.card-title {
    font-family: 'Orbitron', sans-serif;
    font-weight: 600;
    color: var(--voltronix-dark);
    margin-bottom: 0.75rem;
    font-size: 1rem;
    line-height: 1.3;
}

.card-text {
    flex: 1;
    margin-bottom: 1rem;
    line-height: 1.4;
}

.card-footer {
    padding: 1rem 1.25rem;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
}

.card-img-wrapper {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.card-img-top {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.card:hover .card-img-top {
    transform: scale(1.05);
}

.placeholder-img {
    width: 100%;
    height: 100%;
    background: var(--voltronix-light);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--voltronix-primary);
    font-size: 2rem;
}

/* Product Badges Container */
.card-img-wrapper {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.card-img-wrapper .badge {
    position: absolute;
    font-size: 0.7rem;
    padding: 4px 8px;
    border-radius: 12px;
    font-weight: 600;
    z-index: 10;
}

/* Badge positioning to prevent overlap */
.badge-featured {
    top: 10px;
    right: 10px;
    background: linear-gradient(135deg, #fd7e14, #ff6b35);
    color: white;
}

.badge-new {
    top: 10px;
    left: 10px;
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
}

.badge-sale {
    top: 40px;
    right: 10px;
    background: linear-gradient(135deg, #dc3545, #e74c3c);
    color: white;
    animation: pulse 2s infinite;
}

/* When multiple badges exist, stack them properly */
.card-img-wrapper .badge-featured + .badge-sale,
.card-img-wrapper .badge-sale:not(:first-child) {
    top: 40px;
}

.card-img-wrapper .badge-new + .badge-featured {
    right: 10px;
    top: 40px;
}

.card-img-wrapper .badge-new + .badge-featured + .badge-sale {
    top: 70px;
    right: 10px;
}

.no-results-section {
    background: var(--voltronix-light);
    border-radius: 20px;
    margin: 2rem 0;
}

.search-landing {
    background: linear-gradient(135deg, var(--voltronix-light) 0%, rgba(0, 127, 255, 0.05) 100%);
    border-radius: 20px;
    margin: 2rem 0;
}

.search-form-inline .input-group,
.search-form-main .input-group {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    border-radius: 15px;
    overflow: hidden;
}

.search-form-inline .form-control,
.search-form-main .form-control {
    border: none;
    padding: 12px 20px;
}

.search-form-inline .btn,
.search-form-main .btn {
    border: none;
    padding: 12px 20px;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Creative No Results State */
.no-results-creative {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(0, 127, 255, 0.05) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(0, 127, 255, 0.1);
    border-radius: 25px;
    padding: 4rem 2rem;
    margin: 3rem 0;
    text-align: center;
    box-shadow: 0 12px 40px rgba(0, 127, 255, 0.1);
}

.no-results-container {
    max-width: 800px;
    margin: 0 auto;
}

/* Animated Search Icon */
.no-results-icon-wrapper {
    position: relative;
    display: inline-block;
    margin-bottom: 2rem;
}

.no-results-icon {
    font-size: 4rem;
    color: #007fff;
    position: relative;
    z-index: 10;
}

.search-pulse-ring {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 80px;
    height: 80px;
    border: 2px solid #007fff;
    border-radius: 50%;
    animation: pulseRing 2s ease-out infinite;
    opacity: 0;
}

.search-pulse-ring.delay-1 {
    animation-delay: 0.5s;
}

.search-pulse-ring.delay-2 {
    animation-delay: 1s;
}

@keyframes pulseRing {
    0% {
        transform: translate(-50%, -50%) scale(0.8);
        opacity: 1;
    }
    100% {
        transform: translate(-50%, -50%) scale(2);
        opacity: 0;
    }
}

/* No Results Content */
.no-results-content {
    margin-bottom: 3rem;
}

.no-results-title {
    font-family: 'Orbitron', sans-serif;
    font-weight: 700;
    font-size: 2.2rem;
    background: linear-gradient(135deg, #007fff, #23efff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
}

.no-results-subtitle {
    font-size: 1.1rem;
    color: rgba(26, 26, 26, 0.7);
    line-height: 1.6;
    max-width: 500px;
    margin: 0 auto;
}

/* Suggestions Grid */
.search-suggestions-grid {
    margin-top: 3rem;
}

.suggestions-title {
    font-family: 'Orbitron', sans-serif;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 2rem;
    font-size: 1.5rem;
}

.suggestion-card {
    display: block;
    text-decoration: none;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid rgba(0, 127, 255, 0.1);
    border-radius: 20px;
    padding: 2rem 1.5rem;
    text-align: center;
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    height: 100%;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.suggestion-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(0, 127, 255, 0.15);
    border-color: #007fff;
    text-decoration: none;
}

.suggestion-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #007fff, #23efff);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: white;
}

.suggestion-card h4 {
    font-family: 'Orbitron', sans-serif;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.suggestion-card p {
    color: rgba(26, 26, 26, 0.6);
    margin-bottom: 0;
    font-size: 0.9rem;
}

/* Enhanced Search Summary */
.search-summary {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(0, 127, 255, 0.1);
    border-radius: 25px;
    padding: 2rem;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
}

.search-query-title {
    background: linear-gradient(135deg, #007fff, #23efff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-size: 2rem;
}

/* RTL Support */
[dir="rtl"] .section-title {
    flex-direction: row-reverse;
}

[dir="rtl"] .badge-featured,
[dir="rtl"] .badge-sale {
    right: auto;
    left: 10px;
}

[dir="rtl"] .badge-new {
    left: auto;
    right: 10px;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .search-query-title {
        font-size: 1.5rem;
    }
    
    .no-results-title {
        font-size: 1.8rem;
    }
    
    .no-results-creative {
        padding: 3rem 1.5rem;
    }
    
    .suggestion-card {
        padding: 1.5rem 1rem;
    }
}
</style>
@endpush
