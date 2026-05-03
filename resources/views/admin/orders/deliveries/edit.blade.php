@extends('admin.layouts.app')

@section('title', __('admin.delivery.edit_delivery'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">{{ __('admin.delivery.edit_delivery') }}</h1>
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
                    <li class="breadcrumb-item active">{{ __('admin.common.edit') }}</li>
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

    <!-- Edit Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pencil me-2"></i>
                        {{ __('admin.delivery.edit_delivery') }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.deliveries.update', [$order, $delivery]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="title" class="form-label">{{ __('admin.delivery.title') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $delivery->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="type" class="form-label">{{ __('admin.delivery.type') }}</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" onchange="toggleDeliveryTypeFields()">
                                    <option value="file" {{ $delivery->type === 'file' ? 'selected' : '' }}>{{ __('admin.delivery.type_file') }}</option>
                                    <option value="credentials" {{ $delivery->type === 'credentials' ? 'selected' : '' }}>{{ __('admin.delivery.type_credentials') }}</option>
                                    <option value="license" {{ $delivery->type === 'license' ? 'selected' : '' }}>{{ __('admin.delivery.type_license') }}</option>
                                    <option value="service" {{ $delivery->type === 'service' ? 'selected' : '' }}>{{ __('admin.delivery.type_service') }}</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">{{ __('admin.delivery.description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $delivery->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File Upload Section -->
                        <div id="file-section" class="mb-4" style="display: {{ $delivery->type === 'file' ? 'block' : 'none' }};">
                            <h6 class="fw-bold">{{ __('admin.delivery.file_settings') }}</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="file" class="form-label">{{ __('admin.delivery.upload_new_file') }}</label>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file">
                                    <div class="form-text">{{ __('admin.delivery.leave_empty_keep_current') }}</div>
                                    @if($delivery->file_name)
                                        <div class="mt-2">
                                            <small class="text-muted">{{ __('admin.delivery.current_file') }}: {{ $delivery->file_name }}</small>
                                        </div>
                                    @endif
                                    @error('file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Credentials Section -->
                        <div id="credentials-section" class="mb-4" style="display: {{ $delivery->type === 'credentials' ? 'block' : 'none' }};">
                            <h6 class="fw-bold">{{ __('admin.delivery.credentials_settings') }}</h6>
                            @php
                                $credentials = $delivery->getCredentials();
                                $credentials = is_array($credentials) ? $credentials : [];
                            @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="username" class="form-label">{{ __('admin.delivery.username') }}</label>
                                    <input type="text" class="form-control" id="username" name="credentials[username]" 
                                           value="{{ old('credentials.username', $credentials['username'] ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="form-label">{{ __('admin.delivery.password') }}</label>
                                    <input type="text" class="form-control" id="password" name="credentials[password]" 
                                           value="{{ old('credentials.password', $credentials['password'] ?? '') }}">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label for="url" class="form-label">{{ __('admin.delivery.url') }}</label>
                                    <input type="url" class="form-control" id="url" name="credentials[url]" 
                                           value="{{ old('credentials.url', $credentials['url'] ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="notes" class="form-label">{{ __('admin.delivery.notes') }}</label>
                                    <input type="text" class="form-control" id="notes" name="credentials[notes]" 
                                           value="{{ old('credentials.notes', $credentials['notes'] ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <!-- License Section -->
                        <div id="license-section" class="mb-4" style="display: {{ $delivery->type === 'license' ? 'block' : 'none' }};">
                            <h6 class="fw-bold">{{ __('admin.delivery.type_license') }}</h6>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="license_key" class="form-label">{{ __('admin.deliveries.license_key') }}</label>
                                    <input
                                        type="text"
                                        class="form-control @error('credentials.license_key') is-invalid @enderror"
                                        id="license_key"
                                        name="credentials[license_key]"
                                        value="{{ old('credentials.license_key', $delivery->license_key) }}"
                                        placeholder="XXXX-XXXX-XXXX-XXXX">
                                    @error('credentials.license_key')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Access Control -->
                        <h6 class="fw-bold mb-3">{{ __('admin.delivery.access_control') }}</h6>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="expires_at" class="form-label">{{ __('admin.delivery.expires_at') }}</label>
                                <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" 
                                       id="expires_at" name="expires_at" 
                                       value="{{ old('expires_at', $delivery->expires_at ? local_datetime($delivery->expires_at, 'Y-m-d\TH:i') : '') }}">
                                @error('expires_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="max_downloads" class="form-label">{{ __('admin.delivery.max_downloads') }}</label>
                                <input type="number" class="form-control @error('max_downloads') is-invalid @enderror" 
                                       id="max_downloads" name="max_downloads" min="0" 
                                       value="{{ old('max_downloads', $delivery->max_downloads) }}">
                                <div class="form-text">{{ __('admin.delivery.leave_empty_unlimited') }}</div>
                                @error('max_downloads')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="max_views" class="form-label">{{ __('admin.delivery.max_views') }}</label>
                                <input type="number" class="form-control @error('max_views') is-invalid @enderror" 
                                       id="max_views" name="max_views" min="0" 
                                       value="{{ old('max_views', $delivery->max_views) }}">
                                <div class="form-text">{{ __('admin.delivery.leave_empty_unlimited') }}</div>
                                @error('max_views')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Security Options -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="require_otp" name="require_otp" value="1" 
                                           {{ old('require_otp', $delivery->require_otp) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="require_otp">
                                        {{ __('admin.delivery.require_otp') }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="allowed_ips" class="form-label">{{ __('admin.delivery.allowed_ips') }}</label>
                                <input type="text" class="form-control @error('allowed_ips') is-invalid @enderror" 
                                       id="allowed_ips" name="allowed_ips" 
                                       value="{{ old('allowed_ips', is_array($delivery->allowed_ips) ? implode(', ', $delivery->allowed_ips) : $delivery->allowed_ips) }}"
                                       placeholder="192.168.1.1, 10.0.0.1">
                                <div class="form-text">{{ __('admin.delivery.allowed_ips_help') }}</div>
                                @error('allowed_ips')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Admin Notes -->
                        <div class="mb-4">
                            <label for="admin_notes" class="form-label">{{ __('admin.delivery.admin_notes') }}</label>
                            <textarea class="form-control @error('admin_notes') is-invalid @enderror" 
                                      id="admin_notes" name="admin_notes" rows="3">{{ old('admin_notes', $delivery->admin_notes) }}</textarea>
                            @error('admin_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>
                                {{ __('admin.common.save_changes') }}
                            </button>
                            <a href="{{ route('admin.orders.deliveries.show', [$order, $delivery]) }}" class="btn btn-secondary">
                                {{ __('admin.common.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">{{ __('admin.delivery.delivery_info') }}</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>{{ __('admin.delivery.order') }}:</strong><br>
                        <a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a>
                    </div>
                    <div class="mb-3">
                        <strong>{{ __('admin.delivery.customer') }}:</strong><br>
                        {{ $order->customer_name }}<br>
                        <small class="text-muted">{{ $order->customer_email }}</small>
                    </div>
                    <div class="mb-3">
                        <strong>{{ __('admin.delivery.created') }}:</strong><br>
                        {{ local_datetime($order->created_at, 'M d, Y H:i') }}
                    </div>
                    <div class="mb-3">
                        <strong>{{ __('admin.delivery.status') }}:</strong><br>
                        {!! $delivery->status_badge !!}
                    </div>
                    @if($delivery->created_automatically)
                        <div class="mb-3">
                            <strong>{{ __('admin.delivery.automation') }}:</strong><br>
                            {!! $delivery->automation_badge !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleDeliveryTypeFields() {
    const type = document.getElementById('type').value;
    const fileSection = document.getElementById('file-section');
    const credentialsSection = document.getElementById('credentials-section');
    const licenseSection = document.getElementById('license-section');
    
    // Show/hide sections based on type
    fileSection.style.display = type === 'file' ? 'block' : 'none';
    credentialsSection.style.display = type === 'credentials' ? 'block' : 'none';
    licenseSection.style.display = type === 'license' ? 'block' : 'none';
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleDeliveryTypeFields();
});
</script>
@endpush


