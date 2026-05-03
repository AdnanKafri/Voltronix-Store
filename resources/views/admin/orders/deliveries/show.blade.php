@extends('admin.layouts.app')

@section('title', __('admin.deliveries.order_deliveries') . ' - ' . $order->order_number)

@push('styles')
<style>
.delivery-summary-card,
.delivery-card {
    border: 1px solid rgba(0, 127, 255, 0.08);
    box-shadow: 0 12px 28px rgba(15, 34, 56, 0.08);
}

.delivery-primary-btn,
.delivery-action-toggle {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.delivery-action-toggle {
    min-width: 110px;
    justify-content: center;
    border-radius: 12px;
}

.delivery-action-menu {
    min-width: 240px;
    padding: 0.5rem;
    border: 1px solid rgba(15, 34, 56, 0.08);
    border-radius: 16px;
    box-shadow: 0 18px 40px rgba(15, 34, 56, 0.14);
}

.delivery-action-item {
    display: flex;
    align-items: center;
    gap: 0.7rem;
    padding: 0.7rem 0.85rem;
    border-radius: 12px;
    font-weight: 600;
}

.delivery-action-item i {
    width: 1rem;
    text-align: center;
}

.delivery-meta-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.85rem;
}

.delivery-meta-card {
    padding: 0.85rem 0.95rem;
    border-radius: 14px;
    background: #f8fbff;
    border: 1px solid rgba(0, 127, 255, 0.08);
}

.delivery-meta-card small {
    display: block;
    margin-bottom: 0.25rem;
}

.delivery-activity-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 0;
}

