@extends('admin.layouts.app')

@section('title', __('admin.users.user_details'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 title-orbitron">
                <i class="bi bi-person {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.users.user_details') }}
            </h1>
            <p class="text-muted mb-0">{{ $user->name }} - {{ $user->email }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.back') }}
            </a>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-voltronix">
                <i class="bi bi-pencil {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.users.edit') }}
            </a>
        </div>
    </div>

    <div class="row">
        <!-- User Information Card -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="admin-table">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.users.profile_information') }}
                    </h5>
                </div>
                <div class="card-body text-center">
                    <!-- User Avatar -->
                    <div class="user-avatar-large mb-3">
                        <div class="avatar-circle-large">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    </div>

                    <!-- User Name and Status -->
                    <h4 class="fw-bold mb-2">{{ $user->name }}</h4>
                    <div class="mb-3">
                        <span class="badge {{ $user->status_badge }} fs-6 px-3 py-2">
                            {{ $user->status_text }}
                        </span>
                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'moderator' ? 'warning' : 'secondary') }} fs-6 px-3 py-2 {{ app()->getLocale() == 'ar' ? 'me-2' : 'ms-2' }}">
                            {{ $user->role_text }}
                        </span>
                    </div>

                    <!-- User Details -->
                    <div class="user-details text-start">
                        <div class="detail-item">
                            <i class="bi bi-envelope text-primary"></i>
                            <span class="label">{{ __('admin.users.email') }}:</span>
                            <span class="value">{{ $user->email }}</span>
                            @if($user->email_verified_at)
                                <i class="bi bi-check-circle text-success {{ app()->getLocale() == 'ar' ? 'me-2' : 'ms-2' }}" title="{{ __('admin.users.verified') }}"></i>
                            @else
                                <i class="bi bi-exclamation-circle text-warning {{ app()->getLocale() == 'ar' ? 'me-2' : 'ms-2' }}" title="{{ __('admin.users.unverified') }}"></i>
                            @endif
                        </div>

                        @if($user->phone)
                        <div class="detail-item">
                            <i class="bi bi-telephone text-success"></i>
                            <span class="label">{{ __('admin.users.phone') }}:</span>
                            <span class="value">{{ $user->phone }}</span>
                        </div>
                        @endif

                        <div class="detail-item">
                            <i class="bi bi-calendar text-info"></i>
                            <span class="label">{{ __('admin.users.joined_date') }}:</span>
                            <span class="value">{{ $user->created_at->format('M d, Y') }}</span>
                        </div>

                        <div class="detail-item">
                            <i class="bi bi-clock text-warning"></i>
                            <span class="label">{{ __('admin.users.last_login') }}:</span>
                            <span class="value">{{ $user->formatted_last_login }}</span>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    @unless($user->isAdmin())
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="text-muted mb-3">{{ __('admin.users.quick_actions') }}</h6>
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            @if($user->isActive())
                                <button class="btn btn-warning btn-sm" 
                                        onclick="suspendUser({{ $user->id }})"
                                        data-bs-toggle="tooltip" 
                                        title="{{ __('admin.users.suspend_tooltip') }}">
                                    <i class="bi bi-pause-circle {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                    {{ __('admin.users.suspend') }}
                                </button>
                            @else
                                <button class="btn btn-success btn-sm" 
                                        onclick="activateUser({{ $user->id }})"
                                        data-bs-toggle="tooltip" 
                                        title="{{ __('admin.users.activate_tooltip') }}">
                                    <i class="bi bi-play-circle {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                    {{ __('admin.users.activate') }}
                                </button>
                            @endif
                            
                            @if(!$user->orders()->exists())
                                <button class="btn btn-danger btn-sm" onclick="deleteUser({{ $user->id }})">
                                    <i class="bi bi-trash"></i>
                                    {{ __('admin.users.delete') }}
                                </button>
                            @endif
                        </div>
                    </div>
                    @endunless
                </div>
            </div>
        </div>

        <!-- Statistics and Activity -->
        <div class="col-xl-8 col-lg-7">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="delivery-stat-card">
                        <div class="delivery-stat-content">
                            <div class="delivery-stat-icon bg-primary bg-opacity-10">
                                <i class="bi bi-bag text-primary fs-4"></i>
                            </div>
                            <div class="delivery-stat-text">
                                <h6>{{ __('admin.users.total_orders') }}</h6>
                                <h4>{{ $stats['total_orders'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="delivery-stat-card">
                        <div class="delivery-stat-content">
                            <div class="delivery-stat-icon bg-success bg-opacity-10">
                                <i class="bi bi-check-circle text-success fs-4"></i>
                            </div>
                            <div class="delivery-stat-text">
                                <h6>{{ __('admin.users.completed_orders') }}</h6>
                                <h4>{{ $stats['completed_orders'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="delivery-stat-card">
                        <div class="delivery-stat-content">
                            <div class="delivery-stat-icon bg-warning bg-opacity-10">
                                <i class="bi bi-currency-dollar text-warning fs-4"></i>
                            </div>
                            <div class="delivery-stat-text">
                                <h6>{{ __('admin.users.total_spent') }}</h6>
                                <h4>${{ number_format($stats['total_spent'], 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="delivery-stat-card">
                        <div class="delivery-stat-content">
                            <div class="delivery-stat-icon bg-info bg-opacity-10">
                                <i class="bi bi-graph-up text-info fs-4"></i>
                            </div>
                            <div class="delivery-stat-text">
                                <h6>{{ __('admin.users.average_order') }}</h6>
                                <h4>${{ number_format($stats['average_order'], 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Activity Chart -->
            <div class="admin-table mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.users.order_activity') }} ({{ now()->year }})
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="orderChart" height="100"></canvas>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="admin-table">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.users.recent_orders') }}
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('admin.orders.order_id') }}</th>
                                <th>{{ __('admin.orders.total') }}</th>
                                <th>{{ __('admin.orders.status') }}</th>
                                <th>{{ __('admin.orders.date') }}</th>
                                <th>{{ __('admin.orders.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->orders as $order)
                            <tr>
                                <td>
                                    <span class="fw-bold">#{{ $order->id }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold">${{ number_format($order->total_amount, 2) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-muted small">
                                        {{ $order->created_at->format('M d, Y') }}
                                        <br>
                                        <small>{{ $order->created_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" 
                                       class="action-btn btn-view" 
                                       title="{{ __('admin.orders.view') }}"
                                       data-bs-toggle="tooltip">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-bag display-6 d-block mb-2"></i>
                                        <p class="mb-0">{{ __('admin.users.no_orders') }}</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($user->orders->count() > 0)
                <div class="card-footer text-center">
                    <a href="{{ route('admin.orders.index', ['user_id' => $user->id]) }}" class="btn btn-outline-primary">
                        {{ __('admin.users.view_all_orders') }}
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Order Activity Chart
const ctx = document.getElementById('orderChart').getContext('2d');
const orderChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: '{{ __("admin.users.orders") }}',
            data: @json($chartData),
            borderColor: 'rgb(0, 127, 255)',
            backgroundColor: 'rgba(0, 127, 255, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: 'rgb(0, 127, 255)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6,
            pointHoverRadius: 8
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
                    stepSize: 1
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            }
        },
        elements: {
            point: {
                hoverBackgroundColor: 'rgb(35, 239, 255)'
            }
        }
    }
});

// Suspend user
function suspendUser(userId) {
    Swal.fire({
        title: '{{ __("admin.users.confirm_suspend") }}',
        text: '{{ __("admin.users.suspend_warning") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f39c12',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("admin.users.suspend") }}',
        cancelButtonText: '{{ __("admin.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/users/${userId}/suspend`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __("admin.success") }}',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    setTimeout(() => location.reload(), 2000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("admin.error") }}',
                        text: data.message
                    });
                }
            });
        }
    });
}

// Activate user
function activateUser(userId) {
    fetch(`/admin/users/${userId}/activate`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '{{ __("admin.success") }}',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
            setTimeout(() => location.reload(), 2000);
        } else {
            Swal.fire({
                icon: 'error',
                title: '{{ __("admin.error") }}',
                text: data.message
            });
        }
    });
}

// Delete user
function deleteUser(userId) {
    Swal.fire({
        title: '{{ __("admin.users.confirm_delete") }}',
        text: '{{ __("admin.users.delete_warning") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("admin.users.delete") }}',
        cancelButtonText: '{{ __("admin.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/users/${userId}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<style>
.user-avatar-large .avatar-circle-large {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #007fff, #23efff);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 36px;
    margin: 0 auto;
    box-shadow: 0 8px 32px rgba(0, 127, 255, 0.3);
}

.user-details .detail-item {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
    padding: 8px 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.user-details .detail-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.user-details .detail-item i {
    width: 20px;
    margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 12px;
    font-size: 16px;
}

.user-details .detail-item .label {
    font-weight: 600;
    color: #6c757d;
    margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 8px;
    min-width: 80px;
}

.user-details .detail-item .value {
    flex: 1;
    font-weight: 500;
}

#orderChart {
    height: 300px !important;
}
</style>
@endpush
@endsection
