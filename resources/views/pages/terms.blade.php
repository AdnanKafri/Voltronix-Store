@extends('layouts.app')

@section('title', __('app.terms.title') . ' - ' . __('app.hero.title'))
@section('description', __('app.terms.description'))

@push('styles')
<style>
    .terms-header {
        background: linear-gradient(135deg, #1a1a1a, #343a40);
        color: white;
        padding: 5rem 0 3rem;
        position: relative;
        overflow: hidden;
    }

    .terms-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="legal" width="50" height="50" patternUnits="userSpaceOnUse"><path d="M25 10 L35 25 L25 40 L15 25 Z" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23legal)"/></svg>');
        opacity: 0.3;
    }

    .terms-content {
        background: var(--voltronix-light);
        padding: 5rem 0;
    }

    .terms-container {
        background: white;
        border-radius: 25px;
        padding: 3rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 127, 255, 0.1);
        max-width: 900px;
        margin: 0 auto;
    }

    .terms-section {
        margin-bottom: 3rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #e9ecef;
    }

    .terms-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .terms-section h3 {
        color: var(--voltronix-primary);
        font-family: 'Orbitron', sans-serif;
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--voltronix-primary);
        display: inline-block;
    }

    .terms-section p {
        line-height: 1.8;
        color: #495057;
        margin-bottom: 1.5rem;
        text-align: justify;
    }

    .terms-section ul {
        padding-left: 2rem;
        margin-bottom: 1.5rem;
    }

    .terms-section li {
        line-height: 1.8;
        color: #495057;
        margin-bottom: 0.5rem;
        position: relative;
    }

    .terms-section li::marker {
        color: var(--voltronix-primary);
        font-weight: bold;
    }

    .last-updated {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid var(--voltronix-primary);
        text-align: center;
    }

    .contact-box {
        background: var(--voltronix-gradient);
        color: white;
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        margin-top: 3rem;
    }

    .contact-box h4 {
        font-family: 'Orbitron', sans-serif;
        margin-bottom: 1rem;
    }

    .contact-box a {
        color: white;
        text-decoration: underline;
        font-weight: 600;
    }

    .contact-box a:hover {
        color: #f8f9fa;
        text-decoration: none;
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

    .legal-icon {
        width: 80px;
        height: 80px;
        background: var(--voltronix-gradient);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        margin: 0 auto 2rem;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    /* RTL Support */
    [dir="rtl"] .terms-section {
        text-align: right;
    }

    [dir="rtl"] .terms-section p {
        text-align: justify;
    }

    [dir="rtl"] .terms-section ul {
        padding-right: 2rem;
        padding-left: 0;
    }

    [dir="rtl"] .last-updated {
        border-right: 4px solid var(--voltronix-primary);
        border-left: none;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .terms-container {
            padding: 2rem 1.5rem;
            margin: 0 1rem;
        }

        .terms-section h3 {
            font-size: 1.25rem;
        }

        .legal-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Terms Header -->
<section class="terms-header">
    <div class="volt-container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-voltronix">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}">{{ __('app.nav.home') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('app.terms.title') }}
                        </li>
                    </ol>
                </nav>
                
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <h1 class="display-4 fw-bold mb-3">
                            <i class="bi bi-file-text {{ app()->getLocale() == 'ar' ? 'ms-3' : 'me-3' }}"></i>{{ __('app.terms.title') }}
                        </h1>
                        <p class="lead mb-0">{{ __('app.terms.subtitle') }}</p>
                    </div>
                    <div class="col-lg-5 text-lg-end">
                        <div class="legal-icon d-inline-flex" style="margin-bottom: 0;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Terms Content -->
<section class="terms-content">
    <div class="volt-container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11">
                <div class="terms-container">
            <!-- Last Updated -->
            <div class="last-updated">
                <strong>{{ __('app.terms.last_updated') }}: {{ now()->format('F d, Y') }}</strong>
            </div>

            <!-- Introduction -->
            <div class="text-center mb-5">
                <p class="lead">{{ __('app.terms.description') }}</p>
            </div>

            <!-- 1. Acceptance of Terms -->
            <div class="terms-section">
                <h3>{{ __('app.terms.acceptance.title') }}</h3>
                <p>{{ __('app.terms.acceptance.content') }}</p>
            </div>

            <!-- 2. Use License -->
            <div class="terms-section">
                <h3>{{ __('app.terms.use_license.title') }}</h3>
                <p>{{ __('app.terms.use_license.content') }}</p>
                
                <p><strong>{{ __('app.terms.use_license.restrictions') }}</strong></p>
                <ul>
                    @foreach(__('app.terms.use_license.restrictions_list') as $restriction)
                        <li>{{ $restriction }}</li>
                    @endforeach
                </ul>
            </div>

            <!-- 3. Disclaimer -->
            <div class="terms-section">
                <h3>{{ __('app.terms.disclaimer.title') }}</h3>
                <p>{{ __('app.terms.disclaimer.content') }}</p>
            </div>

            <!-- 4. Limitations -->
            <div class="terms-section">
                <h3>{{ __('app.terms.limitations.title') }}</h3>
                <p>{{ __('app.terms.limitations.content') }}</p>
            </div>

            <!-- 5. Privacy Policy -->
            <div class="terms-section">
                <h3>{{ __('app.terms.privacy.title') }}</h3>
                <p>{{ __('app.terms.privacy.content') }}</p>
            </div>

            <!-- 6. Revisions and Errata -->
            <div class="terms-section">
                <h3>{{ __('app.terms.modifications.title') }}</h3>
                <p>{{ __('app.terms.modifications.content') }}</p>
            </div>

            <!-- 7. Governing Law -->
            <div class="terms-section">
                <h3>{{ __('app.terms.governing_law.title') }}</h3>
                <p>{{ __('app.terms.governing_law.content') }}</p>
            </div>

            <!-- Contact Information -->
            <div class="contact-box">
                <h4>{{ __('app.contact.get_in_touch') }}</h4>
                <p class="mb-0">
                    {{ __('app.terms.contact_info') }}
                    @if(setting('contact_email'))
                        <br>
                        <a href="mailto:{{ setting('contact_email') }}">{{ setting('contact_email') }}</a>
                    @endif
                </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for anchor links
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add reading progress indicator
    const progressBar = document.createElement('div');
    progressBar.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 3px;
        background: linear-gradient(90deg, #007fff, #23efff);
        z-index: 9999;
        transition: width 0.3s ease;
    `;
    document.body.appendChild(progressBar);

    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset;
        const docHeight = document.body.scrollHeight - window.innerHeight;
        const scrollPercent = (scrollTop / docHeight) * 100;
        progressBar.style.width = scrollPercent + '%';
    });
});
</script>
@endpush
