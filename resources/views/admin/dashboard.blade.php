@extends('admin.layouts.app')

@section('title', __('admin.dashboard'))
@section('page-title', __('admin.dashboard_data.overview'))

@section('content')
<div class="row g-4 mb-4">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['total_products']) }}</div>
            <div class="stat-label">{{ __('admin.dashboard_data.total_products') }}</div>
            <small class="text-success">
                <i class="bi bi-check-circle"></i>
                {{ $stats['active_products'] }} {{ __('admin.dashboard_data.active') }}
            </small>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-receipt"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['total_orders']) }}</div>
            <div class="stat-label">{{ __('admin.dashboard_data.total_orders') }}</div>
            <small class="text-warning">
                <i class="bi bi-clock"></i>
                {{ $stats['pending_orders'] }} {{ __('admin.dashboard_data.pending') }}
            </small>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="stat-value">${{ number_format($stats['total_revenue'], 0) }}</div>
            <div class="stat-label">{{ __('admin.dashboard_data.total_revenue') }}</div>
            <small class="text-info">
                <i class="bi bi-calendar-month"></i>
                ${{ number_format($stats['monthly_revenue'], 0) }} {{ __('admin.dashboard_data.this_month') }}
            </small>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-star"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['total_reviews']) }}</div>
            <div class="stat-label">{{ __('admin.dashboard_data.customer_reviews') }}</div>
            <small class="text-warning">
                <i class="bi bi-hourglass-split"></i>
                {{ $stats['pending_reviews'] }} {{ __('admin.dashboard_data.pending') }}
            </small>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Sales Chart -->
    <div class="col-xl-8">
        <div class="admin-card">
            <div class="card-header-admin">
                <h5 class="card-title-admin">
                    <i class="bi bi-graph-up {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.dashboard_data.monthly_sales_overview') }}
                </h5>
            </div>
            <div class="card-body p-4">
                <canvas id="salesChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Order Status Distribution -->
    <div class="col-xl-4">
        <div class="admin-card">
            <div class="card-header-admin">
                <h5 class="card-title-admin">
                    <i class="bi bi-pie-chart {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.dashboard_data.order_status') }}
                </h5>
            </div>
            <div class="card-body p-4">
                <canvas id="orderStatusChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <!-- Recent Orders -->
    <div class="col-xl-8">
        <div class="admin-card">
            <div class="card-header-admin d-flex justify-content-between align-items-center">
                <h5 class="card-title-admin">
                    <i class="bi bi-clock-history {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.dashboard_data.recent_orders') }}
                </h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
                    {{ __('admin.dashboard_data.view_all') }}
                </a>
            </div>
            <div class="card-body p-0">
                @if($recentOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('admin.dashboard_data.order_number') }}</th>
                                    <th>{{ __('admin.dashboard_data.customer') }}</th>
                                    <th>{{ __('admin.dashboard_data.amount') }}</th>
                                    <th>{{ __('admin.common.status') }}</th>
                                    <th>{{ __('admin.dashboard_data.date') }}</th>
                                    <th>{{ __('admin.common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $order->order_number }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $order->customer_name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $order->customer_email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>${{ number_format($order->total_amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="badge bg-warning">{{ __('admin.dashboard_data.pending') }}</span>
                                                @break
                                            @case('approved')
                                                <span class="badge bg-success">{{ __('admin.dashboard_data.approved') }}</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">{{ __('admin.dashboard_data.rejected') }}</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-secondary">{{ __('admin.dashboard_data.cancelled') }}</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <small>{{ local_datetime($order->created_at, 'M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-receipt text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">{{ __('admin.dashboard_data.no_recent_orders') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Reviews -->
    <div class="col-xl-4">
        <div class="admin-card">
            <div class="card-header-admin d-flex justify-content-between align-items-center">
                <h5 class="card-title-admin">
                    <i class="bi bi-chat-quote {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.dashboard_data.recent_reviews') }}
                </h5>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-outline-primary">
                    {{ __('admin.dashboard_data.view_all') }}
                </a>
            </div>
            <div class="card-body">
                @if($recentReviews->count() > 0)
                    @foreach($recentReviews as $review)
                    <div class="recent-reviews-item {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                        <div class="recent-reviews-avatar">
                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                        </div>
                        <div class="recent-reviews-content">
                            <div class="recent-reviews-meta">
                                <div>
                                    <strong class="d-block">{{ $review->user->name }}</strong>
                                    <small class="text-muted">{{ Str::limit($review->product->getTranslation('name'), 30) }}</small>
                                </div>
                                <div class="text-end">
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                        @endfor
                                    </div>
                                    @if(!$review->approved)
                                        <span class="badge bg-warning mt-1">{{ __('admin.dashboard_data.pending') }}</span>
                                    @endif
                                </div>
                            </div>
                            @if($review->comment)
                                <p class="mb-1 mt-2 small">{{ Str::limit($review->comment, 80) }}</p>
                            @endif
                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-chat-quote text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2 mb-0">{{ __('admin.dashboard_data.no_recent_reviews') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="admin-card">
            <div class="card-header-admin">
                <h5 class="card-title-admin">
                    <i class="bi bi-lightning {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.dashboard_data.quick_actions') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-voltronix w-100">
                            <i class="bi bi-plus-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('admin.dashboard_data.add_new_product') }}
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-outline-warning w-100">
                            <i class="bi bi-clock {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('admin.dashboard_data.review_orders') }}
                            @if($stats['pending_orders'] > 0)
                                <span class="badge bg-warning text-dark ms-1">{{ $stats['pending_orders'] }}</span>
                            @endif
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="btn btn-outline-info w-100">
                            <i class="bi bi-star {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('admin.dashboard_data.moderate_reviews') }}
                            @if($stats['pending_reviews'] > 0)
                                <span class="badge bg-info ms-1">{{ $stats['pending_reviews'] }}</span>
                            @endif
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-tags {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('admin.dashboard_data.add_category') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: '{{ __('admin.dashboard_data.sales_chart_label') }}',
                data: @json($salesData),
                borderColor: '#007fff',
                backgroundColor: 'rgba(0, 127, 255, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Order Status Chart
    const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
    const statusData = @json($orderStatusData);
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusData).map(key => {
                const translations = {
                    'pending': '{{ __('admin.dashboard_data.pending') }}',
                    'approved': '{{ __('admin.dashboard_data.approved') }}',
                    'rejected': '{{ __('admin.dashboard_data.rejected') }}',
                    'cancelled': '{{ __('admin.dashboard_data.cancelled') }}'
                };
                return translations[key] || key.charAt(0).toUpperCase() + key.slice(1);
            }),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: [
                    '#ffc107', // pending - warning
                    '#28a745', // approved - success
                    '#dc3545', // rejected - danger
                    '#6c757d'  // cancelled - secondary
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
});
</script>
@endpush


