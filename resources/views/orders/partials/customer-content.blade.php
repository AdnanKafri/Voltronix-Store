<div class="row g-3">
    <div class="col-md-6">
        <div class="info-item">
            <label class="info-label">{{ __('orders.customer_name') }}</label>
            <div class="info-value">{{ $order->customer_name }}</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="info-item">
            <label class="info-label">{{ __('orders.customer_email') }}</label>
            <div class="info-value">
                <a href="mailto:{{ $order->customer_email }}" class="email-link">
                    {{ $order->customer_email }}
                </a>
            </div>
        </div>
    </div>
    @if($order->customer_phone)
    <div class="col-md-6">
        <div class="info-item">
            <label class="info-label">{{ __('orders.customer_phone') }}</label>
            <div class="info-value">
                <a href="tel:{{ $order->customer_phone }}" class="phone-link">
                    {{ $order->customer_phone }}
                </a>
            </div>
        </div>
    </div>
    @endif
    @if($order->notes)
    <div class="col-12">
        <div class="info-item">
            <label class="info-label">{{ __('orders.notes') }}</label>
            <div class="info-value notes-text">{{ $order->notes }}</div>
        </div>
    </div>
    @endif
</div>

<style>
.info-item {
    margin-bottom: 1rem;
}

.info-label {
    display: block;
    font-weight: 600;
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 1.1rem;
    color: #212529;
    font-weight: 500;
}

.email-link, .phone-link {
    color: #007fff;
    text-decoration: none;
    transition: color 0.3s ease;
}

.email-link:hover, .phone-link:hover {
    color: #0056b3;
    text-decoration: underline;
}

.notes-text {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    border-left: 4px solid #007fff;
    font-style: italic;
}
</style>
