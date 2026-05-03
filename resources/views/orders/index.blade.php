@extends('layouts.app')

@section('title', __('orders.my_orders') . ' - ' . __('app.hero.title'))
@section('description', __('orders.order_history'))

@push('styles')
<style>
    .orders-header {
        background: linear-gradient(135deg, var(--voltronix-primary), var(--voltronix-secondary));
        color: white;
        padding: 4rem 0 2rem;
        padding-top: calc(var(--navbar-height-desktop) + 2rem);
        position: relative;
        overflow: hidden;
        margin-bottom: 3rem;
    }
    
    /* RTL Support */
    [dir="rtl"] .order-header {
        direction: rtl;
    }
    
    
    [dir="rtl"] .order-meta {
        text-align: right;
    }
    
    .orders-header::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0, 127, 255, 0.1), rgba(35, 239, 255, 0.05));
        z-index: 1;
    }
    
    .orders-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        animation: pulse 4s ease-in-out infinite alternate;
    }
    
    .header-content {
        position: relative;
        z-index: 2;
    }

    .stats-section {
        margin-top: -2rem;
        margin-bottom: 2rem;
        position: relative;
        z-index: 3;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 2px solid rgba(0, 127, 255, 0.1);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 127, 255, 0.2);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--voltronix-primary);
        font-family: 'Orbitron', sans-serif;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }
    
    .filters-section {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        border: 2px solid rgba(0, 127, 255, 0.1);
    }
    
    .order-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .order-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--voltronix-gradient);
    }
    
    .order-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(13, 110, 253, 0.15);
        border-color: var(--voltronix-primary);
    }
    
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .order-number {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--voltronix-primary);
    }
    
    .order-status {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
    }
    
    .status-pending {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }
    
    .status-approved {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .status-rejected {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .status-cancelled {
        background: #e2e3e5;
        color: #383d41;
        border: 1px solid #d6d8db;
    }
    
    .order-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .order-date {
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    .order-total {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--voltronix-primary);
    }
    
    .order-items {
        margin-bottom: 1rem;
    }
    
    .items-preview {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .item-thumbnail {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        overflow: hidden;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .item-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .item-icon {
        font-size: 1.2rem;
        color: var(--voltronix-primary);
        opacity: 0.6;
    }
    
    .items-count {
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    .order-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(0, 127, 255, 0.1);
    }
    
    .btn-view {
        background: linear-gradient(135deg, #007fff, #23efff);
        border: none;
        color: white !important;
        padding: 0.5rem 1.5rem;
        border-radius: 20px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-view:hover {
        background: linear-gradient(135deg, #0056b3, #1bc7e6);
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 127, 255, 0.3);
    }
    
    .btn-cancel {
        background: linear-gradient(135deg, #dc3545, #e74c3c);
        border: none;
        color: white !important;
        padding: 0.5rem 1.5rem;
        border-radius: 20px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-cancel:hover {
        background: linear-gradient(135deg, #c82333, #d32f2f);
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
    }
    
    .btn-cancel:disabled {
        background: #6c757d !important;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
    
    [dir="rtl"] .order-actions {
        direction: rtl;
    }
    
    .empty-orders {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .empty-orders i {
        font-size: 4rem;
        color: var(--voltronix-primary);
        margin-bottom: 1rem;
        opacity: 0.6;
    }
    
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--voltronix-primary);
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        text-transform: uppercase;
        font-weight: 600;
    }
    
    .quick-actions {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .quick-actions h5 {
        margin-bottom: 1rem;
        color: var(--voltronix-accent);
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .action-btn {
        background: linear-gradient(135deg, #007fff, #23efff);
        border: 2px solid transparent;
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: 0 4px 15px rgba(0, 127, 255, 0.2);
    }
    
    .action-btn:hover {
        background: linear-gradient(135deg, #0056b3, #1bc7e6);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 127, 255, 0.4);
    }
    
    .payment-proof-section {
        margin: 1rem 0;
        padding: 1rem;
        background: rgba(0, 127, 255, 0.05);
        border-radius: 10px;
        border: 1px solid rgba(0, 127, 255, 0.1);
    }
    
    .payment-proof-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--voltronix-primary);
        margin-bottom: 0.5rem;
    }
    
    .payment-proof-thumbnail {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .payment-proof-thumbnail img {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid rgba(0, 127, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .payment-proof-thumbnail img:hover {
        border-color: var(--voltronix-primary);
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(0, 127, 255, 0.3);
    }
    
    .payment-proof-thumbnail .file-icon {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 80px;
        height: 60px;
        background: white;
        border: 2px solid rgba(0, 127, 255, 0.2);
        border-radius: 8px;
        color: var(--voltronix-primary);
        transition: all 0.3s ease;
    }
    
    .payment-proof-thumbnail .file-icon:hover {
        border-color: var(--voltronix-primary);
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(0, 127, 255, 0.3);
    }
    
    .payment-proof-thumbnail .file-icon i {
        font-size: 1.5rem;
        margin-bottom: 0.25rem;
    }
    
    .payment-proof-thumbnail .file-icon span {
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .orders-header h1 {
        font-family: 'Orbitron', sans-serif;
        color: #ffffff !important;
        font-weight: 900;
        text-shadow: 0 4px 0px rgba(255, 255, 255, 0.6), 0 2px 10px rgba(0, 0, 0, 0.8) !important;
        letter-spacing: 2px;
        font-size: 3.5rem;
        margin-bottom: 0;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        filter: drop-shadow(0 0 20px rgba(255, 255, 255, 0.8));
    }
    
    .orders-header h1 i {
        color: #ffffff !important;
        text-shadow: 0 4px 0px rgba(253, 253, 253, 0.8), 0 2px 10px rgba(0, 0, 0, 0.9) !important;
        filter: drop-shadow(0 0 15px rgba(255, 255, 255, 0.9));
    }
    
    .orders-header .lead {
        color: #ffffff !important;
        font-weight: 600;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.8), 0 1px 5px rgba(0, 127, 255, 0.4) !important;
        font-size: 1.3rem;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.6));
    }
    
    /* Enhanced Breadcrumb Styling for Orders Page */
    .orders-header .orders-breadcrumb {
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 0.75rem 1.25rem;
        margin-bottom: 2rem;
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }
    
    .orders-header .orders-breadcrumb .breadcrumb-item,
    .orders-header .orders-breadcrumb .breadcrumb-item a {
        color: #ffffff !important;
        text-shadow: none;
        font-weight: 700;
        font-size: 0.9rem;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    
    .orders-header .orders-breadcrumb .breadcrumb-item a {
        text-decoration: none;
        transition: all 0.3s ease;
        opacity: 1;
    }
    
    .orders-header .orders-breadcrumb .breadcrumb-item a:hover {
        color: #23efff !important;
        opacity: 1;
        text-shadow: none;
        transform: translateY(-1px);
    }
    
    .orders-header .orders-breadcrumb .breadcrumb-item.active {
        color: #ffffff !important;
        font-weight: 800;
        opacity: 1;
        text-shadow: none;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    
    .orders-header .orders-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: #ffffff !important;
        font-weight: bold;
        margin: 0 0.75rem;
        opacity: 0.8;
        text-shadow: none;
    }
    
    /* RTL Support for Orders Breadcrumbs */
    [dir="rtl"] .orders-header .orders-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        content: "‹";
    }
    
    /* Orders page specific navbar fixes - DO NOT modify global layout */
    
    /* Fix navbar styling conflicts on Orders page only */
    .voltronix-navbar {
        background: rgba(13, 20, 33, 0.95) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        border-bottom: 1px solid rgba(0, 127, 255, 0.2) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        height: var(--navbar-height-desktop) !important;
    }

    .voltronix-navbar.scrolled {
        background: rgba(13, 20, 33, 0.98) !important;
        box-shadow: 0 8px 32px rgba(0, 127, 255, 0.15) !important;
        border-bottom-color: rgba(0, 127, 255, 0.3) !important;
    }

    .navbar-container {
        max-width: 1400px !important;
        margin: 0 auto !important;
        padding: 0 3rem !important;
        height: 100% !important;
        display: grid !important;
        grid-template-columns: auto 1fr auto !important;
        align-items: center !important;
        gap: 3rem !important;
    }

    .navbar-brand {
        display: flex !important;
        align-items: center !important;
        gap: 14px !important;
        text-decoration: none !important;
        transition: all 0.4s ease !important;
    }

    .logo-image {
        width: 42px !important;
        height: 42px !important;
        border-radius: 10px !important;
        object-fit: contain !important;
        filter: drop-shadow(0 2px 8px rgba(0, 127, 255, 0.2)) !important;
    }

    .brand-name {
        font-family: 'Orbitron', sans-serif !important;
        font-size: 1.4rem !important;
        font-weight: 700 !important;
        background: linear-gradient(135deg, #007fff 0%, #23efff 100%) !important;
        -webkit-background-clip: text !important;
        -webkit-text-fill-color: transparent !important;
        background-clip: text !important;
        line-height: 1.1 !important;
    }

    .nav-link {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        padding: 10px 18px !important;
        color: rgba(255, 255, 255, 0.7) !important;
        text-decoration: none !important;
        border-radius: 18px !important;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) !important;
        font-weight: 500 !important;
        position: relative !important;
        font-size: 0.9rem !important;
    }

    .nav-link:hover {
        color: rgba(255, 255, 255, 0.95) !important;
        background: rgba(255, 255, 255, 0.04) !important;
        transform: translateY(-1px) !important;
    }

    .nav-link.active {
        color: white !important;
        background: rgba(0, 127, 255, 0.08) !important;
        box-shadow: 0 2px 12px rgba(0, 127, 255, 0.1) !important;
    }

    .action-btn {
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        padding: 8px 14px !important;
        background: transparent !important;
        border: none !important;
        border-radius: 20px !important;
        color: rgba(255, 255, 255, 0.7) !important;
        text-decoration: none !important;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) !important;
        font-weight: 500 !important;
        cursor: pointer !important;
        position: relative !important;
        font-size: 0.85rem !important;
    }

    .action-btn:hover {
        color: rgba(255, 255, 255, 0.95) !important;
        background: rgba(255, 255, 255, 0.04) !important;
        transform: translateY(-1px) !important;
    }

    .action-btn.active {
        color: white !important;
        background: rgba(0, 127, 255, 0.08) !important;
        box-shadow: 0 2px 12px rgba(0, 127, 255, 0.1) !important;
    }
    
    /* Mobile responsive navbar spacing */
    @media (max-width: 768px) {
        .orders-header {
            padding-top: calc(var(--navbar-height-mobile) + 1.5rem);
        }
        
        .voltronix-navbar {
            height: var(--navbar-height-mobile) !important;
        }
        
        .navbar-container {
            padding: 0 1rem !important;
            gap: 1rem !important;
        }
    }
</style>
@endpush

@section('content')
<!-- Orders Header -->
<section class="orders-header">
    <div class="volt-container">
        <div class="header-content">
            <br>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb orders-breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">{{ __('app.nav.home') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __('orders.my_orders') }}
                    </li>
                </ol>
            </nav>
            
            <h1 class="display-4 fw-bold mb-3 title-orbitron">
                <i class="bi bi-bag-check {{ app()->getLocale() == 'ar' ? 'ms-3' : 'me-3' }}"></i>
                {{ __('orders.my_orders') }}
            </h1>
            <p class="lead mb-0">{{ __('orders.manage_orders') }}</p>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="volt-container">
        <div class="row g-3">
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['total'] }}</div>
                    <div class="stat-label">{{ __('orders.total_orders') }}</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['pending'] }}</div>
                    <div class="stat-label">{{ __('orders.status.pending') }}</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['approved'] }}</div>
                    <div class="stat-label">{{ __('orders.status.approved') }}</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['rejected'] }}</div>
                    <div class="stat-label">{{ __('orders.status.rejected') }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Orders Content -->
<section class="py-5" style="background: var(--voltronix-light);">
    <div class="volt-container">
        @if($orders->count() > 0)

                

            <!-- Filters -->
            <div class="filters-section">
                <form method="GET" action="{{ route('orders.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('orders.order_status') }}</label>
                        <select name="status" class="form-select">
                            <option value="">{{ __('app.common.all') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                {{ __('orders.status.pending') }}
                            </option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                {{ __('orders.status.approved') }}
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                {{ __('orders.status.rejected') }}
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                {{ __('orders.status.cancelled') }}
                            </option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-voltronix">
                            <i class="bi bi-funnel {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                            {{ __('app.common.filter') }}
                        </button>
                        @if(request('status'))
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary ms-2">
                                {{ __('app.common.clear') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Orders List -->
            @foreach($orders as $order)
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-number">{{ $order->order_number }}</div>
                        <div class="order-status status-{{ $order->status }}">
                            {{ $order->localized_status }}
                        </div>
                    </div>
                    
                    <div class="order-meta">
                        <div class="order-info">
                            <div class="order-date">
                                <i class="bi bi-calendar {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                {{ local_datetime($order->created_at, 'M d, Y H:i') }}
                            </div>
                            <div class="payment-method">
                                <i class="bi bi-credit-card {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                {{ $order->payment_method_label }}
                            </div>
                        </div>
                        <div class="order-total">{{ $order->formatted_total }}</div>
                    </div>
                    
                    <div class="order-items">
                        <div class="items-preview">
                            @foreach($order->items->take(5) as $item)
                                <div class="item-thumbnail">
                                    @if($item->product && $item->product->thumbnail)
                                        <img src="{{ asset('storage/' . $item->product->thumbnail) }}" 
                                             alt="{{ $item->getTranslation('name') }}">
                                    @else
                                        <i class="bi bi-box-seam item-icon"></i>
                                    @endif
                                </div>
                            @endforeach
                            @if($order->items->count() > 5)
                                <div class="item-thumbnail">
                                    <span style="font-size: 0.8rem; font-weight: 600;">+{{ $order->items->count() - 5 }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="items-count">
                            {{ trans_choice('app.orders.items_count', $order->items->count(), ['count' => $order->items->count()]) }}
                        </div>
                    </div>
                    
                    <div class="order-actions">
                        <a href="{{ route('orders.show', $order->order_number) }}" class="btn-view">
                            <i class="bi bi-eye {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                            {{ __('app.orders.view_details') }}
                        </a>
                        
                        @if($order->canBeCancelled())
                            <button type="button" 
                                    class="btn-cancel cancel-order-btn" 
                                    data-order-id="{{ $order->id }}"
                                    data-order-number="{{ $order->order_number }}"
                                    data-cancel-url="{{ route('orders.cancel', $order->order_number) }}"
                                    data-time-remaining="{{ $order->getCancellationTimeRemaining() }}">
                                <i class="bi bi-x-circle {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                {{ __('orders.cancel_order') }}
                                @if($order->getCancellationTimeRemaining() > 0)
                                <small class="d-block text-muted" style="font-size: 0.7rem;">
                                    {{ $order->getCancellationTimeRemaining() }}{{ __('orders.minutes') }} {{ __('orders.remaining') }}
                                </small>
                                @endif
                            </button>
                        @else
                            <button type="button" class="btn-cancel" disabled
                                    data-bs-toggle="tooltip" 
                                    title="{{ __('orders.cannot_cancel_tooltip') }}">
                                <i class="bi bi-x-circle {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                {{ __('orders.cancel_order') }}
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="empty-orders">
                <i class="bi bi-bag-x"></i>
                <h3>{{ __('app.orders.no_orders') }}</h3>
                <p class="mb-4">{{ __('app.orders.start_shopping') }}</p>
                <a href="{{ route('products.index') }}" class="btn btn-voltronix btn-lg">
                    <i class="bi bi-shop {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('app.orders.start_shopping') }}
                </a>
            </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
    // View payment proof function
    function viewPaymentProof(url, orderNumber) {
        Swal.fire({
            title: `{{ __('app.orders.payment_proof') }} - ${orderNumber}`,
            html: `
                <div style="text-align: center;">
                    <img src="${url}" style="max-width: 100%; max-height: 400px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.2);" 
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div style="display: none; padding: 2rem;">
                        <i class="bi bi-file-earmark-pdf" style="font-size: 3rem; color: var(--voltronix-primary);"></i>
                        <p style="margin-top: 1rem;">{{ __('app.orders.pdf_file') }}</p>
                        <a href="${url}" target="_blank" class="btn btn-voltronix-primary">
                            <i class="bi bi-download {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('app.orders.download') }}
                        </a>
                    </div>
                </div>
            `,
            showCloseButton: true,
            showConfirmButton: false,
            width: '600px',
            customClass: {
                popup: 'payment-proof-modal'
            }
        });
    }
    
    // Auto-refresh orders if coming from checkout
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Modern AJAX cancel order functionality
        document.querySelectorAll('.cancel-order-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const orderNumber = this.dataset.orderNumber;
                const cancelUrl = this.dataset.cancelUrl;
                
                Swal.fire({
                    title: '{{ __("orders.cancel_order_confirm") }}',
                    text: '{{ __("orders.cancel_order_text") }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __("orders.yes_cancel") }}',
                    cancelButtonText: '{{ __("orders.no_keep") }}',
                    reverseButtons: {{ app()->getLocale() == 'ar' ? 'true' : 'false' }},
                    background: 'linear-gradient(135deg, #ffffff, #f8f9fa)',
                    customClass: {
                        popup: 'voltronix-swal',
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: '{{ __("app.common.loading") }}',
                            text: '{{ __("orders.cancelling_order") }}',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            background: 'linear-gradient(135deg, #ffffff, #f8f9fa)',
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Send AJAX request
                        fetch(cancelUrl, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: '{{ __("app.common.success") }}',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonColor: '#007fff',
                                    background: 'linear-gradient(135deg, #ffffff, #f8f9fa)',
                                    customClass: {
                                        confirmButton: 'btn btn-primary'
                                    },
                                    buttonsStyling: false
                                }).then(() => {
                                    // Reload the page to show updated status
                                    location.reload();
                                });
                            } else {
                                throw new Error(data.message || 'Cancellation failed');
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: '{{ __("app.common.error") }}',
                                text: error.message || '{{ __("orders.cancellation_failed") }}',
                                icon: 'error',
                                confirmButtonColor: '#dc3545',
                                background: 'linear-gradient(135deg, #ffffff, #f8f9fa)',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                },
                                buttonsStyling: false
                            });
                        });
                    }
                });
            });
        });
        
        // Check if we came from checkout success
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('from') === 'checkout') {
            // Show success message
            Swal.fire({
                icon: 'success',
                title: `{{ __('app.checkout.order_placed_successfully') }}`,
                text: `{{ __('app.orders.order_created_message') }}`,
                confirmButtonColor: '#007fff',
                confirmButtonText: `{{ __('app.common.ok') }}`
            });
            
            // Remove the parameter from URL
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });
    
    // Scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all order cards
    document.querySelectorAll('.order-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
</script>
@endpush

@endsection


