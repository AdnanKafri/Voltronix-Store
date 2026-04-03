@extends('admin.layouts.app')

@section('title', __('admin.users.statistics'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 title-orbitron">
                <i class="bi bi-graph-up {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.users.statistics') }}
            </h1>
            <p class="text-muted mb-0">{{ __('admin.users.analytics_overview') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.back') }}
            </a>
            <button class="btn btn-outline-primary" onclick="refreshStats()">
                <i class="bi bi-arrow-clockwise {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.refresh') }}
            </button>
        </div>
    </div>

    <!-- Main Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="delivery-stat-card">
                <div class="delivery-stat-content">
                    <div class="delivery-stat-icon bg-primary bg-opacity-10">
                        <i class="bi bi-people text-primary fs-4"></i>
                    </div>
                    <div class="delivery-stat-text">
                        <h6>{{ __('admin.users.total_users') }}</h6>
                        <h4>{{ number_format($stats['total_users']) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="delivery-stat-card">
                <div class="delivery-stat-content">
                    <div class="delivery-stat-icon bg-success bg-opacity-10">
                        <i class="bi bi-check-circle text-success fs-4"></i>
                    </div>
                    <div class="delivery-stat-text">
                        <h6>{{ __('admin.users.active_users') }}</h6>
                        <h4>{{ number_format($stats['active_users']) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="delivery-stat-card">
                <div class="delivery-stat-content">
                    <div class="delivery-stat-icon bg-warning bg-opacity-10">
                        <i class="bi bi-x-circle text-warning fs-4"></i>
                    </div>
                    <div class="delivery-stat-text">
                        <h6>{{ __('admin.users.inactive_users') }}</h6>
                        <h4>{{ number_format($stats['inactive_users']) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="delivery-stat-card">
                <div class="delivery-stat-content">
                    <div class="delivery-stat-icon bg-danger bg-opacity-10">
                        <i class="bi bi-pause-circle text-danger fs-4"></i>
                    </div>
                    <div class="delivery-stat-text">
                        <h6>{{ __('admin.users.suspended_users') }}</h6>
                        <h4>{{ number_format($stats['suspended_users']) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="delivery-stat-card">
                <div class="delivery-stat-content">
                    <div class="delivery-stat-icon bg-info bg-opacity-10">
                        <i class="bi bi-shield-check text-info fs-4"></i>
                    </div>
                    <div class="delivery-stat-text">
                        <h6>{{ __('admin.users.verified_users') }}</h6>
                        <h4>{{ number_format($stats['verified_users']) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="delivery-stat-card">
                <div class="delivery-stat-content">
                    <div class="delivery-stat-icon bg-secondary bg-opacity-10">
                        <i class="bi bi-shield-exclamation text-secondary fs-4"></i>
                    </div>
                    <div class="delivery-stat-text">
                        <h6>{{ __('admin.users.unverified_users') }}</h6>
                        <h4>{{ number_format($stats['unverified_users']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- User Registration Chart -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="admin-table">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.users.registration_trend') }} ({{ now()->year }})
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="registrationChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <!-- User Status Distribution -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="admin-table">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-pie-chart {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.users.status_distribution') }}
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- User Growth Chart -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="admin-table">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up-arrow {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.users.growth_trend') }} ({{ __('admin.users.last_30_days') }})
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="growthChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="admin-table">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.users.recent_registrations') }}
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="recent-users-list">
                        @forelse($recentUsers as $user)
                        <div class="recent-user-item">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar-small {{ app()->getLocale() == 'ar' ? 'ms-3' : 'me-3' }}">
                                    <div class="avatar-circle-small">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">{{ $user->name }}</div>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge {{ $user->status_badge }}">{{ $user->status_text }}</span>
                                    <div class="text-muted small">{{ $user->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="bi bi-people display-6 text-muted"></i>
                            <p class="text-muted mb-0">{{ __('admin.users.no_recent_users') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                @if($recentUsers->count() > 0)
                <div class="card-footer text-center">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-sm">
                        {{ __('admin.users.view_all') }}
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Top Users by Orders -->
    <div class="row">
        <div class="col-12">
            <div class="admin-table">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-trophy {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.users.top_customers') }}
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">{{ __('admin.rank') }}</th>
                                <th>{{ __('admin.users.name') }}</th>
                                <th>{{ __('admin.users.email') }}</th>
                                <th>{{ __('admin.users.orders_count') }}</th>
                                <th>{{ __('admin.users.total_spent') }}</th>
                                <th>{{ __('admin.common.status') }}</th>
                                <th>{{ __('admin.users.joined_date') }}</th>
                                <th style="width: 120px;">{{ __('admin.users.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topUsers as $user)
                            <tr>
                                <td>
                                    <div class="rank-badge">
                                        @if($loop->iteration == 1)
                                            <i class="bi bi-trophy-fill text-warning fs-5"></i>
                                        @elseif($loop->iteration == 2)
                                            <i class="bi bi-award-fill text-secondary fs-5"></i>
                                        @elseif($loop->iteration == 3)
                                            <i class="bi bi-award-fill text-warning fs-5"></i>
                                        @else
                                            <span class="badge bg-light text-dark">{{ $loop->iteration }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar-small {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}">
                                            <div class="avatar-circle-small">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <span class="fw-bold">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $user->orders_count }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">${{ number_format($user->total_spent, 2) }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $user->status_badge }}">{{ $user->status_text }}</span>
                                </td>
                                <td>
                                    <div class="text-muted small">{{ $user->formatted_join_date }}</div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="action-btn btn-view" 
                                           title="{{ __('admin.users.view') }}"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                           class="action-btn btn-edit" 
                                           title="{{ __('admin.users.edit') }}"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-graph-down display-6 d-block mb-2"></i>
                                        <p class="mb-0">{{ __('admin.users.no_top_users') }}</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart.js default configuration
Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.color = '#6c757d';

// Registration Trend Chart
const registrationCtx = document.getElementById('registrationChart').getContext('2d');
const registrationChart = new Chart(registrationCtx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: '{{ __("admin.users.new_registrations") }}',
            data: @json($registrationData),
            backgroundColor: 'rgba(0, 127, 255, 0.8)',
            borderColor: 'rgb(0, 127, 255)',
            borderWidth: 2,
            borderRadius: 8,
            borderSkipped: false,
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
                    display: false
                }
            }
        }
    }
});

// Status Distribution Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: [
            '{{ __("admin.users.status.active") }}',
            '{{ __("admin.users.status.inactive") }}',
            '{{ __("admin.users.status.suspended") }}'
        ],
        datasets: [{
            data: [{{ $stats['active_users'] }}, {{ $stats['inactive_users'] }}, {{ $stats['suspended_users'] }}],
            backgroundColor: [
                'rgba(25, 135, 84, 0.8)',
                'rgba(108, 117, 125, 0.8)',
                'rgba(220, 53, 69, 0.8)'
            ],
            borderColor: [
                'rgb(25, 135, 84)',
                'rgb(108, 117, 125)',
                'rgb(220, 53, 69)'
            ],
            borderWidth: 2
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

// Growth Trend Chart
const growthCtx = document.getElementById('growthChart').getContext('2d');
const growthChart = new Chart(growthCtx, {
    type: 'line',
    data: {
        labels: @json(array_column($growthData, 'date')),
        datasets: [{
            label: '{{ __("admin.users.daily_registrations") }}',
            data: @json(array_column($growthData, 'count')),
            borderColor: 'rgb(35, 239, 255)',
            backgroundColor: 'rgba(35, 239, 255, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: 'rgb(35, 239, 255)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6
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
        }
    }
});

// Refresh statistics
function refreshStats() {
    Swal.fire({
        title: '{{ __("admin.refreshing") }}',
        text: '{{ __("admin.users.updating_statistics") }}',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    setTimeout(() => {
        location.reload();
    }, 1500);
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
.user-avatar-small .avatar-circle-small {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #007fff, #23efff);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 12px;
}

.recent-users-list .recent-user-item {
    padding: 12px 16px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    transition: background-color 0.2s ease;
}

.recent-users-list .recent-user-item:hover {
    background-color: rgba(0, 127, 255, 0.02);
}

.recent-users-list .recent-user-item:last-child {
    border-bottom: none;
}

.rank-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
}

#registrationChart,
#growthChart {
    height: 300px !important;
}

#statusChart {
    height: 250px !important;
}
</style>
@endpush
@endsection
