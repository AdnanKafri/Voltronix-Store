<!-- Order Header -->
<div class="order-header-card">
    <div class="row align-items-center">
        <div class="col-md-6">
            <div class="order-title">
                <h1 class="order-number">{{ $order->order_number }}</h1>
                <p class="order-date">{{ __('orders.placed_on') }}: {{ $order->created_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="order-status-section">
                @php
                    $statusClasses = [
                        'pending' => 'status-pending',
                        'approved' => 'status-approved', 
                        'rejected' => 'status-rejected',
                        'cancelled' => 'status-cancelled'
                    ];
                    $statusClass = $statusClasses[$order->status] ?? 'status-pending';
                @endphp
                <span class="order-status {{ $statusClass }}">
                    <i class="bi bi-circle-fill"></i>
                    {{ __('orders.status.' . $order->status) }}
                </span>
                <div class="order-total-header">
                    <span class="total-label">{{ __('orders.total') }}</span>
                    <span class="total-amount">{{ $order->formatted_total }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.order-header-card {
    background: linear-gradient(135deg, #007fff 0%, #23efff 100%);
    color: white;
    padding: 2.5rem 2rem;
    border-radius: 20px;
    margin-bottom: 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 127, 255, 0.3);
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.order-header-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.1);
    z-index: 1;
}

.order-header-card > * {
    position: relative;
    z-index: 2;
}

.order-number {
    font-family: 'Orbitron', monospace;
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.order-date {
    margin: 0.5rem 0 0 0;
    opacity: 0.9;
    font-size: 1.1rem;
}

.order-status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 1rem;
}

.status-pending {
    background: rgba(255, 193, 7, 0.95);
    color: #212529;
    border: 2px solid #ffc107;
    text-shadow: none;
    box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
}

.status-approved {
    background: rgba(40, 167, 69, 0.95);
    color: white;
    border: 2px solid #28a745;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.status-rejected {
    background: rgba(220, 53, 69, 0.95);
    color: white;
    border: 2px solid #dc3545;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
}

.status-cancelled {
    background: rgba(108, 117, 125, 0.95);
    color: white;
    border: 2px solid #6c757d;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
}

.total-label {
    display: block;
    font-size: 0.9rem;
    opacity: 0.8;
}

.total-amount {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    font-family: 'Orbitron', monospace;
}

@media (max-width: 768px) {
    .order-header-card {
        text-align: center;
    }
    
    .order-number {
        font-size: 1.5rem;
    }
    
    .order-status-section {
        margin-top: 1rem;
    }
}
</style>
@endpush
