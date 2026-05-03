<div class="summary-section">
    <div class="summary-row">
        <span class="summary-label">{{ __('orders.subtotal') }}</span>
        <span class="summary-value">{{ $order->formatted_subtotal }}</span>
    </div>

    @if($order->discount_amount > 0)
        <div class="summary-row discount-row">
            <span class="summary-label">
                <i class="bi bi-tag-fill text-success {{ app()->getLocale() === 'ar' ? 'ms-1' : 'me-1' }}"></i>
                {{ __('orders.discount') }}
            </span>
            <span class="summary-value discount-value">-{{ $order->formatted_discount }}</span>
        </div>

        @if($order->coupon_code)
            <div class="summary-row meta-row">
                <span class="summary-label">{{ __('orders.coupon_code') }}</span>
                <span class="summary-value code-badge">{{ $order->coupon_code }}</span>
            </div>
        @endif
    @endif

    <div class="summary-row meta-row">
        <span class="summary-label">{{ __('orders.currency') }}</span>
        <span class="summary-value">{{ $order->currency_code }}</span>
    </div>

    <div class="summary-row total-row">
        <span class="summary-label">{{ __('orders.final_total') }}</span>
        <span class="summary-value total-value">{{ $order->formatted_total }}</span>
    </div>
</div>

@if($order->customer_status_note)
    <div class="admin-response">
        <h5 class="admin-title">
            <i class="bi bi-person-badge"></i>
            {{ __('orders.admin_response') }}
        </h5>
        <div class="admin-message">{{ $order->customer_status_note }}</div>

        @if($order->customer_status_note_date)
            <div class="admin-date">
                {{ $order->rejected_at ? __('orders.rejected_on') : ($order->approved_at ? __('orders.approved_on') : __('orders.order_date')) }}:
                {{ local_datetime($order->customer_status_note_date, 'M d, Y H:i') }}
            </div>
        @endif
    </div>
@endif

<style>
.summary-section {
    display: grid;
    gap: 0.8rem;
}

.summary-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.85rem 1rem;
    border-radius: 14px;
    background: #f7fafc;
    border: 1px solid #e4ebf3;
}

.summary-row.meta-row {
    background: #fff;
}

.summary-row.total-row {
    margin-top: 0.35rem;
    background: linear-gradient(135deg, rgba(0, 127, 255, 0.12), rgba(35, 239, 255, 0.12));
    border-color: rgba(0, 127, 255, 0.18);
}

.summary-label {
    color: #607187;
    font-size: 0.92rem;
    font-weight: 700;
}

.summary-value {
    color: #10233e;
    font-size: 0.98rem;
    font-weight: 700;
    text-align: end;
}

.discount-row {
    border-inline-start: 3px solid #2ca86a;
}

.discount-value {
    color: #2ca86a;
}

.total-value {
    font-size: 1.15rem;
    font-family: 'Orbitron', monospace;
}

.code-badge {
    padding: 0.35rem 0.6rem;
    border-radius: 999px;
    background: #10233e;
    color: #fff;
    font-size: 0.82rem;
}

.admin-response {
    margin-top: 1.4rem;
    padding: 1.2rem;
    border-radius: 16px;
    background: #f8fbfe;
    border: 1px solid #dfe8f2;
}

.admin-title {
    margin-bottom: 0.75rem;
    color: #0d6efd;
    font-size: 1rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.45rem;
}

.admin-message {
    color: #4d6177;
    line-height: 1.7;
}

.admin-date {
    margin-top: 0.7rem;
    color: #7a8a9d;
    font-size: 0.86rem;
}
</style>


