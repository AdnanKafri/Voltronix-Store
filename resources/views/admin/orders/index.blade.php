@extends('admin.layouts.app')

@push('styles')
<style>
.orders-dashboard {
    padding: 2rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
    border: 2px solid rgba(0, 127, 255, 0.1);
    border-radius: 20px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #007fff, #23efff);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 127, 255, 0.15);
    border-color: #007fff;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: white;
}

.stat-icon.pending { background: linear-gradient(135deg, #ffc107, #ffb300); }
.stat-icon.approved { background: linear-gradient(135deg, #28a745, #20c997); }
.stat-icon.rejected { background: linear-gradient(135deg, #dc3545, #e74c3c); }
.stat-icon.total { background: linear-gradient(135deg, #007fff, #23efff); }

.stat-number {
    font-family: 'Orbitron', monospace;
    font-size: 2.5rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #6c757d;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.9rem;
}

.orders-table-container {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 2px solid rgba(0, 127, 255, 0.1);
    overflow: hidden;
    width: 100%;
    max-width: 100%;
}

.table-header {
    background: linear-gradient(135deg, #007fff, #23efff);
    color: white;
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.table-title {
    font-family: 'Orbitron', monospace;
    font-weight: 700;
    font-size: 1.25rem;
    margin: 0;
}

.filters-section {
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
}

.filter-input {
    border: none;
    border-radius: 10px;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    backdrop-filter: blur(10px);
    min-width: 200px;
}

.filter-input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.filter-select {
    border: none;
    border-radius: 10px;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    backdrop-filter: blur(10px);
    min-width: 150px;
}

.orders-table {
    width: 100%;
    margin: 0;
    min-width: 800px; /* Minimum width to prevent cramping */
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.orders-table th {
    background: #f8f9fa;
    color: #1a1a1a;
    font-weight: 600;
    padding: 1rem;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
}

.orders-table td {
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
}

.orders-table tbody tr:hover {
    background: rgba(0, 127, 255, 0.05);
}

.order-number {
    font-family: 'Orbitron', monospace;
    font-weight: 600;
    color: #007fff;
    text-decoration: none;
}

.order-number:hover {
    color: #0056b3;
    text-decoration: underline;
}

.customer-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.customer-name {
    font-weight: 600;
    color: #1a1a1a;
}

.customer-email {
    font-size: 0.85rem;
    color: #6c757d;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.status-badge.pending {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    color: #856404;
}

.status-badge.approved {
    background: linear-gradient(135deg, #d4edda, #a8e6cf);
    color: #155724;
}

.status-badge.rejected {
    background: linear-gradient(135deg, #f8d7da, #ffb3ba);
    color: #721c24;
}

.status-badge.cancelled {
    background: linear-gradient(135deg, #e2e3e5, #d1d3d4);
    color: #383d41;
}

.order-amount {
    font-family: 'Orbitron', monospace;
    font-weight: 600;
    font-size: 1.1rem;
    color: #28a745;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.btn-action {
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    border: none;
    font-size: 0.8rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.btn-view {
    background: linear-gradient(135deg, #007fff, #23efff);
    color: white;
}

.btn-view:hover {
    background: linear-gradient(135deg, #0056b3, #1bb3e6);
    transform: translateY(-1px);
    color: white;
}

.btn-approve {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
}

.btn-approve:hover {
    background: linear-gradient(135deg, #1e7e34, #17a2b8);
    transform: translateY(-1px);
    color: white;
}

.btn-reject {
    background: linear-gradient(135deg, #dc3545, #e74c3c);
    color: white;
}

.btn-reject:hover {
    background: linear-gradient(135deg, #c82333, #dc2626);
    transform: translateY(-1px);
    color: white;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.pagination-wrapper {
    padding: 1.5rem 2rem;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

/* RTL Support */
[dir="rtl"] .table-header {
    flex-direction: row-reverse;
}

[dir="rtl"] .filters-section {
    flex-direction: row-reverse;
}

[dir="rtl"] .action-buttons {
    flex-direction: row-reverse;
}

/* Responsive Design */
@media (max-width: 768px) {
    .orders-dashboard {
        padding: 1rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .stat-card {
        padding: 1.5rem;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .table-header {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .filters-section {
        justify-content: center;
    }
    
    .filter-input,
    .filter-select {
        min-width: auto;
        flex: 1;
    }
    
    .orders-table {
        font-size: 0.9rem;
    }
    
    .orders-table th,
    .orders-table td {
        padding: 0.75rem 0.5rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.3rem;
    }
    
    .btn-action {
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .customer-info {
        font-size: 0.8rem;
    }
    
    .order-amount {
        font-size: 1rem;
    }
}

/* Card Voltronix Styles */
.card-voltronix {
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
    border: 1px solid rgba(13, 110, 253, 0.1);
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(13, 110, 253, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-voltronix:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(13, 110, 253, 0.12);
}

.title-orbitron {
    font-family: 'Orbitron', sans-serif;
    font-weight: 900;
    background: linear-gradient(135deg, #007fff, #23efff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.table th {
    font-weight: 600;
    color: #1a1a1a;
    border-bottom: 2px solid rgba(13, 110, 253, 0.1);
    background: linear-gradient(145deg, #f8f9fa, #e9ecef);
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
    transform: scale(1.01);
}

.btn-group .btn {
    border-radius: 8px;
    margin: 0 2px;
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
}

.order-number {
    font-family: 'Orbitron', monospace;
    font-weight: 600;
    color: #007fff;
    text-decoration: none;
}

.order-number:hover {
    color: #0056b3;
    text-decoration: underline;
}

.order-amount {
    font-family: 'Orbitron', monospace;
    font-weight: 600;
    font-size: 1.1rem;
    color: #28a745;
}

.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
    border-radius: 10px;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group .btn {
        padding: 0.375rem 0.5rem;
    }
    
    .table tbody tr:hover {
        transform: none;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 title-orbitron">
                <i class="bi bi-cart-check {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.orders.management') }}
            </h1>
            <p class="text-muted mb-0">{{ __('admin.orders.manage_customer_orders') }}</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="bi bi-cart-check"></i>
                </div>
                <div class="stat-number">{{ $stats['total'] }}</div>
                <div class="stat-label">{{ __('admin.orders.total_orders') }}</div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-icon pending">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-number">{{ $stats['pending'] }}</div>
                <div class="stat-label">{{ __('admin.orders.pending') }}</div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-icon approved">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-number">{{ $stats['approved'] }}</div>
                <div class="stat-label">{{ __('admin.orders.approved') }}</div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-icon rejected">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stat-number">{{ $stats['rejected'] }}</div>
                <div class="stat-label">{{ __('admin.orders.rejected') }}</div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card card-voltronix mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
                <!-- Search -->
                <div class="col-md-6">
                    <label for="search" class="form-label">{{ __('admin.search') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="{{ __('admin.orders.search_placeholder') }}">
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="col-md-4">
                    <label for="status" class="form-label">{{ __('admin.orders.filter_by_status') }}</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">{{ __('admin.common.all') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            {{ __('admin.orders.pending') }}
                        </option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                            {{ __('admin.orders.approved') }}
                        </option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                            {{ __('admin.orders.rejected') }}
                        </option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                            {{ __('admin.orders.cancelled') }}
                        </option>
                    </select>
                </div>

                <!-- Filter Actions -->
                <div class="col-md-2 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-funnel"></i>
                            {{ __('admin.filter') }}
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="card card-voltronix">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-list-ul {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.orders.orders_list') }}
            </h5>
            <span class="badge bg-primary">{{ $orders->total() }} {{ __('admin.orders.title') }}</span>
        </div>
        
        <div class="card-body p-0">

            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 15%;">{{ __('admin.orders.order_number') }}</th>
                                <th style="width: 20%;">{{ __('admin.orders.customer') }}</th>
                                <th style="width: 20%;">{{ __('admin.orders.products') }}</th>
                                <th style="width: 12%;">{{ __('admin.orders.amount') }}</th>
                                <th style="width: 10%;">{{ __('admin.orders.status') }}</th>
                                <th style="width: 13%;">{{ __('admin.orders.date') }}</th>
                                <th style="width: 10%;">{{ __('admin.orders.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="order-number">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="customer-info">
                                            <div class="fw-bold">{{ $order->customer_name }}</div>
                                            <small class="text-muted">{{ $order->customer_email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            @foreach($order->items->take(2) as $item)
                                                <small class="text-muted">
                                                    {{ $item->product ? $item->product->getTranslation('name') : $item->getTranslation('product_name') }}
                                                    ({{ $item->quantity }}x)
                                                </small>
                                            @endforeach
                                            @if($order->items->count() > 2)
                                                <small class="text-primary">
                                                    +{{ $order->items->count() - 2 }} {{ __('admin.orders.more_items') }}
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="order-amount">${{ number_format($order->total_amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $order->status == 'approved' ? 'success' : ($order->status == 'rejected' ? 'danger' : ($order->status == 'cancelled' ? 'secondary' : 'warning')) }}">
                                            @switch($order->status)
                                                @case('pending')
                                                    <i class="bi bi-clock-history"></i>
                                                    {{ __('admin.orders.pending') }}
                                                    @break
                                                @case('approved')
                                                    <i class="bi bi-check-circle"></i>
                                                    {{ __('admin.orders.approved') }}
                                                    @break
                                                @case('rejected')
                                                    <i class="bi bi-x-circle"></i>
                                                    {{ __('admin.orders.rejected') }}
                                                    @break
                                                @case('cancelled')
                                                    <i class="bi bi-dash-circle"></i>
                                                    {{ __('admin.orders.cancelled') }}
                                                    @break
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ $order->created_at->format('M d, Y') }}</span>
                                            <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="{{ __('admin.orders.view') }}"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                    @endforeach
                        </tbody>
                    </table>
                </div>
            
                @if($orders->hasPages())
                    <div class="card-footer">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-cart-x fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted">{{ __('admin.orders.no_orders') }}</h5>
                    <p class="text-muted mb-4">{{ __('admin.orders.no_orders_message') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

