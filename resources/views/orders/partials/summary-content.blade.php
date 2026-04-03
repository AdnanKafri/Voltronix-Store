<div class="summary-section">
    <div class="summary-row">
        <span class="summary-label">{{ __('orders.subtotal') }}</span>
        <span class="summary-value">{{ currency_format($order->total_amount + $order->discount_amount) }}</span>
    </div>
    
    @if($order->discount_amount > 0)
    <div class="summary-row discount-row">
        <span class="summary-label">
            <i class="bi bi-tag-fill text-success me-1"></i>
            {{ __('orders.discount') }}
            @if($order->coupon_code)
                <small class="coupon-code">({{ $order->coupon_code }})</small>
            @endif
        </span>
        <span class="summary-value discount-value">-{{ currency_format($order->discount_amount) }}</span>
    </div>
    @endif
    
    <div class="summary-row">
        <span class="summary-label">{{ __('orders.tax') }}</span>
        <span class="summary-value">{{ __('orders.included') }}</span>
    </div>
    <div class="summary-row total-row">
        <span class="summary-label">{{ __('orders.total') }}</span>
        <span class="summary-value total-value">{{ currency_format($order->total_amount) }}</span>
    </div>
</div>

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

<style>
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

.discount-row {
    background: rgba(40, 167, 69, 0.05);
    padding: 0.75rem;
    border-radius: 8px;
    margin: 0.5rem 0;
    border-left: 3px solid #28a745;
}

.discount-value {
    color: #28a745;
    font-weight: 700;
}

.coupon-code {
    background: #28a745;
    color: white;
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-left: 0.5rem;
}

.admin-response {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    margin-bottom: 1rem;
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
</style>
