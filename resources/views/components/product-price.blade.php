@if($product->hasDiscount())
    <div class="product-price-container {{ $size === 'large' ? 'price-large' : '' }}">
        <span class="price-original">{{ currency_format($product->price) }}</span>
        <span class="price-current">{{ currency_format($product->discount_price) }}</span>
        <span class="savings-badge">{{ __('app.common.save') }} {{ $product->discount_percentage }}%</span>
    </div>
@else
    <div class="product-price-container {{ $size === 'large' ? 'price-large' : '' }}">
        <span class="price-current">{{ currency_format($product->price) }}</span>
    </div>
@endif

@push('styles')
<style>
    .product-price-container {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    .price-current {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--voltronix-primary);
    }
    
    .price-original {
        font-size: 1.1rem;
        color: #6c757d;
        text-decoration: line-through;
        font-weight: 500;
    }
    
    .savings-badge {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .price-large .price-current {
        font-size: 2rem;
    }
    
    .price-large .price-original {
        font-size: 1.3rem;
    }
    
    .price-large .savings-badge {
        font-size: 0.85rem;
        padding: 0.4rem 0.75rem;
    }
    
    /* RTL Support */
    [dir="rtl"] .product-price-container {
        flex-direction: row-reverse;
    }
</style>
@endpush