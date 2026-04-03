@extends('admin.layouts.app')

@section('title', __('admin.currency.view'))

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800 font-orbitron">
                        <i class="fas fa-eye me-2 text-primary"></i>
                        {{ $currency->getTranslation() }}
                        @if($currency->is_default)
                            <span class="badge bg-warning ms-2">{{ __('admin.currency.default') }}</span>
                        @endif
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">{{ __('admin.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.currencies.index') }}">{{ __('admin.currency.title') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $currency->code }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-group">
                    <a href="{{ route('admin.currencies.edit', $currency) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>{{ __('admin.currency.edit') }}
                    </a>
                    <a href="{{ route('admin.currencies.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>{{ __('admin.currency.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-xl-8">
            <!-- Currency Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ __('admin.currency.currency_info') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="text-muted">{{ __('admin.currency.name') }} ({{ __('admin.english') }})</label>
                                <div class="fw-bold">{{ $currency->getTranslation('en') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="text-muted">{{ __('admin.currency.name') }} ({{ __('admin.arabic') }})</label>
                                <div class="fw-bold">{{ $currency->getTranslation('ar') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-item mb-3">
                                <label class="text-muted">{{ __('admin.currency.code') }}</label>
                                <div class="fw-bold">
                                    <span class="badge bg-secondary fs-6">{{ $currency->code }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-item mb-3">
                                <label class="text-muted">{{ __('admin.currency.symbol') }}</label>
                                <div class="fw-bold fs-4">{{ $currency->symbol }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-item mb-3">
                                <label class="text-muted">{{ __('admin.currency.status') }}</label>
                                <div>
                                    <span class="badge bg-{{ $currency->is_active ? 'success' : 'secondary' }} fs-6">
                                        {{ $currency->is_active ? __('admin.currency.active') : __('admin.currency.inactive') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="text-muted">{{ __('admin.currency.exchange_rate') }}</label>
                                <div class="fw-bold fs-5">
                                    1 USD = {{ $currency->formatted_rate }} {{ $currency->code }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="text-muted">{{ __('admin.currency.last_updated') }}</label>
                                <div class="fw-bold">{{ $currency->updated_at->format('M d, Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exchange Rate History (Placeholder) -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>
                        {{ __('admin.currency.exchange_rates') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-3x text-gray-300 mb-3"></i>
                        <p class="text-muted">{{ __('admin.currency.api_integration') }}</p>
                        <button class="btn btn-outline-primary" onclick="refreshRates()">
                            <i class="fas fa-sync-alt me-2"></i>{{ __('admin.currency.refresh_rates') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-xl-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>
                        {{ __('admin.currency.quick_actions') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.currencies.edit', $currency) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>{{ __('admin.currency.edit') }}
                        </a>
                        
                        @unless($currency->is_default)
                        <button class="btn btn-warning" onclick="setDefault({{ $currency->id }})">
                            <i class="fas fa-star me-2"></i>{{ __('admin.currency.set_default') }}
                        </button>
                        @endunless

                        <button class="btn btn-info" onclick="updateRate({{ $currency->id }}, '{{ $currency->exchange_rate }}')">
                            <i class="fas fa-chart-line me-2"></i>{{ __('admin.currency.update_rate') }}
                        </button>

                        @if($currency->is_active && !$currency->is_default)
                        <button class="btn btn-secondary" onclick="toggleStatus({{ $currency->id }})">
                            <i class="fas fa-pause me-2"></i>{{ __('admin.currency.deactivate') }}
                        </button>
                        @elseif(!$currency->is_active)
                        <button class="btn btn-success" onclick="toggleStatus({{ $currency->id }})">
                            <i class="fas fa-play me-2"></i>{{ __('admin.currency.activate') }}
                        </button>
                        @endif

                        @unless($currency->is_default)
                        <button class="btn btn-danger" onclick="deleteCurrency({{ $currency->id }})">
                            <i class="fas fa-trash me-2"></i>{{ __('admin.currency.delete') }}
                        </button>
                        @endunless
                    </div>
                </div>
            </div>

            <!-- Currency Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>
                        {{ __('admin.statistics') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="stat-mini">
                                <div class="stat-mini-number">{{ $currency->created_at->diffForHumans() }}</div>
                                <div class="stat-mini-label">{{ __('admin.currency.created_at') }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-mini">
                                <div class="stat-mini-number">{{ $currency->updated_at->diffForHumans() }}</div>
                                <div class="stat-mini-label">{{ __('admin.currency.updated_at') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Currency Details -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info me-2"></i>
                        {{ __('admin.details') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="info-item mb-2">
                        <small class="text-muted">{{ __('admin.currency.created_at') }}</small>
                        <div>{{ $currency->created_at->format('F d, Y \a\t H:i') }}</div>
                    </div>
                    <div class="info-item mb-2">
                        <small class="text-muted">{{ __('admin.currency.updated_at') }}</small>
                        <div>{{ $currency->updated_at->format('F d, Y \a\t H:i') }}</div>
                    </div>
                    <div class="info-item">
                        <small class="text-muted">ID</small>
                        <div><code>{{ $currency->id }}</code></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Set currency as default
function setDefault(currencyId) {
    Swal.fire({
        title: '{{ __("admin.currency.set_default") }}',
        text: '{{ __("admin.are_you_sure") }}',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("admin.yes") }}',
        cancelButtonText: '{{ __("admin.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/currencies/${currencyId}/set-default`, {
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

// Update exchange rate
function updateRate(currencyId, currentRate) {
    Swal.fire({
        title: '{{ __("admin.currency.update_rate") }}',
        input: 'number',
        inputValue: currentRate,
        inputAttributes: {
            step: '0.00000001',
            min: '0.00000001'
        },
        showCancelButton: true,
        confirmButtonText: '{{ __("admin.update") }}',
        cancelButtonText: '{{ __("admin.cancel") }}',
        inputValidator: (value) => {
            if (!value || value <= 0) {
                return '{{ __("admin.currency.validation.rate_min") }}'
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/currencies/${currencyId}/update-rate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    exchange_rate: result.value
                })
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

// Toggle currency status
function toggleStatus(currencyId) {
    fetch(`/admin/currencies/${currencyId}/toggle-status`, {
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

// Delete currency
function deleteCurrency(currencyId) {
    Swal.fire({
        title: '{{ __("admin.currency.confirm_delete") }}',
        text: '{{ __("admin.cannot_be_undone") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("admin.currency.yes_delete") }}',
        cancelButtonText: '{{ __("admin.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/currencies/${currencyId}`, {
                method: 'DELETE',
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
                    setTimeout(() => window.location.href = '{{ route("admin.currencies.index") }}', 2000);
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

// Refresh exchange rates (placeholder)
function refreshRates() {
    Swal.fire({
        title: '{{ __("admin.currency.refresh_rates") }}',
        text: '{{ __("admin.currency.api_integration") }}',
        icon: 'info',
        confirmButtonText: '{{ __("admin.ok") }}'
    });
}
</script>
@endpush
@endsection
