@php
    $statusClasses = [
        'pending' => 'status-pending',
        'approved' => 'status-approved',
        'rejected' => 'status-rejected',
        'cancelled' => 'status-cancelled',
    ];

    $statusClass = $statusClasses[$order->status] ?? 'status-pending';
@endphp

<div class="invoice-hero">
    <div class="invoice-hero-copy">
        <span class="invoice-kicker">
            <i class="bi bi-receipt-cutoff"></i>
            {{ __('orders.invoice') }}
        </span>

        <h1 class="invoice-number">{{ $order->order_number }}</h1>
        <p class="invoice-meta-copy">{{ __('orders.amounts_snapshot') }}</p>

        <div class="invoice-status-row">
            <span class="order-status {{ $statusClass }}">
                <i class="bi bi-circle-fill"></i>
                {{ $order->localized_status }}
            </span>
        </div>
    </div>

    <div class="invoice-hero-stats">
        <div class="hero-stat">
            <span class="hero-stat-label">{{ __('orders.order_date') }}</span>
            <strong class="hero-stat-value">{{ $order->formatted_date }}</strong>
        </div>
        <div class="hero-stat">
            <span class="hero-stat-label">{{ __('orders.currency') }}</span>
            <strong class="hero-stat-value">{{ $order->currency_code }}</strong>
        </div>
        <div class="hero-stat hero-stat-total">
            <span class="hero-stat-label">{{ __('orders.final_total') }}</span>
            <strong class="hero-stat-value">{{ $order->formatted_total }}</strong>
        </div>
    </div>
</div>

@push('styles')
<style>
.invoice-hero {
    display: flex;
    justify-content: space-between;
    gap: 2rem;
    padding: 2.4rem;
    color: #fff;
    background:
        radial-gradient(circle at top right, rgba(35, 239, 255, 0.22), transparent 34%),
        linear-gradient(135deg, #071a2d 0%, #0e4f8c 100%);
}

.invoice-hero-copy {
    max-width: 620px;
}

.invoice-kicker {
    display: inline-flex;
    align-items: center;
    gap: 0.55rem;
    padding: 0.45rem 0.9rem;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.18);
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.invoice-number {
    margin: 1rem 0 0.5rem;
    font-family: 'Orbitron', monospace;
    font-size: clamp(1.8rem, 2.8vw, 2.8rem);
    font-weight: 700;
}

.invoice-meta-copy {
    margin: 0;
    max-width: 560px;
    color: rgba(255, 255, 255, 0.82);
    line-height: 1.75;
}

.invoice-status-row {
    margin-top: 1.25rem;
}

.invoice-hero-stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1rem;
    min-width: min(420px, 100%);
    align-self: stretch;
}

.hero-stat {
    padding: 1rem 1.1rem;
    border-radius: 18px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.12);
}

.hero-stat-total {
    background: rgba(255, 255, 255, 0.16);
}

.hero-stat-label {
    display: block;
    margin-bottom: 0.35rem;
    color: rgba(255, 255, 255, 0.68);
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.hero-stat-value {
    color: #fff;
    font-size: 1rem;
    line-height: 1.45;
}

.order-status {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.7rem 1rem;
    border-radius: 999px;
    font-size: 0.92rem;
    font-weight: 700;
}

.status-pending {
    background: rgba(255, 193, 7, 0.95);
    color: #362b02;
}

.status-approved {
    background: rgba(40, 167, 69, 0.95);
    color: #fff;
}

.status-rejected {
    background: rgba(220, 53, 69, 0.95);
    color: #fff;
}

.status-cancelled {
    background: rgba(108, 117, 125, 0.95);
    color: #fff;
}

@media (max-width: 991px) {
    .invoice-hero {
        flex-direction: column;
    }

    .invoice-hero-stats {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        min-width: 0;
    }
}

@media (max-width: 768px) {
    .invoice-hero {
        padding: 1.5rem;
    }

    .invoice-hero-stats {
        grid-template-columns: 1fr;
    }
}

@media print {
    .invoice-hero {
        padding: 1.4rem 1.6rem;
        color: #10233e;
        background: #fff;
        border-bottom: 2px solid #d8e2ee;
    }

    .invoice-kicker,
    .hero-stat,
    .status-pending,
    .status-approved,
    .status-rejected,
    .status-cancelled {
        color: #10233e !important;
        background: #fff !important;
        border: 1px solid #cdd8e5 !important;
        box-shadow: none !important;
    }

    .invoice-meta-copy,
    .hero-stat-label {
        color: #526273 !important;
    }
}
</style>
@endpush
