@extends('layouts.app')

@section('title', __('app.delivery.access_denied'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg">
                <div class="card-body text-center p-5">
                    <!-- Access Denied Icon -->
                    <div class="mb-4">
                        <i class="bi bi-shield-x display-1 text-danger"></i>
                    </div>
                    
                    <!-- Title -->
                    <h2 class="h3 fw-bold text-dark mb-3">{{ __('app.delivery.access_denied') }}</h2>
                    
                    <!-- Reason -->
                    <p class="text-muted mb-4">{{ $reason }}</p>
                    
                    <!-- Actions -->
                    <div class="d-flex flex-column gap-3">
                        @if(isset($token))
                            <a href="{{ route('delivery.request', $token) }}" 
                               class="btn btn-primary">
                                <i class="bi bi-envelope {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('app.delivery.request_new_access') }}
                            </a>
                        @endif
                        
                        <a href="{{ route('orders.index') }}" 
                           class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('app.delivery.back_to_orders') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
