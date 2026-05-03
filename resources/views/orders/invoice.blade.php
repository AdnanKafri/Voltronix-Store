@extends('layouts.app')

@section('title', __('orders.invoice') . ' - ' . $order->order_number)

@push('styles')
<style>
.navbar-sticky-wrapper,
footer.footer-voltronix {
    display: none !important;
}

.main-content {
    padding: 0 !important;
    min-height: auto !important;
}

.invoice-print-page {
    background: #f4f6f8;
    min-height: 100vh;
    padding: 24px 12px;
}

.receipt-sheet {
    width: 100%;
    max-width: 820px;
    margin: 0 auto;
    background: #fff;
    border: 1px solid #e2e6ea;
    border-radius: 10px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    color: #212529;
}

.receipt-inner {
    padding: 24px;
}

.receipt-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    padding-bottom: 16px;
    border-bottom: 1px solid #dee2e6;
}

.store-brand {
    display: flex;
    align-items: center;
    gap: 10px;
}

.store-logo {
    width: 44px;
    height: 44px;
    object-fit: contain;
}

.store-name {
    font-size: 1.15rem;
    font-weight: 700;
}

.receipt-title {
    text-align: end;
}

.receipt-title h1 {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 700;
}

.receipt-title p {
    margin: 2px 0 0;
    color: #6c757d;
    font-size: 0.9rem;
}

.info-grid {
    margin-top: 14px;
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 8px 10px;
    align-items: stretch;
}

.info-box {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 8px 11px;
    background: #fbfcfd;
    min-height: 62px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.info-label {
    display: block;
    color: #6c757d;
    font-size: 0.74rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 3px;
    line-height: 1.25;
}

.info-value {
    font-size: 0.93rem;
    font-weight: 700;
    line-height: 1.35;
    word-break: break-word;
}

.status-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 700;
    background: #e9ecef;
    letter-spacing: 0.02em;
}

