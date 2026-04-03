<!-- Payment Information -->
<div class="info-card">
    <div class="card-header">
        <h3 class="section-title">
            <i class="bi bi-credit-card"></i>
            {{ __('orders.payment_information') }}
        </h3>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="info-item">
                    <label class="info-label">{{ __('orders.payment_method') }}</label>
                    <div class="info-value">
                        @if($order->payment_method)
                            <span class="payment-method-badge">
                                @if($order->payment_method === 'bank_transfer')
                                    <i class="bi bi-bank"></i>
                                @elseif($order->payment_method === 'crypto_usdt')
                                    <i class="bi bi-currency-bitcoin"></i>
                                @elseif($order->payment_method === 'crypto_btc')
                                    <i class="bi bi-currency-bitcoin"></i>
                                @else
                                    <i class="bi bi-credit-card"></i>
                                @endif
                                {{ __('app.checkout.payment_methods.' . $order->payment_method) }}
                            </span>
                        @else
                            <span class="payment-method-badge">
                                <i class="bi bi-question-circle"></i>
                                {{ __('orders.payment_method_not_specified') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-item">
                    <label class="info-label">{{ __('orders.payment_status') }}</label>
                    <div class="info-value">
                        @if($order->status === 'approved')
                            <span class="payment-status verified">
                                <i class="bi bi-check-circle-fill"></i>
                                {{ __('orders.payment_verified') }}
                            </span>
                        @elseif($order->status === 'rejected')
                            <span class="payment-status rejected">
                                <i class="bi bi-x-circle-fill"></i>
                                {{ __('orders.payment_rejected') }}
                            </span>
                        @else
                            <span class="payment-status pending">
                                <i class="bi bi-clock-fill"></i>
                                {{ __('orders.payment_pending') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        @if($order->payment_proof_path)
        <div class="receipt-section">
            <label class="info-label">{{ __('orders.payment_receipt') }}</label>
            <div class="receipt-card">
                <div class="receipt-icon">
                    <i class="bi bi-file-earmark-check"></i>
                </div>
                <div class="receipt-info">
                    <div class="receipt-name">{{ basename($order->payment_proof_path) }}</div>
                    <div class="receipt-date">{{ __('orders.uploaded_on') }}: {{ $order->created_at->format('M d, Y H:i') }}</div>
                </div>
                <div class="receipt-actions">
                    <a href="{{ route('orders.receipt.view', $order) }}" 
                       target="_blank" 
                       class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                        {{ __('orders.view_receipt') }}
                    </a>
                    <a href="{{ route('orders.receipt.download', $order) }}" 
                       download 
                       class="btn btn-primary btn-sm">
                        <i class="bi bi-download {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                        {{ __('orders.download_receipt') }}
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="receipt-section">
            <label class="info-label">{{ __('orders.payment_receipt') }}</label>
            <div class="receipt-placeholder">
                <i class="bi bi-file-earmark-x"></i>
                <span>{{ __('orders.no_receipt_uploaded') }}</span>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.payment-method-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #007fff, #23efff);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.9rem;
}

.payment-status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.9rem;
}

.payment-status.verified {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
    border: 1px solid rgba(40, 167, 69, 0.3);
}

.payment-status.rejected {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.3);
}

.payment-status.pending {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.receipt-section {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #dee2e6;
}

.receipt-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    border: 1px solid #dee2e6;
}

.receipt-icon {
    font-size: 2rem;
    color: #007fff;
}

.receipt-info {
    flex: 1;
}

.receipt-name {
    font-weight: 600;
    color: #212529;
    margin-bottom: 0.25rem;
}

.receipt-date {
    font-size: 0.9rem;
    color: #6c757d;
}

.receipt-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.receipt-placeholder {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 10px;
    border: 2px dashed #dee2e6;
    color: #6c757d;
    justify-content: center;
    font-style: italic;
}

.receipt-placeholder i {
    font-size: 2rem;
}

@media (max-width: 768px) {
    .receipt-card {
        flex-direction: column;
        text-align: center;
    }
    
    .receipt-actions {
        justify-content: center;
        width: 100%;
    }
}
</style>
@endpush
