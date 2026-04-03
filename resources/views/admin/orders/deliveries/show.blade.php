@extends('admin.layouts.app')

@section('title', __('admin.deliveries.order_deliveries') . ' - ' . $order->order_number)

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
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-plus {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.deliveries.create_delivery') }}
                </button>
                <ul class="dropdown-menu">
                    @foreach($order->items as $item)
                        @if(!$item->delivery)
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.orders.deliveries.create', ['order' => $order, 'item_id' => $item->id]) }}">
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
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <h6 class="text-muted mb-1">Order Number</h6>
                <p class="mb-0 fw-bold">{{ $order->order_number }}</p>
            </div>
            <div class="col-md-3">
                <h6 class="text-muted mb-1">Customer</h6>
                <p class="mb-0">{{ $order->customer_name }}</p>
                <small class="text-muted">{{ $order->customer_email }}</small>
            </div>
            <div class="col-md-3">
                <h6 class="text-muted mb-1">Status</h6>
                <span class="badge {{ $order->status_badge_class }}">{{ $order->localized_status }}</span>
            </div>
            <div class="col-md-3">
                <h6 class="text-muted mb-1">Total</h6>
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
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">
                                {{ $delivery->title }}
                                {!! $delivery->automation_badge !!}
                            </h6>
                            <small class="text-muted">{{ ucfirst($delivery->type) }}</small>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.orders.deliveries.edit', [$order, $delivery]) }}">
                                    <i class="bi bi-pencil {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>Edit
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.orders.deliveries.logs', [$order, $delivery]) }}">
                                    <i class="bi bi-list-ul {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>View Logs
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><button class="dropdown-item" onclick="regenerateToken({{ $delivery->id }})">
                                    <i class="bi bi-arrow-clockwise {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>Regenerate Token
                                </button></li>
                                <li><button class="dropdown-item" onclick="extendExpiration({{ $delivery->id }})">
                                    <i class="bi bi-calendar-plus {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>Extend Expiration
                                </button></li>
                                @if($delivery->revoked)
                                    <li><button class="dropdown-item text-success" onclick="restoreDelivery({{ $delivery->id }})">
                                        <i class="bi bi-arrow-counterclockwise {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>Restore Access
                                    </button></li>
                                @else
                                    <li><button class="dropdown-item text-warning" onclick="revokeDelivery({{ $delivery->id }})">
                                        <i class="bi bi-x-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>Revoke Access
                                    </button></li>
                                @endif
                                <li><button class="dropdown-item" onclick="resetCounts({{ $delivery->id }})">
                                    <i class="bi bi-arrow-counterclockwise {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>Reset Counts
                                </button></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><button class="dropdown-item text-danger" onclick="deleteDelivery({{ $delivery->id }})">
                                    <i class="bi bi-trash {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>Delete
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
                        <div class="row g-2 mb-3">
                            @if($delivery->type === 'file')
                                <div class="col-6">
                                    <small class="text-muted d-block">File Size</small>
                                    <span>{{ $delivery->formatted_file_size }}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Downloads</small>
                                    <span>{{ $delivery->downloads_count }}
                                        @if($delivery->max_downloads)
                                            / {{ $delivery->max_downloads }}
                                        @endif
                                    </span>
                                </div>
                            @else
                                <div class="col-6">
                                    <small class="text-muted d-block">Type</small>
                                    <span>{{ $delivery->credentials_type }}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Views</small>
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
                                <small class="text-muted d-block">Expires</small>
                                <span class="{{ $delivery->expires_at->isPast() ? 'text-danger' : 'text-success' }}">
                                    {{ $delivery->expires_at->format('M d, Y H:i') }}
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
                                <small class="text-muted d-block mb-2">Recent Activity</small>
                                @foreach($delivery->logs->take(3) as $log)
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small>{!! $log->action_badge !!}</small>
                                        <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                    </div>
                                @endforeach
                                @if($delivery->logs->count() > 3)
                                    <a href="{{ route('admin.orders.deliveries.logs', [$order, $delivery]) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        View All Logs
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
            <h5 class="text-muted">No Deliveries Created</h5>
            <p class="text-muted mb-4">Create deliveries for this order's items to enable secure file downloads or credential access.</p>
            @if($order->items->count() > 0)
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-plus {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        Create First Delivery
                    </button>
                    <ul class="dropdown-menu">
                        @foreach($order->items as $item)
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.orders.deliveries.create', ['order' => $order, 'item_id' => $item->id]) }}">
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
        title: 'Regenerate Token?',
        text: 'This will invalidate the current access URL and generate a new one.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#007fff',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Regenerate',
        cancelButtonText: 'Cancel'
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
        title: 'Extend Expiration',
        input: 'number',
        inputLabel: 'Number of days to extend',
        inputValue: 7,
        inputAttributes: {
            min: 1,
            max: 365,
            step: 1
        },
        showCancelButton: true,
        confirmButtonColor: '#007fff',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Extend',
        cancelButtonText: 'Cancel'
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
        title: 'Revoke Access?',
        input: 'textarea',
        inputLabel: 'Reason for revocation (optional)',
        inputPlaceholder: 'Enter reason...',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Revoke',
        cancelButtonText: 'Cancel'
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
        title: 'Restore Access?',
        text: 'This will restore access to the delivery.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Restore',
        cancelButtonText: 'Cancel'
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
        title: 'Reset Counts?',
        text: 'This will reset download/view counts and access history.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#007fff',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Reset',
        cancelButtonText: 'Cancel'
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
        title: 'Delete Delivery?',
        text: 'This action cannot be undone. All associated files and logs will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel'
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
