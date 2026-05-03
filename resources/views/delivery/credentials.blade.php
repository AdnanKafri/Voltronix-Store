@extends('layouts.app')

@section('title', $delivery->title)

@php
    $isRtl = app()->getLocale() === 'ar';
    $directionClass = $isRtl ? 'rtl' : 'ltr';
    $credentialTypeLabel = str_replace('_', ' ', $delivery->credentials_type ?: $delivery->type);
@endphp

@push('styles')
<style>
.secure-delivery-page {
    padding-top: calc(var(--navbar-height-desktop) + 2rem);
    padding-bottom: 3rem;
    min-height: 100vh;
    background:
        radial-gradient(circle at top, rgba(0, 127, 255, 0.12), transparent 32%),
        linear-gradient(180deg, #07111d 0%, #0d1b2d 28%, #f4f7fb 28%, #eef3f9 100%);
}

.secure-delivery-shell {
    max-width: 980px;
    margin: 0 auto;
}

.secure-delivery-hero {
    position: relative;
    overflow: hidden;
    border-radius: 28px;
    padding: 2rem;
    background: linear-gradient(135deg, rgba(8, 18, 36, 0.96), rgba(19, 53, 96, 0.92));
    color: #fff;
    box-shadow: 0 28px 60px rgba(7, 17, 29, 0.24);
}

.secure-delivery-hero::after {
    content: '';
    position: absolute;
    inset: auto -10% -40% 35%;
    height: 240px;
    background: radial-gradient(circle, rgba(0, 212, 255, 0.22), transparent 70%);
    pointer-events: none;
}

.secure-delivery-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.55rem;
    padding: 0.45rem 0.9rem;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.14);
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.secure-delivery-title {
    margin: 1.2rem 0 0.65rem;
    font-size: clamp(1.8rem, 2.4vw, 2.7rem);
    font-weight: 800;
    line-height: 1.1;
}

.secure-delivery-subtitle {
    max-width: 700px;
    margin: 0;
    color: rgba(255, 255, 255, 0.76);
    font-size: 1rem;
    line-height: 1.75;
}

.secure-delivery-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.6fr) minmax(300px, 0.95fr);
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.secure-delivery-panel {
    background: rgba(255, 255, 255, 0.94);
    border: 1px solid rgba(11, 33, 61, 0.08);
    border-radius: 24px;
    box-shadow: 0 18px 40px rgba(18, 35, 58, 0.08);
}

.secure-delivery-panel-body {
    padding: 1.5rem;
}

.secure-delivery-section-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.45rem;
    color: #10233e;
    font-size: 1.05rem;
    font-weight: 800;
}

.secure-delivery-section-title i {
    width: 2.3rem;
    height: 2.3rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    background: rgba(0, 127, 255, 0.12);
    color: #0d6efd;
}

.secure-delivery-section-copy {
    margin-bottom: 1.25rem;
    color: #5b6b80;
    line-height: 1.7;
}

.secure-delivery-meta {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.85rem;
}

.secure-delivery-meta-card {
    padding: 1rem 1.05rem;
    border-radius: 18px;
    background: linear-gradient(180deg, rgba(245, 248, 252, 0.98), rgba(235, 241, 248, 0.92));
    border: 1px solid rgba(16, 35, 62, 0.08);
}

.secure-delivery-meta-label {
    display: block;
    margin-bottom: 0.35rem;
    color: #6a7b90;
    font-size: 0.82rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
}

.secure-delivery-meta-value {
    color: #10233e;
    font-size: 1rem;
    font-weight: 700;
    line-height: 1.45;
    word-break: break-word;
}

.secure-delivery-security {
    margin-top: 1.2rem;
    padding: 1rem 1.1rem;
    border-radius: 18px;
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.14), rgba(255, 244, 214, 0.72));
    border: 1px solid rgba(255, 193, 7, 0.24);
    color: #6a4b00;
}

.secure-delivery-security strong {
    display: block;
    margin-bottom: 0.3rem;
    color: #5a4100;
}