@media (max-width: 768px) {
    .delivery-meta-grid {
        grid-template-columns: 1fr;
    }

    .delivery-action-toggle,
    .delivery-primary-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">{{ __('admin.deliveries.order_deliveries') }}</h1>
        <p class="text-muted mb-0">{{ __('admin.deliveries.manage_deliveries_for_order', ['order' => $order->order_number]) }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
            {{ __('admin.common.back_to_order') }}
        </a>
        @if($order->items->count() > 0)
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle delivery-primary-btn" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-plus {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.deliveries.create_delivery') }}
                </button>
                <ul class="dropdown-menu delivery-action-menu">
                    @foreach($order->items as $item)
                        @if(!$item->delivery)
                            <li>
                                <a class="dropdown-item delivery-action-item" href="{{ route('admin.orders.deliveries.create', ['order' => $order, 'item_id' => $item->id]) }}">
                                    <i class="bi bi-box-seam"></i>
                                    {{ $item->getTranslation() }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>

<!-- Order Summary -->
<div class="card mb-4 delivery-summary-card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <h6 class="text-muted mb-1">{{ __('admin.orders.order_number') }}</h6>
                <p class="mb-0 fw-bold">{{ $order->order_number }}</p>
            </div>
            <div class="col-md-3">
                <h6 class="text-muted mb-1">{{ __('admin.orders.customer') }}</h6>
                <p class="mb-0">{{ $order->customer_name }}</p>
                <small class="text-muted">{{ $order->customer_email }}</small>
            </div>
            <div class="col-md-3">
                <h6 class="text-muted mb-1">{{ __('admin.orders.status') }}</h6>
                <span class="badge {{ $order->status_badge_class }}">{{ $order->localized_status }}</span>
            </div>
            <div class="col-md-3">
                <h6 class="text-muted mb-1">{{ __('admin.orders.total_amount') }}</h6>
                <p class="mb-0 fw-bold">{{ $order->formatted_total }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Deliveries List -->
@if($order->deliveries->count() > 0)
    <div class="row">
        @foreach($order->deliveries as $delivery)
            <div class="col-lg-6 mb-4">
                <div class="card h-100 delivery-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">
                                {{ $delivery->title }}
                                {!! $delivery->automation_badge !!}
                            </h6>
                            <small class="text-muted">{{ ucfirst($delivery->type) }}</small>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle delivery-action-toggle" type="button" data-bs-toggle="dropdown">
                                {{ __('admin.common.actions') }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end delivery-action-menu">
                                <li><a class="dropdown-item delivery-action-item" href="{{ route('admin.orders.deliveries.edit', [$order, $delivery]) }}">
                                    <i class="bi bi-pencil"></i>{{ __('admin.common.edit') }}
                                </a></li>
                                <li><a class="dropdown-item delivery-action-item" href="{{ route('admin.orders.deliveries.logs', [$order, $delivery]) }}">
                                    <i class="bi bi-list-ul"></i>{{ __('admin.delivery.view_logs') }}
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><button class="dropdown-item delivery-action-item" onclick="regenerateToken({{ $delivery->id }})">
                                    <i class="bi bi-arrow-clockwise"></i>{{ __('admin.delivery.regenerate_tokens') }}
                                </button></li>
                                <li><button class="dropdown-item delivery-action-item" onclick="extendExpiration({{ $delivery->id }})">
                                    <i class="bi bi-calendar-plus"></i>{{ __('admin.delivery.expires') }}
                                </button></li>
                                @if($delivery->revoked)
                                    <li><button class="dropdown-item delivery-action-item text-success" onclick="restoreDelivery({{ $delivery->id }})">
                                        <i class="bi bi-arrow-counterclockwise"></i>{{ __('admin.delivery.restore_access') }}
                                    </button></li>
                                @else
                                    <li><button class="dropdown-item delivery-action-item text-warning" onclick="revokeDelivery({{ $delivery->id }})">
                                        <i class="bi bi-x-circle"></i>{{ __('admin.delivery.revoke_access') }}
                                    </button></li>
                                @endif
                                <li><button class="dropdown-item delivery-action-item" onclick="resetCounts({{ $delivery->id }})">
                                    <i class="bi bi-arrow-counterclockwise"></i>{{ __('admin.delivery.reset_counts') }}
                                </button></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><button class="dropdown-item delivery-action-item text-danger" onclick="deleteDelivery({{ $delivery->id }})">
                                    <i class="bi bi-trash"></i>{{ __('admin.common.delete') }}
                                </button></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($delivery->description)
                            <p class="text-muted mb-3">{{ $delivery->description }}</p>
                        @endif
                        
                        <!-- Status -->
                        <div class="mb-3">
                            {!! $delivery->status_badge !!}
                        </div>
                        
                        <!-- Delivery Info -->
                        <div class="delivery-meta-grid mb-3">
                            @if($delivery->type === 'file')
                                <div class="delivery-meta-card">
                                    <small class="text-muted d-block">{{ __('admin.delivery.file_size') }}</small>
                                    <span>{{ $delivery->formatted_file_size }}</span>
                                </div>
                                <div class="delivery-meta-card">
                                    <small class="text-muted d-block">{{ __('admin.orders.downloads') }}</small>
                                    <span>{{ $delivery->downloads_count }}
                                        @if($delivery->max_downloads)
                                            / {{ $delivery->max_downloads }}
                                        @endif
                                    </span>
                                </div>
                            @else
                                <div class="delivery-meta-card">
                                    <small class="text-muted d-block">{{ __('admin.orders.type') }}</small>
                                    <span>{{ $delivery->credentials_type }}</span>
                                </div>
                                <div class="delivery-meta-card">
                                    <small class="text-muted d-block">{{ __('admin.orders.views') }}</small>
                                    <span>{{ $delivery->views_count }}
                                        @if($delivery->max_views)
                                            / {{ $delivery->max_views }}
                                        @endif
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        @if($delivery->expires_at)
                            <div class="mb-3">
                                <small class="text-muted d-block">{{ __('admin.orders.expires') }}</small>
                                <span class="{{ $delivery->expires_at->isPast() ? 'text-danger' : 'text-success' }}">
                                    {{ local_datetime($delivery->expires_at, 'M d, Y H:i') }}
                                </span>
                            </div>
                        @endif
                        
                        <!-- Access URLs -->
                        <div class="mt-3">
                            @if($delivery->type === 'file')
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control form-control-sm" 
                                           value="{{ $delivery->getDownloadUrl() }}" readonly>
                                    <button class="btn btn-outline-secondary btn-sm" 
                                            onclick="copyToClipboard('{{ $delivery->getDownloadUrl() }}', this)">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            @else
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control form-control-sm" 
                                           value="{{ $delivery->getCredentialsUrl() }}" readonly>
                                    <button class="btn btn-outline-secondary btn-sm" 
                                            onclick="copyToClipboard('{{ $delivery->getCredentialsUrl() }}', this)">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Recent Activity -->
                        @if($delivery->logs->count() > 0)
                            <div class="mt-3">
                                <small class="text-muted d-block mb-2">{{ __('admin.delivery.recent_activity') }}</small>
                                @foreach($delivery->logs->take(3) as $log)
                                    <div class="delivery-activity-row">
                                        <small>{!! $log->action_badge !!}</small>
                                        <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                    </div>
                                @endforeach
                                @if($delivery->logs->count() > 3)
                                    <a href="{{ route('admin.orders.deliveries.logs', [$order, $delivery]) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        {{ __('admin.delivery.view_logs') }}
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox display-4 text-muted mb-3"></i>
            <h5 class="text-muted">{{ __('admin.orders.no_deliveries') }}</h5>
            <p class="text-muted mb-4">{{ __('admin.orders.no_deliveries_message') }}</p>
            @if($order->items->count() > 0)
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle delivery-primary-btn" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-plus {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.deliveries.create_delivery') }}
                    </button>
                    <ul class="dropdown-menu delivery-action-menu">
                        @foreach($order->items as $item)
                            <li>
                                <a class="dropdown-item delivery-action-item" href="{{ route('admin.orders.deliveries.create', ['order' => $order, 'item_id' => $item->id]) }}">
                                    <i class="bi bi-box-seam"></i>
                                    {{ $item->getTranslation() }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
function copyToClipboard(text, button) {
    navigator.clipboard.writeText(text).then(() => {
        const originalIcon = button.innerHTML;
        button.innerHTML = '<i class="bi bi-check text-success"></i>';
        
        setTimeout(() => {
            button.innerHTML = originalIcon;
        }, 2000);
    });
}

function regenerateToken(deliveryId) {
    Swal.fire({
        title: '{{ __("admin.delivery.regenerate_tokens") }}?',
        text: 'This will invalidate the current access URL and generate a new one.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#007fff',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("admin.confirm") }}',
        cancelButtonText: '{{ __("admin.common.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route('admin.orders.deliveries.regenerate-token', [$order, ':id']) }}`.replace(':id', deliveryId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '{{ __("admin.common.success") }}!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#007fff'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: '{{ __("admin.common.error") }}!',
                        text: data.message || '{{ __("admin.delivery.token_regenerated_error") }}',
                        icon: 'error',
                        confirmButtonColor: '#007fff'
                    });
                }
            })
            .catch(error => {
                console.error('Regenerate token error:', error);
                Swal.fire({
                    title: '{{ __("admin.common.error") }}!',
                    text: '{{ __("admin.delivery.token_regenerated_error") }}',
                    icon: 'error',
                    confirmButtonColor: '#007fff'
                });
            });
        }
    });
}

