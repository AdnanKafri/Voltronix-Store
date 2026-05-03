@php
    $itemsSubtotal = $order->items->sum(fn ($item) => (float) $item->subtotal);
@endphp

<div class="table-responsive">
    <table class="table invoice-items-table mb-0">
        <thead>
            <tr>
                <th>{{ __('orders.product') }}</th>
                <th class="text-center">{{ __('orders.quantity') }}</th>
                <th class="text-end">{{ __('orders.unit_price') }}</th>
                <th class="text-end">{{ __('orders.subtotal') }}</th>
                @if($order->status === 'approved')
                    <th class="text-center">{{ __('app.delivery.delivery_title') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>
                        <div class="product-info">
                            <div class="product-thumb">
                                @if($item->product && $item->product->thumbnail)
                                    <img src="{{ asset('storage/' . $item->product->thumbnail) }}" alt="{{ $item->getTranslation() }}">
                                @else
                                    <i class="bi bi-box-seam"></i>
                                @endif
                            </div>

                            <div class="product-copy">
                                <div class="product-name">{{ $item->getTranslation() }}</div>
                                @if($item->product && $item->product->category)
                                    <div class="product-meta">{{ $item->product->category->getTranslation('name') }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="quantity-pill">{{ $item->quantity }}</span>
                    </td>
                    <td class="text-end">
                        <span class="money-text">{{ $order->formatMoney($item->product_price) }}</span>
                    </td>
                    <td class="text-end">
                        <span class="money-text money-strong">{{ $order->formatMoney($item->subtotal) }}</span>
                    </td>
                    @if($order->status === 'approved')
                        <td class="text-center">
                            @if($item->delivery)
                                @php $delivery = $item->delivery; @endphp

                                <div class="delivery-stack">
                                    @if($delivery->type === 'file')
                                        @if(!$delivery->isExpired() && !$delivery->isRevoked() && !$delivery->isDownloadLimitReached())
                                            <a href="{{ route('delivery.download', $delivery->token) }}"
                                               class="btn btn-sm btn-success delivery-btn"
                                               target="_blank"
                                               rel="noopener"
                                               data-download-link="true">
                                                <i class="bi bi-download {{ app()->getLocale() === 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                                {{ __('app.delivery.download_file') }}
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary delivery-btn" disabled>
                                                <i class="bi bi-x-circle {{ app()->getLocale() === 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                                @if($delivery->isExpired())
                                                    {{ __('app.delivery.access_expired') }}
                                                @elseif($delivery->isRevoked())
                                                    {{ __('app.delivery.access_revoked') }}
                                                @else
                                                    {{ __('app.delivery.download_limit_reached') }}
                                                @endif
                                            </button>
                                        @endif
                                    @else
                                        @if(!$delivery->isExpired() && !$delivery->isRevoked() && !$delivery->isViewLimitReached())
                                            <a href="{{ $delivery->getCredentialsUrl() }}" class="btn btn-sm btn-primary delivery-btn">
                                                <i class="bi {{ $delivery->type === 'license' ? 'bi-award' : 'bi-key' }} {{ app()->getLocale() === 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                                {{ $delivery->type === 'license' ? __('app.delivery.view_license') : __('app.delivery.view_credentials') }}
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary delivery-btn" disabled>
                                                <i class="bi bi-x-circle {{ app()->getLocale() === 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                                @if($delivery->isExpired())
                                                    {{ __('app.delivery.access_expired') }}
                                                @elseif($delivery->isRevoked())
                                                    {{ __('app.delivery.access_revoked') }}
                                                @else
                                                    {{ __('app.delivery.view_limit_reached') }}
                                                @endif
                                            </button>
                                        @endif
                                    @endif

                                    <div class="delivery-meta">
                                        @if($delivery->expires_at)
                                            <span>{{ __('app.delivery.expires') }}: {{ local_datetime($delivery->expires_at, 'M d, Y') }}</span>
                                        @else
                                            <span>{{ __('app.delivery.never_expires') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <span class="delivery-pending">{{ __('app.delivery.pending_setup') }}</span>
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="{{ $order->status === 'approved' ? '3' : '2' }}" class="text-end footer-label">
                    {{ __('orders.subtotal') }}
                </td>
                <td class="text-end footer-value">{{ $order->formatMoney($itemsSubtotal) }}</td>
                @if($order->status === 'approved')
                    <td></td>
                @endif
            </tr>
        </tfoot>
    </table>
</div>

<style>
.invoice-items-table thead th {
    padding: 1rem 1.2rem;
    border: none;
    background: #f7fafc;
    color: #607187;
    font-size: 0.82rem;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.invoice-items-table tbody td,
.invoice-items-table tfoot td {
    padding: 1.15rem 1.2rem;
    vertical-align: middle;
    border-color: #edf2f7;
}

.invoice-items-table tbody tr:hover {
    background: #fbfdff;
}

.product-info {
    display: flex;
    align-items: center;
    gap: 0.95rem;
    min-width: 0;
}

.product-thumb {
    width: 60px;
    height: 60px;
    flex-shrink: 0;
    border-radius: 14px;
    overflow: hidden;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #eef4fa;
    color: #6e8096;
}

.product-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-copy {
    min-width: 0;
}

.product-name {
    color: #10233e;
    font-weight: 700;
    line-height: 1.5;
}

.product-meta {
    margin-top: 0.2rem;
    color: #73849a;
    font-size: 0.88rem;
}

.quantity-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.25rem;
    padding: 0.4rem 0.7rem;
    border-radius: 999px;
    background: #eef4fa;
    color: #10233e;
    font-weight: 700;
}

.money-text {
    color: #32465d;
    font-weight: 700;
    white-space: nowrap;
}

.money-strong {
    color: #10233e;
}

.footer-label,
.footer-value {
    background: #f8fbfe;
    font-weight: 800;
}

.delivery-stack {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.delivery-btn {
    min-width: 145px;
}

.delivery-meta,
.delivery-pending {
    color: #7a8a9d;
    font-size: 0.8rem;
}

@media (max-width: 991px) {
    .invoice-items-table thead th,
    .invoice-items-table tbody td,
    .invoice-items-table tfoot td {
        padding-inline: 0.85rem;
    }
}

@media print {
    .invoice-items-table {
        font-size: 0.92rem;
    }

    .delivery-btn,
    .delivery-meta,
    .delivery-pending {
        display: none !important;
    }
}
</style>

@once
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delivery-btn[data-download-link="true"]').forEach(function (button) {
            button.addEventListener('click', function (event) {
                if (button.dataset.busy === '1') {
                    event.preventDefault();
                    return;
                }

                button.dataset.busy = '1';
                button.classList.add('disabled');
                button.setAttribute('aria-disabled', 'true');

                setTimeout(function () {
                    button.dataset.busy = '0';
                    button.classList.remove('disabled');
                    button.removeAttribute('aria-disabled');
                }, 3000);
            });
        });
    });
    </script>
    @endpush
@endonce
