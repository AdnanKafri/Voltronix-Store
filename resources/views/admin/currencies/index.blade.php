@extends('admin.layouts.app')

@section('title', __('admin.currency.title'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 title-orbitron">
                <i class="bi bi-currency-exchange {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.currency.title') }}
            </h1>
            <p class="text-muted mb-0">{{ __('admin.currency.management') }}</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="updateAllRates()" class="btn btn-primary" id="updateRatesBtn">
                <i class="bi bi-arrow-clockwise {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.currency.update_rates_now') }}
            </button>
            <a href="{{ route('admin.currencies.create') }}" class="btn btn-voltronix">
                <i class="bi bi-plus-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.currency.create') }}
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-funnel {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.filter') }}
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.currencies.index') }}">
                <div class="filter-row">
                    <!-- Search -->
                    <div class="filter-col">
                        <label for="search" class="form-label-enhanced">{{ __('admin.search') }}</label>
                        <div class="search-input-group">
                            <input type="text" 
                                   class="form-control form-control-enhanced" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="{{ __('admin.currency.search_placeholder') }}">
                            <button type="submit" class="btn search-btn">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="filter-col">
                        <label for="status" class="form-label-enhanced">{{ __('admin.common.status') }}</label>
                        <select name="status" id="status" class="form-control form-control-enhanced">
                            <option value="">{{ __('admin.all') }}</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                {{ __('admin.common.active') }}
                            </option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                {{ __('admin.common.inactive') }}
                            </option>
                        </select>
                    </div>

                    <!-- Filter Actions -->
                    <div class="filter-col filter-col-auto">
                        <div class="filter-actions">
                            <button type="submit" class="filter-btn btn-filter">
                                <i class="bi bi-funnel"></i>
                                {{ __('admin.filter') }}
                            </button>
                            <a href="{{ route('admin.currencies.index') }}" class="filter-btn btn-clear">
                                <i class="bi bi-arrow-clockwise"></i>
                                {{ __('admin.common.clear') }}
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="delivery-stat-card">
                <div class="delivery-stat-content">
                    <div class="delivery-stat-icon bg-primary bg-opacity-10">
                        <i class="bi bi-currency-exchange text-primary fs-4"></i>
                    </div>
                    <div class="delivery-stat-text">
                        <h6>{{ __('admin.currency.total_currencies') }}</h6>
                        <h4>{{ $stats['total'] }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="delivery-stat-card">
                <div class="delivery-stat-content">
                    <div class="delivery-stat-icon bg-success bg-opacity-10">
                        <i class="bi bi-check-circle text-success fs-4"></i>
                    </div>
                    <div class="delivery-stat-text">
                        <h6>{{ __('admin.currency.active_currencies') }}</h6>
                        <h4>{{ $stats['active'] }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="delivery-stat-card">
                <div class="delivery-stat-content">
                    <div class="delivery-stat-icon bg-warning bg-opacity-10">
                        <i class="bi bi-star text-warning fs-4"></i>
                    </div>
                    <div class="delivery-stat-text">
                        <h6>{{ __('admin.currency.default_currency') }}</h6>
                        <h4>{{ $stats['default'] ?? 'USD' }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="delivery-stat-card">
                <div class="delivery-stat-content">
                    <div class="delivery-stat-icon bg-info bg-opacity-10">
                        <i class="bi bi-clock text-info fs-4"></i>
                    </div>
                    <div class="delivery-stat-text">
                        <h6>{{ __('admin.currency.last_updated') }}</h6>
                        <h4 id="lastUpdatedDisplay">
                            @php
                                $lastUpdated = $currencies->where('last_updated_at', '!=', null)->sortByDesc('last_updated_at')->first();
                            @endphp
                            @if($lastUpdated && $lastUpdated->last_updated_at)
                                {{ $lastUpdated->last_updated_at->diffForHumans() }}
                            @else
                                {{ __('admin.currency.never_updated') }}
                            @endif
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Currencies Table -->
    <div class="admin-table">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-list-ul {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.currency.list') }}
            </h5>
            <div class="table-actions">
                <button class="btn btn-sm btn-outline-primary" onclick="refreshRates()" title="{{ __('admin.currency.refresh_rates') }}">
                    <i class="bi bi-arrow-clockwise {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                    {{ __('admin.currency.refresh_rates') }}
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>{{ __('admin.currency.name') }}</th>
                        <th>{{ __('admin.currency.code') }}</th>
                        <th>{{ __('admin.currency.symbol') }}</th>
                        <th>{{ __('admin.currency.exchange_rate') }}</th>
                        <th>{{ __('admin.currency.status') }}</th>
                        <th>{{ __('admin.currency.last_updated') }}</th>
                        <th style="width: 150px;">{{ __('admin.currency.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                        @forelse($currencies as $currency)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($currency->is_default)
                                        <i class="bi bi-star text-warning {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}" title="{{ __('admin.currency.default') }}"></i>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $currency->getTranslation() }}</div>
                                        <small class="text-muted">{{ $currency->getTranslation('ar') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $currency->code }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $currency->symbol }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="{{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}">{{ $currency->formatted_rate }}</span>
                                    <button class="btn btn-sm btn-outline-primary" 
                                            onclick="updateRate({{ $currency->id }}, '{{ $currency->exchange_rate }}')"
                                            title="{{ __('admin.currency.update_rate') }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" 
                                           id="status_{{ $currency->id }}"
                                           {{ $currency->is_active ? 'checked' : '' }}
                                           onchange="toggleStatus({{ $currency->id }})"
                                           {{ $currency->is_default ? 'disabled' : '' }}>
                                    <label class="form-check-label" for="status_{{ $currency->id }}">
                                        <span class="badge bg-{{ $currency->is_active ? 'success' : 'secondary' }}">
                                            {{ $currency->is_active ? __('admin.currency.active') : __('admin.currency.inactive') }}
                                        </span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="text-muted small">
                                    <i class="bi bi-clock {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                    {{ local_datetime($currency->updated_at, 'M d, Y') }}
                                    <br>
                                    <small>{{ local_datetime($currency->updated_at, 'H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.currencies.show', $currency) }}" 
                                       class="action-btn btn-view" 
                                       title="{{ __('admin.currency.view') }}"
                                       data-bs-toggle="tooltip">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.currencies.edit', $currency) }}" 
                                       class="action-btn btn-edit" 
                                       title="{{ __('admin.currency.edit') }}"
                                       data-bs-toggle="tooltip">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @unless($currency->is_default)
                                    <button class="action-btn btn-warning-action" 
                                            onclick="setDefault({{ $currency->id }})"
                                            title="{{ __('admin.currency.set_default') }}"
                                            data-bs-toggle="tooltip">
                                        <i class="bi bi-star"></i>
                                    </button>
                                    <button class="action-btn btn-delete" 
                                            onclick="deleteCurrency({{ $currency->id }})"
                                            title="{{ __('admin.currency.delete') }}"
                                            data-bs-toggle="tooltip">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-currency-exchange display-4 d-block mb-2"></i>
                                    <p class="mb-0">{{ __('admin.currency.no_currencies') }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if(method_exists($currencies, 'hasPages') && $currencies->hasPages())
        <div class="d-flex justify-content-center">
            {{ $currencies->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
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
            // Revert checkbox
            document.getElementById(`status_${currencyId}`).checked = !document.getElementById(`status_${currencyId}`).checked;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: '{{ __("admin.error") }}',
            text: '{{ __("admin.something_went_wrong") }}'
        });
    });
}

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

// Refresh exchange rates (legacy function - kept for compatibility)
function refreshRates() {
    updateAllRates();
}

// Update all currency rates from API
function updateAllRates() {
    const updateBtn = document.getElementById('updateRatesBtn');
    const originalText = updateBtn.innerHTML;
    
    // Show loading state
    Swal.fire({
        title: '{{ __("admin.currency.update_in_progress") }}',
        html: '<div class="text-center"><i class="bi bi-arrow-clockwise fa-spin fs-1 text-primary"></i><p class="mt-3">{{ __("admin.currency.api_source") }}</p></div>',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            // Disable button
            updateBtn.disabled = true;
            updateBtn.innerHTML = '<i class="bi bi-arrow-clockwise fa-spin {{ app()->getLocale() == "ar" ? "ms-2" : "me-2" }}"></i>{{ __("admin.currency.updating") }}';
        }
    });
    
    // Call API endpoint
    fetch('{{ route("admin.currencies.update-all-rates") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Re-enable button
        updateBtn.disabled = false;
        updateBtn.innerHTML = originalText;
        
        if (data.success) {
            // Show success message
            Swal.fire({
                icon: 'success',
                title: '{{ __("admin.currency.update_success") }}',
                html: `
                    <p>${data.message}</p>
                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle {{ app()->getLocale() == "ar" ? "ms-2" : "me-2" }}"></i>
                        {{ __("admin.currency.currencies_updated", ["count" => ""]) }}${data.updated}
                    </div>
                    ${data.skipped && data.skipped.length > 0 ? `
                        <div class="alert alert-warning mt-2">
                            <small><strong>{{ __("admin.common.skipped") }}:</strong> ${data.skipped.join(', ')}</small>
                        </div>
                    ` : ''}
                    ${data.errors && data.errors.length > 0 ? `
                        <div class="alert alert-danger mt-2">
                            <small><strong>{{ __("admin.common.errors") }}:</strong> ${data.errors.join(', ')}</small>
                        </div>
                    ` : ''}
                `,
                confirmButtonText: '{{ __("admin.common.ok") }}',
                timer: 5000
            }).then(() => {
                // Reload page to show updated rates
                location.reload();
            });
        } else {
            // Show error message
            Swal.fire({
                icon: 'error',
                title: '{{ __("admin.currency.update_failed") }}',
                html: `
                    <p>${data.message}</p>
                    ${data.errors && data.errors.length > 0 ? `
                        <div class="alert alert-danger mt-3">
                            <small>${data.errors.join('<br>')}</small>
                        </div>
                    ` : ''}
                `,
                confirmButtonText: '{{ __("admin.common.ok") }}'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Re-enable button
        updateBtn.disabled = false;
        updateBtn.innerHTML = originalText;
        
        Swal.fire({
            icon: 'error',
            title: '{{ __("admin.error") }}',
            text: '{{ __("admin.something_went_wrong") }}',
            confirmButtonText: '{{ __("admin.common.ok") }}'
        });
    });
}
</script>
@endpush
@endsection


