@extends('admin.layouts.app')

@section('title', __('admin.delivery.logs_title'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">{{ __('admin.delivery.logs_title') }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">{{ __('admin.dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.orders.index') }}">{{ __('admin.orders.title') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.orders.deliveries.show', [$order, $delivery]) }}">{{ __('admin.delivery.title') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('admin.delivery.logs') }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.orders.deliveries.show', [$order, $delivery]) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                {{ __('admin.common.back') }}
            </a>
        </div>
    </div>

    <!-- Delivery Info Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-info-circle me-2"></i>
                {{ __('admin.delivery.info') }}
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>{{ __('admin.delivery.title') }}:</strong><br>
                    {{ $delivery->title }}
                </div>
                <div class="col-md-3">
                    <strong>{{ __('admin.delivery.type') }}:</strong><br>
                    <span class="badge bg-primary">{{ ucfirst($delivery->type) }}</span>
                </div>
                <div class="col-md-3">
                    <strong>{{ __('admin.delivery.status') }}:</strong><br>
                    {!! $delivery->status_badge !!}
                </div>
                <div class="col-md-3">
                    <strong>{{ __('admin.delivery.created') }}:</strong><br>
                    {{ $delivery->created_at->format('M d, Y H:i') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-clock-history me-2"></i>
                {{ __('admin.delivery.access_logs') }}
                <span class="badge bg-secondary ms-2">{{ $logs->total() }}</span>
            </h5>
        </div>
        <div class="card-body">
            @if($logs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>{{ __('admin.delivery.log_action') }}</th>
                                <th>{{ __('admin.delivery.log_status') }}</th>
                                <th>{{ __('admin.delivery.log_user') }}</th>
                                <th>{{ __('admin.delivery.log_ip') }}</th>
                                <th>{{ __('admin.delivery.log_details') }}</th>
                                <th>{{ __('admin.delivery.log_timestamp') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>
                                        @php
                                            $badgeClass = match($log->action) {
                                                'auto_created' => 'success',
                                                'download' => 'primary',
                                                'revoked' => 'danger',
                                                'access_request' => 'info',
                                                'reset_counts' => 'warning',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }}">
                                            @if($log->action === 'access_request')
                                                <i class="bi bi-person-raised-hand me-1"></i>
                                            @endif
                                            {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $log->status === 'success' ? 'success' : ($log->status === 'failed' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($log->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($log->user)
                                            <div>
                                                <strong>{{ $log->user->name }}</strong><br>
                                                <small class="text-muted">{{ $log->user->email }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted">{{ __('admin.delivery.system_user') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <code>{{ $log->ip_address ?: 'N/A' }}</code>
                                    </td>
                                    <td>
                                        @php
                                            $details = $log->details;
                                            if (is_string($details)) {
                                                $details = json_decode($details, true);
                                            }
                                        @endphp
                                        
                                        @if($log->action === 'access_request' && $details && is_array($details) && isset($details['reason']))
                                            <!-- Special display for customer access requests -->
                                            <div class="alert alert-info customer-request-alert mb-0 py-2">
                                                <i class="bi bi-chat-square-text me-2"></i>
                                                <strong>{{ __('admin.delivery.customer_request') }}:</strong>
                                                <div class="mt-1 alert-content">{{ $details['reason'] }}</div>
                                            </div>
                                        @elseif($details && is_array($details) && !empty($details))
                                            <button class="btn btn-sm btn-outline-info" type="button" data-bs-toggle="collapse" data-bs-target="#details-{{ $log->id }}">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <div class="collapse mt-2" id="details-{{ $log->id }}">
                                                <div class="card card-body bg-light">
                                                    <pre class="mb-0"><code>{{ json_encode($details, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                                </div>
                                            </div>
                                        @elseif($log->details && is_string($log->details))
                                            <span class="text-muted">{{ Str::limit($log->details, 50) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $log->created_at->format('M d, Y') }}</strong><br>
                                            <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $logs->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-clock-history display-1 text-muted"></i>
                    <h4 class="mt-3">{{ __('admin.delivery.no_logs') }}</h4>
                    <p class="text-muted">{{ __('admin.delivery.no_logs_desc') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.customer-request-alert {
    border-left: 4px solid #0dcaf0;
    background: linear-gradient(135deg, #e7f3ff 0%, #f0f9ff 100%);
    border-radius: 8px;
}
.customer-request-alert .alert-content {
    font-style: italic;
    color: #0c5460;
    line-height: 1.4;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh logs every 30 seconds
    setInterval(function() {
        if (document.visibilityState === 'visible') {
            window.location.reload();
        }
    }, 30000);
});
</script>
@endpush
