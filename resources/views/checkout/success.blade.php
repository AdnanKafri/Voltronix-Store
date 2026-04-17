@extends('layouts.app')

@section('title', __('app.orders.order_success_title') . ' - ' . __('app.hero.title'))
@section('description', __('app.orders.order_success_message'))

@php
    $isApproved = $order->status === \App\Models\Order::STATUS_APPROVED;
    $isPending = $order->status === \App\Models\Order::STATUS_PENDING;
    $isRejected = $order->status === \App\Models\Order::STATUS_REJECTED;
    $isCancelled = $order->status === \App\Models\Order::STATUS_CANCELLED;
    $deliveries = $order->items->pluck('delivery')->filter();
    $hasDeliveries = $deliveries->isNotEmpty();
    $hasAccessibleDeliveries = $deliveries->contains(fn ($delivery) => $delivery && $delivery->isAccessible());

    if ($isApproved && $hasAccessibleDeliveries) {
        $heroIcon = 'bi bi-lightning-charge-fill';
        $heroStateClass = 'is-approved';
        $heroMessage = __('app.orders.success_state_approved');
        $nextStepTitle = __('app.orders.next_step_ready_title');
        $nextStepMessage = __('app.orders.next_step_ready_message');
        $primaryActionLabel = __('app.orders.access_product');
    } elseif ($isApproved) {
        $heroIcon = 'bi bi-check2-circle';
        $heroStateClass = 'is-approved';
        $heroMessage = __('app.orders.success_state_processing');
        $nextStepTitle = __('app.orders.next_step_approved_title');
        $nextStepMessage = __('app.orders.next_step_approved_message');
        $primaryActionLabel = __('app.orders.view_details');
    } elseif ($isRejected) {
        $heroIcon = 'bi bi-exclamation-triangle-fill';
        $heroStateClass = 'is-rejected';
        $heroMessage = __('app.orders.success_state_rejected');
        $nextStepTitle = __('app.orders.next_step_rejected_title');
        $nextStepMessage = __('app.orders.next_step_rejected_message');
        $primaryActionLabel = __('app.orders.view_details');
    } elseif ($isCancelled) {
        $heroIcon = 'bi bi-slash-circle-fill';
        $heroStateClass = 'is-cancelled';
        $heroMessage = __('app.orders.success_state_cancelled');
        $nextStepTitle = __('app.orders.next_step_cancelled_title');
        $nextStepMessage = __('app.orders.next_step_cancelled_message');
        $primaryActionLabel = __('app.orders.view_details');
    } else {
        $heroIcon = 'bi bi-check-circle-fill';
        $heroStateClass = 'is-pending';
        $heroMessage = __('app.orders.success_state_pending');
        $nextStepTitle = __('app.orders.next_step_pending_title');
        $nextStepMessage = __('app.orders.next_step_pending_message');
        $primaryActionLabel = __('app.orders.view_details');
    }
@endphp

@push('styles')
<style>
.checkout-success-page {
    padding-top: calc(var(--navbar-height-desktop) + 1.5rem);
    padding-bottom: 3rem;
    min-height: 100vh;
    background:
        radial-gradient(circle at top, rgba(0, 127, 255, 0.14), transparent 32%),
        linear-gradient(180deg, #08121f 0%, #0f2238 18%, #eff4f9 18%, #f7fafc 100%);
}

.checkout-success-shell {
    max-width: 1120px;
    margin: 0 auto;
}

.success-hero {
    position: relative;
    overflow: hidden;
    border-radius: 30px;
    padding: 2rem;
    color: #fff;
    background: linear-gradient(135deg, rgba(8, 18, 31, 0.98), rgba(14, 52, 102, 0.94));
    box-shadow: 0 28px 60px rgba(7, 17, 29, 0.24);
}

.success-hero::after {
    content: '';
    position: absolute;
    inset: auto -8% -40% 42%;
    height: 280px;
    background: radial-gradient(circle, rgba(0, 212, 255, 0.22), transparent 72%);
    pointer-events: none;
}

.success-hero.is-approved {
    background: linear-gradient(135deg, rgba(7, 32, 26, 0.98), rgba(15, 89, 66, 0.94));
}

.success-hero.is-rejected {
    background: linear-gradient(135deg, rgba(50, 14, 18, 0.98), rgba(125, 31, 45, 0.94));
}

.success-hero.is-cancelled {
    background: linear-gradient(135deg, rgba(43, 45, 49, 0.98), rgba(85, 92, 105, 0.94));
}

.success-hero-top {
    position: relative;
    z-index: 1;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1.5rem;
}

.success-hero-copy {
    max-width: 700px;
}

.success-kicker {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.45rem 0.9rem;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.16);
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.success-title {
    margin: 1.1rem 0 0.7rem;
    font-size: clamp(2rem, 3vw, 3rem);
    font-weight: 800;
    line-height: 1.08;
}

.success-message {
    margin: 0;
    max-width: 620px;
    color: rgba(255, 255, 255, 0.8);
    font-size: 1rem;
    line-height: 1.8;
}

.success-state-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.7rem 1rem;
    border-radius: 18px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.14);
    font-size: 0.92rem;
    font-weight: 700;
    white-space: nowrap;
}

