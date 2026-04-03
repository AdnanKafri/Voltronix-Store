@extends('layouts.app')

@section('title', __('app.auth.register_title') . ' - Voltronix')

@push('styles')
<style>
/* Modern Glassmorphic Register Page - Extends Login Styles */
.voltronix-auth-container {
    min-height: calc(100vh - var(--navbar-height-desktop));
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
    padding-top: calc(var(--navbar-height-desktop) + 2rem);
    overflow: hidden;
}

.voltronix-auth-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 80%, rgba(0, 127, 255, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(35, 239, 255, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
    animation: backgroundFloat 20s ease-in-out infinite;
}

@keyframes backgroundFloat {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(1deg); }
}

.voltronix-auth-card {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 24px;
    padding: 3rem 2.5rem;
    width: 100%;
    max-width: 500px;
    box-shadow: 
        0 25px 50px rgba(0, 0, 0, 0.1),
        0 0 0 1px rgba(255, 255, 255, 0.1) inset;
    position: relative;
    z-index: 2;
    animation: cardFloat 6s ease-in-out infinite;
}

@keyframes cardFloat {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.voltronix-auth-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.voltronix-auth-title {
    font-family: 'Orbitron', sans-serif;
    font-size: 2.2rem;
    font-weight: 900;
    background: linear-gradient(135deg, #007fff, #23efff, #ffffff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
    text-shadow: 0 4px 20px rgba(0, 127, 255, 0.3);
}

.voltronix-auth-subtitle {
    color: rgba(255, 255, 255, 0.8);
    font-size: 1rem;
    font-weight: 400;
    margin: 0;
}

.voltronix-form-group {
    margin-bottom: 1.5rem;
    position: relative;
}

.voltronix-form-label {
    display: block;
    color: rgba(255, 255, 255, 0.9);
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.voltronix-form-input {
    width: 100%;
    padding: 1rem 1.25rem;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    color: white;
    font-size: 1rem;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.voltronix-form-input:focus {
    outline: none;
    border-color: rgba(0, 127, 255, 0.6);
    background: rgba(255, 255, 255, 0.15);
    box-shadow: 0 0 0 3px rgba(0, 127, 255, 0.2);
    transform: translateY(-2px);
}

.voltronix-form-input::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.voltronix-form-input.is-invalid {
    border-color: rgba(220, 53, 69, 0.6);
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.2);
}

.voltronix-invalid-feedback {
    color: #ff6b6b;
    font-size: 0.85rem;
    margin-top: 0.5rem;
    display: block;
}

.voltronix-submit-btn {
    width: 100%;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #007fff, #23efff);
    border: none;
    border-radius: 12px;
    color: white;
    font-size: 1.1rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.voltronix-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0, 127, 255, 0.4);
}

.voltronix-submit-btn:active {
    transform: translateY(0);
}

.voltronix-submit-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.voltronix-auth-links {
    text-align: center;
}

.voltronix-auth-link {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.voltronix-auth-link:hover {
    color: #23efff;
    text-decoration: none;
}

.voltronix-auth-text {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.9rem;
}

.voltronix-optional-text {
    color: rgba(255, 255, 255, 0.5);
    font-size: 0.8rem;
    font-weight: 400;
}

/* Social Login Styles */
.social-login {
    margin-top: 1.5rem;
}

.divider-text {
    position: relative;
    text-align: center;
    margin: 1.5rem 0;
}

.divider-text::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: rgba(255, 255, 255, 0.3);
    z-index: 1;
}

.divider-text span {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.85rem;
    position: relative;
    z-index: 2;
}

.google-login-btn {
    background: rgba(255, 255, 255, 0.95) !important;
    border: 1px solid rgba(0, 0, 0, 0.1) !important;
    color: #3c4043 !important;
    padding: 0.8rem 1.5rem;
    border-radius: 12px;
    font-weight: 500;
    font-size: 0.95rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.google-login-btn:hover {
    background: rgba(255, 255, 255, 1) !important;
    border-color: rgba(0, 0, 0, 0.2) !important;
    color: #3c4043 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    text-decoration: none;
}

.google-login-btn:focus {
    box-shadow: 0 0 0 3px rgba(66, 133, 244, 0.3) !important;
    color: #3c4043 !important;
    outline: none;
    text-decoration: none;
}

.google-login-btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.google-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.google-logo svg {
    width: 18px;
    height: 18px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .voltronix-auth-container {
        min-height: calc(100vh - var(--navbar-height-mobile));
        padding: 1rem;
        padding-top: calc(var(--navbar-height-mobile) + 1rem);
    }
    
    .voltronix-auth-card {
        padding: 2rem 1.5rem;
    }
    
    .voltronix-auth-title {
        font-size: 1.8rem;
    }
}

/* Terms Checkbox Styling */
.form-check-input {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 4px;
    width: 1.2em;
    height: 1.2em;
}

.form-check-input:checked {
    background: linear-gradient(135deg, #007fff, #23efff);
    border-color: #007fff;
}

.form-check-input:focus {
    border-color: rgba(0, 127, 255, 0.6);
    box-shadow: 0 0 0 2px rgba(0, 127, 255, 0.2);
}

.form-check-input.is-invalid {
    border-color: rgba(220, 53, 69, 0.6);
}

/* RTL Support */
[dir="rtl"] .voltronix-form-check {
    flex-direction: row-reverse;
}
</style>
@endpush

@section('content')
<div class="voltronix-auth-container">
    <div class="voltronix-auth-card">
        <div class="voltronix-auth-header">
            <h1 class="voltronix-auth-title">{{ __('app.auth.register_title') }}</h1>
            <p class="voltronix-auth-subtitle">{{ __('app.auth.register_subtitle') }}</p>
        </div>
        
        <form method="POST" action="{{ route('register') }}" id="voltronixRegisterForm">
            @csrf

            <!-- Name -->
            <div class="voltronix-form-group">
                <label for="name" class="voltronix-form-label">
                    <i class="bi bi-person {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('app.auth.name') }}
                </label>
                <input type="text" 
                       class="voltronix-form-input @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       placeholder="{{ __('app.auth.name_placeholder') }}"
                       required 
                       autofocus 
                       autocomplete="name">
                @error('name')
                    <div class="voltronix-invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email Address -->
            <div class="voltronix-form-group">
                <label for="email" class="voltronix-form-label">
                    <i class="bi bi-envelope {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('app.auth.email') }}
                </label>
                <input type="email" 
                       class="voltronix-form-input @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       placeholder="{{ __('app.auth.email_placeholder') }}"
                       required 
                       autocomplete="username">
                @error('email')
                    <div class="voltronix-invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Phone -->
            <div class="voltronix-form-group">
                <label for="phone" class="voltronix-form-label">
                    <i class="bi bi-telephone {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('app.auth.phone') }}
                </label>
                <input type="tel" 
                       class="voltronix-form-input @error('phone') is-invalid @enderror" 
                       id="phone" 
                       name="phone" 
                       value="{{ old('phone') }}" 
                       placeholder="{{ __('app.auth.phone_placeholder') }}"
                       required
                       autocomplete="tel">
                @error('phone')
                    <div class="voltronix-invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="voltronix-form-group">
                <label for="password" class="voltronix-form-label">
                    <i class="bi bi-lock {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('app.auth.password') }}
                </label>
                <input type="password" 
                       class="voltronix-form-input @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       placeholder="{{ __('app.auth.password_placeholder') }}"
                       required 
                       autocomplete="new-password">
                @error('password')
                    <div class="voltronix-invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="voltronix-form-group">
                <label for="password_confirmation" class="voltronix-form-label">
                    <i class="bi bi-shield-check {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('app.auth.password_confirm') }}
                </label>
                <input type="password" 
                       class="voltronix-form-input @error('password_confirmation') is-invalid @enderror" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       placeholder="{{ __('app.auth.password_confirm_placeholder') }}"
                       required 
                       autocomplete="new-password">
                @error('password_confirmation')
                    <div class="voltronix-invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Terms & Conditions -->
            <div class="voltronix-form-group">
                <div class="form-check d-flex align-items-start">
                    <input type="checkbox" 
                           class="form-check-input @error('terms') is-invalid @enderror" 
                           id="terms" 
                           name="terms" 
                           value="1"
                           {{ old('terms') ? 'checked' : '' }}
                           required
                           style="margin-top: 0.25rem; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.75rem;">
                    <label class="form-check-label voltronix-form-label" for="terms" style="margin-bottom: 0; font-size: 0.85rem; line-height: 1.4;">
                        {{ __('app.auth.agree_to') }} 
                        <a href="{{ route('terms') }}" target="_blank" class="voltronix-auth-link">
                            {{ __('app.auth.terms_conditions') }}
                        </a>
                    </label>
                </div>
                @error('terms')
                    <div class="voltronix-invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="voltronix-submit-btn" id="registerSubmitBtn">
                <i class="bi bi-person-plus {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('app.auth.register_button') }}
            </button>

            <!-- Social Login -->
            <div class="social-login mt-4 text-center">
                <div class="divider-text mb-3">
                    <span>{{ __('app.common.or') }}</span>
                </div>
                <a href="{{ route('auth.google.redirect') }}" class="btn w-100 google-login-btn">
                    <div class="google-logo {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                    </div>
                    {{ __('app.auth.google_register') }}
                </a>
            </div>

            <!-- Links -->
            <div class="voltronix-auth-links">
                <span class="voltronix-auth-text">{{ __('app.auth.already_registered') }}</span>
                <a href="{{ route('login') }}" class="voltronix-auth-link">
                    {{ __('app.nav.login') }}
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('voltronixRegisterForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('registerSubmitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __("app.common.loading") }}...';
});

// Show errors with SweetAlert2 if any
@if ($errors->any())
    Swal.fire({
        title: '{{ __("app.common.error") }}',
        html: '@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach',
        icon: 'error',
        confirmButtonColor: '#007fff',
        confirmButtonText: '{{ __("app.common.ok") }}',
        background: 'rgba(255, 255, 255, 0.95)',
        backdrop: 'rgba(0, 0, 0, 0.4)'
    });
@endif
</script>
@endpush
@endsection