function extendExpiration(deliveryId) {
    Swal.fire({
        title: '{{ __("admin.delivery.expires") }}',
        input: 'number',
        inputLabel: '{{ __("admin.delivery.expiration_extended_success") }}',
        inputValue: 7,
        inputAttributes: {
            min: 1,
            max: 365,
            step: 1
        },
        showCancelButton: true,
        confirmButtonColor: '#007fff',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("admin.confirm") }}',
        cancelButtonText: '{{ __("admin.common.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            fetch(`{{ route('admin.orders.deliveries.extend', [$order, ':id']) }}`.replace(':id', deliveryId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ days: parseInt(result.value) })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '{{ __("admin.common.success") }}!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#007fff'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: '{{ __("admin.common.error") }}!',
                        text: data.message || '{{ __("admin.delivery.expiration_extended_error") }}',
                        icon: 'error',
                        confirmButtonColor: '#007fff'
                    });
                }
            })
            .catch(error => {
                console.error('Extend expiration error:', error);
                Swal.fire({
                    title: '{{ __("admin.common.error") }}!',
                    text: '{{ __("admin.delivery.expiration_extended_error") }}',
                    icon: 'error',
                    confirmButtonColor: '#007fff'
                });
            });
        }
    });
}

function revokeDelivery(deliveryId) {
    Swal.fire({
        title: '{{ __("admin.delivery.revoked_success") }}?',
        input: 'textarea',
        inputLabel: '{{ __("admin.notes.note_content") }}',
        inputPlaceholder: '{{ __("admin.notes.add_note") }}',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("admin.confirm") }}',
        cancelButtonText: '{{ __("admin.common.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route('admin.orders.deliveries.revoke', [$order, ':id']) }}`.replace(':id', deliveryId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ reason: result.value })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '{{ __("admin.common.success") }}!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#007fff'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: '{{ __("admin.common.error") }}!',
                        text: data.message || '{{ __("admin.delivery.revoked_error") }}',
                        icon: 'error',
                        confirmButtonColor: '#007fff'
                    });
                }
            })
            .catch(error => {
                console.error('Revoke delivery error:', error);
                Swal.fire({
                    title: '{{ __("admin.common.error") }}!',
                    text: '{{ __("admin.delivery.revoked_error") }}',
                    icon: 'error',
                    confirmButtonColor: '#007fff'
                });
            });
        }
    });
}

