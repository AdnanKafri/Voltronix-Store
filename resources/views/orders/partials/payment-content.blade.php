@php
    $paymentIcons = [
        'bank_transfer' => 'bi-bank2',
        'crypto_usdt' => 'bi-currency-dollar',
        'crypto_btc' => 'bi-currency-bitcoin',
        'mtn_cash' => 'bi-phone',
        'syriatel_cash' => 'bi-phone-vibrate',
    ];

    $paymentIcon = $paymentIcons[$order->payment_method] ?? 'bi-credit-card';
    $details = is_array($order->payment_details) ? $order->payment_details : [];
@endphp

<div class="payment-grid">
    <div class="payment-panel">
        <label class="payment-label">{{ __('orders.payment_method') }}</label>
        <div class="payment-value">
            <span class="payment-method-badge">
                <i class="bi {{ $paymentIcon }}"></i>
                {{ $order->payment_method_name ?: __('orders.payment_method_not_specified') }}
            </span>
        </div>
    </div>

    <div class="payment-panel">
        <label class="payment-label">{{ __('orders.payment_status') }}</label>
        <div class="payment-value">
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

    <div class="payment-panel">
        <label class="payment-label">{{ __('orders.currency') }}</label>
        <div class="payment-reference">{{ $order->currency_code }}</div>
    </div>

    @if(isset($details['timestamp']))
        <div class="payment-panel">
            <label class="payment-label">{{ __('orders.payment_reference') }}</label>
            <div class="payment-reference">{{ local_datetime($details['timestamp'], 'M d, Y H:i') }}</div>
        </div>
    @endif
</div>

@if($order->payment_proof_path)
    <div class="receipt-section">
        <label class="payment-label">{{ __('orders.payment_receipt') }}</label>
        <div class="receipt-card">
            <div class="receipt-icon">
                <i class="bi bi-file-earmark-check"></i>
            </div>

            <div class="receipt-info">
                <div class="receipt-name">{{ basename($order->payment_proof_path) }}</div>
                <div class="receipt-date">{{ __('orders.uploaded_on') }}: {{ local_datetime($order->created_at, 'M d, Y H:i') }}</div>
            </div>

            <div class="receipt-actions">
                @php
                    $fileExtension = strtolower(pathinfo($order->payment_proof_path, PATHINFO_EXTENSION));
                    $isPdf = $fileExtension === 'pdf';
                @endphp

                @if($isPdf)
                    <span class="receipt-file-badge">{{ __('orders.pdf_file') }}</span>
                @else
                    <button
                        type="button"
                        class="btn btn-outline-primary btn-sm"
                        onclick="viewPaymentProof('{{ route('orders.receipt.view', $order->order_number) }}', '{{ $order->order_number }}')"
                    >
                        <i class="bi bi-eye {{ app()->getLocale() === 'ar' ? 'ms-1' : 'me-1' }}"></i>
                        {{ __('orders.view_receipt') }}
                    </button>
                @endif

                <a href="{{ route('orders.receipt.download', $order->order_number) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-download {{ app()->getLocale() === 'ar' ? 'ms-1' : 'me-1' }}"></i>
                    {{ __('orders.download_receipt') }}
                </a>
            </div>
        </div>
    </div>
@else
    <div class="receipt-section">
        <label class="payment-label">{{ __('orders.payment_receipt') }}</label>
        <div class="receipt-placeholder">
            <i class="bi bi-file-earmark-x"></i>
            <span>{{ __('orders.no_receipt_uploaded') }}</span>
        </div>
    </div>
@endif

<style>
.payment-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
}

.payment-panel {
    padding: 1rem;
    border-radius: 14px;
    background: #f8fbfe;
    border: 1px solid #e3ebf4;
}

.payment-label {
    display: block;
    margin-bottom: 0.45rem;
    color: #6d7d91;
    font-size: 0.8rem;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.payment-method-badge,
.payment-status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.55rem 0.9rem;
    border-radius: 999px;
    font-weight: 700;
}

.payment-method-badge {
    background: linear-gradient(135deg, #0d6efd, #23efff);
    color: #fff;
}

.payment-status.verified {
    background: rgba(40, 167, 69, 0.12);
    color: #2a8c57;
}

.payment-status.rejected {
    background: rgba(220, 53, 69, 0.12);
    color: #cc4455;
}

.payment-status.pending {
    background: rgba(255, 193, 7, 0.12);
    color: #8f6a00;
}

.payment-reference {
    color: #10233e;
    font-size: 1rem;
    font-weight: 700;
}

.receipt-section {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e8edf3;
}

.receipt-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: 16px;
    background: #f8fbfe;
    border: 1px solid #dde7f1;
}

.receipt-icon {
    width: 52px;
    height: 52px;
    flex-shrink: 0;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(13, 110, 253, 0.12);
    color: #0d6efd;
    font-size: 1.5rem;
}

.receipt-info {
    flex: 1;
    min-width: 0;
}

.receipt-name {
    color: #10233e;
    font-weight: 700;
    word-break: break-word;
}

.receipt-date {
    margin-top: 0.2rem;
    color: #7a8a9d;
    font-size: 0.88rem;
}

.receipt-actions {
    display: flex;
    gap: 0.6rem;
    align-items: center;
    flex-wrap: wrap;
}

.receipt-file-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.45rem 0.75rem;
    border-radius: 999px;
    background: rgba(220, 53, 69, 0.12);
    color: #c23d4a;
    font-size: 0.82rem;
    font-weight: 700;
}

.receipt-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.7rem;
    padding: 1.35rem;
    border: 2px dashed #d7e0ea;
    border-radius: 14px;
    color: #8090a4;
    background: #fbfdff;
}

@media (max-width: 768px) {
    .payment-grid {
        grid-template-columns: 1fr;
    }

    .receipt-card {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>


