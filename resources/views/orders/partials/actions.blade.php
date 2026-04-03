<div class="action-buttons">
    @if($order->canBeCancelled())
    <div class="cancel-section">
        <button type="button" class="btn btn-outline-danger btn-block cancel-btn" 
                data-order-id="{{ $order->id }}"
                data-cancel-url="{{ route('orders.cancel', $order) }}">
            <i class="bi bi-x-circle {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
            {{ __('orders.cancel_order') }}
        </button>
        @if($order->getCancellationTimeRemaining() > 0)
        <div class="time-remaining">
            <i class="bi bi-clock text-warning"></i>
            <small class="text-muted">
                {{ __('orders.time_remaining') }}: 
                <span class="countdown-timer" data-minutes="{{ $order->getCancellationTimeRemaining() }}">
                    {{ $order->getCancellationTimeRemaining() }} {{ __('orders.minutes') }}
                </span>
            </small>
        </div>
        @endif
    </div>
    @else
    <div class="cancel-section">
        <button type="button" class="btn btn-outline-secondary btn-block" disabled
                data-bs-toggle="tooltip" 
                title="{{ __('orders.cannot_cancel_tooltip') }}">
            <i class="bi bi-x-circle {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
            {{ __('orders.cancel_order') }}
        </button>
        <small class="text-muted mt-1">
            <i class="bi bi-info-circle"></i>
            {{ $order->isPending() ? __('orders.cancellation_expired') : __('orders.cannot_cancel_status') }}
        </small>
    </div>
    @endif
    
    <button type="button" class="btn btn-primary btn-block print-btn" onclick="window.print()">
        <i class="bi bi-printer {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
        {{ __('orders.print_invoice') }}
    </button>
    
    <a href="{{ route('orders.index') }}" class="btn btn-outline-primary btn-block">
        <i class="bi bi-arrow-left {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
        {{ __('orders.back_to_orders') }}
    </a>
</div>

<style>
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.action-buttons .btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    font-weight: 500;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.action-buttons .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-primary {
    background: linear-gradient(135deg, #007fff, #23efff);
    border: none;
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3, #1bc7e6);
    color: white;
}

.btn-outline-primary {
    border: 2px solid #007fff;
    color: #007fff;
    background: transparent;
}

.btn-outline-primary:hover {
    background: #007fff;
    color: white;
}

.btn-outline-danger {
    border: 2px solid #dc3545;
    color: #dc3545;
    background: transparent;
}

.btn-outline-danger:hover {
    background: #dc3545;
    color: white;
}

.cancel-section {
    margin-bottom: 1rem;
}

.time-remaining {
    margin-top: 0.5rem;
    padding: 0.5rem;
    background: rgba(255, 193, 7, 0.1);
    border-radius: 8px;
    border-left: 3px solid #ffc107;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.countdown-timer {
    font-weight: 600;
    color: #856404;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.voltronix-swal {
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0, 127, 255, 0.2);
}

@media (max-width: 768px) {
    .action-buttons .btn {
        padding: 1rem;
        font-size: 0.9rem;
    }
    
    .time-remaining {
        flex-direction: column;
        text-align: center;
        gap: 0.25rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Countdown timer for cancellation
    const countdownTimer = document.querySelector('.countdown-timer');
    if (countdownTimer) {
        let minutes = parseInt(countdownTimer.dataset.minutes);
        
        const updateTimer = () => {
            if (minutes <= 0) {
                location.reload(); // Refresh page when time expires
                return;
            }
            
            countdownTimer.textContent = `${minutes} {{ __("orders.minutes") }}`;
            minutes--;
        };
        
        // Update every minute
        const interval = setInterval(updateTimer, 60000);
        
        // Clean up on page unload
        window.addEventListener('beforeunload', () => clearInterval(interval));
    }

    // Modern AJAX cancel order with SweetAlert2
    const cancelBtn = document.querySelector('.cancel-btn');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const orderId = this.dataset.orderId;
            const cancelUrl = this.dataset.cancelUrl;
            
            Swal.fire({
                title: '{{ __("orders.cancel_order_confirm") }}',
                text: '{{ __("orders.cancel_order_text") }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '{{ __("orders.yes_cancel") }}',
                cancelButtonText: '{{ __("orders.no_keep") }}',
                reverseButtons: {{ app()->getLocale() == 'ar' ? 'true' : 'false' }},
                background: 'linear-gradient(135deg, #ffffff, #f8f9fa)',
                customClass: {
                    popup: 'voltronix-swal',
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: '{{ __("app.common.loading") }}',
                        text: '{{ __("orders.cancelling_order") }}',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        background: 'linear-gradient(135deg, #ffffff, #f8f9fa)',
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Send AJAX request
                    fetch(cancelUrl, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: '{{ __("app.common.success") }}',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#007fff',
                                background: 'linear-gradient(135deg, #ffffff, #f8f9fa)',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            }).then(() => {
                                // Redirect to orders list or reload page
                                window.location.href = '{{ route("orders.index") }}';
                            });
                        } else {
                            throw new Error(data.message || 'Cancellation failed');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: '{{ __("app.common.error") }}',
                            text: error.message || '{{ __("orders.cancellation_failed") }}',
                            icon: 'error',
                            confirmButtonColor: '#dc3545',
                            background: 'linear-gradient(135deg, #ffffff, #f8f9fa)',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            },
                            buttonsStyling: false
                        });
                    });
                }
            });
        });
    }
});
</script>