function restoreDelivery(deliveryId) {
    Swal.fire({
        title: '{{ __("admin.delivery.restored_success") }}?',
        text: '{{ __("admin.delivery.manage_deliveries") }}',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("admin.confirm") }}',
        cancelButtonText: '{{ __("admin.common.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route('admin.orders.deliveries.restore', [$order, ':id']) }}`.replace(':id', deliveryId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '{{ __("admin.common.success") }}!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#007fff'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: '{{ __("admin.common.error") }}!',
                        text: data.message || '{{ __("admin.delivery.restored_error") }}',
                        icon: 'error',
                        confirmButtonColor: '#007fff'
                    });
                }
            })
            .catch(error => {
                console.error('Restore delivery error:', error);
                Swal.fire({
                    title: '{{ __("admin.common.error") }}!',
                    text: '{{ __("admin.delivery.restored_error") }}',
                    icon: 'error',
                    confirmButtonColor: '#007fff'
                });
            });
        }
    });
}

function resetCounts(deliveryId) {
    Swal.fire({
        title: '{{ __("admin.delivery.counts_reset_success") }}?',
        text: '{{ __("admin.delivery.downloads") }} / {{ __("admin.delivery.views") }}',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#007fff',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("admin.confirm") }}',
        cancelButtonText: '{{ __("admin.common.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route('admin.orders.deliveries.reset-counts', [$order, ':id']) }}`.replace(':id', deliveryId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '{{ __("admin.common.success") }}!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#007fff'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: '{{ __("admin.common.error") }}!',
                        text: data.message || '{{ __("admin.delivery.counts_reset_error") }}',
                        icon: 'error',
                        confirmButtonColor: '#007fff'
                    });
                }
            })
            .catch(error => {
                console.error('Reset counts error:', error);
                Swal.fire({
                    title: '{{ __("admin.common.error") }}!',
                    text: '{{ __("admin.delivery.counts_reset_error") }}',
                    icon: 'error',
                    confirmButtonColor: '#007fff'
                });
            });
        }
    });
}

function deleteDelivery(deliveryId) {
    Swal.fire({
        title: '{{ __("admin.delivery.delete") }}?',
        text: '{{ __("admin.cannot_be_undone") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("admin.yes_delete") }}',
        cancelButtonText: '{{ __("admin.common.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route('admin.orders.deliveries.destroy', [$order, ':id']) }}`.replace(':id', deliveryId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '{{ __("admin.common.success") }}!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#007fff'
                    }).then(() => {
                        window.location.href = '{{ route("admin.orders.show", $order) }}';
                    });
                } else {
                    Swal.fire({
                        title: '{{ __("admin.common.error") }}!',
                        text: data.message || '{{ __("admin.delivery.delete_error") }}',
                        icon: 'error',
                        confirmButtonColor: '#007fff'
                    });
                }
            })
            .catch(error => {
                console.error('Delete delivery error:', error);
                Swal.fire({
                    title: '{{ __("admin.common.error") }}!',
                    text: '{{ __("admin.delivery.delete_error") }}',
                    icon: 'error',
                    confirmButtonColor: '#007fff'
                });
            });
        }
    });
}
</script>
@endpush