.success-hero-stats {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1rem;
    margin-top: 1.75rem;
}

.hero-stat {
    padding: 1rem 1.1rem;
    border-radius: 18px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.hero-stat-label {
    display: block;
    margin-bottom: 0.3rem;
    color: rgba(255, 255, 255, 0.62);
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.hero-stat-value {
    color: #fff;
    font-size: 1.05rem;
    font-weight: 800;
    line-height: 1.4;
    word-break: break-word;
}

.success-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.55fr) minmax(320px, 0.9fr);
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.success-card {
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(11, 33, 61, 0.08);
    border-radius: 24px;
    box-shadow: 0 18px 42px rgba(18, 35, 58, 0.08);
}

.success-card-body {
    padding: 1.5rem;
}

.success-card-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.45rem;
    color: #10233e;
    font-size: 1.1rem;
    font-weight: 800;
}

.success-card-title i {
    width: 2.35rem;
    height: 2.35rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    background: rgba(0, 127, 255, 0.12);
    color: #0d6efd;
}

.success-card-copy {
    margin-bottom: 1.25rem;
    color: #607187;
    line-height: 1.75;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.9rem;
}

.summary-item {
    padding: 1rem 1.05rem;
    border-radius: 18px;
    background: linear-gradient(180deg, rgba(245, 248, 252, 0.98), rgba(237, 242, 248, 0.96));
    border: 1px solid rgba(16, 35, 62, 0.08);
}

.summary-label {
    display: block;
    margin-bottom: 0.35rem;
    color: #6a7b90;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.summary-value {
    color: #10233e;
    font-size: 1rem;
    font-weight: 800;
    line-height: 1.5;
    word-break: break-word;
}

.summary-value.is-total {
    color: #0d6efd;
    font-size: 1.15rem;
}

.purchase-list {
    display: grid;
    gap: 0.9rem;
}

.purchase-item {
    display: grid;
    grid-template-columns: 72px minmax(0, 1fr) auto;
    gap: 1rem;
    align-items: center;
    padding: 1rem;
    border-radius: 20px;
    background: linear-gradient(180deg, rgba(249, 251, 254, 0.98), rgba(239, 244, 250, 0.98));
    border: 1px solid rgba(15, 34, 56, 0.08);
}

.purchase-thumb {
    width: 72px;
    height: 72px;
    border-radius: 18px;
    overflow: hidden;
    background: linear-gradient(135deg, #dde7f3, #f3f6fa);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6e7c90;
    font-size: 1.35rem;
}

.purchase-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.purchase-name {
    color: #10233e;
    font-size: 1.02rem;
    font-weight: 800;
    line-height: 1.5;
}

.purchase-meta {
    margin-top: 0.3rem;
    color: #67778c;
    font-size: 0.92rem;
}

.purchase-total {
    color: #0d6efd;
    font-size: 1.05rem;
    font-weight: 800;
    white-space: nowrap;
}

.next-step-card {
    position: relative;
    overflow: hidden;
}

.next-step-card::after {
    content: '';
    position: absolute;
    inset: auto -15% -45% 45%;
    height: 220px;
    background: radial-gradient(circle, rgba(0, 127, 255, 0.12), transparent 72%);
    pointer-events: none;
}

.next-step-panel {
    position: relative;
    z-index: 1;
    padding: 1.3rem;
    border-radius: 20px;
    background: linear-gradient(180deg, rgba(245, 248, 252, 0.95), rgba(236, 242, 249, 0.95));
    border: 1px solid rgba(16, 35, 62, 0.08);
}

.next-step-panel.is-approved {
    background: linear-gradient(180deg, rgba(231, 251, 241, 0.96), rgba(236, 248, 241, 0.96));
    border-color: rgba(25, 135, 84, 0.16);
}

.next-step-panel.is-pending {
    background: linear-gradient(180deg, rgba(255, 250, 225, 0.98), rgba(255, 246, 211, 0.98));
    border-color: rgba(255, 193, 7, 0.2);
}

.next-step-panel.is-rejected {
    background: linear-gradient(180deg, rgba(255, 234, 236, 0.98), rgba(255, 242, 243, 0.98));
    border-color: rgba(220, 53, 69, 0.18);
}

.next-step-panel.is-cancelled {
    background: linear-gradient(180deg, rgba(242, 244, 247, 0.98), rgba(247, 249, 251, 0.98));
    border-color: rgba(108, 117, 125, 0.16);
}

.next-step-kicker {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.45rem 0.8rem;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.7);
    border: 1px solid rgba(16, 35, 62, 0.08);
    color: #10233e;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.next-step-title {
    margin: 1rem 0 0.5rem;
    color: #10233e;
    font-size: 1.4rem;
    font-weight: 800;
    line-height: 1.25;
}

