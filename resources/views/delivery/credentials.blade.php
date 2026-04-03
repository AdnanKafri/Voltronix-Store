@extends('layouts.app')

@section('title', $delivery->title)

@push('styles')
<style>
.delivery-credentials-page {
    padding-top: calc(var(--navbar-height-desktop) + 2rem);
    padding-bottom: 2rem;
    min-height: 100vh;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    animation: fadeIn 0.6s ease-out;
}

@media (max-width: 768px) {
    .delivery-credentials-page {
        padding-top: calc(var(--navbar-height-mobile) + 1rem);
    }
}

.credentials-container {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    padding: 1.5rem;
    border: 2px solid rgba(0, 127, 255, 0.1);
    backdrop-filter: blur(10px);
}

.masked-credentials .form-control {
    background: rgba(248, 249, 250, 0.8) !important;
    border: 1px solid rgba(0, 127, 255, 0.2);
    font-family: 'Courier New', monospace;
    letter-spacing: 2px;
}

.real-credentials .form-control {
    background: rgba(40, 167, 69, 0.1) !important;
    border: 1px solid rgba(40, 167, 69, 0.3);
    font-family: 'Courier New', monospace;
}

.credential-value {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: var(--voltronix-primary);
}

.alert-warning {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 193, 7, 0.05));
    border: 1px solid rgba(255, 193, 7, 0.3);
    border-radius: 10px;
}

.btn-voltronix-primary {
    background: var(--voltronix-gradient);
    border: none;
    color: white;
    font-weight: 600;
    border-radius: 25px;
    padding: 0.75rem 2rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 127, 255, 0.3);
}

.btn-voltronix-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 127, 255, 0.4);
    color: white;
}

.btn-voltronix-secondary {
    background: rgba(108, 117, 125, 0.1);
    border: 1px solid rgba(108, 117, 125, 0.3);
    color: #6c757d;
    font-weight: 600;
    border-radius: 20px;
    padding: 0.5rem 1.5rem;
    transition: all 0.3s ease;
}

.btn-voltronix-secondary:hover {
    background: rgba(108, 117, 125, 0.2);
    transform: translateY(-1px);
    color: #495057;
}

.btn-voltronix-outline {
    background: transparent;
    border: 2px solid var(--voltronix-primary);
    color: var(--voltronix-primary);
    font-weight: 600;
    border-radius: 25px;
    padding: 0.75rem 2rem;
    transition: all 0.3s ease;
}

.btn-voltronix-outline:hover {
    background: var(--voltronix-gradient);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 127, 255, 0.3);
}

