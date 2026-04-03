<!-- Customer Information -->
<div class="info-card">
    <div class="card-header">
        <h3 class="section-title">
            <i class="bi bi-person-circle"></i>
            {{ __('orders.customer_information') }}
        </h3>
    </div>
    <div class="card-body">
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
    </div>
</div>

@push('styles')
<style>
.info-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 1.5rem;
    overflow: hidden;
    border: 1px solid rgba(0, 127, 255, 0.1);
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem;
    border-bottom: 1px solid #dee2e6;
}

.section-title {
    font-family: 'Orbitron', monospace;
    font-size: 1.25rem;
    font-weight: 600;
    color: #007fff;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-title i {
    font-size: 1.5rem;
}

.card-body {
    padding: 2rem;
}

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
@endpush
