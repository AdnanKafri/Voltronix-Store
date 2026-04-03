/**
 * Voltronix Digital Store - Cart Management System
 * Modern AJAX-based cart operations with SweetAlert2 notifications
 */

class VoltronixCart {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.locale = document.documentElement.lang || 'en';
        this.isRTL = this.locale === 'ar';
        this.init();
    }

    init() {
        // Initialize SweetAlert2 defaults
        this.configureSweetAlert();

        // Update cart badge on page load
        this.updateCartBadge();
    }

    configureSweetAlert() {
        // Set default SweetAlert2 configuration
        if (typeof Swal !== 'undefined') {
            Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-primary mx-2',
                    cancelButton: 'btn btn-secondary mx-2'
                },
                buttonsStyling: false,
                reverseButtons: this.isRTL
            });
        }
    }

    /**
     * Add product to cart
     * @param {number} productId - Product ID to add
     * @param {number} quantity - Quantity to add (default: 1)
     */
    async addToCart(productId, quantity = 1) {
        if (!productId) {
            this.showError(this.getTranslation('invalid_product'));
            return;
        }

        this.showLoading();

        try {
            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showSuccess(data.message);
                this.updateCartDisplay(data.cart_summary);

                // Show auth prompt if needed
                if (data.show_auth_prompt) {
                    this.showAuthPrompt();
                }
            } else {
                this.showError(data.message);
            }
        } catch (error) {
            console.error('Cart error:', error);
            this.showError(this.getTranslation('error_occurred'));
        } finally {
            this.hideLoading();
        }
    }

    /**
     * Update product quantity in cart
     * @param {number} productId - Product ID
     * @param {number} quantity - New quantity
     */
    async updateQuantity(productId, quantity) {
        if (!productId || quantity < 0) {
            this.showError(this.getTranslation('invalid_quantity'));
            return;
        }

        this.showLoading();

        try {
            const response = await fetch('/cart/update', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showSuccess(data.message);
                this.updateCartDisplay(data.cart_summary);

                // Handle item removal
                if (data.item_removed) {
                    this.removeCartItemFromDOM(productId);
                }
            } else {
                this.showError(data.message);
            }
        } catch (error) {
            console.error('Cart update error:', error);
            this.showError(this.getTranslation('error_occurred'));
        } finally {
            this.hideLoading();
        }
    }

    /**
     * Remove product from cart
     * @param {number} productId - Product ID to remove
     */
    async removeFromCart(productId) {
        if (!productId) {
            this.showError(this.getTranslation('invalid_product'));
            return;
        }

        // Show confirmation dialog
        const result = await Swal.fire({
            title: this.getTranslation('remove_item'),
            text: this.getTranslation('remove_confirm'),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: this.getTranslation('yes_remove'),
            cancelButtonText: this.getTranslation('cancel'),
            reverseButtons: this.isRTL
        });

        if (!result.isConfirmed) return;

        this.showLoading();

        try {
            const response = await fetch('/cart/remove', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showSuccess(data.message);
                this.updateCartDisplay(data.cart_summary);
                this.removeCartItemFromDOM(productId);
            } else {
                this.showError(data.message);
            }
        } catch (error) {
            console.error('Cart remove error:', error);
            this.showError(this.getTranslation('error_occurred'));
        } finally {
            this.hideLoading();
        }
    }

    /**
     * Clear entire cart
     */
    async clearCart() {
        // Show confirmation dialog
        const result = await Swal.fire({
            title: this.getTranslation('clear_cart'),
            text: this.getTranslation('clear_confirm'),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: this.getTranslation('yes_clear'),
            cancelButtonText: this.getTranslation('cancel'),
            reverseButtons: this.isRTL
        });

        if (!result.isConfirmed) return;

        this.showLoading();

        try {
            const response = await fetch('/cart/clear', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showSuccess(data.message);
                this.updateCartDisplay(data.cart_summary);

                // Reload page if on cart page
                if (window.location.pathname === '/cart') {
                    setTimeout(() => window.location.reload(), 1000);
                }
            } else {
                this.showError(data.message);
            }
        } catch (error) {
            console.error('Cart clear error:', error);
            this.showError(this.getTranslation('error_occurred'));
        } finally {
            this.hideLoading();
        }
    }

    /**
     * Update cart display elements
     * @param {Object} cartSummary - Cart summary data
     */
    updateCartDisplay(cartSummary) {
        if (!cartSummary) return;

        // Update cart badge
        const cartBadge = document.querySelector('.cart-badge');
        if (cartBadge) {
            const newCount = cartSummary.count || 0;
            cartBadge.textContent = newCount;
            cartBadge.setAttribute('data-count', newCount);

            // Show/hide badge based on count
            if (newCount > 0) {
                cartBadge.style.display = 'flex';
                // Add bounce animation
                cartBadge.style.animation = 'none';
                setTimeout(() => {
                    cartBadge.style.animation = 'cartBadgeBounce 0.5s ease';
                }, 10);
            } else {
                cartBadge.style.display = 'none';
            }
        }

        // Update cart total if on cart page
        const cartTotal = document.querySelector('.cart-total');
        if (cartTotal) {
            cartTotal.textContent = cartSummary.formatted_total;

            // Add animation effect
            cartTotal.style.transform = 'scale(1.1)';
            cartTotal.style.color = '#007fff';
            setTimeout(() => {
                cartTotal.style.transform = 'scale(1)';
                cartTotal.style.color = '';
            }, 300);
        }

        // Update cart count display
        const cartCount = document.querySelector('.cart-count');
        if (cartCount) {
            cartCount.textContent = cartSummary.count || 0;
        }
    }

    /**
     * Update cart badge from server
     */
    async updateCartBadge() {
        try {
            const response = await fetch('/api/cart/summary');
            const data = await response.json();

            if (data.success) {
                this.updateCartDisplay(data.cart_summary);
            }
        } catch (error) {
            console.error('Failed to update cart badge:', error);
        }
    }

    /**
     * Remove cart item from DOM
     * @param {number} productId - Product ID
     */
    removeCartItemFromDOM(productId) {
        const cartItem = document.querySelector(`[data-product-id="${productId}"]`);
        if (cartItem) {
            cartItem.style.transition = 'all 0.3s ease';
            cartItem.style.opacity = '0';
            cartItem.style.transform = 'translateX(-100%)';

            setTimeout(() => {
                cartItem.remove();

                // Check if cart is empty
                const remainingItems = document.querySelectorAll('.cart-item');
                if (remainingItems.length === 0) {
                    this.showEmptyCart();
                }
            }, 300);
        }
    }

    /**
     * Show empty cart message
     */
    showEmptyCart() {
        const cartContainer = document.querySelector('.cart-items-container');
        if (cartContainer) {
            cartContainer.innerHTML = `
                <div class="empty-cart text-center py-5">
                    <i class="bi bi-cart-x empty-cart-icon"></i>
                    <h3 class="empty-cart-title">${this.getTranslation('empty_cart')}</h3>
                    <p class="empty-cart-text">${this.getTranslation('empty_cart_message')}</p>
                    <a href="/products" class="btn btn-primary">
                        <i class="bi bi-shop ${this.isRTL ? 'ms-2' : 'me-2'}"></i>
                        ${this.getTranslation('continue_shopping')}
                    </a>
                </div>
            `;
        }
    }

    /**
     * Show authentication prompt
     */
    showAuthPrompt() {
        Swal.fire({
            title: this.getTranslation('login_required'),
            text: this.getTranslation('login_to_checkout'),
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#007fff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: this.getTranslation('login'),
            cancelButtonText: this.getTranslation('continue_shopping'),
            reverseButtons: this.isRTL
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/login';
            }
        });
    }

    /**
     * Show loading overlay using global Voltronix spinner
     */
    showLoading() {
        const message = this.getTranslation('loading');
        if (window.showSpinner) {
            window.showSpinner(message);
        } else {
            // Fallback for legacy browsers
            console.log('Loading:', message);
        }
    }

    /**
     * Hide loading overlay using global Voltronix spinner
     */
    hideLoading() {
        if (window.hideSpinner) {
            window.hideSpinner();
        }
    }

    /**
     * Show success notification
     * @param {string} message - Success message
     */
    showSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: {
                popup: 'colored-toast'
            }
        });
    }

    /**
     * Show error notification
     * @param {string} message - Error message
     */
    showError(message) {
        Swal.fire({
            icon: 'error',
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            customClass: {
                popup: 'colored-toast'
            }
        });
    }

    /**
     * Get translation based on current locale
     * @param {string} key - Translation key
     * @returns {string} - Translated text
     */
    getTranslation(key) {
        const translations = {
            en: {
                invalid_product: 'Invalid product selected',
                invalid_quantity: 'Invalid quantity specified',
                error_occurred: 'An error occurred. Please try again.',
                remove_item: 'Remove Item',
                remove_confirm: 'This item will be removed from your cart.',
                yes_remove: 'Yes, Remove',
                cancel: 'Cancel',
                clear_cart: 'Clear Cart',
                clear_confirm: 'All items will be removed from your cart.',
                yes_clear: 'Yes, Clear Cart',
                empty_cart: 'Your cart is empty',
                empty_cart_message: 'Add some products to get started!',
                continue_shopping: 'Continue Shopping',
                login_required: 'Login Required',
                login_to_checkout: 'Please login to proceed to checkout.',
                login: 'Login',
                loading: 'Loading...'
            },
            ar: {
                invalid_product: 'منتج غير صالح محدد',
                invalid_quantity: 'كمية غير صالحة محددة',
                error_occurred: 'حدث خطأ. يرجى المحاولة مرة أخرى.',
                remove_item: 'إزالة العنصر',
                remove_confirm: 'سيتم إزالة هذا العنصر من سلتك.',
                yes_remove: 'نعم، إزالة',
                cancel: 'إلغاء',
                clear_cart: 'إفراغ السلة',
                clear_confirm: 'سيتم إزالة جميع العناصر من سلتك.',
                yes_clear: 'نعم، إفراغ السلة',
                empty_cart: 'سلتك فارغة',
                empty_cart_message: 'أضف بعض المنتجات للبدء!',
                continue_shopping: 'متابعة التسوق',
                login_required: 'تسجيل الدخول مطلوب',
                login_to_checkout: 'يرجى تسجيل الدخول للمتابعة إلى الدفع.',
                login: 'تسجيل الدخول',
                loading: 'جاري التحميل...'
            }
        };

        return translations[this.locale]?.[key] || translations.en[key] || key;
    }
}

// Initialize cart system when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    window.voltronixCart = new VoltronixCart();
});

// Global functions for backward compatibility
function addToCart(productId, quantity = 1) {
    if (window.voltronixCart) {
        window.voltronixCart.addToCart(productId, quantity);
    }
}

function updateCartQuantity(productId, quantity) {
    if (window.voltronixCart) {
        window.voltronixCart.updateQuantity(productId, quantity);
    }
}

function removeFromCart(productId) {
    if (window.voltronixCart) {
        window.voltronixCart.removeFromCart(productId);
    }
}

function clearCart() {
    if (window.voltronixCart) {
        window.voltronixCart.clearCart();
    }
}
