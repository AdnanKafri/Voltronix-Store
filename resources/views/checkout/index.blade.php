@extends('layouts.app')

@section('title', __('app.checkout.title') . ' - ' . __('app.hero.title'))
@section('description', __('app.checkout.title'))

@push('styles')
<style>
    .checkout-header {
        background: linear-gradient(135deg, var(--voltronix-accent), #000000);
        color: white;
        padding: 4rem 0 2rem;
        margin-top: 0;
        position: relative;
        overflow: hidden;
    }
    
    .checkout-form {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }
    
    .form-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--voltronix-accent);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }
    
    .section-icon {
        background: var(--voltronix-primary);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.1rem;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--voltronix-accent);
        margin-bottom: 0.5rem;
    }
    
    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: var(--voltronix-primary);
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    .file-upload-area {
        border: 2px dashed #e9ecef;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .file-upload-area:hover {
        border-color: var(--voltronix-primary);
        background: rgba(13, 110, 253, 0.05);
    }
    
    .file-upload-area.dragover {
        border-color: var(--voltronix-primary);
        background: rgba(13, 110, 253, 0.1);
    }
    
    .upload-icon {
        font-size: 3rem;
        color: var(--voltronix-primary);
        margin-bottom: 1rem;
        opacity: 0.6;
    }
    
    .upload-text {
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    
    .upload-help {
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .file-preview {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
        display: none;
    }
    
    .file-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .file-details {
        display: flex;
        align-items: center;
    }
    
    .file-icon {
        font-size: 2rem;
        color: var(--voltronix-primary);
        margin-right: 1rem;
    }
    
    .remove-file {
        background: #dc3545;
        border: none;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.875rem;
        cursor: pointer;
    }
    
    .order-summary {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 100px;
    }
    
    .summary-item {
        display: flex;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .summary-item:last-child {
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
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }
    
    .item-quantity {
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    .item-price {
        font-weight: 600;
        color: var(--voltronix-primary);
    }
    
    .summary-total {
        background: var(--voltronix-light);
        border-radius: 15px;
        padding: 1.5rem;
        margin: 1.5rem 0;
        text-align: center;
    }
    
    .total-amount {
        font-size: 2rem;
        font-weight: 700;
        color: var(--voltronix-primary);
    }
    
    .btn-place-order {
        background: linear-gradient(45deg, var(--voltronix-primary), #00d4ff);
        border: none;
        color: white;
        font-weight: 600;
        padding: 1rem 2rem;
        border-radius: 30px;
        font-size: 1.1rem;
        width: 100%;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-place-order:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(13, 110, 253, 0.4);
        color: white;
    }
    
    .btn-place-order:disabled {
        background: #6c757d;
        cursor: not-allowed;
        transform: none;
    }
    
    .bank-info {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .bank-info h6 {
        margin-bottom: 1rem;
        font-weight: 600;
    }
    
    .progress-steps {
        display: flex;
        justify-content: center;
        margin-bottom: 2rem;
    }
    
    .step {
        display: flex;
        align-items: center;
        color: #6c757d;
    }
    
    .step.active {
        color: var(--voltronix-primary);
        font-weight: 600;
    }
    
    .step-number {
        background: #e9ecef;
        color: #6c757d;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .step.active .step-number {
        background: var(--voltronix-primary);
        color: white;
    }
    
    .step-divider {
        width: 50px;
        height: 2px;
        background: #e9ecef;
        margin: 0 1rem;
    }
    
    .step.active + .step-divider {
        background: var(--voltronix-primary);
    }
    
    .payment-method-card {
        border: 2px solid #e9ecef;
        border-radius: 15px;
        padding: 0;
        transition: all 0.3s ease;
        cursor: pointer;
        height: 100%;
    }
    
    .payment-method-card:hover {
        border-color: var(--voltronix-primary);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.1);
    }
    
    .payment-method-card input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    
    .payment-method-card input[type="radio"]:checked + .payment-method-label {
        border-color: var(--voltronix-primary);
        background: rgba(13, 110, 253, 0.05);
    }
    
    .payment-method-label {
        display: flex;
        align-items: center;
        padding: 1.5rem;
        margin: 0;
        cursor: pointer;
        border: 2px solid transparent;
        border-radius: 15px;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .payment-icon {
        background: var(--voltronix-primary);
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    
    .payment-text {
        flex-grow: 1;
    }
    
    .payment-title {
        font-weight: 600;
        color: var(--voltronix-accent);
        margin-bottom: 0.25rem;
    }
    
    .payment-desc {
        font-size: 0.875rem;
        color: #6c757d;
        line-height: 1.4;
    }
    
    .payment-instructions {
        margin-bottom: 1.5rem;
    }
    
    /* Progress Steps Enhancement */
    .progress-steps {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 3rem;
        padding: 2rem 0;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        backdrop-filter: blur(10px);
    }
    
    .step {
        display: flex;
        align-items: center;
        color: #6c757d;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .step.completed {
        color: #28a745;
    }
    
    .step.completed .step-number {
        background: #28a745;
        color: white;
    }
    
    .step.active {
        color: var(--voltronix-primary);
        font-weight: 600;
        transform: scale(1.05);
    }
    
    .step-number {
        background: #e9ecef;
        color: #6c757d;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .step.completed .step-number::after {
        content: '✓';
        position: absolute;
        font-size: 0.75rem;
    }
    
    .step.active .step-number {
        background: var(--voltronix-primary);
        color: white;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.2);
    }
    
    .step-divider {
        width: 60px;
        height: 3px;
        background: #e9ecef;
        margin: 0 1.5rem;
        border-radius: 2px;
        transition: all 0.3s ease;
    }
    
    .step.completed + .step-divider {
        background: #28a745;
    }
    
    .step.active + .step-divider {
        background: var(--voltronix-primary);
    }
    
    /* RTL Support for Payment Methods */
    [dir="rtl"] .payment-icon {
        margin-right: 0;
        margin-left: 1rem;
    }
    
    [dir="rtl"] .step-number {
        margin-right: 0;
        margin-left: 0.75rem;
    }
</style>
@endpush

@section('content')
<!-- Checkout Header -->
<section class="checkout-header">
    <div class="volt-container">
        <br>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-voltronix">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">{{ __('app.nav.home') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('cart.index') }}">{{ __('app.cart.title') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ __('app.checkout.title') }}
                </li>
            </ol>
        </nav>
        
        <h1 class="display-4 fw-bold mb-3">
            <i class="bi bi-credit-card me-3"></i>{{ __('app.checkout.title') }}
        </h1>
        <p class="lead mb-0">{{ __('app.checkout.bank_transfer_info') }}</p>
    </div>
</section>

<!-- Checkout Content -->
<section class="py-5" style="background: var(--voltronix-light);">
    <div class="volt-container">
        <!-- Progress Steps -->
        <div class="progress-steps">
            <div class="step completed">
                <div class="step-number">1</div>
                <span>{{ __('app.cart.title') }}</span>
            </div>
            <div class="step-divider"></div>
            <div class="step active">
                <div class="step-number">2</div>
                <span>{{ __('app.checkout.title') }}</span>
            </div>
            <div class="step-divider"></div>
            <div class="step">
                <div class="step-number">3</div>
                <span>{{ __('app.orders.title') }}</span>
            </div>
        </div>

        <div class="row g-4">
            <!-- Checkout Form -->
            <div class="col-lg-8">
                <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
                    @csrf
                    
                    <div class="checkout-form">
                        <!-- Customer Information -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <div class="section-icon">
                                    <i class="bi bi-person"></i>
                                </div>
                                {{ __('app.checkout.customer_information') }}
                            </h3>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="customer_name" class="form-label">{{ __('app.checkout.customer_name') }} *</label>
                                    <input type="text" 
                                           class="form-control @error('customer_name') is-invalid @enderror" 
                                           id="customer_name" 
                                           name="customer_name" 
                                           value="{{ old('customer_name', $user->name ?? '') }}" 
                                           required>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="customer_email" class="form-label">{{ __('app.checkout.customer_email') }} *</label>
                                    <input type="email" 
                                           class="form-control @error('customer_email') is-invalid @enderror" 
                                           id="customer_email" 
                                           name="customer_email" 
                                           value="{{ old('customer_email', $user->email ?? '') }}" 
                                           required>
                                    @error('customer_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="customer_phone" class="form-label">{{ __('app.checkout.customer_phone') }} *</label>
                                    <input type="tel" 
                                           class="form-control @error('customer_phone') is-invalid @enderror" 
                                           id="customer_phone" 
                                           name="customer_phone" 
                                           value="{{ old('customer_phone') }}" 
                                           required>
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="notes" class="form-label">{{ __('app.checkout.notes') }}</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" 
                                              name="notes" 
                                              rows="3" 
                                              placeholder="{{ __('app.checkout.notes') }}">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <div class="section-icon">
                                    <i class="bi bi-credit-card"></i>
                                </div>
                                {{ __('app.checkout.payment_information') }}
                            </h3>
                            
                            <!-- Payment Method Selection -->
                            <div class="mb-4">
                                <label class="form-label">{{ __('app.checkout.payment_method') }} *</label>
                                <div class="row g-3">
                                    @foreach($paymentMethods as $method => $label)
                                        <div class="col-md-6">
                                            <div class="payment-method-card">
                                                <input type="radio" 
                                                       class="form-check-input @error('payment_method') is-invalid @enderror" 
                                                       id="payment_{{ $method }}" 
                                                       name="payment_method" 
                                                       value="{{ $method }}"
                                                       {{ old('payment_method') == $method ? 'checked' : '' }}
                                                       required>
                                                <label class="payment-method-label" for="payment_{{ $method }}">
                                                    <div class="payment-icon">
                                                        @if($method == 'bank_transfer')
                                                            <i class="bi bi-bank"></i>
                                                        @elseif($method == 'crypto_usdt')
                                                            <i class="bi bi-currency-dollar"></i>
                                                        @elseif($method == 'crypto_btc')
                                                            <i class="bi bi-currency-bitcoin"></i>
                                                        @elseif($method == 'mtn_cash')
                                                            <img src="{{ asset('images/payment-logos/mtn-cash.svg') }}" 
                                                                 alt="MTN Cash" 
                                                                 class="payment-logo" 
                                                                 style="height: 32px; width: auto;"
                                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                                                            <i class="bi bi-phone" style="display: none;"></i>
                                                        @elseif($method == 'syriatel_cash')
                                                            <img src="{{ asset('images/payment-logos/syriatel-cash.svg') }}" 
                                                                 alt="Syriatel Cash" 
                                                                 class="payment-logo" 
                                                                 style="height: 32px; width: auto;"
                                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                                                            <i class="bi bi-phone" style="display: none;"></i>
                                                        @endif
                                                    </div>
                                                    <div class="payment-text">
                                                        <div class="payment-title">{{ $label }}</div>
                                                        <div class="payment-desc">
                                                            @if($method == 'bank_transfer')
                                                                {{ __('app.checkout.bank_transfer_instructions') }}
                                                            @elseif($method == 'mtn_cash')
                                                                {{ __('app.checkout.mtn_cash_instructions') }}
                                                            @elseif($method == 'syriatel_cash')
                                                                {{ __('app.checkout.syriatel_cash_instructions') }}
                                                            @else
                                                                {{ __('app.checkout.crypto_instructions') }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('payment_method')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Payment Instructions -->
                            <div class="payment-instructions" id="paymentInstructions">
                                <div class="bank-info">
                                    <h6><i class="bi bi-info-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.checkout.payment_information') }}</h6>
                                    <p class="mb-0">{{ __('app.checkout.bank_transfer_info') }}</p>
                                </div>
                            </div>
                            
                            <!-- Receipt Upload -->
                            <div class="mb-3">
                                <label for="payment_proof" class="form-label">{{ __('app.checkout.payment_proof') }} *</label>
                                <div class="file-upload-area" onclick="document.getElementById('payment_proof').click()">
                                    <i class="bi bi-cloud-upload upload-icon"></i>
                                    <div class="upload-text">{{ __('app.checkout.receipt') }}</div>
                                    <div class="upload-help">{{ __('app.checkout.receipt_help') }}</div>
                                </div>
                                <input type="file" 
                                       class="form-control d-none @error('payment_proof') is-invalid @enderror" 
                                       id="payment_proof" 
                                       name="payment_proof" 
                                       accept=".jpg,.jpeg,.png,.pdf" 
                                       required>
                                @error('payment_proof')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                
                                <!-- File Preview -->
                                <div class="file-preview" id="filePreview">
                                    <div class="file-info">
                                        <div class="file-details">
                                            <i class="bi bi-file-earmark file-icon"></i>
                                            <div>
                                                <div class="file-name"></div>
                                                <div class="file-size text-muted"></div>
                                            </div>
                                        </div>
                                        <button type="button" class="remove-file" onclick="removeFile()">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="order-summary">
                    <h3 class="text-center mb-4">
                        <i class="bi bi-receipt me-2"></i>{{ __('app.checkout.order_summary') }}
                    </h3>
                    
                    <!-- Cart Items -->
                    @foreach($cartItems as $item)
                        <div class="summary-item">
                            <div class="item-image">
                                @if($item['product']->thumbnail)
                                    <img src="{{ asset('storage/' . $item['product']->thumbnail) }}" 
                                         alt="{{ $item['product']->getTranslation('name') }}">
                                @else
                                    <i class="bi bi-box-seam product-icon"></i>
                                @endif
                            </div>
                            
                            <div class="item-details">
                                <div class="item-name">{{ $item['product']->getTranslation('name') }}</div>
                                <div class="item-quantity">{{ __('app.cart.quantity') }}: {{ $item['quantity'] }}</div>
                            </div>
                            
                            <div class="item-price">{{ currency_format($item['subtotal']) }}</div>
                        </div>
                    @endforeach
                    
                    <!-- Coupon Section -->
                    <div class="coupon-section mb-4">
                        <div class="section-title">
                            <i class="fas fa-ticket-alt section-icon {{ app()->getLocale() == 'ar' ? 'ms-3' : 'me-3' }}"></i>
                            {{ __('app.checkout.coupon_code') }}
                        </div>
                        
                        <div class="coupon-form">
                            <div class="input-group">
                                <input type="text" id="couponCode" class="form-control" 
                                       placeholder="{{ __('app.checkout.enter_coupon') }}" 
                                       maxlength="50">
                                <button type="button" id="applyCouponBtn" class="btn btn-outline-primary">
                                    <i class="fas fa-check {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                    {{ __('app.checkout.apply_coupon') }}
                                </button>
                            </div>
                            <div id="couponMessage" class="mt-2" style="display: none;"></div>
                        </div>
                        
                        <!-- Applied Coupon Display -->
                        <div id="appliedCoupon" class="applied-coupon mt-3" style="display: none;">
                            <div class="alert alert-success d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-check-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                    <strong id="appliedCouponCode"></strong> - <span id="appliedCouponName"></span>
                                </div>
                                <button type="button" id="removeCouponBtn" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="discount-details">
                                <div class="d-flex justify-content-between">
                                    <span>{{ __('app.checkout.discount') }}:</span>
                                    <span class="text-success">-$<span id="discountAmount">0.00</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total -->
                    <div class="summary-total">
                        <div class="text-muted mb-2">{{ __('app.cart.total') }}</div>
                        <div class="total-amount"><span id="finalTotal">{{ currency_format($cartTotal) }}</span></div>
                        <div id="originalTotal" class="text-muted small mt-1" style="display: none;">
                            {{ __('app.checkout.original_total') }}: <span>{{ currency_format($cartTotal) }}</span>
                        </div>
                    </div>
                    
                    <!-- Place Order Button -->
                    <button type="submit" form="checkoutForm" class="btn-place-order" id="placeOrderBtn">
                        <i class="bi bi-check-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('app.checkout.place_order') }}
                    </button>
                    
                    <!-- Back to Cart -->
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-3">
                        <i class="bi bi-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }} {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                        {{ __('app.cart.title') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let appliedCouponData = null;
    const originalTotal = {{ $cartTotal }};
    
    // Coupon functionality
    const couponCodeInput = document.getElementById('couponCode');
    const applyCouponBtn = document.getElementById('applyCouponBtn');
    const couponMessage = document.getElementById('couponMessage');
    const appliedCouponDiv = document.getElementById('appliedCoupon');
    const removeCouponBtn = document.getElementById('removeCouponBtn');
    const finalTotalSpan = document.getElementById('finalTotal');
    const originalTotalDiv = document.getElementById('originalTotal');
    
    // Apply coupon
    applyCouponBtn.addEventListener('click', function() {
        const couponCode = couponCodeInput.value.trim().toUpperCase();
        
        if (!couponCode) {
            showCouponMessage('{{ __("app.checkout.enter_coupon_code") }}', 'error');
            return;
        }
        
        // Show loading state
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin {{ app()->getLocale() == "ar" ? "ms-2" : "me-2" }}"></i> {{ __("app.common.loading") }}';
        this.disabled = true;
        
        // Send AJAX request to validate coupon
        fetch('{{ route("api.coupons.validate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                code: couponCode,
                order_total: originalTotal
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.valid) {
                // Apply coupon
                appliedCouponData = data;
                applyCoupon(data);
                showCouponMessage(data.message, 'success');
            } else {
                showCouponMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showCouponMessage('{{ __("app.common.error") }}', 'error');
        })
        .finally(() => {
            // Reset button
            this.innerHTML = originalText;
            this.disabled = false;
        });
    });
    
    // Remove coupon
    removeCouponBtn.addEventListener('click', function() {
        removeCoupon();
    });
    
    // Enter key support
    couponCodeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyCouponBtn.click();
        }
    });
    
    function applyCoupon(data) {
        // Update UI
        document.getElementById('appliedCouponCode').textContent = data.coupon.code;
        document.getElementById('appliedCouponName').textContent = data.coupon.name;
        document.getElementById('discountAmount').textContent = data.discount;
        document.getElementById('finalTotal').textContent = data.new_total;
        
        // Show applied coupon section
        appliedCouponDiv.style.display = 'block';
        originalTotalDiv.style.display = 'block';
        
        // Hide coupon form
        document.querySelector('.coupon-form').style.display = 'none';
        
        // Clear input
        couponCodeInput.value = '';
        
        // Add hidden input to form for submission
        const form = document.getElementById('checkoutForm');
        let couponInput = form.querySelector('input[name="coupon_code"]');
        if (!couponInput) {
            couponInput = document.createElement('input');
            couponInput.type = 'hidden';
            couponInput.name = 'coupon_code';
            form.appendChild(couponInput);
        }
        couponInput.value = data.coupon.code;
        
        let discountInput = form.querySelector('input[name="discount_amount"]');
        if (!discountInput) {
            discountInput = document.createElement('input');
            discountInput.type = 'hidden';
            discountInput.name = 'discount_amount';
            form.appendChild(discountInput);
        }
        discountInput.value = data.discount;
    }
    
    function removeCoupon() {
        // Reset UI
        appliedCouponDiv.style.display = 'none';
        originalTotalDiv.style.display = 'none';
        document.querySelector('.coupon-form').style.display = 'block';
        document.getElementById('finalTotal').textContent = originalTotal.toFixed(2);
        
        // Remove hidden inputs
        const form = document.getElementById('checkoutForm');
        const couponInput = form.querySelector('input[name="coupon_code"]');
        const discountInput = form.querySelector('input[name="discount_amount"]');
        if (couponInput) couponInput.remove();
        if (discountInput) discountInput.remove();
        
        // Clear data
        appliedCouponData = null;
        
        // Clear message
        hideCouponMessage();
        
        // Show success message
        showCouponMessage('{{ __("app.checkout.coupon_removed") }}', 'success');
    }
    
    function showCouponMessage(message, type) {
        couponMessage.textContent = message;
        couponMessage.className = `mt-2 alert alert-${type === 'error' ? 'danger' : 'success'}`;
        couponMessage.style.display = 'block';
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            hideCouponMessage();
        }, 5000);
    }
    
    function hideCouponMessage() {
        couponMessage.style.display = 'none';
    }
});
</script>
@endpush