.badge {
    background: var(--voltronix-gradient) !important;
    color: white;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.access-info-item {
    background: rgba(255, 255, 255, 0.7);
    border-radius: 10px;
    padding: 1rem;
    border: 1px solid rgba(0, 127, 255, 0.1);
}

/* RTL Support */
[dir="rtl"] .credential-value {
    text-align: right;
}

[dir="rtl"] .form-control {
    text-align: right;
}

/* Animations */
.real-credentials {
    animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-voltronix {
    animation: slideInUp 0.6s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Copy button styling */
.btn-outline-secondary {
    border-color: rgba(0, 127, 255, 0.3);
    color: var(--voltronix-primary);
    transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
    background: var(--voltronix-primary);
    border-color: var(--voltronix-primary);
    color: white;
}

/* Enhanced Header Styling */
.card-header-voltronix {
    padding: 1.5rem 2rem !important;
    background: var(--voltronix-gradient) !important;
    border-radius: 20px 20px 0 0 !important;
    border: none !important;
    position: relative;
    overflow: hidden;
}

.card-header-voltronix::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 50%);
    pointer-events: none;
}

.card-header-voltronix .d-flex {
    position: relative;
    z-index: 2;
}

.card-header-voltronix i {
    font-size: 1.5rem;
    color: rgba(255, 255, 255, 0.9);
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.card-header-voltronix h5 {
    color: white !important;
    font-weight: 700 !important;
    font-size: 1.4rem !important;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    letter-spacing: 0.5px;
    margin: 0 !important;
}

@media (max-width: 768px) {
    .card-header-voltronix {
        padding: 1.25rem 1.5rem !important;
    }
    
    .card-header-voltronix h5 {
        font-size: 1.2rem !important;
    }
    
    .card-header-voltronix i {
        font-size: 1.3rem;
    }
}

/* Enhanced Card Body Styling */
.card-voltronix .card-body {
    padding: 2rem !important;
    border-radius: 0 0 20px 20px;
}

@media (max-width: 768px) {
    .card-voltronix .card-body {
        padding: 1.5rem !important;
    }
}
</style>
@endpush

@section('content')
<div class="delivery-credentials-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Delivery Info Card -->
                <div class="card-voltronix mb-4">
                    <div class="card-header-voltronix">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-key {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            <h5 class="mb-0 title-orbitron">{{ $delivery->title }}</h5>
                        </div>
                    </div>
                <div class="card-body">
                    @if($delivery->description)
                        <p class="text-muted mb-3">{{ $delivery->description }}</p>
                    @endif
                    
                    <!-- Access Info -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="access-info-item">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-eye text-info {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                    <span class="text-muted">{{ __('app.delivery.views_used') }}:</span>
                                    <strong class="{{ app()->getLocale() == 'ar' ? 'me-2' : 'ms-2' }}">
                                        {{ $delivery->views_count }}
                                        @if($delivery->max_views)
                                            / {{ $delivery->max_views }}
                                        @else
                                            / {{ __('app.delivery.unlimited') }}
                                        @endif
                                    </strong>
                                </div>
                            </div>
                        </div>
                        
                        @if($delivery->expires_at)
                            <div class="col-md-6">
                                <div class="access-info-item">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clock text-warning {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                        <span class="text-muted">{{ __('app.delivery.expires') }}:</span>
                                        <strong class="{{ app()->getLocale() == 'ar' ? 'me-2' : 'ms-2' }}">
                                            {{ $delivery->expires_at->format('M d, Y H:i') }}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Credentials Display -->
                    <div class="credentials-container">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">{{ __('app.delivery.credentials') }}</h6>
                            <span class="badge bg-secondary">{{ $delivery->credentials_type }}</span>
                        </div>
                        
                        <!-- Masked Credentials (Always Visible) -->
                        <div class="masked-credentials">
                            @foreach($maskedCredentials as $key => $value)
                                <div class="mb-2">
                                    <label class="form-label text-muted">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                                    <div class="form-control bg-light">{{ $value }}</div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Reveal Button -->
                        <div class="text-center mt-4">
                            <button type="button" 
                                    class="btn btn-voltronix-primary btn-lg"
                                    id="revealCredentialsBtn"
                                    onclick="revealCredentials()">
                                <i class="fas fa-eye {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('app.delivery.reveal_credentials') }}
                            </button>
                        </div>
                        
                        <!-- Real Credentials (Hidden by default) -->
                        <div class="real-credentials d-none" id="realCredentials">
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('app.delivery.credentials_warning') }}
                                @if($delivery->view_duration)
                                    <br><strong>{{ __('app.delivery.auto_hide_in') }}: <span id="countdown">{{ $delivery->view_duration }}</span> {{ __('app.delivery.seconds') }}</strong>
                                @endif
                            </div>
                            
                            <div id="credentialsContent">
                                <!-- Will be populated by JavaScript -->
                            </div>
                            
                            <div class="text-center mt-3">
                                <button type="button" 
                                        class="btn btn-voltronix-secondary"
                                        onclick="hideCredentials()">
                                    <i class="fas fa-eye-slash {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                    {{ __('app.delivery.hide_credentials') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Back Button -->
            <div class="text-center">
                <a href="{{ route('orders.index') }}" class="btn btn-voltronix-outline">
                    <i class="fas fa-arrow-left {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('app.delivery.back_to_orders') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let countdownTimer = null;

function revealCredentials() {
    const btn = document.getElementById('revealCredentialsBtn');
    const realCredentials = document.getElementById('realCredentials');
    
    // Show loading
    btn.innerHTML = '<i class="fas fa-spinner fa-spin {{ app()->getLocale() == "ar" ? "ms-2" : "me-2" }}"></i> {{ __("app.delivery.loading") }}';
    btn.disabled = true;
    
    // Make AJAX request to reveal credentials
    fetch('{{ route("delivery.reveal", $delivery->token) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.credentials) {
            // Populate credentials
            let html = '';
            Object.entries(data.credentials).forEach(([key, value]) => {
                html += `
                    <div class="mb-2">
                        <label class="form-label text-muted">${key.charAt(0).toUpperCase() + key.slice(1).replace('_', ' ')}</label>
                        <div class="form-control bg-success bg-opacity-10 border-success">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="credential-value">${value}</span>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('${value}', this)">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            document.getElementById('credentialsContent').innerHTML = html;
            realCredentials.classList.remove('d-none');
            btn.style.display = 'none';
            
            // Start countdown if duration is set
            if (data.view_duration) {
                startCountdown(data.view_duration);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: '{{ __("app.delivery.error") }}',
            text: '{{ __("app.delivery.reveal_error") }}',
            icon: 'error',
            confirmButtonColor: '#007fff'
        });
        
        // Reset button
        btn.innerHTML = '<i class="fas fa-eye {{ app()->getLocale() == "ar" ? "ms-2" : "me-2" }}"></i> {{ __("app.delivery.reveal_credentials") }}';
        btn.disabled = false;
    });
}

function hideCredentials() {
    const realCredentials = document.getElementById('realCredentials');
    const btn = document.getElementById('revealCredentialsBtn');
    
    realCredentials.classList.add('d-none');
    btn.style.display = 'inline-block';
    
    if (countdownTimer) {
        clearInterval(countdownTimer);
    }
}

function startCountdown(seconds) {
    const countdownElement = document.getElementById('countdown');
    let remaining = seconds;
    
    countdownTimer = setInterval(() => {
        remaining--;
        if (countdownElement) {
            countdownElement.textContent = remaining;
        }
        
        if (remaining <= 0) {
            hideCredentials();
            Swal.fire({
                title: '{{ __("app.delivery.credentials_hidden") }}',
                text: '{{ __("app.delivery.credentials_hidden_message") }}',
                icon: 'info',
                confirmButtonColor: '#007fff'
            });
        }
    }, 1000);
}

function copyToClipboard(text, button) {
    navigator.clipboard.writeText(text).then(() => {
        const originalIcon = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check text-success"></i>';
        
        setTimeout(() => {
            button.innerHTML = originalIcon;
        }, 2000);
    });
}
</script>
@endpush
