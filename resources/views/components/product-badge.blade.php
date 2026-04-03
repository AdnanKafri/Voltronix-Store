@if($type === 'sale' && $discount)
    <span class="product-badge {{ $getBadgeClasses() }}">
        <i class="bi bi-percent"></i>
        {{ $discount }}% {{ __('app.common.off') }}
    </span>
@elseif($type === 'new')
    <span class="product-badge {{ $getBadgeClasses() }}">
        <i class="bi bi-star-fill"></i>
        {{ __('app.common.new') }}
    </span>
@elseif($type === 'featured')
    <span class="product-badge {{ $getBadgeClasses() }}">
        <i class="bi bi-fire"></i>
        {{ __('app.common.featured') }}
    </span>
@endif

@push('styles')
<style>
    .product-badge {
        position: absolute;
        top: 10px;
        {{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 10px;
        padding: 0.5rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    
    .badge-new {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
    }
    
    .badge-featured {
        background: linear-gradient(45deg, #fd7e14, #ffc107);
        color: white;
    }
    
    .badge-sale {
        background: linear-gradient(45deg, #dc3545, #e83e8c);
        color: white;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .product-badge i {
        font-size: 0.7rem;
    }
</style>
@endpush