.secure-delivery-security p {
    margin: 0;
    line-height: 1.7;
}

.secure-delivery-preview {
    display: grid;
    gap: 0.85rem;
}

.secure-credential-item {
    padding: 1rem 1rem 0.95rem;
    border-radius: 18px;
    background: linear-gradient(180deg, rgba(249, 251, 254, 0.96), rgba(240, 244, 250, 0.96));
    border: 1px solid rgba(12, 28, 52, 0.08);
}

.secure-credential-item.is-revealed {
    background: linear-gradient(180deg, rgba(227, 255, 244, 0.98), rgba(237, 251, 244, 0.98));
    border-color: rgba(25, 135, 84, 0.2);
}

.secure-credential-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 0.55rem;
}

.secure-credential-label {
    margin: 0;
    color: #637388;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
}

.secure-credential-value {
    font-family: Consolas, 'Courier New', monospace;
    font-size: 1rem;
    font-weight: 700;
    color: #10233e;
    line-height: 1.75;
    letter-spacing: 0.01em;
    word-break: break-all;
}

.secure-credential-item.is-revealed .secure-credential-value {
    color: #0f5132;
}

.secure-copy-btn {
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
    font-size: 1.05rem;
    font-weight: 700;
    line-height: 1;
    white-space: nowrap;
    box-shadow: 0 1px 2px rgba(10, 61, 145, 0.08);
    transition: all 0.2s ease, box-shadow 0.2s ease;
}

.secure-copy-btn:hover {
    background: #dceaff;
    border-color: #4f8dff;
    color: #08306f;
    box-shadow: 0 6px 14px rgba(13, 110, 253, 0.2);
    transform: translateY(-1px);
}

.secure-copy-btn:focus-visible {
    outline: 2px solid rgba(13, 110, 253, 0.45);
    outline-offset: 2px;
}

[data-bs-theme="dark"] .secure-copy-btn {
    background: #1b2a42;
    border-color: #3b5f9b;
    color: #c9dcff;
    box-shadow: none;
}

[data-bs-theme="dark"] .secure-copy-btn:hover {
    background: #243757;
    border-color: #5f8fe0;
    color: #e6f0ff;
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.28);
}

[data-bs-theme="dark"] .secure-copy-btn:focus-visible {
    outline-color: rgba(120, 168, 255, 0.55);
}

.secure-copy-btn i {
    font-size: 1.12rem;
    opacity: 1;
    filter: none;
}

.secure-copy-btn.is-success i {
    font-size: 1.05rem;
}

.secure-copy-btn:hover i {
    color: inherit;
    opacity: 1;
}

.secure-copy-btn:hover {
    color: #fff;
}

.secure-copy-btn.is-success {
    background: #198754;
    border-color: #198754;
    color: #fff;
}

.secure-copy-btn-label {
    display: none;
}

.secure-action-row {
    display: flex;
    flex-wrap: wrap;
    gap: 0.85rem;
    margin-top: 1.4rem;
}

.btn-secure-primary,
.btn-secure-secondary,
.btn-secure-outline {
    border-radius: 999px;
    font-weight: 700;
    padding: 0.85rem 1.35rem;
    transition: all 0.25s ease;
}

