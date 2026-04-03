@extends('admin.layouts.app')

@section('title', __('admin.nav.deliveries'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">{{ __('admin.nav.deliveries') }}</h1>
        <p class="text-muted mb-0">{{ __('admin.order.manage_deliveries') }}</p>
    </div>
    <div class="d-flex gap-2">
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-funnel {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.common.filter') }}
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('admin.deliveries.index') }}">{{ __('admin.common.all') }}</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.deliveries.index', ['type' => 'file']) }}">{{ __('admin.common.files') }}</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.deliveries.index', ['type' => 'credentials']) }}">{{ __('admin.common.credentials') }}</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.deliveries.index', ['status' => 'active']) }}">{{ __('admin.common.active') }}</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.deliveries.index', ['status' => 'expired']) }}">{{ __('admin.common.expired') }}</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.deliveries.index', ['status' => 'revoked']) }}">{{ __('admin.common.revoked') }}</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('admin.deliveries.index', ['automation' => 'auto']) }}">
                    <i class="fas fa-cog {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('admin.deliveries.automated_only') }}
                </a></li>
                <li><a class="dropdown-item" href="{{ route('admin.deliveries.index', ['automation' => 'manual']) }}">
                    <i class="fas fa-user {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('admin.deliveries.manual_only') }}
                </a></li>
            </ul>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="delivery-stat-card">
            <div class="delivery-stat-content">
                <div class="delivery-stat-icon bg-primary bg-opacity-10">
                    <i class="bi bi-truck text-primary fs-4"></i>
                </div>
                <div class="delivery-stat-text">
                    <h6>{{ __('admin.deliveries.total_deliveries') }}</h6>
                    <h4>{{ $stats['total'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="delivery-stat-card">
            <div class="delivery-stat-content">
                <div class="delivery-stat-icon bg-success bg-opacity-10">
                    <i class="bi bi-check-circle text-success fs-4"></i>
                </div>
                <div class="delivery-stat-text">
                    <h6>{{ __('admin.common.active') }}</h6>
                    <h4>{{ $stats['active'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="delivery-stat-card">
            <div class="delivery-stat-content">
                <div class="delivery-stat-icon bg-warning bg-opacity-10">
                    <i class="bi bi-clock text-warning fs-4"></i>
                </div>
                <div class="delivery-stat-text">
                    <h6>{{ __('admin.common.expired') }}</h6>
                    <h4>{{ $stats['expired'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="delivery-stat-card">
            <div class="delivery-stat-content">
                <div class="delivery-stat-icon bg-danger bg-opacity-10">
                    <i class="bi bi-x-circle text-danger fs-4"></i>
                </div>
                <div class="delivery-stat-text">
                    <h6>{{ __('admin.common.revoked') }}</h6>
                    <h4>{{ $stats['revoked'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Deliveries Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">{{ __('admin.deliveries.deliveries_list') }}</h6>
            <div class="d-flex gap-2">
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control" placeholder="{{ __('admin.deliveries.search_deliveries') }}" 
                           value="{{ request('search') }}" id="searchInput">
                    <button class="btn btn-outline-secondary" type="button" onclick="searchDeliveries()">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($deliveries->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('admin.deliveries.delivery_title') }}</th>
                            <th>{{ __('admin.deliveries.order_number') }}</th>
                            <th>{{ __('admin.deliveries.customer') }}</th>
                            <th>{{ __('admin.deliveries.type') }}</th>
                            <th>{{ __('admin.common.status') }}</th>
                            <th>{{ __('admin.deliveries.usage') }}</th>
                            <th>{{ __('admin.deliveries.expires') }}</th>
                            <th>{{ __('admin.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deliveries as $delivery)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="{{ app()->getLocale() == 'ar' ? 'me-3' : 'ms-3' }}">
                                        <h6 class="mb-0">{{ $delivery->title }}</h6>
                                        @if($delivery->description)
                                            <small class="text-muted">{{ Str::limit($delivery->description, 50) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $delivery->order) }}" class="text-decoration-none">
                                    {{ $delivery->order->order_number }}
                                </a>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-medium">{{ $delivery->order->customer_name }}</div>
                                    <small class="text-muted">{{ $delivery->order->customer_email }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($delivery->type) }}</span>
                            </td>
                            <td>
                                {!! $delivery->status_badge !!}
                            </td>
                            <td>
                                @if($delivery->type === 'file')
                                    <small class="text-muted">
                                        {{ $delivery->downloads_count }}
                                        @if($delivery->max_downloads)
                                            / {{ $delivery->max_downloads }}
                                        @endif
                                        {{ __('admin.order.downloads') }}
                                    </small>
                                @else
                                    <small class="text-muted">
                                        {{ $delivery->views_count }}
                                        @if($delivery->max_views)
                                            / {{ $delivery->max_views }}
                                        @endif
                                        {{ __('admin.order.views') }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if($delivery->expires_at)
                                    <small class="{{ $delivery->expires_at->isPast() ? 'text-danger' : 'text-success' }}">
                                        {{ $delivery->expires_at->format('M d, Y') }}
                                    </small>
                                @else
                                    <small class="text-muted">{{ __('admin.order.never') }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.orders.deliveries.show', [$delivery->order, $delivery]) }}" 
                                       class="btn btn-sm btn-outline-primary" title="{{ __('admin.common.view') }}">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.deliveries.edit', [$delivery->order, $delivery]) }}" 
                                       class="btn btn-sm btn-outline-secondary" title="{{ __('admin.common.edit') }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.deliveries.logs', [$delivery->order, $delivery]) }}" 
                                       class="btn btn-sm btn-outline-info" title="{{ __('admin.order.view_logs') }}">
                                        <i class="bi bi-list-ul"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($deliveries->hasPages())
                <div class="card-footer bg-white border-top">
                    {{ $deliveries->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('admin.order.no_deliveries') }}</h5>
                <p class="text-muted mb-4">{{ __('admin.order.no_deliveries_message') }}</p>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                    <i class="bi bi-plus {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.order.view_orders') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function searchDeliveries() {
    const search = document.getElementById('searchInput').value;
    const url = new URL(window.location);
    if (search) {
        url.searchParams.set('search', search);
    } else {
        url.searchParams.delete('search');
    }
    window.location = url;
}

// Allow Enter key to trigger search
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchDeliveries();
    }
});
</script>
@endpush
