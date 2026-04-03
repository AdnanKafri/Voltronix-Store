<!-- Order Summary -->
<div class="summary-card">
    <div class="card-header">
        <h3 class="section-title">
            <i class="bi bi-receipt"></i>
            {{ __('orders.order_summary') }}
        </h3>
    </div>
    <div class="card-body">
        <!-- Order Totals -->
        <div class="summary-section">
            <div class="summary-row">
                <span class="summary-label">{{ __('orders.subtotal') }}</span>
                <span class="summary-value">{{ $order->formatted_total }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">{{ __('orders.tax') }}</span>
                <span class="summary-value">{{ __('orders.included') }}</span>
            </div>
            <div class="summary-row total-row">
                <span class="summary-label">{{ __('orders.total') }}</span>
                <span class="summary-value total-value">{{ $order->formatted_total }}</span>
            </div>
        </div>

        <!-- Order Actions -->
        <div class="order-actions">
            @if($order->canBeCancelled())
            <form action="{{ route('orders.cancel', $order) }}" method="POST" class="cancel-form">
                @csrf
                @method('PATCH')
                <button type="button" class="btn btn-outline-danger btn-block cancel-btn">
                    <i class="bi bi-x-circle"></i>
                    {{ __('orders.cancel_order') }}
                </button>
            </form>
            @endif
            
            <a href="{{ route('orders.index') }}" class="btn btn-outline-primary btn-block">
                <i class="bi bi-arrow-left"></i>
                {{ __('orders.back_to_orders') }}
            </a>
        </div>

        <!-- Admin Response -->
        @if($order->admin_notes)
        <div class="admin-response">
            <h5 class="admin-title">
                <i class="bi bi-person-badge"></i>
                {{ __('orders.admin_response') }}
            </h5>
            <div class="admin-message">
                {{ $order->admin_notes }}
            </div>
            @if($order->approved_at)
            <div class="admin-date">
                {{ __('orders.approved_on') }}: {{ $order->approved_at->format('M d, Y H:i') }}
            </div>
            @elseif($order->rejected_at)
            <div class="admin-date">
                {{ __('orders.rejected_on') }}: {{ $order->rejected_at->format('M d, Y H:i') }}
            </div>
            @endif
        </div>
        @endif

        <!-- Order Timeline -->
        <div class="order-timeline">
            <h5 class="timeline-title">
                <i class="bi bi-clock-history"></i>
                {{ __('orders.order_timeline') }}
            </h5>
            <div class="timeline-items">
                <div class="timeline-item active">
                    <div class="timeline-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-title">{{ __('orders.order_placed') }}</div>
                        <div class="timeline-date">{{ $order->created_at->format('M d, Y H:i') }}</div>
                    </div>
                </div>
                
                @if($order->approved_at)
                <div class="timeline-item active">
                    <div class="timeline-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-title">{{ __('orders.order_approved') }}</div>
                        <div class="timeline-date">{{ $order->approved_at->format('M d, Y H:i') }}</div>
                    </div>
                </div>
                @elseif($order->rejected_at)
                <div class="timeline-item rejected">
                    <div class="timeline-icon">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-title">{{ __('orders.order_rejected') }}</div>
                        <div class="timeline-date">{{ $order->rejected_at->format('M d, Y H:i') }}</div>
                    </div>
                </div>
                @else
                <div class="timeline-item pending">
                    <div class="timeline-icon">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-title">{{ __('orders.awaiting_approval') }}</div>
                        <div class="timeline-date">{{ __('orders.pending_review') }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.summary-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(0, 127, 255, 0.1);
    position: sticky;
    top: 2rem;
}

@media (max-width: 991px) {
    .summary-card {
        position: static;
        margin-top: 1rem;
    }
}

.summary-section {
    padding: 1rem 0;
    border-bottom: 1px solid #dee2e6;
    margin-bottom: 1.5rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
}

.summary-row.total-row {
    border-top: 2px solid #007fff;
    padding-top: 1rem;
    margin-top: 1rem;
}

.summary-label {
    font-weight: 500;
    color: #6c757d;
}

.summary-value {
    font-weight: 600;
    color: #212529;
}

.total-value {
    font-size: 1.25rem;
    color: #007fff;
    font-family: 'Orbitron', monospace;
}

.order-actions {
    margin-bottom: 2rem;
}

.order-actions .btn {
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.admin-response {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    margin-bottom: 2rem;
    border-left: 4px solid #007fff;
}

.admin-title {
    font-size: 1rem;
    font-weight: 600;
    color: #007fff;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.admin-message {
    color: #495057;
    line-height: 1.6;
    margin-bottom: 0.5rem;
}

.admin-date {
    font-size: 0.85rem;
    color: #6c757d;
    font-style: italic;
}

.order-timeline {
    border-top: 1px solid #dee2e6;
    padding-top: 1.5rem;
}

.timeline-title {
    font-size: 1rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.timeline-items {
    position: relative;
}

.timeline-items::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.5rem;
    position: relative;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-icon {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    z-index: 1;
    background: white;
}

.timeline-item.active .timeline-icon {
    color: #28a745;
}

.timeline-item.rejected .timeline-icon {
    color: #dc3545;
}

.timeline-item.pending .timeline-icon {
    color: #ffc107;
}

.timeline-content .timeline-title {
    font-weight: 600;
    color: #212529;
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
}

.timeline-content .timeline-date {
    font-size: 0.8rem;
    color: #6c757d;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cancel order confirmation
    const cancelBtn = document.querySelector('.cancel-btn');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: '{{ __("orders.cancel_order_confirm") }}',
                text: '{{ __("orders.cancel_order_text") }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '{{ __("orders.yes_cancel") }}',
                cancelButtonText: '{{ __("orders.no_keep") }}',
                reverseButtons: {{ app()->getLocale() == 'ar' ? 'true' : 'false' }}
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('.cancel-form').submit();
                }
            });
        });
    }
});
</script>
@endpush
