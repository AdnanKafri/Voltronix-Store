<div class="row g-3">
    <div class="col-md-6">
        <div class="info-item">
            <label class="info-label">{{ __('orders.payment_method') }}</label>
            <div class="info-value">
                <span class="payment-method-badge">
                    <i class="bi bi-bank"></i>
                    {{ __('orders.payment_methods.bank_transfer') }}
                </span>
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
            @php
                $fileExtension = strtolower(pathinfo($order->payment_proof_path, PATHINFO_EXTENSION));
                $isPdf = in_array($fileExtension, ['pdf']);
            @endphp
            
            @if($isPdf)
                <!-- PDF file - show download only -->
                <div class="pdf-indicator">
                    <i class="bi bi-file-earmark-pdf text-danger {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                    <small class="text-muted">{{ __('orders.pdf_file') }}</small>
                </div>
                <a href="{{ route('orders.receipt.download', $order->order_number) }}" 
                   class="btn btn-primary btn-sm">
                    <i class="bi bi-download {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                    {{ __('orders.download_receipt') }}
                </a>
            @else
                <!-- Image file - show view and download -->
                <button type="button" 
                        class="btn btn-outline-primary btn-sm"
                        onclick="viewPaymentProof('{{ route('orders.receipt.view', $order->order_number) }}', '{{ $order->order_number }}')">
                    <i class="bi bi-eye {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                    {{ __('orders.view_receipt') }}
                </button>
                <a href="{{ route('orders.receipt.download', $order->order_number) }}" 
                   class="btn btn-primary btn-sm">
                    <i class="bi bi-download {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                    {{ __('orders.download_receipt') }}
                </a>
            @endif
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
    align-items: center;
}

.pdf-indicator {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.5rem;
    background: rgba(220, 53, 69, 0.1);
    border: 1px solid rgba(220, 53, 69, 0.2);
    border-radius: 15px;
    font-size: 0.8rem;
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