.next-step-message {
    margin: 0;
    color: #58697f;
    line-height: 1.8;
}

.next-step-note {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(16, 35, 62, 0.08);
    color: #6c7a8f;
    line-height: 1.75;
}

.success-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.85rem;
    margin-top: 1.4rem;
}

.btn-success-primary,
.btn-success-secondary,
.btn-success-outline {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.65rem;
    border-radius: 999px;
    padding: 0.9rem 1.35rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.24s ease;
}

.btn-success-primary {
    background: linear-gradient(135deg, #0d6efd, #00a3ff);
    color: #fff;
    box-shadow: 0 16px 28px rgba(13, 110, 253, 0.22);
}

.btn-success-primary:hover {
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 18px 30px rgba(13, 110, 253, 0.28);
}

.btn-success-secondary {
    background: rgba(16, 35, 62, 0.08);
    color: #10233e;
}

.btn-success-secondary:hover {
    background: rgba(16, 35, 62, 0.12);
    color: #10233e;
}

.btn-success-outline {
    border: 1px solid rgba(16, 35, 62, 0.12);
    color: #10233e;
    background: transparent;
}

.btn-success-outline:hover {
    background: rgba(16, 35, 62, 0.05);
    color: #10233e;
}

[dir="rtl"] .checkout-success-page h1,
[dir="rtl"] .checkout-success-page h2,
[dir="rtl"] .checkout-success-page h3,
[dir="rtl"] .checkout-success-page h4,
[dir="rtl"] .checkout-success-page h5,
[dir="rtl"] .checkout-success-page h6 {
    font-family: 'Tajawal', 'Noto Sans Arabic', sans-serif;
}

[dir="rtl"] .success-state-chip,
[dir="rtl"] .success-kicker,
[dir="rtl"] .next-step-kicker,
[dir="rtl"] .btn-success-primary,
[dir="rtl"] .btn-success-secondary,
[dir="rtl"] .btn-success-outline,
[dir="rtl"] .success-card-title {
    flex-direction: row-reverse;
}

[dir="rtl"] .purchase-total,
[dir="rtl"] .summary-value.is-total {
    text-align: left;
}

@media (max-width: 991px) {
    .checkout-success-page {
        padding-top: calc(var(--navbar-height-mobile) + 1rem);
        background:
            radial-gradient(circle at top, rgba(0, 127, 255, 0.14), transparent 24%),
            linear-gradient(180deg, #08121f 0%, #0f2238 14%, #eff4f9 14%, #f7fafc 100%);
    }

    .success-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .success-hero {
        padding: 1.4rem;
    }

    .success-hero-top {
        flex-direction: column;
    }

    .success-hero-stats,
    .summary-grid {
        grid-template-columns: 1fr;
    }

    .purchase-item {
        grid-template-columns: 64px minmax(0, 1fr);
    }

    .purchase-total {
        grid-column: 2;
        justify-self: start;
    }

    .success-actions {
        flex-direction: column;
    }

    .success-actions a {
        width: 100%;
    }
}
</style>
@endpush

@section('content')
<div class="checkout-success-page">
    <div class="container">
        <div class="checkout-success-shell">
            <section class="success-hero {{ $heroStateClass }}">
                <div class="success-hero-top">
                    <div class="success-hero-copy">
                        <span class="success-kicker">
                            <i class="{{ $heroIcon }}"></i>
                            {{ __('app.common.success') }}
                        </span>
                        <h1 class="success-title">{{ __('app.orders.order_success_title') }}</h1>
                        <p class="success-message">{{ $heroMessage }}</p>
                    </div>

                    <div class="success-state-chip">
                        <i class="bi bi-dot"></i>
                        <span>{{ $order->localized_status }}</span>
                    </div>
                </div>

                <div class="success-hero-stats">
                    <div class="hero-stat">
                        <span class="hero-stat-label">{{ __('app.orders.order_number') }}</span>
                        <div class="hero-stat-value">{{ $order->order_number }}</div>
                    </div>

                    <div class="hero-stat">
                        <span class="hero-stat-label">{{ __('app.orders.items_count') }}</span>
                        <div class="hero-stat-value">{{ $order->items->sum('quantity') }} {{ __('app.orders.items') }}</div>
                    </div>

                    <div class="hero-stat">
                        <span class="hero-stat-label">{{ __('app.orders.order_total') }}</span>
                        <div class="hero-stat-value">{{ $order->formatted_total }}</div>
                    </div>
                </div>
            </section>

            <div class="success-grid">
                <div class="success-card">
                    <div class="success-card-body">
                        <div class="success-card-title">
                            <i class="bi bi-receipt"></i>
                            <span>{{ __('app.orders.order_summary_title') }}</span>
                        </div>
                        <p class="success-card-copy">{{ __('app.orders.order_summary_copy') }}</p>

                        <div class="summary-grid">
                            <div class="summary-item">
                                <span class="summary-label">{{ __('app.orders.order_number') }}</span>
                                <div class="summary-value">{{ $order->order_number }}</div>
                            </div>

                            <div class="summary-item">
                                <span class="summary-label">{{ __('app.orders.order_date') }}</span>
                                <div class="summary-value">{{ $order->created_at->format('M d, Y H:i') }}</div>
                            </div>

                            <div class="summary-item">
                                <span class="summary-label">{{ __('app.orders.payment_method') }}</span>
                                <div class="summary-value">{{ $order->payment_method_name }}</div>
                            </div>

                            <div class="summary-item">
                                <span class="summary-label">{{ __('app.orders.order_total') }}</span>
                                <div class="summary-value is-total">{{ $order->formatted_total }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <aside class="success-card next-step-card">
                    <div class="success-card-body">
                        <div class="success-card-title">
                            <i class="bi bi-signpost-split"></i>
                            <span>{{ __('app.orders.next_step_title') }}</span>
                        </div>
                        <p class="success-card-copy">{{ __('app.orders.next_step_copy') }}</p>

                        <div class="next-step-panel
                            {{ $isApproved && $hasAccessibleDeliveries ? 'is-approved' : '' }}
                            {{ $isPending ? 'is-pending' : '' }}
                            {{ $isRejected ? 'is-rejected' : '' }}
                            {{ $isCancelled ? 'is-cancelled' : '' }}">
                            <span class="next-step-kicker">
                                <i class="bi bi-stars"></i>
                                {{ __('app.orders.current_status') }}
                            </span>

                            <h2 class="next-step-title">{{ $nextStepTitle }}</h2>
                            <p class="next-step-message">{{ $nextStepMessage }}</p>

                            <div class="next-step-note">
                                @if($isApproved && $hasAccessibleDeliveries)
                                    {{ __('app.orders.next_step_ready_note') }}
                                @elseif($isPending)
                                    {{ __('app.orders.next_step_pending_note') }}
                                @elseif($isRejected)
                                    {{ __('app.orders.next_step_rejected_note') }}
                                @elseif($isCancelled)
                                    {{ __('app.orders.next_step_cancelled_note') }}
                                @else
                                    {{ __('app.orders.next_step_approved_note') }}
                                @endif
                            </div>
                        </div>
                    </div>
                </aside>
            </div>

            <section class="success-card" style="margin-top: 1.5rem;">
                <div class="success-card-body">
                    <div class="success-card-title">
                        <i class="bi bi-bag-check"></i>
                        <span>{{ __('app.orders.purchased_products_title') }}</span>
                    </div>
                    <p class="success-card-copy">{{ __('app.orders.purchased_products_copy') }}</p>

                    <div class="purchase-list">
                        @foreach($order->items as $item)
                            <article class="purchase-item">
                                <div class="purchase-thumb">
                                    @if($item->product && $item->product->thumbnail)
                                        <img src="{{ asset('storage/' . $item->product->thumbnail) }}" alt="{{ $item->getTranslation() }}">
                                    @else
                                        <i class="bi bi-box-seam"></i>
                                    @endif
                                </div>

                                <div>
                                    <div class="purchase-name">{{ $item->getTranslation() }}</div>
                                    <div class="purchase-meta">
                                        {{ __('app.cart.quantity') }}: {{ $item->quantity }}
                                    </div>
                                </div>

                                <div class="purchase-total">{{ $item->formatted_subtotal }}</div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <div class="success-actions">
                <a href="{{ route('orders.show', $order) }}" class="btn-success-primary">
                    <i class="bi {{ $isApproved && $hasAccessibleDeliveries ? 'bi-lightning-charge-fill' : 'bi-eye' }}"></i>
                    <span>{{ $primaryActionLabel }}</span>
                </a>

                <a href="{{ route('orders.index') }}" class="btn-success-secondary">
                    <i class="bi bi-list-ul"></i>
                    <span>{{ __('app.orders.go_to_my_orders') }}</span>
                </a>

                <a href="{{ route('products.index') }}" class="btn-success-outline">
                    <i class="bi bi-shop"></i>
                    <span>{{ __('app.cart.continue_shopping') }}</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
