<div class="timeline-items">
    <div class="timeline-item completed">
        <div class="timeline-icon">
            <i class="bi bi-cart-check-fill"></i>
        </div>
        <div class="timeline-content">
            <div class="timeline-title">{{ __('orders.order_placed') }}</div>
            <div class="timeline-date">{{ $order->created_at->format('M d, Y H:i') }}</div>
        </div>
    </div>
    
    @if($order->approved_at)
    <div class="timeline-item completed">
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
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div class="timeline-content">
            <div class="timeline-title">{{ __('orders.awaiting_approval') }}</div>
            <div class="timeline-date">{{ __('orders.pending_review') }}</div>
        </div>
    </div>
    @endif
</div>

<style>
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
    border: 2px solid;
}

.timeline-item.completed .timeline-icon {
    color: #28a745;
    border-color: #28a745;
    background: rgba(40, 167, 69, 0.1);
}

.timeline-item.rejected .timeline-icon {
    color: #dc3545;
    border-color: #dc3545;
    background: rgba(220, 53, 69, 0.1);
}

.timeline-item.pending .timeline-icon {
    color: #ffc107;
    border-color: #ffc107;
    background: rgba(255, 193, 7, 0.1);
    animation: pulse 2s infinite;
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

/* Active timeline line */
.timeline-item.active ~ .timeline-item::before {
    background: #28a745;
}

.timeline-item.rejected::before {
    background: #dc3545;
}

.timeline-item.pending::before {
    background: #ffc107;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(255, 193, 7, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255, 193, 7, 0);
    }
}
</style>
