@extends('layouts.app')

@section('title', __('app.cart.title') . ' - ' . __('app.hero.title'))
@section('description', __('app.cart.title'))

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush



@section('content')
<!-- Cart Header -->
<section class="cart-header">
    <div class="volt-container">
        <div class="header-content">
            <br>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-voltronix">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">{{ __('app.nav.home') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __('app.cart.title') }}
                    </li>
                </ol>
            </nav>
            
            <h1 class="display-4 fw-bold mb-3">
                <i class="bi bi-cart3 me-3"></i>{{ __('app.cart.title') }}
            </h1>
            <p class="lead mb-0" id="cartCount">
                @if($cartCount > 0)
                    {{ trans_choice('app.cart.item_count', $cartCount, ['count' => $cartCount]) }}
                @else
                    {{ __('app.cart.empty') }}
                @endif
            </p>
        </div>
    </div>
</section>

<!-- Cart Content -->
<section class="py-5" style="background: var(--voltronix-light);">
    <div class="volt-container">
        @if(count($cartItems) > 0)
            <div class="row g-4">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <!-- Cart Actions -->
                    <div class="cart-actions">
                        <div>
                            <button class="btn btn-outline-secondary" onclick="updateAllQuantities()">
                                <i class="bi bi-arrow-clockwise {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                {{ __('app.cart.update_cart') }}
                            </button>
                        </div>
                        <div>
                            <button type="button" 
                                    class="btn btn-outline-danger"
                                    onclick="clearCart()">
                                <i class="bi bi-trash {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                {{ __('app.cart.clear_cart') }}
                            </button>
                        </div>
                    </div>

                    <!-- Cart Table -->
                    <div class="cart-table">
                        @foreach($cartItems as $item)
                            <div class="cart-item" data-product-id="{{ $item['product']->id }}">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-2">
                                        <div class="product-image">
                                            @if($item['product']->thumbnail)
                                                <img src="{{ asset('storage/' . $item['product']->thumbnail) }}" 
                                                     alt="{{ $item['product']->getTranslation('name') }}">
                                            @else
                                                <i class="bi bi-box-seam product-icon"></i>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="product-info">
                                            <h5 class="product-name">{{ $item['product']->getTranslation('name') }}</h5>
                                            <div class="product-price">{{ currency_format($item['product']->price) }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="quantity-controls">
                                            <button class="quantity-btn" onclick="updateQuantity({{ $item['product']->id }}, {{ $item['quantity'] - 1 }})" {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <span class="quantity-display" data-product-id="{{ $item['product']->id }}">{{ $item['quantity'] }}</span>
                                            <button class="quantity-btn" onclick="updateQuantity({{ $item['product']->id }}, {{ $item['quantity'] + 1 }})" {{ $item['quantity'] >= 99 ? 'disabled' : '' }}>
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <div class="text-center">
                                            <strong class="subtotal">{{ currency_format($item['subtotal']) }}</strong>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-1">
                                        <button class="remove-btn" onclick="removeFromCart({{ $item['product']->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Cart Summary -->
                <div class="col-lg-4">
                    <div class="cart-summary">
                        <h3 class="summary-title">{{ __('app.checkout.order_summary') }}</h3>
                        
                        <div class="summary-row">
                            <span class="summary-label">{{ __('app.cart.subtotal') }}</span>
                            <span class="summary-value" id="cartSubtotal">{{ currency_format($cartTotal) }}</span>
                        </div>
                        
                        <div class="summary-row">
                            <span class="summary-label">{{ __('app.cart.total') }}</span>
                            <span class="summary-value" id="cartTotal">{{ currency_format($cartTotal) }}</span>
                        </div>
                        
                        <a href="{{ route('checkout.index') }}" class="btn-checkout">
                            <i class="bi bi-credit-card {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('app.cart.proceed_to_checkout') }}
                        </a>
                        
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary w-100 mt-2" style="padding: 1rem 2rem; font-size: 1rem; font-weight: 600;">
                            <i class="bi bi-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }} {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                            {{ __('app.cart.continue_shopping') }}
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="empty-cart">
                <i class="bi bi-cart-x"></i>
                <h3>{{ __('app.cart.empty') }}</h3>
                <p class="mb-4">{{ __('app.cart.continue_shopping') }}</p>
                <a href="{{ route('products.index') }}" class="btn btn-voltronix btn-lg">
                    <i class="bi bi-shop {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('app.cart.continue_shopping') }}
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Loading handled by global Voltronix spinner system -->
@endsection

@push('scripts')
<script>
    // Update product quantity
    function updateQuantity(productId, quantity) {
        if (quantity < 1) {
            removeFromCart(productId);
            return;
        }
        
        showLoading();
        
        fetch('{{ route("cart.update") }}', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: parseInt(quantity)
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.success) {
                updateCartDisplay(data);
                
                // Update the specific item's quantity display and subtotal
                const productRow = document.querySelector(`[data-product-id="${productId}"]`);
                if (productRow) {
                    // Update quantity display
                    const quantityDisplay = productRow.querySelector('.quantity-display');
                    if (quantityDisplay) {
                        quantityDisplay.textContent = quantity;
                        quantityDisplay.style.transform = 'scale(1.2)';
                        quantityDisplay.style.color = 'var(--voltronix-primary)';
                        setTimeout(() => {
                            quantityDisplay.style.transform = 'scale(1)';
                            quantityDisplay.style.color = '';
                        }, 300);
                    }
                    
                    // Update quantity buttons state
                    const minusBtn = productRow.querySelector('.quantity-btn:first-child');
                    const plusBtn = productRow.querySelector('.quantity-btn:last-child');
                    if (minusBtn) {
                        minusBtn.disabled = quantity <= 1;
                        minusBtn.onclick = () => updateQuantity(productId, quantity - 1);
                    }
                    if (plusBtn) {
                        plusBtn.disabled = quantity >= 99;
                        plusBtn.onclick = () => updateQuantity(productId, quantity + 1);
                    }
                    
                    // Update subtotal
                    const subtotalElement = productRow.querySelector('.subtotal');
                    if (subtotalElement && data.item_subtotal_formatted) {
                        subtotalElement.textContent = data.item_subtotal_formatted;
                        subtotalElement.style.transform = 'scale(1.1)';
                        subtotalElement.style.color = 'var(--voltronix-primary)';
                        setTimeout(() => {
                            subtotalElement.style.transform = 'scale(1)';
                            subtotalElement.style.color = '';
                        }, 300);
                    }
                }
                
                showNotification('{{ __("app.cart.updated_successfully") }}', 'success');
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            hideLoading();
            showNotification('{{ __("app.cart.update_failed") }}', 'error');
        });
    }
    
    // Remove product from cart
    function removeFromCart(productId) {
        Swal.fire({
            title: '{{ __("app.cart.remove_item") }}?',
            text: '{{ __("app.cart.remove_confirm") }}',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '{{ __("app.common.yes_remove") }}',
            cancelButtonText: '{{ __("app.common.cancel") }}',
            reverseButtons: {{ app()->getLocale() == 'ar' ? 'true' : 'false' }}
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                
                fetch('{{ route("cart.remove") }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    
                    if (data.success) {
                        // Remove item from DOM
                        const cartItem = document.querySelector(`[data-product-id="${productId}"]`);
                        if (cartItem) {
                            cartItem.remove();
                        }
                        
                        updateCartDisplay(data);
                        showNotification(data.message, 'success');
                        
                        // Reload page if cart is empty
                        if (data.cart_count === 0) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    hideLoading();
                    showNotification('{{ __("app.cart.remove_failed") }}', 'error');
                });
            }
        });
    }
    
    // Update cart display
    function updateCartDisplay(data) {
        // Update cart count in header
        const cartCountElement = document.getElementById('cartCount');
        if (cartCountElement) {
            cartCountElement.textContent = data.cart_count > 0 ? 
                `${data.cart_count} {{ __('app.cart.item_count') }}` : 
                '{{ __('app.cart.empty') }}';
        }
        
        // Update cart totals
        const cartSubtotal = document.getElementById('cartSubtotal');
        const cartTotal = document.getElementById('cartTotal');
        
        if (cartSubtotal && data.cart_total_formatted !== undefined) {
            cartSubtotal.textContent = data.cart_total_formatted;
        }
        
        if (cartTotal && data.cart_total_formatted !== undefined) {
            cartTotal.textContent = data.cart_total_formatted;
        }
        
        // Update cart badge
        updateCartBadge();
        
        // Add visual feedback with animation
        if (cartSubtotal) {
            cartSubtotal.style.transform = 'scale(1.1)';
            cartSubtotal.style.color = 'var(--voltronix-primary)';
            setTimeout(() => {
                cartSubtotal.style.transform = 'scale(1)';
                cartSubtotal.style.color = '';
            }, 300);
        }
        
        if (cartTotal) {
            cartTotal.style.transform = 'scale(1.1)';
            cartTotal.style.color = 'var(--voltronix-primary)';
            setTimeout(() => {
                cartTotal.style.transform = 'scale(1)';
                cartTotal.style.color = '';
            }, 300);
        }
    }
    
    // Show loading overlay using global Voltronix spinner
    function showLoading() {
        if (window.showSpinner) {
            window.showSpinner('{{ __("app.common.loading") }}');
        }
    }
    
    // Hide loading overlay using global Voltronix spinner
    function hideLoading() {
        if (window.hideSpinner) {
            window.hideSpinner();
        }
    }
    
    // Clear entire cart
    function clearCart() {
        Swal.fire({
            title: '{{ __("app.cart.clear_cart") }}?',
            text: '{{ __("app.cart.clear_confirm") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '{{ __("app.common.yes_clear") }}',
            cancelButtonText: '{{ __("app.common.cancel") }}',
            reverseButtons: {{ app()->getLocale() == 'ar' ? 'true' : 'false' }}
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                
                fetch('{{ route("cart.clear") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    
                    if (data.success) {
                        showNotification(data.message, 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    hideLoading();
                    showNotification('{{ __("app.cart.clear_failed") }}', 'error');
                });
            }
        });
    }
    
    // Update all quantities (for update cart button)
    function updateAllQuantities() {
        showLoading();
        
        // Get all quantity displays
        const quantityDisplays = document.querySelectorAll('.quantity-display');
        const updates = [];
        
        quantityDisplays.forEach(display => {
            const productId = display.dataset.productId;
            const quantity = parseInt(display.textContent);
            updates.push({ product_id: productId, quantity: quantity });
        });
        
        // Send batch update request
        Promise.all(updates.map(update => 
            fetch('{{ route("cart.update") }}', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(update)
            })
        ))
        .then(responses => Promise.all(responses.map(r => r.json())))
        .then(results => {
            hideLoading();
            
            const allSuccessful = results.every(result => result.success);
            
            if (allSuccessful) {
                showNotification('{{ __("app.cart.updated_successfully") }}', 'success');
                updateCartDisplay(results[results.length - 1]); // Use last result for totals
            } else {
                showNotification('{{ __("app.cart.update_failed") }}', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            showNotification('{{ __("app.cart.update_failed") }}', 'error');
        });
    }

    // Show notification
    function showNotification(message, type) {
        Swal.fire({
            icon: type === 'success' ? 'success' : 'error',
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    }
</script>
@endpush