@push('scripts')
<script>
    // File upload handling
    document.getElementById('payment_proof').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            showFilePreview(file);
        }
    });
    
    // Drag and drop handling
    const uploadArea = document.querySelector('.file-upload-area');
    
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('payment_proof').files = files;
            showFilePreview(files[0]);
        }
    });
    
    // Show file preview
    function showFilePreview(file) {
        const preview = document.getElementById('filePreview');
        const fileName = preview.querySelector('.file-name');
        const fileSize = preview.querySelector('.file-size');
        const fileIcon = preview.querySelector('.file-icon');
        
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        
        // Update icon based on file type
        if (file.type.startsWith('image/')) {
            fileIcon.className = 'bi bi-file-earmark-image file-icon';
        } else if (file.type === 'application/pdf') {
            fileIcon.className = 'bi bi-file-earmark-pdf file-icon';
        } else {
            fileIcon.className = 'bi bi-file-earmark file-icon';
        }
        
        preview.style.display = 'block';
    }
    
    // Remove file
    function removeFile() {
        document.getElementById('payment_proof').value = '';
        document.getElementById('filePreview').style.display = 'none';
    }
    
    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 {{ __("app.common.file_sizes.bytes") }}';
        const k = 1024;
        const sizes = [
            '{{ __("app.common.file_sizes.bytes") }}', 
            '{{ __("app.common.file_sizes.kb") }}', 
            '{{ __("app.common.file_sizes.mb") }}', 
            '{{ __("app.common.file_sizes.gb") }}'
        ];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Payment method change handling
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            updatePaymentInstructions(this.value);
        });
    });
    
    // Payment details from backend
    const paymentDetails = @json($paymentDetails);
    
    // Update payment instructions based on selected method
    function updatePaymentInstructions(method) {
        const instructionsDiv = document.getElementById('paymentInstructions');
        let content = '';
        
        if (method === 'bank_transfer') {
            const bankDetails = paymentDetails.bank_transfer;
            const hasDetails = bankDetails.bank_name || bankDetails.account_name || bankDetails.account_number || bankDetails.iban;
            
            content = `
                <div class="bank-info">
                    <h6><i class="bi bi-bank {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.checkout.payment_methods.bank_transfer') }}</h6>
                    ${hasDetails ? `
                        <div class="payment-details mt-3">
                            ${bankDetails.bank_name ? `<p><strong>{{ __('admin.site_settings.bank_name') }}:</strong> ${bankDetails.bank_name}</p>` : ''}
                            ${bankDetails.account_name ? `<p><strong>{{ __('admin.site_settings.account_name') }}:</strong> ${bankDetails.account_name}</p>` : ''}
                            ${bankDetails.account_number ? `<p><strong>{{ __('admin.site_settings.account_number') }}:</strong> ${bankDetails.account_number}</p>` : ''}
                            ${bankDetails.iban ? `<p><strong>{{ __('admin.site_settings.iban') }}:</strong> ${bankDetails.iban}</p>` : ''}
                        </div>
                    ` : `<p class="mb-0">{{ __('app.checkout.bank_transfer_instructions') }}</p>`}
                </div>
            `;
        } else if (method === 'crypto_usdt') {
            const usdtDetails = paymentDetails.crypto_usdt;
            
            content = `
                <div class="bank-info" style="background: linear-gradient(135deg, #26a69a, #00695c);">
                    <h6><i class="bi bi-currency-dollar {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.checkout.payment_methods.crypto_usdt') }}</h6>
                    ${usdtDetails.wallet ? `
                        <div class="payment-details mt-3">
                            <p><strong>{{ __('admin.site_settings.usdt_wallet') }}:</strong> ${usdtDetails.wallet}</p>
                            ${usdtDetails.network ? `<p><strong>{{ __('admin.site_settings.usdt_network') }}:</strong> ${usdtDetails.network}</p>` : ''}
                        </div>
                    ` : `<p class="mb-0">{{ __('app.checkout.crypto_instructions') }}</p>`}
                </div>
            `;
        } else if (method === 'crypto_btc') {
            const btcDetails = paymentDetails.crypto_btc;
            
            content = `
                <div class="bank-info" style="background: linear-gradient(135deg, #ff9800, #f57c00);">
                    <h6><i class="bi bi-currency-bitcoin {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.checkout.payment_methods.crypto_btc') }}</h6>
                    ${btcDetails.wallet ? `
                        <div class="payment-details mt-3">
                            <p><strong>{{ __('admin.site_settings.btc_wallet') }}:</strong> ${btcDetails.wallet}</p>
                        </div>
                    ` : `<p class="mb-0">{{ __('app.checkout.crypto_instructions') }}</p>`}
                </div>
            `;
        } else if (method === 'mtn_cash') {
            const mtnDetails = paymentDetails.mtn_cash;
            
            content = `
                <div class="bank-info" style="background: linear-gradient(135deg, #FFD700, #FFA500);">
                    <h6>
                        <img src="{{ asset('images/payment-logos/mtn-cash.svg') }}" 
                             alt="MTN Cash" 
                             class="payment-logo {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}" 
                             style="height: 24px; width: auto; vertical-align: middle;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                        <i class="bi bi-phone {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}" style="display: none;"></i>
                        {{ __('app.checkout.payment_methods.mtn_cash') }}
                    </h6>
                    ${mtnDetails.phone ? `
                        <div class="payment-details mt-3">
                            <p><strong>{{ __('app.checkout.phone_number') }}:</strong> ${mtnDetails.phone}</p>
                            <p class="mb-0"><i class="bi bi-info-circle {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>{{ __('app.checkout.send_amount_to') }}</p>
                        </div>
                    ` : `<p class="mb-0">{{ __('app.checkout.mtn_cash_instructions') }}</p>`}
                </div>
            `;
        } else if (method === 'syriatel_cash') {
            const syriatelDetails = paymentDetails.syriatel_cash;
            
            content = `
                <div class="bank-info" style="background: linear-gradient(135deg, #DC143C, #B22222);">
                    <h6>
                        <img src="{{ asset('images/payment-logos/syriatel-cash.svg') }}" 
                             alt="Syriatel Cash" 
                             class="payment-logo {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}" 
                             style="height: 24px; width: auto; vertical-align: middle;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                        <i class="bi bi-phone {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}" style="display: none;"></i>
                        {{ __('app.checkout.payment_methods.syriatel_cash') }}
                    </h6>
                    ${syriatelDetails.phone ? `
                        <div class="payment-details mt-3">
                            <p><strong>{{ __('app.checkout.phone_number') }}:</strong> ${syriatelDetails.phone}</p>
                            <p class="mb-0"><i class="bi bi-info-circle {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>{{ __('app.checkout.send_amount_to') }}</p>
                        </div>
                    ` : `<p class="mb-0">{{ __('app.checkout.syriatel_cash_instructions') }}</p>`}
                </div>
            `;
        }
        
        instructionsDiv.innerHTML = content;
    }
    
    // Form submission handling with SweetAlert2
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        if (!validateForm()) {
            Swal.fire({
                icon: 'error',
                title: '{{ __('app.common.error') }}',
                text: '{{ __('app.checkout.please_fill_required_fields') }}',
                confirmButtonColor: '#007fff',
                confirmButtonText: '{{ __('app.common.ok') }}'
            });
            return;
        }
        
        const submitBtn = document.getElementById('placeOrderBtn');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin {{ app()->getLocale() == "ar" ? "ms-2" : "me-2" }}"></i> {{ __("app.checkout.processing") }}';
        
        // Submit form
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.redirected) {
                // Handle successful redirect
                window.location.href = response.url;
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success === false) {
                // Handle validation errors
                if (data.errors) {
                    displayValidationErrors(data.errors);
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("app.common.validation_failed") }}',
                        text: data.message || '{{ __("app.checkout.please_fix_errors") }}',
                        confirmButtonColor: '#007fff',
                        confirmButtonText: '{{ __("app.common.ok") }}'
                    });
                } else {
                    // General error
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("app.checkout.order_failed") }}',
                        text: data.message || '{{ __("app.common.error_occurred") }}',
                        confirmButtonColor: '#007fff',
                        confirmButtonText: '{{ __("app.common.ok") }}'
                    });
                }
            } else if (data && data.success === true) {
                // Handle success response
                Swal.fire({
                    icon: 'success',
                    title: '{{ __("app.checkout.order_placed_successfully") }}',
                    text: data.message || '{{ __("app.checkout.order_success_message") }}',
                    confirmButtonColor: '#007fff',
                    confirmButtonText: '{{ __("app.orders.view_details") }}'
                }).then(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.href = '{{ route("orders.index") }}';
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            Swal.fire({
                icon: 'error',
                title: '{{ __("app.checkout.order_failed") }}',
                text: '{{ __("app.common.please_try_again") }}',
                confirmButtonColor: '#007fff',
                confirmButtonText: '{{ __("app.common.ok") }}'
            });
        })
        .finally(() => {
            // Reset button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
    
    // Validate form before submission
    function validateForm() {
        const requiredFields = ['customer_name', 'customer_email', 'customer_phone', 'payment_method', 'payment_proof'];
        let isValid = true;
        
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName) || document.querySelector(`input[name="${fieldName}"]`);
            if (field) {
                if (fieldName === 'payment_method') {
                    const checkedMethod = document.querySelector('input[name="payment_method"]:checked');
                    if (!checkedMethod) {
                        document.querySelector('input[name="payment_method"]').classList.add('is-invalid');
                        isValid = false;
                    } else {
                        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
                            radio.classList.remove('is-invalid');
                        });
                    }
                } else if (!field.value || !field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            }
        });
        
        return isValid;
    }
    
    // Display validation errors function
    function displayValidationErrors(errors) {
        // Clear previous errors
        document.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(feedback => {
            feedback.style.display = 'none';
        });
        
        // Display new errors
        Object.keys(errors).forEach(fieldName => {
            const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.classList.add('is-invalid');
                
                // Find or create error message element
                let errorElement = field.parentNode.querySelector('.invalid-feedback');
                if (!errorElement) {
                    errorElement = document.createElement('div');
                    errorElement.className = 'invalid-feedback';
                    field.parentNode.appendChild(errorElement);
                }
                
                errorElement.textContent = errors[fieldName][0];
                errorElement.style.display = 'block';
            }
        });
    }
    
    // Initialize payment instructions on page load
    document.addEventListener('DOMContentLoaded', function() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        if (selectedMethod) {
            updatePaymentInstructions(selectedMethod.value);
        } else {
            // Default to bank transfer
            const bankTransferRadio = document.getElementById('payment_bank_transfer');
            if (bankTransferRadio) {
                bankTransferRadio.checked = true;
                updatePaymentInstructions('bank_transfer');
            }
        }
    });
</script>
@endpush
