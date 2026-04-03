@extends('layouts.app')

@section('title', __('app.auth.verify_email') . ' - Voltronix')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <i class="bi bi-envelope-check display-4 mb-3"></i>
            <h2 class="mb-2">{{ __('app.auth.verify_email') }}</h2>
            <p class="mb-0 opacity-90">{{ __('app.auth.verify_message') }}</p>
        </div>
        
        <div class="auth-body text-center">
            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success mb-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ __('app.auth.verification_sent') }}
                </div>
            @endif

            <div class="mb-4">
                <p class="text-muted">
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </p>
            </div>

            <div class="d-grid gap-2">
                <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-voltronix">
                        <i class="bi bi-envelope-arrow-up {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('app.auth.resend_verification') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="bi bi-box-arrow-right {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('app.nav.logout') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Show success message if verification email was sent
@if (session('status') == 'verification-link-sent')
    Swal.fire({
        title: '{{ __("app.common.success") }}',
        text: '{{ __("app.auth.verification_sent") }}',
        icon: 'success',
        confirmButtonColor: '#0d6efd',
        confirmButtonText: '{{ __("app.common.ok") }}'
    });
@endif
</script>
@endpush
@endsection
