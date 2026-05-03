@extends('layouts.app')

@section('title', $delivery->title)

@php
    $isRtl = app()->getLocale() === 'ar';
@endphp

@push('styles')
<style>
.license-page {
    padding-top: calc(var(--navbar-height-desktop) + 2rem);
    padding-bottom: 3rem;
    min-height: 100vh;
    background: linear-gradient(180deg, #f5f8fc 0%, #eef3f9 100%);
}

.license-shell {
    max-width: 860px;
    margin: 0 auto;
}

.license-card {
    border: 1px solid rgba(13, 110, 253, 0.12);
    border-radius: 20px;
    background: #fff;
    box-shadow: 0 18px 35px rgba(17, 32, 53, 0.08);
}

.license-card .card-body {
    padding: 1.6rem;
}

.license-label {
    font-size: 0.8rem;
    font-weight: 700;
    color: #6f8096;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 0.5rem;
}

.license-value-wrap {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.9rem;
    border-radius: 14px;
    background: #f6f9ff;
    border: 1px solid rgba(13, 110, 253, 0.15);
}

.license-value {
    margin: 0;
    flex: 1;
    font-family: Consolas, 'Courier New', monospace;
    font-size: 1rem;
    font-weight: 700;
    color: #10233e;
    word-break: break-all;
}

.license-copy-btn {
    border: 1px solid #8bbcff;
    background: #eef5ff;
    color: #0a3d91;
    border-radius: 12px;
    min-width: 2.6rem;
    height: 2.4rem;
    padding: 0 0.7rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    transition: all 0.2s ease;
}

.license-copy-btn:hover {
    background: #dceaff;
    border-color: #4f8dff;
    color: #08306f;
}

.license-copy-btn.is-success {
    background: #198754;
    border-color: #198754;
    color: #fff;
}

.license-copy-btn:focus-visible {
    outline: 2px solid rgba(13, 110, 253, 0.45);
    outline-offset: 2px;
}

@media (max-width: 991px) {
    .license-page {
        padding-top: calc(var(--navbar-height-mobile) + 1rem);
    }
}
</style>
@endpush

@section('content')
<div class="license-page">
    <div class="container">
        <div class="license-shell">
            <div class="license-card card">
                <div class="card-body">
                    <h4 class="mb-2">{{ $delivery->title }}</h4>
                    <p class="text-muted mb-4">{{ $delivery->description ?: __('app.delivery.secure_viewer_intro') }}</p>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="license-label">{{ __('app.delivery.delivery_title') }}</div>
                            <div>{{ $delivery->title }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="license-label">{{ __('app.delivery.type') }}</div>
                            <div>{{ ucfirst($delivery->type) }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="license-label">{{ __('app.delivery.expires') }}</div>
                            <div>{{ $delivery->expires_at ? local_datetime($delivery->expires_at, 'M d, Y H:i') : __('app.delivery.never_expires') }}</div>
                        </div>
                    </div>

                    <div class="license-label">{{ __('app.delivery.license_key') }}</div>
                    <div class="license-value-wrap">
                        <p id="licenseValue" class="license-value">{{ $licenseKey ?: $maskedLicenseKey ?: __('app.delivery.no_credentials_available') }}</p>
                        @if($licenseKey)
                            <button
                                id="copyLicenseBtn"
                                type="button"
                                class="license-copy-btn"
                                title="{{ app()->getLocale() === 'ar' ? 'نسخ' : 'Copy' }}"
                                aria-label="{{ app()->getLocale() === 'ar' ? 'نسخ الترخيص' : 'Copy license' }}">
                                <i class="fas fa-clipboard"></i>
                            </button>
                        @endif
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                            <i class="fas {{ $isRtl ? 'fa-arrow-right ms-1' : 'fa-arrow-left me-1' }}"></i>
                            {{ __('app.delivery.back_to_orders') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const copyBtn = document.getElementById('copyLicenseBtn');
    const valueNode = document.getElementById('licenseValue');

    if (!copyBtn || !valueNode) {
        return;
    }

    const labels = {
        copy: @json(app()->getLocale() === 'ar' ? 'نسخ' : 'Copy'),
        copied: @json(app()->getLocale() === 'ar' ? 'تم النسخ' : 'Copied!'),
        failed: @json(__('app.delivery.copy_failed')),
    };

    copyBtn.addEventListener('click', async () => {
        try {
            await navigator.clipboard.writeText(valueNode.textContent.trim());
            copyBtn.classList.add('is-success');
            copyBtn.title = labels.copied;
            copyBtn.querySelector('i').className = 'fas fa-check';

            setTimeout(() => {
                copyBtn.classList.remove('is-success');
                copyBtn.title = labels.copy;
                copyBtn.querySelector('i').className = 'fas fa-clipboard';
            }, 1500);
        } catch (error) {
            if (window.Swal) {
                window.Swal.fire({
                    title: @json(__('app.delivery.error')),
                    text: labels.failed,
                    icon: 'error',
                    confirmButtonColor: '#0d6efd',
                });
            } else {
                window.alert(labels.failed);
            }
        }
    });
})();
</script>
@endpush
