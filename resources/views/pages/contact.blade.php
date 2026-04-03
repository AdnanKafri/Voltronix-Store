@extends('layouts.app')

@section('title', __('app.contact.title') . ' - ' . __('app.hero.title'))
@section('description', __('app.contact.description'))

@push('styles')
<style>
    .contact-header {
        background: linear-gradient(135deg, #007fff, #23efff);
        color: white;
        padding: 5rem 0 3rem;
        position: relative;
        overflow: hidden;
    }

    .contact-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="60" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="40" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .contact-content {
        background: var(--voltronix-light);
        padding: 5rem 0;
    }

    .contact-card {
        background: white;
        border-radius: 25px;
        padding: 2.5rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 127, 255, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
    }

    .contact-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 60px rgba(0, 127, 255, 0.15);
        border-color: var(--voltronix-primary);
    }

    .contact-info-card {
        background: linear-gradient(145deg, #ffffff, #f8f9fa);
        border: 2px solid rgba(0, 127, 255, 0.1);
    }

    .contact-form-card {
        background: white;
        border: 2px solid rgba(35, 239, 255, 0.1);
    }

    .contact-icon {
        width: 60px;
        height: 60px;
        background: var(--voltronix-gradient);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        animation: pulse 2s ease-in-out infinite;
    }

    .form-control-modern {
        border: 2px solid #e9ecef;
        border-radius: 15px;
        padding: 1rem 1.25rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .form-control-modern:focus {
        border-color: var(--voltronix-primary);
        box-shadow: 0 0 0 0.2rem rgba(0, 127, 255, 0.25);
        background: white;
    }

    .btn-contact {
        background: var(--voltronix-gradient);
        border: none;
        color: white;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-contact:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 127, 255, 0.3);
        color: white;
    }

    .btn-contact:before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-contact:hover:before {
        left: 100%;
    }

    .social-links {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .social-link {
        width: 50px;
        height: 50px;
        background: var(--voltronix-gradient);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 1.2rem;
    }

    .social-link:hover {
        transform: translateY(-3px) scale(1.1);
        box-shadow: 0 10px 25px rgba(0, 127, 255, 0.3);
        color: white;
    }

    .social-link svg {
        width: 1.2rem;
        height: 1.2rem;
        fill: currentColor;
    }

    .office-hours {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-radius: 15px;
        padding: 1.5rem;
        margin-top: 2rem;
        border: 1px solid rgba(0, 127, 255, 0.1);
    }

    .breadcrumb-voltronix {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        backdrop-filter: blur(10px);
        padding: 0.75rem 1rem;
    }

    .breadcrumb-voltronix .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.7);
    }

    .breadcrumb-voltronix a {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
    }

    .breadcrumb-voltronix a:hover {
        color: white;
    }

    .breadcrumb-voltronix .active {
        color: white;
        font-weight: 600;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    /* RTL Support */
    [dir="rtl"] .social-links {
        direction: ltr;
    }

    [dir="rtl"] .contact-icon {
        margin-left: 0;
        margin-right: 0;
    }
</style>
@endpush

@section('content')
<!-- Contact Header -->
<section class="contact-header">
    <div class="volt-container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11">
                <br>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-voltronix">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}">{{ __('app.nav.home') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('app.contact.title') }}
                        </li>
                    </ol>
                </nav>
                
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <h1 class="display-4 fw-bold mb-3">
                            <i class="bi bi-envelope {{ app()->getLocale() == 'ar' ? 'ms-3' : 'me-3' }}"></i>{{ __('app.contact.title') }}
                        </h1>
                        <p class="lead mb-0">{{ __('app.contact.subtitle') }}</p>
                    </div>
                    <div class="col-lg-5 text-lg-end">
                        <div class="contact-icon d-inline-flex" style="margin-bottom: 0;">
                            <i class="bi bi-headset"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Content -->
<section class="contact-content">
    <div class="volt-container">
        <div class="row g-5 justify-content-center">
            <div class="col-xl-10 col-lg-11">
                <div class="row g-5">
            <!-- Contact Information -->
            <div class="col-lg-5">
                <div class="contact-card contact-info-card">
                    <div class="contact-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <h3 class="mb-4">{{ __('app.contact.contact_info') }}</h3>
                    
                    <!-- Email -->
                    @if(setting('contact_email'))
                    <div class="mb-4">
                        <h5 class="text-primary mb-2">
                            <i class="bi bi-envelope {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('app.contact.your_email') }}
                        </h5>
                        <p class="mb-0">
                            <a href="mailto:{{ setting('contact_email') }}" class="text-decoration-none">
                                {{ setting('contact_email') }}
                            </a>
                        </p>
                    </div>
                    @endif

                    <!-- Phone -->
                    @if(setting('contact_phone'))
                    <div class="mb-4">
                        <h5 class="text-primary mb-2">
                            <i class="bi bi-telephone {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('app.checkout.customer_phone') }}
                        </h5>
                        <p class="mb-0">
                            <a href="tel:{{ setting('contact_phone') }}" class="text-decoration-none">
                                {{ setting('contact_phone') }}
                            </a>
                        </p>
                    </div>
                    @endif

                    <!-- Address -->
                    @if(setting('contact_address_' . app()->getLocale()))
                    <div class="mb-4">
                        <h5 class="text-primary mb-2">
                            <i class="bi bi-geo-alt {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('app.footer.address') }}
                        </h5>
                        <p class="mb-0">{{ setting('contact_address_' . app()->getLocale()) }}</p>
                    </div>
                    @endif


                    <!-- Social Media -->
                    <div class="mt-4">
                        <h5 class="text-primary mb-3">{{ __('app.contact.follow_us') }}</h5>
                        <div class="social-links">
                            @if(setting('facebook_url'))
                                <a href="{{ setting('facebook_url') }}" class="social-link" title="{{ __('app.social.facebook') }}" target="_blank" rel="noopener">
                                    <i class="bi bi-facebook"></i>
                                </a>
                            @endif
                            @if(setting('twitter_url'))
                                <a href="{{ setting('twitter_url') }}" class="social-link" title="{{ __('app.social.x') }}" target="_blank" rel="noopener">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                    </svg>
                                </a>
                            @endif
                            @if(setting('instagram_url'))
                                <a href="{{ setting('instagram_url') }}" class="social-link" title="{{ __('app.social.instagram') }}" target="_blank" rel="noopener">
                                    <i class="bi bi-instagram"></i>
                                </a>
                            @endif
                            @if(setting('whatsapp_number'))
                                <a href="https://wa.me/{{ setting('whatsapp_number') }}" class="social-link" title="{{ __('app.social.whatsapp') }}" target="_blank" rel="noopener">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="contact-card contact-form-card">
                    <div class="contact-icon">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <h3 class="mb-4">{{ __('app.contact.send_message') }}</h3>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" id="contactForm">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label">{{ __('app.contact.your_name') }} *</label>
                                <input type="text" class="form-control form-control-modern @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">{{ __('app.contact.your_email') }} *</label>
                                <input type="email" class="form-control form-control-modern @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="subject" class="form-label">{{ __('app.contact.subject') }} *</label>
                                <input type="text" class="form-control form-control-modern @error('subject') is-invalid @enderror" 
                                       id="subject" name="subject" value="{{ old('subject') }}" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label">{{ __('app.contact.message') }} *</label>
                                <textarea class="form-control form-control-modern @error('message') is-invalid @enderror" 
                                          id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-contact">
                                    <i class="bi bi-send {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                    {{ __('app.contact.send_button') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('contactForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const subject = document.getElementById('subject').value.trim();
            const message = document.getElementById('message').value.trim();

            if (!name) {
                e.preventDefault();
                alert('{{ __("app.contact.name_required") }}');
                return;
            }

            if (!email || !email.includes('@')) {
                e.preventDefault();
                alert('{{ __("app.contact.email_invalid") }}');
                return;
            }

            if (!subject) {
                e.preventDefault();
                alert('{{ __("app.contact.subject_required") }}');
                return;
            }

            if (!message) {
                e.preventDefault();
                alert('{{ __("app.contact.message_required") }}');
                return;
            }
        });
    }

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endpush
