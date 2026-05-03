@extends('layouts.app')

@section('title', __('app.delivery.request_access'))

@section('content')
<div class="container py-5" style="margin-top: 80px; position: relative; z-index: 1;">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg"  style="position: relative; z-index: 2;">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        <h5 class="mb-0">{{ __('app.delivery.request_access') }}</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Delivery Info -->
                    <div class="mb-4">
                        <h6 class="text-muted">{{ __('app.delivery.delivery_title') }}</h6>
                        <p class="fw-bold">{{ $delivery->title }}</p>
                        
                        @if($delivery->description)
                            <h6 class="text-muted mt-3">{{ __('app.delivery.description') }}</h6>
                            <p>{{ $delivery->description }}</p>
                        @endif
                    </div>
                    
                    <!-- Status Info -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading">{{ __('app.delivery.current_status') }}</h6>
                        <ul class="mb-0">
                            @if($delivery->revoked)
                                <li>{{ __('app.delivery.status_revoked') }}</li>
                            @endif
                            
                            @if($delivery->expires_at && $delivery->expires_at->isPast())
                                <li>{{ __('app.delivery.status_expired', ['date' => local_datetime($delivery->expires_at, 'M d, Y H:i')]) }}</li>
                            @endif
                            
                            @if($delivery->max_downloads && $delivery->downloads_count >= $delivery->max_downloads)
                                <li>{{ __('app.delivery.status_download_limit') }}</li>
                            @endif
                            
                            @if($delivery->max_views && $delivery->views_count >= $delivery->max_views)
                                <li>{{ __('app.delivery.status_view_limit') }}</li>
                            @endif
                        </ul>
                    </div>
                    
                    <!-- Request Form -->
                    <form id="accessRequestForm">
                        @csrf
                        <div class="mb-3">
                            <label for="reason" class="form-label">{{ __('app.delivery.request_reason') }}</label>
                            <textarea class="form-control" 
                                      id="reason" 
                                      name="reason" 
                                      rows="4" 
                                      placeholder="{{ __('app.delivery.request_reason_placeholder') }}"
                                      required></textarea>
                            <div class="form-text">{{ __('app.delivery.request_reason_help') }}</div>
                        </div>
                        
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-warning flex-fill">
                                <i class="bi bi-send {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('app.delivery.submit_request') }}
                            </button>
                            
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                                {{ __('app.delivery.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('accessRequestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = form.querySelector('button[type="submit"]');
    const reason = form.querySelector('#reason').value;
    
    if (!reason.trim()) {
        Swal.fire({
            title: '{{ __("app.delivery.error") }}',
            text: '{{ __("app.delivery.reason_required") }}',
            icon: 'error',
            confirmButtonColor: '#007fff'
        });
        return;
    }
    
    // Show loading
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm {{ app()->getLocale() == "ar" ? "ms-2" : "me-2" }}"></i> {{ __("app.delivery.submitting") }}';
    submitBtn.disabled = true;
    
    // Submit request
    fetch('{{ route("delivery.request.submit", $delivery->token) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ reason: reason })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: '{{ __("app.delivery.request_submitted") }}',
                text: data.message,
                icon: 'success',
                confirmButtonColor: '#007fff'
            }).then(() => {
                window.location.href = '{{ route("orders.index") }}';
            });
        } else {
            throw new Error(data.message || 'Request failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: '{{ __("app.delivery.error") }}',
            text: '{{ __("app.delivery.request_error") }}',
            icon: 'error',
            confirmButtonColor: '#007fff'
        });
        
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});
</script>
@endpush


