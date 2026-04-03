@props([
    'product',
    'showCategory' => true,
    'compact' => false
])

<div class="product-tile-card">
    <a href="{{ route('products.show', $product->slug) }}" class="tile-card-link">
        {{-- Large Image Area (70%) --}}
        <div class="tile-image-wrapper">
            @if($product->thumbnail)
                <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                     alt="{{ $product->getTranslation('name', app()->getLocale()) }}" 
                     class="tile-image"
                     loading="lazy">
            @else
                <div class="tile-image-placeholder">
                    <i class="bi bi-box-seam"></i>
                </div>
            @endif
            
            {{-- Badge Overlay --}}
            @if($product->hasDiscount())
                <div class="tile-badge discount">-{{ $product->discount_percentage }}%</div>
            @elseif($product->is_new)
                <div class="tile-badge new">NEW</div>
            @elseif($product->is_featured)
                <div class="tile-badge featured">★</div>
            @endif
            
            {{-- Hover Quick Action --}}
            <div class="tile-quick-view">
                <i class="bi bi-eye"></i>
            </div>
        </div>
        
        {{-- Floating Info Bar (30%) --}}
        <div class="tile-info-bar">
            <div class="tile-content">
                <h3 class="tile-title">{{ Str::limit($product->getTranslation('name', app()->getLocale()), 40) }}</h3>
                
                <div class="tile-price-row">
                    @if($product->hasDiscount())
                        <span class="tile-price-old">{{ currency_format($product->price) }}</span>
                        <span class="tile-price">{{ currency_format($product->discount_price) }}</span>
                    @else
                        <span class="tile-price">{{ currency_format($product->price) }}</span>
                    @endif
                </div>
            </div>
            
            <button onclick="addToCart({{ $product->id }}); event.preventDefault();" class="tile-cart-btn" title="{{ __('app.products.add_to_cart') }}">
                <i class="bi bi-cart-plus"></i>
            </button>
        </div>
    </a>
</div>
