@extends('layouts.app')

@section('title', __('app.privacy.title') . ' - ' . __('app.hero.title'))
@section('description', __('app.privacy.subtitle'))

@push('styles')
<style>
    .privacy-header {
        background: linear-gradient(135deg, #1a1a1a, #343a40);
        color: white;
        padding: 5rem 0 3rem;
        position: relative;
        overflow: hidden;
    }

    .privacy-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="legal" width="50" height="50" patternUnits="userSpaceOnUse"><path d="M25 10 L35 25 L25 40 L15 25 Z" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23legal)"/></svg>');
        opacity: 0.3;
    }

    .privacy-content {
        background: var(--voltronix-light);
        padding: 5rem 0;
    }

    .privacy-container {
        background: white;
        border-radius: 25px;
        padding: 3rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 127, 255, 0.1);
        max-width: 900px;
        margin: 0 auto;
    }

    .privacy-section {
        margin-bottom: 3rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #e9ecef;
    }

    .privacy-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .privacy-section h3 {
        color: var(--voltronix-primary);
        font-family: 'Orbitron', sans-serif;
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--voltronix-primary);
        display: inline-block;
    }

    .privacy-section h4 {
        color: #343a40;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }

    .privacy-section p {
        line-height: 1.8;
        color: #495057;
        margin-bottom: 1.5rem;
        text-align: justify;
    }

    .privacy-section ul {
        padding-left: 2rem;
        margin-bottom: 1.5rem;
    }

    .privacy-section li {
        line-height: 1.8;
        color: #495057;
        margin-bottom: 0.5rem;
        position: relative;
    }

    .privacy-section li::marker {
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

    .privacy-icon {
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
    [dir="rtl"] .privacy-section {
        text-align: right;
    }

    [dir="rtl"] .privacy-section p {
        text-align: justify;
    }

    [dir="rtl"] .privacy-section ul {
        padding-right: 2rem;
        padding-left: 0;
    }

    [dir="rtl"] .last-updated {
        border-right: 4px solid var(--voltronix-primary);
        border-left: none;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .privacy-container {
            padding: 2rem 1.5rem;
            margin: 0 1rem;
        }

        .privacy-section h3 {
            font-size: 1.25rem;
        }

        .privacy-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Privacy Header -->
<section class="privacy-header">
    <div class="volt-container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-voltronix">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}">{{ __('app.nav.home') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('app.privacy.title') }}
                        </li>
                    </ol>
                </nav>
                
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <h1 class="display-4 fw-bold mb-3">
                            <i class="bi bi-shield-lock {{ app()->getLocale() == 'ar' ? 'ms-3' : 'me-3' }}"></i>{{ __('app.privacy.title') }}
                        </h1>
                        <p class="lead mb-0">{{ __('app.privacy.subtitle') }}</p>
                    </div>
                    <div class="col-lg-5 text-lg-end">
                        <div class="privacy-icon d-inline-flex" style="margin-bottom: 0;">
                            <i class="bi bi-eye"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Privacy Content -->
<section class="privacy-content">
    <div class="volt-container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11">
                <div class="privacy-container">
                    <!-- Last Updated -->
                    <div class="last-updated">
                        <strong>{{ __('app.terms.last_updated') }}: {{ now()->format('F d, Y') }}</strong>
                    </div>

                    <!-- Introduction -->
                    <div class="text-center mb-5">
                        <p class="lead">{{ __('app.privacy.intro') }}</p>
                    </div>

                    <!-- 1. Information We Collect -->
                    <div class="privacy-section">
                        <h3>{{ __('app.privacy.sections.1.title') }}</h3>
                        <p>{{ __('app.privacy.sections.1.content') }}</p>
                        <ul>
                            @foreach(__('app.privacy.sections.1.list') as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- 2. How We Use Your Information -->
                    <div class="privacy-section">
                        <h3>{{ __('app.privacy.sections.2.title') }}</h3>
                        <p>{{ __('app.privacy.sections.2.content') }}</p>
                        <ul>
                            @foreach(__('app.privacy.sections.2.list') as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- 3. Cookies and Tracking -->
                    <div class="privacy-section">
                        <h3>{{ __('app.privacy.sections.3.title') }}</h3>
                        <p>{{ __('app.privacy.sections.3.content') }}</p>
                        <p>{{ __('app.privacy.sections.3.content_2') }}</p>
                    </div>

                    <!-- 4. Data Security -->
                    <div class="privacy-section">
                        <h3>{{ __('app.privacy.sections.4.title') }}</h3>
                        <p>{{ __('app.privacy.sections.4.content') }}</p>
                        <p>{{ __('app.privacy.sections.4.content_2') }}</p>
                    </div>

                    <!-- 5. Third-Party Disclosure -->
                    <div class="privacy-section">
                        <h3>{{ __('app.privacy.sections.5.title') }}</h3>
                        <p>{{ __('app.privacy.sections.5.content') }}</p>
                    </div>

                    <!-- 6. Your Rights -->
                    <div class="privacy-section">
                        <h3>{{ __('app.privacy.sections.6.title') }}</h3>
                        <p>{{ __('app.privacy.sections.6.content') }}</p>
                        <ul>
                            @foreach(__('app.privacy.sections.6.list') as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Contact Information -->
                    <div class="contact-box">
                        <h4>{{ __('app.privacy.contact.title') }}</h4>
                        <p class="mb-0">
                            {{ __('app.privacy.contact.text') }}
                            @if(setting('contact_email'))
                                <br>
                                <a href="mailto:{{ setting('contact_email') }}">{{ setting('contact_email') }}</a>
                            @endif
                        </p>
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
    // Reading progress indicator
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