.btn-secure-primary {
    background: linear-gradient(135deg, #0088ff, #0057ff);
    color: #fff;
    border: none;
    box-shadow: 0 14px 28px rgba(0, 102, 255, 0.22);
}

.btn-secure-primary:hover,
.btn-secure-primary:focus {
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 16px 32px rgba(0, 102, 255, 0.28);
}

.btn-secure-secondary {
    background: rgba(25, 135, 84, 0.12);
    color: #146c43;
    border: 1px solid rgba(25, 135, 84, 0.18);
}

.btn-secure-secondary:hover,
.btn-secure-secondary:focus {
    background: rgba(25, 135, 84, 0.18);
    color: #0f5132;
}

.btn-secure-outline {
    background: transparent;
    border: 1px solid rgba(16, 35, 62, 0.12);
    color: #10233e;
}

.btn-secure-outline:hover,
.btn-secure-outline:focus {
    background: rgba(16, 35, 62, 0.05);
    color: #10233e;
}

.secure-reveal-status {
    margin-top: 1rem;
    display: none;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.9rem 1rem;
    border-radius: 16px;
    background: rgba(25, 135, 84, 0.1);
    border: 1px solid rgba(25, 135, 84, 0.18);
    color: #146c43;
}

.secure-reveal-status.is-visible {
    display: flex;
}

.secure-reveal-status strong {
    display: block;
    font-size: 0.95rem;
}

.secure-reveal-status span {
    color: #4b6354;
    font-size: 0.9rem;
}

.secure-countdown {
    min-width: 2.5rem;
    text-align: center;
    font-family: Consolas, 'Courier New', monospace;
    font-size: 1.15rem;
    font-weight: 800;
    color: #0f5132;
}

.secure-back-link {
    margin-top: 1.5rem;
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    color: #10233e;
    font-weight: 700;
    text-decoration: none;
}

.secure-back-link:hover {
    color: #0d6efd;
}

[dir="rtl"] .secure-delivery-page h1,
[dir="rtl"] .secure-delivery-page h2,
[dir="rtl"] .secure-delivery-page h3,
[dir="rtl"] .secure-delivery-page h4,
[dir="rtl"] .secure-delivery-page h5,
[dir="rtl"] .secure-delivery-page h6 {
    font-family: var(--font-ar-heading);
}

[dir="rtl"] .secure-credential-value,
[dir="rtl"] .secure-delivery-meta-value {
    direction: ltr;
    text-align: left;
}

@media (max-width: 991px) {
    .secure-delivery-page {
        padding-top: calc(var(--navbar-height-mobile) + 1rem);
        background:
            radial-gradient(circle at top, rgba(0, 127, 255, 0.12), transparent 26%),
            linear-gradient(180deg, #07111d 0%, #0d1b2d 22%, #f4f7fb 22%, #eef3f9 100%);
    }

    .secure-delivery-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .secure-delivery-hero,
    .secure-delivery-panel-body {
        padding: 1.25rem;
    }

    .secure-delivery-meta {
        grid-template-columns: 1fr;
    }

    .secure-credential-header,
    .secure-reveal-status {
        align-items: flex-start;
        flex-direction: column;
    }

    .secure-action-row {
        flex-direction: column;
    }

    .secure-action-row .btn {
        width: 100%;
        justify-content: center;
    }

    .secure-copy-btn {
        min-width: 2.4rem;
        padding: 0 0.6rem;
    }
}
</style>
@endpush

@section('content')
<div class="secure-delivery-page">
    <div class="container">
        <div class="secure-delivery-shell">
            <section class="secure-delivery-hero">
                <span class="secure-delivery-badge">
                    <i class="fas fa-shield-halved"></i>
                    {{ __('app.delivery.secure_viewer') }}
                </span>
                <h1 class="secure-delivery-title">{{ $delivery->title }}</h1>
                <p class="secure-delivery-subtitle">
                    {{ $delivery->description ?: __('app.delivery.secure_viewer_intro') }}
                </p>
            </section>

            <div class="secure-delivery-grid">
                <section class="secure-delivery-panel">
                    <div class="secure-delivery-panel-body">
                        <div class="secure-delivery-section-title">
                            <i class="fas fa-lock"></i>
                            <span>{{ __('app.delivery.credentials') }}</span>
                        </div>
                        <p class="secure-delivery-section-copy">
                            {{ __('app.delivery.reveal_to_continue') }}
                        </p>

                        <div class="secure-delivery-preview" id="credentialsPreview">
                            @forelse($maskedCredentials as $key => $value)
                                <article class="secure-credential-item" data-credential-key="{{ $key }}">
                                    <div class="secure-credential-header">
                                        <p class="secure-credential-label">{{ ucfirst(str_replace('_', ' ', $key)) }}</p>
                                    </div>
                                    <div class="secure-credential-value">{{ $value }}</div>
                                </article>
                            @empty
                                <article class="secure-credential-item">
                                    <div class="secure-credential-value">{{ __('app.delivery.no_credentials_available') }}</div>
                                </article>
                            @endforelse
                        </div>

                        <div class="secure-reveal-status" id="revealStatus" aria-live="polite">
                            <div>
                                <strong>{{ __('app.delivery.credentials_visible') }}</strong>
                                <span>{{ __('app.delivery.credentials_warning') }}</span>
                            </div>
                            <div class="secure-countdown" id="countdownValue"></div>
                        </div>

                        <div class="secure-action-row">
                            <button
                                type="button"
                                class="btn btn-secure-primary d-inline-flex align-items-center gap-2"
                                id="revealCredentialsBtn"
                            >
                                <i class="fas fa-eye"></i>
                                <span>{{ __('app.delivery.reveal_credentials') }}</span>
                            </button>

                            <button
                                type="button"
                                class="btn btn-secure-secondary d-none align-items-center gap-2"
                                id="hideCredentialsBtn"
                            >
                                <i class="fas fa-eye-slash"></i>
                                <span>{{ __('app.delivery.hide_credentials') }}</span>
                            </button>
                        </div>
                    </div>
                </section>

                <aside class="secure-delivery-panel">
                    <div class="secure-delivery-panel-body">
                        <div class="secure-delivery-section-title">
                            <i class="fas fa-circle-info"></i>
                            <span>{{ __('app.delivery.access_details') }}</span>
                        </div>
                        <p class="secure-delivery-section-copy">
                            {{ __('app.delivery.masked_preview_notice') }}
                        </p>

                        <div class="secure-delivery-meta">
                            <div class="secure-delivery-meta-card">
                                <span class="secure-delivery-meta-label">{{ __('app.delivery.delivery_title') }}</span>
                                <div class="secure-delivery-meta-value">{{ $delivery->title }}</div>
                            </div>

                            <div class="secure-delivery-meta-card">
                                <span class="secure-delivery-meta-label">{{ __('app.delivery.type') }}</span>
                                <div class="secure-delivery-meta-value">{{ ucwords(str_replace('_', ' ', $credentialTypeLabel)) }}</div>
                            </div>

                            <div class="secure-delivery-meta-card">
                                <span class="secure-delivery-meta-label">{{ __('app.delivery.views_used') }}</span>
                                <div class="secure-delivery-meta-value">
                                    {{ $delivery->views_count }}
                                    /
                                    {{ $delivery->max_views ?: __('app.delivery.unlimited') }}
                                </div>
                            </div>

                            <div class="secure-delivery-meta-card">
                                <span class="secure-delivery-meta-label">{{ __('app.delivery.expires') }}</span>
                                <div class="secure-delivery-meta-value">
                                    {{ $delivery->expires_at ? local_datetime($delivery->expires_at, 'M d, Y H:i') : __('app.delivery.never_expires') }}
                                </div>
                            </div>
                        </div>

                        <div class="secure-delivery-security">
                            <strong>{{ __('app.delivery.security_notice') }}</strong>
                            <p>{{ __('app.delivery.reveal_confirmation_message') }}</p>
                        </div>
                    </div>
                </aside>
            </div>

            <a href="{{ route('orders.index') }}" class="secure-back-link">
                <i class="fas {{ $isRtl ? 'fa-arrow-right' : 'fa-arrow-left' }}"></i>
                <span>{{ __('app.delivery.back_to_orders') }}</span>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const revealButton = document.getElementById('revealCredentialsBtn');
    const hideButton = document.getElementById('hideCredentialsBtn');
    const previewContainer = document.getElementById('credentialsPreview');
    const revealStatus = document.getElementById('revealStatus');
    const countdownValue = document.getElementById('countdownValue');

    const maskedCredentials = @json($maskedCredentials);
    let revealedCredentials = null;
    let countdownTimer = null;

    const labels = {
        loading: @json(__('app.delivery.loading')),
        revealCredentials: @json(__('app.delivery.reveal_credentials')),
        hideCredentials: @json(__('app.delivery.hide_credentials')),
        revealTitle: @json(__('app.delivery.reveal_confirmation_title')),
        revealText: @json(__('app.delivery.reveal_confirmation_message')),
        revealButton: @json(__('app.delivery.reveal_confirmation_button')),
        cancelButton: @json(__('app.common.cancel')),
        errorTitle: @json(__('app.delivery.error')),
        revealError: @json(__('app.delivery.reveal_error')),
        credentialsHidden: @json(__('app.delivery.credentials_hidden')),
        credentialsHiddenMessage: @json(__('app.delivery.credentials_hidden_message')),
        copied: @json(__('app.delivery.copy_success')),
        copyFailed: @json(__('app.delivery.copy_failed')),
        copyAction: @json(app()->getLocale() === 'ar' ? 'نسخ' : 'Copy'),
        copiedAction: @json(app()->getLocale() === 'ar' ? 'تم النسخ' : 'Copied!'),
        noCredentials: @json(__('app.delivery.no_credentials_available')),
        credentialsWarning: @json(__('app.delivery.credentials_warning')),
    };

    function formatLabel(key) {
        return key
            .replace(/_/g, ' ')
            .replace(/\b\w/g, letter => letter.toUpperCase());
    }

    function escapeSelector(value) {
        return window.CSS && typeof window.CSS.escape === 'function'
            ? window.CSS.escape(value)
            : value.replace(/"/g, '\\"');
    }

    function resetButtonState() {
        revealButton.disabled = false;
        revealButton.innerHTML = `<i class="fas fa-eye"></i><span>${labels.revealCredentials}</span>`;
    }

    function clearCountdown() {
        if (countdownTimer) {
            clearInterval(countdownTimer);
            countdownTimer = null;
        }
    }

    function setCopyButtonState(button, success) {
        const icon = button.querySelector('i');
        const text = button.querySelector('.secure-copy-btn-label');

        if (!icon) {
            return;
        }

        if (success) {
            button.classList.add('is-success');
            icon.className = 'fas fa-check';
            if (text) {
                text.textContent = labels.copiedAction;
            }
            button.setAttribute('title', labels.copiedAction);
        } else {
            icon.className = 'fas fa-clipboard';
            button.classList.remove('is-success');
            if (text) {
                text.textContent = labels.copyAction;
            }
            button.setAttribute('title', labels.copyAction);
        }
    }

    async function showDialog(options) {
        if (window.Swal) {
            return window.Swal.fire(options);
        }

        const confirmed = window.confirm(options.text || options.title || '');
        return { isConfirmed: confirmed };
    }

    function renderCredentials(data, revealed) {
        const source = data && Object.keys(data).length ? data : {};

        if (!Object.keys(source).length) {
            previewContainer.innerHTML = `
                <article class="secure-credential-item${revealed ? ' is-revealed' : ''}">
                    <div class="secure-credential-value">${labels.noCredentials}</div>
                </article>
            `;
            return;
        }

        previewContainer.innerHTML = '';

        Object.entries(source).forEach(([key, value]) => {
            const card = document.createElement('article');
            card.className = `secure-credential-item${revealed ? ' is-revealed' : ''}`;
            card.dataset.credentialKey = key;

            const header = document.createElement('div');
            header.className = 'secure-credential-header';

            const label = document.createElement('p');
            label.className = 'secure-credential-label';
            label.textContent = formatLabel(key);
            header.appendChild(label);

            if (revealed) {
                const copyButton = document.createElement('button');
                copyButton.type = 'button';
                copyButton.className = 'secure-copy-btn';
                copyButton.dataset.value = String(value ?? '');
                copyButton.setAttribute('aria-label', `${labels.copyAction} ${formatLabel(key)}`);
                copyButton.setAttribute('title', labels.copyAction);
                copyButton.innerHTML = `<i class="fas fa-clipboard"></i><span class="secure-copy-btn-label">${labels.copyAction}</span>`;
                copyButton.addEventListener('click', handleCopyClick);
                header.appendChild(copyButton);
            }

            const content = document.createElement('div');
            content.className = 'secure-credential-value';
            content.textContent = String(value ?? '');

            card.appendChild(header);
            card.appendChild(content);
            previewContainer.appendChild(card);
        });
    }

    function updateRevealState(revealed, seconds = null) {
        if (revealed) {
            revealButton.classList.add('d-none');
            hideButton.classList.remove('d-none');
            hideButton.classList.add('d-inline-flex');
            revealStatus.classList.add('is-visible');
            countdownValue.textContent = seconds ? String(seconds) : '';
            return;
        }

        revealButton.classList.remove('d-none');
        hideButton.classList.add('d-none');
        hideButton.classList.remove('d-inline-flex');
        revealStatus.classList.remove('is-visible');
        countdownValue.textContent = '';
    }

    function hideCredentials(showMessage = false) {
        clearCountdown();
        revealedCredentials = null;
        renderCredentials(maskedCredentials, false);
        updateRevealState(false);
        resetButtonState();

        if (showMessage && window.Swal) {
            window.Swal.fire({
                title: labels.credentialsHidden,
                text: labels.credentialsHiddenMessage,
                icon: 'info',
                confirmButtonColor: '#0d6efd',
            });
        }
    }

    function startCountdown(seconds) {
        clearCountdown();

        if (!seconds || seconds <= 0) {
            countdownValue.textContent = '';
            return;
        }

        let remaining = seconds;
        countdownValue.textContent = String(remaining);

        countdownTimer = setInterval(() => {
            remaining -= 1;
            countdownValue.textContent = String(Math.max(remaining, 0));

            if (remaining <= 0) {
                hideCredentials(true);
            }
        }, 1000);
    }

    async function revealCredentials() {
        const confirmation = await showDialog({
            title: labels.revealTitle,
            text: labels.revealText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: labels.revealButton,
            cancelButtonText: labels.cancelButton,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
        });

        if (!confirmation.isConfirmed) {
            return;
        }

        revealButton.disabled = true;
        revealButton.innerHTML = `<i class="fas fa-spinner fa-spin"></i><span>${labels.loading}</span>`;

        try {
            const response = await fetch(@json(route('delivery.reveal', $delivery->token)), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
            });

            const payload = await response.json();

            if (!response.ok || !payload.success) {
                throw new Error(payload.error || payload.message || labels.revealError);
            }

            revealedCredentials = payload.credentials || {};
            renderCredentials(revealedCredentials, true);
            updateRevealState(true, payload.view_duration || null);
            resetButtonState();

            if (payload.view_duration) {
                startCountdown(payload.view_duration);
            }
        } catch (error) {
            resetButtonState();

            if (window.Swal) {
                window.Swal.fire({
                    title: labels.errorTitle,
                    text: labels.revealError,
                    icon: 'error',
                    confirmButtonColor: '#0d6efd',
                });
            } else {
                window.alert(labels.revealError);
            }
        }
    }

    async function handleCopyClick(event) {
        const button = event.currentTarget;
        const value = button.dataset.value || '';

        try {
            await navigator.clipboard.writeText(value);
            setCopyButtonState(button, true);

            setTimeout(() => {
                setCopyButtonState(button, false);
            }, 1600);
        } catch (error) {
            if (window.Swal) {
                window.Swal.fire({
                    title: labels.errorTitle,
                    text: labels.copyFailed,
                    icon: 'error',
                    confirmButtonColor: '#0d6efd',
                });
            } else {
                window.alert(labels.copyFailed);
            }
        }
    }

    revealButton.addEventListener('click', revealCredentials);
    hideButton.addEventListener('click', () => hideCredentials(false));

    renderCredentials(maskedCredentials, false);

    document.addEventListener('visibilitychange', () => {
        if (document.hidden && revealedCredentials) {
            hideCredentials(false);
        }
    });

    window.addEventListener('beforeunload', () => {
        clearCountdown();
    });
})();
</script>
@endpush


