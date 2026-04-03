@extends('layouts.app')

@section('title', __('app.orders.order_success_title') . ' - ' . __('app.hero.title'))
@section('description', __('app.orders.order_success_message'))

@push('styles')
<style>
    :root {
        --voltronix-primary: #007fff;
        --voltronix-secondary: #23efff;
        --voltronix-accent: #1a1a1a;
        --voltronix-light: rgba(0, 127, 255, 0.05);
        --success-gradient: linear-gradient(135deg, #28a745, #20c997);
        --primary-gradient: linear-gradient(135deg, var(--voltronix-primary), var(--voltronix-secondary));
    }

    .success-header {
        background: var(--success-gradient);
        color: white;
        padding: 4rem 0 2rem;
        margin-top: 76px;
        position: relative;
        overflow: hidden;
    }
    
    .success-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        animation: pulse 4s ease-in-out infinite alternate;
    }
    
    .header-content {
        position: relative;
        z-index: 2;
        text-align: center;
    }
    
    .success-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        animation: bounceIn 1s ease-out;
    }
    
    @keyframes bounceIn {
        0% {
            transform: scale(0.3);
            opacity: 0;
        }
        50% {
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    .order-details {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }
    
    .order-header {
        text-align: center;
        padding: 2rem 0;
        border-bottom: 2px solid #f8f9fa;
        margin-bottom: 2rem;
    }
    
    .order-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--voltronix-primary);
        margin-bottom: 0.5rem;
    }
    
    .order-status {
        display: inline-block;
        background: #ffc107;
        color: #000;
        padding: 0.5rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9rem;
    }
    
    .info-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .info-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--voltronix-accent);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }
    
    .section-icon {
        background: var(--voltronix-primary);
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 0.9rem;
    }
    
    .order-item {
        display: flex;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .order-item:last-child {
        border-bottom: none;
    }
    
    .item-image {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }
    
    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .item-details {
        flex-grow: 1;
    }
    
    .item-name {
        font-weight: 600;
        color: var(--voltronix-accent);
        margin-bottom: 0.25rem;
    }
    
    .item-meta {
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .item-price {
        text-align: right;
    }
    
    .item-unit-price {
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .item-total {
        font-weight: 600;
        color: var(--voltronix-primary);
        font-size: 1.1rem;
    }
    
    .customer-info {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1.5rem;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }
    
    .info-row:last-child {
        margin-bottom: 0;
    }
    
    .info-label {
        font-weight: 600;
        color: var(--voltronix-accent);
    }
    
    .info-value {
        color: #495057;
    }
    
    .receipt-preview {
        text-align: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 10px;
    }
    
    .receipt-icon {
        font-size: 3rem;
        color: var(--voltronix-primary);
        margin-bottom: 1rem;
    }
    
    .total-summary {
        background: var(--voltronix-light);
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        margin: 2rem 0;
    }
    
    .total-amount {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--voltronix-primary);
        margin-bottom: 0.5rem;
    }
    
    .total-label {
        color: #6c757d;
        font-size: 1.1rem;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 2rem;
    }
    
    .btn-primary-action {
        background: var(--primary-gradient);
        border: none;
        color: white;
        font-weight: 600;
        padding: 1rem 2rem;
        border-radius: 30px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 127, 255, 0.3);
    }
    
    .btn-primary-action:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(13, 110, 253, 0.4);
        color: white;
    }
    
    .next-steps {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        text-align: center;
    }
    
    .steps-list {
        list-style: none;
        padding: 0;
        margin: 1.5rem 0;
    }
    
    .steps-list li {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f8f9fa;
        display: flex;
        align-items: center;
    }
    
    .steps-list li:last-child {
        border-bottom: none;
    }
    
    .step-number {
        background: var(--voltronix-primary);
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-weight: 600;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    /* RTL Support */
    [dir="rtl"] .step-number {
        margin-right: 0;
        margin-left: 1rem;
    }

    [dir="rtl"] .section-icon {
        margin-right: 0;
        margin-left: 1rem;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .success-header {
            padding: 3rem 0 1.5rem;
        }
        
        .success-icon {
            font-size: 3rem;
        }
        
        .order-details {
            padding: 1.5rem;
        }
        
        .action-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .action-buttons .btn,
        .action-buttons .btn-primary-action {
            width: 100%;
            max-width: 300px;
        }
        
        .total-amount {
            font-size: 2rem;
        }
        
        .order-number {
            font-size: 1.5rem;
        }
    }

    /* Animation improvements */
    @keyframes pulse {
        0%, 100% { opacity: 0.1; }
        50% { opacity: 0.2; }
    }

    .order-details {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<!-- Success Header -->
<section class="success-header">
    <div class="container">
        <div class="header-content">
            <i class="bi bi-check-circle-fill success-icon"></i>
            <h1 class="display-4 fw-bold mb-3">{{ __('app.orders.order_success_title') }}</h1>
            <p class="lead mb-0">{{ __('app.orders.order_success_message') }}</p>
        </div>
    </div>
</section>

<!-- Order Details -->
<section class="py-5" style="background: var(--voltronix-light);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Order Header -->
                <div class="order-details">
                    <div class="order-header">
                        <div class="order-number">{{ $order->order_number }}</div>
                        <div class="order-status">{{ $order->localized_status }}</div>
                        <p class="text-muted mt-3 mb-0">
                            {{ __('app.orders.order_date') }}: {{ $order->created_at->format('M d, Y H:i') }}
                        </p>
                    </div>

                    <!-- Order Items -->
                    <div class="info-section">
                        <h3 class="section-title">
                            <div class="section-icon">
                                <i class="bi bi-bag"></i>
                            </div>
                            {{ __('app.orders.items') }} ({{ $order->items->count() }})
                        </h3>
                        
                        @foreach($order->items as $item)
                            <div class="order-item">
                                <div class="item-image">
                                    @if($item->product && $item->product->thumbnail)
                                        <img src="{{ asset('storage/' . $item->product->thumbnail) }}" 
                                             alt="{{ $item->getTranslation('name') }}">
                                    @else
                                        <i class="bi bi-box-seam product-icon"></i>
                                    @endif
                                </div>
                                
                                <div class="item-details">
                                    <div class="item-name">{{ $item->getTranslation('name') }}</div>
                                    <div class="item-meta">
                                        {{ __('app.cart.quantity') }}: {{ $item->quantity }} × {{ $item->formatted_price }}
                                    </div>
                                </div>
                                
                                <div class="item-price">
                                    <div class="item-total">{{ $item->formatted_subtotal }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- Customer Information -->
                    <div class="info-section">
                        <h3 class="section-title">
                            <div class="section-icon">
                                <i class="bi bi-person"></i>
                            </div>
                            {{ __('app.orders.customer_info') }}
                        </h3>
                        
                        <div class="customer-info">
                            <div class="info-row">
                                <span class="info-label">{{ __('app.checkout.customer_name') }}:</span>
                                <span class="info-value">{{ $order->customer_name }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">{{ __('app.checkout.customer_email') }}:</span>
                                <span class="info-value">{{ $order->customer_email }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">{{ __('app.checkout.customer_phone') }}:</span>
                                <span class="info-value">{{ $order->customer_phone }}</span>
                            </div>
                            @if($order->notes)
                                <div class="info-row">
                                    <span class="info-label">{{ __('app.checkout.notes') }}:</span>
                                    <span class="info-value">{{ $order->notes }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Receipt -->
                    @if($order->receipt_path)
                        <div class="info-section">
                            <h3 class="section-title">
                                <div class="section-icon">
                                    <i class="bi bi-receipt"></i>
                                </div>
                                {{ __('app.orders.payment_receipt') }}
                            </h3>
                            
                            <div class="receipt-preview">
                                <i class="bi bi-file-earmark-check receipt-icon"></i>
                                <p class="mb-3">{{ __('app.orders.payment_receipt') }}</p>
                                <a href="{{ asset('storage/' . $order->receipt_path) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-primary">
                                    <i class="bi bi-download {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                    {{ __('app.orders.download_receipt') }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Total Summary -->
                <div class="total-summary">
                    <div class="total-label">{{ __('app.cart.total') }}</div>
                    <div class="total-amount">{{ $order->formatted_total }}</div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('orders.show', $order->order_number) }}" class="btn-primary-action">
                        <i class="bi bi-eye {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                        {{ __('app.orders.view_details') }}
                    </a>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-list-ul {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                        {{ __('app.orders.order_history') }}
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-shop {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                        {{ __('app.cart.continue_shopping') }}
                    </a>
                </div>

                <!-- Next Steps -->
                <div class="next-steps">
                    <h4 class="mb-4">{{ __('app.orders.next_steps', ['What happens next?']) }}</h4>
                    <ul class="steps-list">
                        <li>
                            <div class="step-number">1</div>
                            <span>{{ __('app.orders.step_review', ['We will review your payment receipt']) }}</span>
                        </li>
                        <li>
                            <div class="step-number">2</div>
                            <span>{{ __('app.orders.step_verify', ['Payment verification (1-2 business days)']) }}</span>
                        </li>
                        <li>
                            <div class="step-number">3</div>
                            <span>{{ __('app.orders.step_deliver', ['Digital products will be delivered via email']) }}</span>
                        </li>
                        <li>
                            <div class="step-number">4</div>
                            <span>{{ __('app.orders.step_support', ['24/7 customer support available']) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(isset($showSuccess) && $showSuccess)
            // Show success notification for newly placed orders
            Swal.fire({
                title: '{{ __("app.orders.order_success_title") }}',
                text: '{{ __("app.orders.order_success_message") }}',
                icon: 'success',
                confirmButtonText: '{{ __("app.common.ok") }}',
                confirmButtonColor: '#28a745',
                background: 'linear-gradient(135deg, #ffffff, #f8f9fa)',
                customClass: {
                    popup: 'border-0 shadow-lg',
                    title: 'text-success fw-bold',
                    content: 'text-muted'
                },
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        @endif
        
        // Auto-refresh order status (optional)
        setTimeout(function() {
            // Could add AJAX call to check order status updates
        }, 30000); // Check every 30 seconds
    });
</script>
@endpush
