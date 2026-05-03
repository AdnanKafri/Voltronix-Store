<div class="customer-grid">
    <div class="customer-panel">
        <span class="customer-label">{{ __('orders.customer_name') }}</span>
        <div class="customer-value">{{ $order->customer_name }}</div>
    </div>

    <div class="customer-panel">
        <span class="customer-label">{{ __('orders.customer_email') }}</span>
        <div class="customer-value">
            <a href="mailto:{{ $order->customer_email }}" class="customer-link">{{ $order->customer_email }}</a>
        </div>
    </div>

    @if($order->customer_phone)
        <div class="customer-panel">
            <span class="customer-label">{{ __('orders.customer_phone') }}</span>
            <div class="customer-value">
                <a href="tel:{{ $order->customer_phone }}" class="customer-link">{{ $order->customer_phone }}</a>
            </div>
        </div>
    @endif

    <div class="customer-panel">
        <span class="customer-label">{{ __('orders.order_number') }}</span>
        <div class="customer-value">{{ $order->order_number }}</div>
    </div>

    @if($order->notes)
        <div class="customer-panel customer-panel-wide">
            <span class="customer-label">{{ __('orders.notes') }}</span>
            <div class="customer-note">{{ $order->notes }}</div>
        </div>
    @endif
</div>

<style>
.customer-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
}

.customer-panel {
    padding: 1rem;
    border-radius: 14px;
    background: #f8fbfe;
    border: 1px solid #e3ebf4;
}

.customer-panel-wide {
    grid-column: 1 / -1;
}

.customer-label {
    display: block;
    margin-bottom: 0.45rem;
    color: #6d7d91;
    font-size: 0.8rem;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.customer-value {
    color: #10233e;
    font-size: 1rem;
    font-weight: 700;
    word-break: break-word;
}

.customer-link {
    color: #0d6efd;
    text-decoration: none;
}

.customer-link:hover {
    text-decoration: underline;
}

.customer-note {
    padding: 0.9rem 1rem;
    border-radius: 12px;
    background: #fff;
    border-inline-start: 4px solid #0d6efd;
    color: #4d6177;
    line-height: 1.75;
}

@media (max-width: 768px) {
    .customer-grid {
        grid-template-columns: 1fr;
    }
}
</style>