.status-pending { background: #fff3cd; color: #7a5b00; }
.status-approved { background: #d1e7dd; color: #0f5132; }
.status-rejected { background: #f8d7da; color: #842029; }
.status-cancelled { background: #e2e3e5; color: #41464b; }

.items-section {
    margin-top: 18px;
}

.section-title {
    margin: 0 0 8px;
    font-size: 1rem;
    font-weight: 700;
}

.items-table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #dee2e6;
}

.items-table th,
.items-table td {
    border: 1px solid #dee2e6;
    padding: 9px 10px;
    font-size: 0.92rem;
    vertical-align: top;
}

.items-table th {
    background: #f8f9fa;
    font-weight: 700;
}

.text-end { text-align: end; }
.text-center { text-align: center; }

.summary {
    margin-top: 14px;
    margin-left: auto;
    width: min(100%, 320px);
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    padding: 10px 12px;
    border-bottom: 1px solid #e9ecef;
    font-size: 0.92rem;
}

.summary-row:last-child { border-bottom: 0; }
.summary-row.total {
    background: #f8f9fa;
    font-size: 1rem;
    font-weight: 700;
}

.receipt-foot {
    margin-top: 18px;
    padding-top: 12px;
    border-top: 1px dashed #ced4da;
    text-align: center;
    color: #6c757d;
    font-size: 0.88rem;
    line-height: 1.6;
}

.print-actions {
    max-width: 820px;
    margin: 12px auto 0;
    display: flex;
    gap: 8px;
    justify-content: flex-end;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }

    .receipt-head {
        flex-direction: column;
        align-items: flex-start;
    }

    .receipt-title {
        text-align: start;
    }

    .print-actions {
        justify-content: stretch;
    }

    .print-actions .btn {
        flex: 1;
    }
}

[dir="rtl"] .receipt-title {
    text-align: start;
}

[dir="rtl"] .text-end {
    text-align: left;
}

@page {
    size: A4;
    margin: 12mm;
}

@media print {
    .navbar-sticky-wrapper,
    footer.footer-voltronix,
    .print-actions {
        display: none !important;
    }

    body,
    .invoice-print-page {
        background: #fff !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .receipt-sheet {
        max-width: 100% !important;
        border: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .items-table tr,
    .summary,
    .info-box {
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .receipt-inner {
        padding: 16px;
    }

    .info-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 6px 8px;
    }

    .info-box {
        min-height: 56px;
        padding: 7px 9px;
    }

    @media (max-width: 640px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
}
</style>
@endpush

@section('content')
@php
    $statusClasses = [
        'pending' => 'status-pending',
        'approved' => 'status-approved',
        'rejected' => 'status-rejected',
        'cancelled' => 'status-cancelled',
    ];

    $statusClass = $statusClasses[$order->status] ?? 'status-pending';
@endphp

<div class="invoice-print-page">
    <div class="receipt-sheet">
        <div class="receipt-inner">
            <div class="receipt-head">
                <div class="store-brand">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ __('app.brand.name') }}" class="store-logo">
                    <div class="store-name">{{ __('app.brand.name') }}</div>
                </div>

                <div class="receipt-title">
                    <h1>{{ __('orders.invoice') }}</h1>
                    <p>#{{ $order->order_number }}</p>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-box">
                    <span class="info-label">{{ __('orders.order_number') }}</span>
                    <div class="info-value">{{ $order->order_number }}</div>
                </div>
                <div class="info-box">
                    <span class="info-label">{{ __('orders.order_date') }}</span>
                    <div class="info-value">{{ $order->formatted_date }}</div>
                </div>
                <div class="info-box">
                    <span class="info-label">{{ __('orders.customer_name') }}</span>
                    <div class="info-value">{{ $order->customer_name }}</div>
                </div>
                <div class="info-box">
                    <span class="info-label">{{ __('orders.customer_email') }}</span>
                    <div class="info-value">{{ $order->customer_email }}</div>
                </div>
                <div class="info-box">
                    <span class="info-label">{{ __('orders.payment_method') }}</span>
                    <div class="info-value">{{ $order->payment_method_name ?: __('orders.payment_method_not_specified') }}</div>
                </div>
                <div class="info-box">
                    <span class="info-label">{{ __('orders.order_status') }}</span>
                    <div class="info-value">
                        <span class="status-badge {{ $statusClass }}">{{ $order->localized_status }}</span>
                    </div>
                </div>
            </div>

            <div class="items-section">
                <h2 class="section-title">{{ __('orders.order_items') }}</h2>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>{{ __('orders.product') }}</th>
                            <th class="text-center">{{ __('orders.quantity') }}</th>
                            <th class="text-end">{{ __('orders.unit_price') }}</th>
                            <th class="text-end">{{ __('orders.subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->getTranslation() }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">{{ $order->formatMoney($item->product_price) }}</td>
                                <td class="text-end">{{ $order->formatMoney($item->subtotal) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="summary">
                <div class="summary-row">
                    <span>{{ __('orders.subtotal') }}</span>
                    <span>{{ $order->formatted_subtotal }}</span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="summary-row">
                        <span>{{ __('orders.discount') }}</span>
                        <span>-{{ $order->formatted_discount }}</span>
                    </div>
                @endif
                <div class="summary-row">
                    <span>{{ __('orders.currency') }}</span>
                    <span>{{ $order->currency_code }}</span>
                </div>
                <div class="summary-row total">
                    <span>{{ __('orders.final_total') }}</span>
                    <span>{{ $order->formatted_total }}</span>
                </div>
            </div>

            <div class="receipt-foot">
                <div>{{ __('app.orders.order_success_message') }}</div>
                <div>{{ __('app.footer.copyright') }}</div>
            </div>
        </div>
    </div>

    <div class="print-actions">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">{{ __('orders.back_to_orders') }}</a>
        <button type="button" class="btn btn-primary" onclick="window.print()">{{ __('orders.print_invoice') }}</button>
    </div>
</div>
@endsection
