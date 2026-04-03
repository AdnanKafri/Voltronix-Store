<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- SEO Meta Tags -->
    <x-seo-meta 
        :title="isset($title) ? $title : ''"
        :description="isset($description) ? $description : ''"
        :keywords="isset($keywords) ? $keywords : ''"
        :image="isset($image) ? $image : ''"
        :url="isset($url) ? $url : ''"
        :type="isset($type) ? $type : 'website'"
        :canonical-url="isset($canonicalUrl) ? $canonicalUrl : ''"
        :no-index="isset($noindex) ? $noindex : false"
        :structured-data="isset($structuredData) ? $structuredData : null"
    />
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo.png') }}">
    <link rel="mask-icon" href="{{ asset('images/logo.png') }}" color="#007fff">
    
    <!-- Web App Manifest -->
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    
    <!-- Theme Color -->
    <meta name="theme-color" content="#007fff">
    <meta name="msapplication-TileColor" content="#007fff">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- ✅ Google Analytics & SEO Integration -->
    <x-ga-script />
    

    <!-- Bootstrap CSS -->
    @if(app()->getLocale() == 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    @if(app()->getLocale() == 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@300;400;500;600;700&family=Tajawal:wght@500;700;800&display=swap" rel="stylesheet">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Loading System CSS -->
    <link href="{{ asset('css/loading.css') }}" rel="stylesheet">
    <link href="{{ asset('css/layout-premium.css') }}" rel="stylesheet">
    
    <!-- Global Currency Update Script -->
    <script>
    // Global currency update function
    function updateAllPrices(newCurrency, newRate) {
        const priceElements = document.querySelectorAll('[data-price]');
        
        priceElements.forEach(element => {
            const basePrice = parseFloat(element.dataset.price);
            const convertedPrice = basePrice * newRate;
            const formattedPrice = formatCurrency(convertedPrice, newCurrency);
            
            // Update the displayed price
            element.textContent = formattedPrice;
            
            // Add animation effect
            element.style.transform = 'scale(1.05)';
            element.style.color = 'var(--voltronix-primary)';
            
            setTimeout(() => {
                element.style.transform = 'scale(1)';
                element.style.color = '';
            }, 300);
        });
    }
    
    // Format currency helper
    function formatCurrency(amount, currency) {
        const currencySymbols = {
            'USD': '$',
            'EUR': '€',
            'GBP': '£',
            'SAR': 'ر.س',
            'EGP': 'ج.م',
            'AED': 'د.إ'
        };
        
        const symbol = currencySymbols[currency] || currency;
        return symbol + ' ' + amount.toFixed(2);
    }
    </script>
    
    <!-- Cart System Assets -->
    <link href="{{ asset('css/cart.css') }}" rel="stylesheet">
    <script src="{{ asset('js/cart.js') }}" defer></script>
    
    <!-- Premium Navbar Styles -->
    <link href="{{ asset('css/navbar-premium.css') }}" rel="stylesheet">

    <style>
        /* CSS Variables - Radical New Design System */
        :root {
            --voltronix-primary: #007fff;
            --voltronix-secondary: #23efff;
            --voltronix-accent: #0a0a0a;
            --voltronix-light: #f0f4ff;
            --voltronix-dark: #0d1421;
            --voltronix-gradient: linear-gradient(135deg, #007fff 0%, #23efff 50%, #00d4ff 100%);
            --voltronix-gradient-reverse: linear-gradient(135deg, #00d4ff 0%, #23efff 50%, #007fff 100%);
            --voltronix-gradient-dark: linear-gradient(135deg, #0d1421 0%, #1a1a2e 100%);
            --voltronix-gradient-light: linear-gradient(135deg, #f0f4ff 0%, #ffffff 100%);
            --navbar-height-desktop: 75px;
            --navbar-height-mobile: 60px;
            --border-radius-sm: 12px;
            --border-radius-md: 20px;
            --border-radius-lg: 30px;
            --border-radius-xl: 40px;
            --shadow-sm: 0 4px 20px rgba(0, 127, 255, 0.1);
            --shadow-md: 0 8px 30px rgba(0, 127, 255, 0.15);
            --shadow-lg: 0 15px 50px rgba(0, 127, 255, 0.2);
            --shadow-xl: 0 25px 80px rgba(0, 127, 255, 0.25);
        }
        
        /* CRITICAL: Prevent horizontal overflow and ensure perfect viewport containment */
        * {
            box-sizing: border-box;
        }
        
        html, body {
            overflow-x: hidden;
            max-width: 100vw;
            position: relative;
            margin: 0;
            padding: 0;
        }
        
        /* Fix Bootstrap container overflow issues */
        .container, .container-fluid {
            max-width: 100%;
            padding-left: 15px;
            padding-right: 15px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .row {
            margin-left: -15px;
            margin-right: -15px;
            max-width: calc(100% + 30px);
        }
        
        .col, [class*="col-"] {
            padding-left: 15px;
            padding-right: 15px;
            max-width: 100%;
        }
        
        /* Ensure all sections stay within viewport */
        section, .section, main {
            max-width: 100vw;
            overflow-x: hidden;
        }
        
        /* Fix any potential width issues with media elements */
        img, video, iframe, canvas {
            max-width: 100%;
            height: auto;
        }
        
        /* Prevent text overflow */
        .text-nowrap {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Fix navbar potential overflow */
        .navbar {
            max-width: 100vw;
            overflow-x: hidden;
        }
        
        .navbar-nav {
            max-width: 100%;
        }
        
        /* Fix hero section potential overflow */
        .hero-slider, .hero-section {
            max-width: 100vw;
            overflow-x: hidden;
        }
        
        body {
            font-family: {{ app()->getLocale() == 'ar' ? "'Noto Sans Arabic', sans-serif" : "'Poppins', sans-serif" }};
            background-color: var(--voltronix-light);
            margin: 0;
            padding: 0;
            line-height: 1.6;
            color: var(--voltronix-accent);
        }
        
        /* Prevent mobile menu body scroll */
        body.mobile-menu-open {
            overflow: hidden;
        }
        
        /* Hero Section */
        .hero-section {
            background: var(--voltronix-bg-dark);
            position: relative;
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4rem 0;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 127, 255, 0.1), rgba(35, 239, 255, 0.05));
            z-index: 1;
        }
        
        .hero-section .container {
            position: relative;
            z-index: 2;
        }
        
        /* RADICAL NEW NAVBAR DESIGN */
        .navbar-voltronix {
            background: var(--voltronix-gradient-dark) !important;
            backdrop-filter: blur(20px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 0.3rem 0;
            box-shadow: var(--shadow-lg);
            border-bottom: 2px solid rgba(0, 127, 255, 0.2);
            position: relative;
            width: 100%;
            top: 0;
            z-index: 1050;
            height: var(--navbar-height-desktop);
        }
        
        .navbar-voltronix.scrolled {
            padding: 0.2rem 0;
            background: rgba(13, 20, 33, 0.98) !important;
        }
        
        .navbar-brand-voltronix {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            font-weight: 900;
            color: white !important;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .navbar-logo {
            height: 95px;
            width: auto;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            filter: drop-shadow(0 6px 20px rgba(0, 127, 255, 0.5));
        }
        
        @media (max-width: 768px) {
            .navbar-voltronix {
                height: var(--navbar-height-mobile);
            }
            
            .navbar-logo {
                height: 75px;
            }
        }
        
        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-5px) rotate(1deg); }
        }
        
        .navbar-brand-voltronix:hover .navbar-logo {
            transform: scale(1.1) rotate(5deg);
            filter: drop-shadow(0 12px 35px rgba(0, 127, 255, 0.6));
        }
        
        .navbar-brand-text {
            background: var(--voltronix-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .nav-link-voltronix {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.6rem 1.2rem !important;
            margin: 0 0.3rem;
            border-radius: var(--border-radius-md);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 127, 255, 0.2);
        }
        
        .nav-link-voltronix:hover,
        .nav-link-voltronix.active {
            color: white !important;
            background: var(--voltronix-gradient);
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
            border-color: var(--voltronix-secondary);
        }
        
        .nav-link-voltronix::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--voltronix-gradient);
            border-radius: var(--border-radius-lg);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: -1;
        }
        
        .nav-link-voltronix:hover::before,
        .nav-link-voltronix.active::before {
            opacity: 1;
        }
        
        /* RADICAL NEW BUTTON SYSTEM */
        .btn-voltronix-primary {
            background: var(--voltronix-gradient);
            border: 2px solid transparent;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            padding: 1.2rem 3rem;
            border-radius: var(--border-radius-xl);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }
        
        .btn-voltronix-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s ease;
        }
        
        .btn-voltronix-primary:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: var(--shadow-xl);
            color: white;
            border-color: var(--voltronix-secondary);
        }
        
        .btn-voltronix-primary:hover::before {
            left: 100%;
        }
        
        .btn-voltronix-secondary {
            background: transparent;
            border: 2px solid var(--voltronix-primary);
            color: var(--voltronix-primary);
            font-weight: 700;
            font-size: 1.1rem;
            padding: 1.2rem 3rem;
            border-radius: var(--border-radius-xl);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            position: relative;
            overflow: hidden;
        }
        
        .btn-voltronix-secondary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: var(--voltronix-gradient);
            transition: width 0.4s ease;
            z-index: -1;
        }
        
        .btn-voltronix-secondary:hover {
            color: white;
            border-color: var(--voltronix-secondary);
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }
        
        .btn-voltronix-secondary:hover::before {
            width: 100%;
        }
        
        .btn-voltronix-outline {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 1.2rem 3rem;
            border-radius: var(--border-radius-xl);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            backdrop-filter: blur(10px);
        }
        
        .btn-voltronix-outline:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--voltronix-secondary);
            color: white;
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }
        
        /* Auth Pages Styles */
        .auth-container {
            min-height: calc(100vh - var(--navbar-height-desktop));
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            /* padding-top: calc(var(--navbar-height-desktop) + 2rem); - REMOVED */
            position: relative;
            overflow: hidden;
        }
        
        .auth-card {
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            margin: 0 1rem;
        }
        
        .auth-header {
            background: linear-gradient(45deg, var(--voltronix-primary), #0056b3);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .auth-body {
            padding: 2rem;
        }
        
        .form-control-modern {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .form-control-modern:focus {
            border-color: var(--voltronix-primary);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            background: white;
        }
        
        .form-label-modern {
            font-weight: 600;
            color: var(--voltronix-accent);
            margin-bottom: 0.5rem;
        }
        
        /* Profile Page Styles */
        .profile-nav {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            margin-bottom: 2rem;
        }
        
        .profile-nav .nav-link {
            border-radius: 10px;
            font-weight: 500;
            color: var(--voltronix-accent);
            transition: all 0.3s ease;
        }
        
        .profile-nav .nav-link.active {
            background: var(--voltronix-primary);
            color: white;
        }
        
        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        /* Enhanced Card Designs */
        .card-voltronix {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border: 1px solid rgba(0, 127, 255, 0.1);
            border-radius: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
        }
        
        .card-voltronix::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--voltronix-gradient);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }
        
        .card-voltronix:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 50px rgba(0, 127, 255, 0.15);
            border-color: var(--voltronix-primary);
        }
        
        .card-voltronix:hover::before {
            transform: scaleX(1);
        }
        
        .card-voltronix .card-img-top {
            transition: transform 0.4s ease;
            border-radius: 25px 25px 0 0;
            height: 200px;
            object-fit: cover;
        }
        
        .card-voltronix:hover .card-img-top {
            transform: scale(1.05);
        }
        
        .card-voltronix .card-body {
            padding: 2rem;
            position: relative;
        }
        
        .card-voltronix .card-title {
            font-family: 'Orbitron', sans-serif;
            font-weight: 600;
            color: var(--voltronix-accent);
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        
        .card-voltronix .card-text {
            color: #6c757d;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        
        .card-voltronix .card-footer {
            background: transparent;
            border: none;
            padding: 0 2rem 2rem;
        }
        
        .badge-voltronix {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            z-index: 10;
        }
        
        .badge-new {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            animation: pulse 2s infinite;
        }
        
        .badge-featured {
            background: linear-gradient(45deg, #fd7e14, #ffc107);
            color: white;
        }
        
        .badge-sale {
            background: linear-gradient(45deg, #dc3545, #e83e8c);
            color: white;
            animation: bounce 1s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-3px); }
            60% { transform: translateY(-2px); }
        }
        
        .price-display {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--voltronix-primary);
            margin: 1rem 0;
        }
        
        .price-original {
            text-decoration: line-through;
            color: #6c757d;
            font-size: 1rem;
            font-weight: 500;
            margin-right: 0.5rem;
        }
        
        .price-savings {
            background: linear-gradient(45deg, #dc3545, #e83e8c);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }
        
        /* Footer and common UI styles have been moved to public/css/layout-premium.css */
        
        .btn-voltronix-outline {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            font-weight: 500;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .btn-voltronix-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--voltronix-secondary);
            color: white;
            transform: translateY(-1px);
        }
        
        /* Enhanced Form Styles */
        .form-control-voltronix {
            border: 2px solid rgba(0, 127, 255, 0.1);
            border-radius: 15px;
            padding: 0.75rem 1.25rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }
        
        .form-control-voltronix:focus {
            border-color: var(--voltronix-primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 127, 255, 0.25);
            background: white;
        }
        
        .form-label-voltronix {
            font-weight: 600;
            color: var(--voltronix-accent);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        
        /* Enhanced Alert Styles */
        .alert-voltronix {
            border: none;
            border-radius: 15px;
            padding: 1rem 1.5rem;
            font-weight: 500;
            backdrop-filter: blur(10px);
        }
        
        .alert-voltronix.alert-success {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1));
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-voltronix.alert-danger {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(232, 62, 140, 0.1));
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .alert-voltronix.alert-info {
            background: linear-gradient(135deg, rgba(0, 127, 255, 0.1), rgba(35, 239, 255, 0.1));
            color: #0c5460;
            border-left: 4px solid var(--voltronix-primary);
        }
        
        /* Glassmorphism Effects */
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
        }
        
        .glass-effect-dark {
            background: rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
        }
        
        /* Enhanced Breadcrumb Styles */
        .breadcrumb {
            background: rgba(0, 127, 255, 0.1);
            border-radius: 15px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 127, 255, 0.2);
        }
        
        .breadcrumb-item {
            font-weight: 600;
            color: var(--voltronix-accent);
        }
        
        .breadcrumb-item a {
            color: var(--voltronix-primary);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .breadcrumb-item a:hover {
            color: var(--voltronix-secondary);
        }
        
        .breadcrumb-item.active {
            color: var(--voltronix-accent);
            font-weight: 700;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            color: var(--voltronix-primary);
            font-weight: bold;
            margin: 0 0.5rem;
        }
        
        /* Section Title Styling */
        .section-title,
        h1, h2, h3.title-orbitron,
        .hero-title,
        .page-title {
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
        }
        
        .section-title {
            color: var(--voltronix-accent);
            margin-bottom: 3rem;
            text-align: center;
            position: relative;
            padding-bottom: 1rem;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--voltronix-gradient);
            border-radius: 2px;
        }
        
        /* Main Content Spacing - NO WHITE GAP */
        main {
            padding-top: 0;
            margin-top: 0;
        }
        
        /* First section should start directly under navbar */
        main > section:first-child,
        main > .hero-slider {
            /* padding-top: var(--navbar-height-desktop); - REMOVED for relative navbar */
            padding-top: 0;
        }
        
        /* Enhanced Pagination */
        .pagination {
            justify-content: center;
            margin: 3rem 0;
        }
        
        .page-link {
            border: 2px solid rgba(0, 127, 255, 0.1);
            color: var(--voltronix-primary);
            font-weight: 500;
            padding: 0.75rem 1rem;
            margin: 0 0.25rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: white;
        }
        
        .page-link:hover {
            background: var(--voltronix-gradient);
            border-color: var(--voltronix-primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 127, 255, 0.3);
        }
        
        .page-item.active .page-link {
            background: var(--voltronix-gradient);
            border-color: var(--voltronix-primary);
            color: white;
            box-shadow: 0 5px 15px rgba(0, 127, 255, 0.3);
        }
        
        .page-item.disabled .page-link {
            color: #6c757d;
            background: #f8f9fa;
            border-color: #dee2e6;
        }
        
        /* Enhanced Search Components */
        .search-container {
            position: relative;
            max-width: 500px;
            margin: 0 auto 2rem;
        }
        
        .search-input {
            width: 100%;
            padding: 1rem 1.5rem 1rem 3.5rem;
            border: 2px solid rgba(0, 127, 255, 0.1);
            border-radius: 25px;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--voltronix-primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 127, 255, 0.25);
            background: white;
        }
        
        .search-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--voltronix-primary);
            font-size: 1.1rem;
        }
        
        .search-btn {
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            background: var(--voltronix-gradient);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .search-btn:hover {
            transform: translateY(-50%) scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 127, 255, 0.3);
        }
        
        /* Enhanced Filter Components */
        .filter-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }
        
        .filter-title {
            font-family: 'Orbitron', sans-serif;
            font-weight: 600;
            color: var(--voltronix-accent);
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }
        
        .filter-group {
            margin-bottom: 1.5rem;
        }
        
        .filter-label {
            font-weight: 500;
            color: var(--voltronix-accent);
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .filter-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid rgba(0, 127, 255, 0.1);
            border-radius: 12px;
            background: white;
            color: var(--voltronix-accent);
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .filter-select:focus {
            outline: none;
            border-color: var(--voltronix-primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 127, 255, 0.25);
        }
        
        /* Enhanced Loading States */
        .loading-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 4rem 2rem;
        }
        
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(0, 127, 255, 0.1);
            border-left: 4px solid var(--voltronix-primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .loading-text {
            margin-left: 1rem;
            color: var(--voltronix-primary);
            font-weight: 500;
        }
        
        /* Enhanced Empty States */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }
        
        .empty-state-icon {
            font-size: 4rem;
            color: var(--voltronix-primary);
            opacity: 0.6;
            margin-bottom: 1.5rem;
        }
        
        .empty-state-title {
            font-family: 'Orbitron', sans-serif;
            font-weight: 600;
            color: var(--voltronix-accent);
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }
        
        .empty-state-text {
            color: #6c757d;
            margin-bottom: 2rem;
            font-size: 1rem;
            line-height: 1.6;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar-brand-voltronix {
                font-size: 1.5rem;
            }
            
            .navbar-logo {
                height: 55px;
            }
            
            .auth-container {
                min-height: calc(100vh - var(--navbar-height-mobile));
                padding: 1rem 0;
                /* padding-top: calc(var(--navbar-height-mobile) + 1rem); - REMOVED */
            }
            
            .auth-card {
                margin: 0 0.5rem;
                border-radius: 15px;
            }
            
            .auth-header, .auth-body {
                padding: 1.5rem;
            }
            
            main {
                padding-top: 0;
                margin-top: 0;
            }
            
            main > section:first-child,
            main > .hero-slider {
                padding-top: 0;
            }
            
            .card-voltronix {
                border-radius: 20px;
                margin-bottom: 1.5rem;
            }
            
            .card-voltronix:hover {
                transform: translateY(-6px);
            }
            
            .card-voltronix .card-body {
                padding: 1.5rem;
            }
            
            .search-container {
                max-width: 100%;
                margin-bottom: 1.5rem;
            }
            
            .search-input {
                padding: 0.75rem 1rem 0.75rem 3rem;
                font-size: 0.95rem;
            }
            
            .filter-container {
                padding: 1.5rem;
                border-radius: 15px;
            }
            
            .results-summary {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
                text-align: center;
            }
            
            .pagination {
                margin: 2rem 0;
            }
            
            .page-link {
                padding: 0.5rem 0.75rem;
                margin: 0 0.1rem;
                font-size: 0.9rem;
            }
            
            .btn-voltronix-primary,
            .btn-voltronix-secondary {
                padding: 0.6rem 1.5rem;
                font-size: 0.9rem;
            }
            
            .footer-voltronix {
                padding: 1.5rem 0 1rem;
                margin-top: 2rem;
            }
            
            .footer-logo {
                height: 32px;
            }

            .footer-brand {
                justify-content: center;
                text-align: center;
            }

            .footer-brand-text {
                font-size: 1rem;
            }

            .footer-section {
                margin-bottom: 1rem;
                text-align: center;
            }

            .footer-title {
                font-size: 0.9rem;
            }

            .footer-links a {
                justify-content: center;
                font-size: 0.85rem;
            }

            .social-links {
                justify-content: center;
                margin-top: 0.75rem;
            }

            .contact-info {
                align-items: center;
            }

            .footer-divider {
                margin: 1rem 0 0.75rem;
            }
            
            .social-links {
                justify-content: center;
                margin-top: 1rem;
            }
            
            .contact-info {
                text-align: center;
            }
            
            .title-orbitron {
                font-size: 2rem;
            }
            
            .subtitle-orbitron {
                font-size: 1.25rem;
            }
        }

        /* ========================================
           GLOBAL VOLTRONIX SPINNER SYSTEM
           ======================================== */
        
        /* Global Loading Overlay */
        .voltronix-loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(13, 20, 33, 0.85);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            transition: opacity 0.3s ease;
        }

        .voltronix-loading-overlay.show {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        /* Modern Spinner Container */
        .voltronix-spinner-container {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.95), rgba(248, 249, 250, 0.9));
            border-radius: var(--border-radius-lg);
            padding: 3rem 2.5rem;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.25),
                0 15px 35px rgba(0, 127, 255, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            text-align: center;
            min-width: 280px;
            border: 2px solid rgba(0, 127, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .voltronix-spinner-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 127, 255, 0.1), transparent);
            animation: shimmer 3s ease-in-out infinite;
        }

        /* Spinner Content Layout */
        .voltronix-spinner-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
            position: relative;
            z-index: 2;
        }

        /* Modern Spinner Animation */
        .voltronix-spinner {
            width: 60px;
            height: 60px;
            position: relative;
        }

        .voltronix-spinner-circle {
            width: 100%;
            height: 100%;
            border: 4px solid rgba(0, 127, 255, 0.2);
            border-top: 4px solid var(--voltronix-primary);
            border-radius: 50%;
            animation: voltronixSpin 1.2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
            position: relative;
        }

        .voltronix-spinner-circle::after {
            content: '';
            position: absolute;
            top: -4px;
            right: -4px;
            width: 12px;
            height: 12px;
            background: var(--voltronix-gradient);
            border-radius: 50%;
            box-shadow: 0 0 15px rgba(0, 127, 255, 0.6);
        }

        /* Static Text with Subtle Animation */
        .voltronix-spinner-text {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--voltronix-accent);
            animation: voltronixTextPulse 2.5s ease-in-out infinite;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .voltronix-spinner-subtext {
            font-size: 0.9rem;
            color: #6c757d;
            margin: 0;
            opacity: 0.8;
            animation: voltronixTextPulse 2.5s ease-in-out infinite 0.5s;
        }

        /* Alternative Dot Spinner */
        .voltronix-dots-spinner {
            display: flex;
            gap: 8px;
            align-items: center;
            justify-content: center;
        }

        .voltronix-dot {
            width: 12px;
            height: 12px;
            background: var(--voltronix-gradient);
            border-radius: 50%;
            animation: voltronixDotBounce 1.4s ease-in-out infinite;
        }

        .voltronix-dot:nth-child(1) { animation-delay: 0s; }
        .voltronix-dot:nth-child(2) { animation-delay: 0.2s; }
        .voltronix-dot:nth-child(3) { animation-delay: 0.4s; }

        /* Animations */
        @keyframes voltronixSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes voltronixTextPulse {
            0%, 100% { opacity: 0.7; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.02); }
        }

        @keyframes voltronixDotBounce {
            0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
            40% { transform: scale(1.2); opacity: 1; }
        }

        @keyframes shimmer {
            0% { left: -100%; }
            50% { left: 100%; }
            100% { left: 100%; }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .voltronix-spinner-container {
                min-width: 240px;
                padding: 2.5rem 2rem;
            }

            .voltronix-spinner {
                width: 50px;
                height: 50px;
            }

            .voltronix-spinner-text {
                font-size: 1rem;
            }

            .voltronix-spinner-subtext {
                font-size: 0.85rem;
            }
        }

        /* RTL Support */
        [dir="rtl"] .voltronix-spinner-container::before {
            animation: shimmerRTL 3s ease-in-out infinite;
        }

        @keyframes shimmerRTL {
            0% { right: -100%; left: auto; }
            50% { right: 100%; left: auto; }
            100% { right: 100%; left: auto; }
        }

        /* Accessibility */
        @media (prefers-reduced-motion: reduce) {
            .voltronix-spinner-circle {
                animation: voltronixSpin 2s linear infinite;
            }
            
            .voltronix-spinner-text,
            .voltronix-spinner-subtext {
                animation: none;
                opacity: 0.9;
            }
            
            .voltronix-dot {
                animation: none;
                opacity: 0.8;
            }
        }
    </style>
    
    <style>
        /* ========================================
           VOLTRONIX ELECTRIC HEADER SYSTEM
           ======================================== */
        
        /* .voltronix-header style moved to navbar-premium.css */
        
        .electric-border-top,
        .electric-border-bottom {
            position: absolute;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #007fff, #23efff, #007fff, transparent);
            background-size: 200% 100%;
            animation: electricBorderFlow 4s linear infinite;
        }
        
        .electric-border-top {
            top: 0;
            box-shadow: 0 0 10px rgba(0, 127, 255, 0.6);
        }
        
        .electric-border-bottom {
            bottom: 0;
            animation-delay: 2s;
            box-shadow: 0 0 8px rgba(35, 239, 255, 0.5);
        }
        
        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            gap: 2rem;
            position: relative;
            z-index: 10;
        }
        
        .header-left,
        .header-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex: 1;
        }

        .header-center {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .brand-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .brand-logo-wrapper {
            position: relative;
            width: 95px;
            height: 95px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo-glow-ring {
            position: absolute;
            inset: -12px;
            border-radius: 50%;
            background: conic-gradient(from 0deg, #007fff, #23efff, #007fff);
            opacity: 0.35;
            animation: ringRotate 4s linear infinite;
            filter: blur(12px);
        }
        
        .brand-logo-img {
            width: 85px;
            height: 85px;
            position: relative;
            z-index: 2;
            filter: drop-shadow(0 0 20px rgba(0, 127, 255, 0.6));
            transition: all 0.3s ease;
        }
        
        .electric-arc-container {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
            opacity: 0;
        }
        
        .electric-arc {
            fill: none;
            stroke: #23efff;
            stroke-width: 1.5;
            stroke-linecap: round;
            filter: drop-shadow(0 0 4px rgba(0, 127, 255, 1)) drop-shadow(0 0 8px rgba(35, 239, 255, 0.8));
            opacity: 0;
        }
        
        .arc-1 {
            animation: electricFlash 1.8s ease-in-out infinite;
        }
        
        .arc-2 {
            animation: electricFlash 1.8s ease-in-out infinite 0.3s;
        }
        
        .arc-3 {
            animation: electricFlash 1.8s ease-in-out infinite 0.6s;
        }
        
        .arc-4 {
            stroke: #007fff;
            stroke-width: 2;
            animation: electricFlash 1.8s ease-in-out infinite 0.9s;
        }
        
        .spark {
            position: absolute;
            width: 4px;
            height: 4px;
            background: radial-gradient(circle, #23efff, transparent);
            border-radius: 50%;
            opacity: 0;
            z-index: 4;
            box-shadow: 0 0 8px rgba(35, 239, 255, 1), 0 0 12px rgba(0, 127, 255, 0.8);
        }
        
        .spark-1 {
            top: 15%;
            right: 20%;
            animation: sparkFlicker 2s ease-in-out infinite;
        }
        
        .spark-2 {
            bottom: 20%;
            left: 15%;
            animation: sparkFlicker 2s ease-in-out infinite 0.5s;
        }
        
        .spark-3 {
            top: 25%;
            left: 10%;
            animation: sparkFlicker 2s ease-in-out infinite 1s;
        }
        
        .spark-4 {
            bottom: 15%;
            right: 15%;
            animation: sparkFlicker 2s ease-in-out infinite 1.5s;
        }
        
        .logo-lightning-accent {
            position: absolute;
            width: 22px;
            height: 22px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 3;
            filter: drop-shadow(0 0 8px rgba(255, 215, 0, 0.9)) drop-shadow(0 0 12px rgba(0, 127, 255, 0.6));
            opacity: 0.85;
            animation: lightningPulse 2.5s ease-in-out infinite;
            pointer-events: none;
        }
        
        .brand-section:hover .brand-logo-img {
            transform: scale(1.05);
            filter: drop-shadow(0 0 32px rgba(0, 127, 255, 1));
        }
        
        .brand-section:hover .logo-lightning-accent {
            opacity: 1;
            filter: drop-shadow(0 0 12px rgba(255, 215, 0, 1)) drop-shadow(0 0 18px rgba(0, 127, 255, 0.8));
        }
        
        .brand-section:hover .electric-arc-container {
            opacity: 1;
        }
        
        .brand-identity {
            display: flex;
            flex-direction: column;
            position: relative;
        }
        
        .brand-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.4rem;
            font-weight: 700;
            background: linear-gradient(135deg, #007fff, #23efff, #007fff);
            background-size: 200% 100%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: titleShimmer 3s ease-in-out infinite;
            letter-spacing: 2px;
            position: relative;
            z-index: 2;
        }
        
        .brand-title::before {
            content: attr(data-text);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            color: transparent;
            text-shadow: 
                0 0 10px rgba(0, 127, 255, 0.8),
                0 0 20px rgba(35, 239, 255, 0.6),
                0 0 30px rgba(0, 127, 255, 0.4);
            animation: textElectricPulse 1.5s ease-in-out infinite;
        }
        
        .text-lightning-svg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
            opacity: 0.7;
        }
        
        .text-arc {
            fill: none;
            stroke: #007fff;
            stroke-width: 1.5;
            stroke-linecap: round;
            filter: drop-shadow(0 0 3px rgba(0, 127, 255, 0.9));
            stroke-dasharray: 20 10;
            stroke-dashoffset: 0;
            opacity: 0;
        }
        
        .t-arc-1 {
            animation: textArcTravel 1.6s ease-in-out infinite;
        }
        
        .t-arc-2 {
            stroke: #23efff;
            animation: textArcTravel 1.6s ease-in-out infinite 0.8s;
        }
        
        .header-controls {
            display: flex;
            gap: 0.5rem;
        }
        
        .control-group {
            position: relative;
            z-index: 9999;
        }
        
        .ctrl-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: rgba(0, 127, 255, 0.08);
            border: 1px solid rgba(0, 127, 255, 0.25);
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .ctrl-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(0, 127, 255, 0.15), rgba(35, 239, 255, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .ctrl-btn:hover {
            border-color: rgba(0, 127, 255, 0.5);
            box-shadow: 0 0 15px rgba(0, 127, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .ctrl-btn:hover::before {
            opacity: 1;
        }
        
        .ctrl-text {
            position: relative;
            z-index: 1;
        }
        
        .header-nav {
            display: flex;
            gap: 0.5rem;
            flex-wrap: nowrap;
            justify-content: flex-start;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-item::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #007fff, #23efff);
            transition: width 0.3s ease;
        }
        
        .nav-item:hover,
        .nav-item.active {
            color: #fff;
            background: rgba(0, 127, 255, 0.1);
        }
        
        .nav-item:hover::before,
        .nav-item.active::before {
            width: 80%;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .action-icon {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 127, 255, 0.08);
            border: 1px solid rgba(0, 127, 255, 0.2);
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            text-decoration: none;
        }
        
        .action-icon:hover {
            background: rgba(0, 127, 255, 0.15);
            border-color: rgba(0, 127, 255, 0.4);
            color: #fff;
            box-shadow: 0 0 20px rgba(0, 127, 255, 0.4);
            transform: translateY(-2px);
        }

        [dir="rtl"] .header-container,
        [dir="rtl"] .header-right,
        [dir="rtl"] .header-actions,
        [dir="rtl"] .header-controls,
        [dir="rtl"] .ctrl-btn,
        [dir="rtl"] .header-nav,
        [dir="rtl"] .nav-item,
        [dir="rtl"] .brand-section,
        [dir="rtl"] .mobile-brand {
            direction: rtl;
        }

        [dir="rtl"] .brand-identity {
            align-items: flex-end;
            text-align: right;
        }

        [dir="rtl"] .mobile-brand-name {
            text-align: right;
        }

        /* Removed headerPulse animation to prevent stacking context issues */
        /* .voltronix-header {
            animation: headerPulse 3.2s ease-in-out infinite;
        }

        @keyframes headerPulse {
            0%, 100% {
                background: linear-gradient(180deg, rgba(10, 15, 25, 0.98), rgba(15, 20, 30, 0.95));
            }
            50% {
                background: linear-gradient(180deg, rgba(12, 18, 30, 0.99), rgba(17, 23, 35, 0.97));
            }
        } */
        
        .cart-icon {
            position: relative;
        }
        
        .action-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: linear-gradient(135deg, #007fff, #23efff);
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 3px 7px;
            border-radius: 10px;
            min-width: 20px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 127, 255, 0.6);
            animation: badgePulse 2s ease-in-out infinite;
        }
        
        .action-btn-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-wrap: nowrap;
            gap: 8px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #007fff, #0066cc);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            font-size: 0.9rem;
            line-height: 1;
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0, 127, 255, 0.3);
        }

        .action-btn-login span {
            display: inline-block;
            line-height: 1.1;
            white-space: nowrap;
        }
        
        .action-btn-login:hover {
            background: linear-gradient(135deg, #0088ff, #007fff);
            box-shadow: 0 6px 25px rgba(0, 127, 255, 0.5);
            transform: translateY(-2px);
            color: #fff;
        }

        [dir="rtl"] .action-btn-login {
            gap: 6px;
            padding: 10px 16px;
            font-size: 0.82rem;
            min-height: 44px;
            letter-spacing: 0;
        }

        [dir="rtl"] .action-btn-login i,
        [dir="rtl"] .action-btn-login span {
            flex-shrink: 0;
        }
        
        .ctrl-dropdown {
            background: rgba(15, 20, 30, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 127, 255, 0.3);
            border-radius: 12px;
            padding: 8px;
            min-width: 180px;
            box-shadow: 0 10px 40px rgba(0, 127, 255, 0.2);
            z-index: 9999;
        }
        
        .ctrl-dropdown .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }
        
        .ctrl-dropdown .dropdown-item:hover {
            background: rgba(0, 127, 255, 0.15);
            color: #fff;
        }
        
        .ctrl-dropdown .dropdown-item.active {
            background: rgba(0, 127, 255, 0.2);
            color: #23efff;
        }
        
        .ctrl-dropdown .dropdown-item i {
            font-size: 1rem;
            opacity: 0;
        }
        
        .ctrl-dropdown .dropdown-item.active i {
            opacity: 1;
            color: #23efff;
        }
        
        .user-dropdown {
            min-width: 240px;
        }
        
        .dropdown-header {
            padding: 0;
            margin-bottom: 8px;
        }
        
        .user-info-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: rgba(0, 127, 255, 0.1);
            border-radius: 8px;
        }
        
        .user-info-header i {
            font-size: 2rem;
            color: #007fff;
        }
        
        .user-name-text {
            font-weight: 600;
            color: #fff;
            font-size: 0.95rem;
        }
        
        .user-email-text {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .dropdown-divider {
            border-color: rgba(0, 127, 255, 0.2);
            margin: 8px 0;
        }
        
        .mobile-menu-btn {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 8px;
        }
        
        .mobile-menu-btn span {
            width: 24px;
            height: 2px;
            background: #fff;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        
        @keyframes electricBorderFlow {
            0% { background-position: 0% 50%; }
            100% { background-position: 200% 50%; }
        }
        
        @keyframes ringRotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes lightningPulse {
            0%, 100% {
                opacity: 0.85;
                transform: translate(-50%, -50%) scale(1);
            }
            50% {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1.08);
            }
        }
        
        @keyframes electricFlash {
            0%, 100% {
                opacity: 0;
                stroke-dasharray: 5 15;
                stroke-dashoffset: 0;
            }
            10% {
                opacity: 1;
                stroke-dasharray: 5 15;
                stroke-dashoffset: 0;
            }
            20% {
                opacity: 0;
                stroke-dasharray: 5 15;
                stroke-dashoffset: 20;
            }
            25% {
                opacity: 0.8;
                stroke-dasharray: 5 15;
                stroke-dashoffset: 20;
            }
            35%, 95% {
                opacity: 0;
                stroke-dasharray: 5 15;
                stroke-dashoffset: 40;
            }
        }
        
        @keyframes sparkFlicker {
            0%, 100% {
                opacity: 0;
                transform: scale(0);
            }
            15% {
                opacity: 1;
                transform: scale(1.5);
            }
            30% {
                opacity: 0;
                transform: scale(0.5);
            }
            40% {
                opacity: 0.8;
                transform: scale(1.2);
            }
            55%, 95% {
                opacity: 0;
                transform: scale(0);
            }
        }
        
        @keyframes textElectricPulse {
            0%, 100% {
                text-shadow: 
                    0 0 10px rgba(0, 127, 255, 0.8),
                    0 0 20px rgba(35, 239, 255, 0.6),
                    0 0 30px rgba(0, 127, 255, 0.4);
            }
            15% {
                text-shadow: 
                    0 0 15px rgba(0, 127, 255, 1),
                    0 0 30px rgba(35, 239, 255, 0.9),
                    0 0 45px rgba(0, 127, 255, 0.7);
            }
            30% {
                text-shadow: 
                    0 0 8px rgba(0, 127, 255, 0.6),
                    0 0 15px rgba(35, 239, 255, 0.4),
                    0 0 20px rgba(0, 127, 255, 0.3);
            }
            45%, 95% {
                text-shadow: 
                    0 0 10px rgba(0, 127, 255, 0.8),
                    0 0 20px rgba(35, 239, 255, 0.6),
                    0 0 30px rgba(0, 127, 255, 0.4);
            }
        }
        
        @keyframes textArcTravel {
            0%, 100% {
                opacity: 0;
                stroke-dashoffset: 0;
            }
            10% {
                opacity: 0.8;
                stroke-dashoffset: 0;
            }
            30% {
                opacity: 1;
                stroke-dashoffset: 30;
            }
            50% {
                opacity: 0.6;
                stroke-dashoffset: 60;
            }
            70%, 95% {
                opacity: 0;
                stroke-dashoffset: 90;
            }
        }
        
        @keyframes titleShimmer {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        @keyframes badgePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        @media (max-width: 1024px) {
            .header-nav {
                display: none;
            }
            .mobile-menu-btn {
                display: flex;
            }
        }
        
        @media (max-width: 768px) {
            .header-container {
                padding: 0 1rem;
            }
            .header-controls {
                display: none;
            }
            .brand-logo-wrapper {
                width: 70px;
                height: 70px;
            }
            .brand-logo-img {
                width: 60px;
                height: 60px;
            }
            .logo-lightning-accent {
                width: 16px;
                height: 16px;
            }
            .brand-title {
                font-size: 1.75rem;
            }
            .action-btn-login span {
                display: none;
            }
            .spark {
                width: 3px;
                height: 3px;
            }
        }
        
        @media (max-width: 480px) {
            .brand-logo-wrapper {
                width: 60px;
                height: 60px;
            }
            .brand-logo-img {
                width: 52px;
                height: 52px;
            }
            .logo-lightning-accent {
                width: 14px;
                height: 14px;
            }
            .brand-title {
                font-size: 1.5rem;
            }
            .spark {
                width: 2px;
                height: 2px;
            }
            .electric-arc {
                stroke-width: 1;
            }
            .text-arc {
                stroke-width: 1;
            }
        }
        
        /* Specific fixes for exact mobile widths only */
        
        /* Mobile S - 320px width */
        @media (max-width: 320px) {
            .header-container {
                padding: 0 0.75rem !important;
                height: 60px !important;
                gap: 0.5rem !important;
                justify-content: space-between !important;
            }
            
            /* .voltronix-header height moved to navbar-premium.css */
            
            /* Collapse unused left area so logo + icons fit */
            .header-left {
                display: none !important;
            }
            
            .header-center,
            .header-right {
                flex: 0 0 auto !important;
            }
            
            /* Hide ONLY text, keep logo visible */
            .brand-identity {
                display: none !important;
            }
            
            .brand-title {
                display: none !important;
            }
            
            /* KEEP LOGO VISIBLE */
            .brand-logo-wrapper {
                width: 40px !important;
                height: 40px !important;
                display: block !important;
                visibility: visible !important;
            }
            
            .brand-logo-img {
                width: 36px !important;
                height: 36px !important;
                display: block !important;
                visibility: visible !important;
            }
            
            /* KEEP BUTTONS VISIBLE */
            .action-icon {
                width: 32px !important;
                height: 32px !important;
                font-size: 0.85rem !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                visibility: visible !important;
            }
            
            .header-actions {
                gap: 0.25rem !important;
                display: flex !important;
                visibility: visible !important;
            }
            
            .mobile-menu-btn {
                display: flex !important;
                width: 32px !important;
                height: 32px !important;
                visibility: visible !important;
            }
        }
        
        /* Mobile M - 375px width */
        @media (min-width: 321px) and (max-width: 375px) {
            .header-container {
                padding: 0 0.75rem !important;
                height: 60px !important;
                gap: 0.5rem !important;
                justify-content: space-between !important;
            }
            
            /* .voltronix-header height moved to navbar-premium.css */
            
            /* Collapse unused left area so logo + icons fit */
            .header-left {
                display: none !important;
            }
            
            .header-center,
            .header-right {
                flex: 0 0 auto !important;
            }
            
            /* Hide ONLY text, keep logo visible - SAME AS 320px */
            .brand-identity {
                display: none !important;
            }
            
            .brand-title {
                display: none !important;
            }
            
            .brand-logo-wrapper {
                width: 40px !important;
                height: 40px !important;
                display: block !important;
                visibility: visible !important;
            }
            
            .brand-logo-img {
                width: 36px !important;
                height: 36px !important;
                display: block !important;
                visibility: visible !important;
            }
            
            .action-icon {
                width: 32px !important;
                height: 32px !important;
                font-size: 0.85rem !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                visibility: visible !important;
            }
            
            .header-actions {
                gap: 0.25rem !important;
                display: flex !important;
                visibility: visible !important;
            }
            
            .mobile-menu-btn {
                display: flex !important;
                width: 32px !important;
                height: 32px !important;
                visibility: visible !important;
            }
        }
        
        /* Mobile L - 425px width */
        @media (min-width: 376px) and (max-width: 425px) {
            .header-container {
                padding: 0 0.75rem !important;
                height: 60px !important;
                gap: 0.5rem !important;
                justify-content: space-between !important;
            }
            
            /* .voltronix-header height moved to navbar-premium.css */
            
            /* Collapse unused left area so logo + icons fit */
            .header-left {
                display: none !important;
            }
            
            .header-center,
            .header-right {
                flex: 0 0 auto !important;
            }
            
            /* Hide ONLY text, keep logo visible - SAME AS 320px */
            .brand-identity {
                display: none !important;
            }
            
            .brand-title {
                display: none !important;
            }
            
            .brand-logo-wrapper {
                width: 40px !important;
                height: 40px !important;
                display: block !important;
                visibility: visible !important;
            }
            
            .brand-logo-img {
                width: 36px !important;
                height: 36px !important;
                display: block !important;
                visibility: visible !important;
            }
            
            .action-icon {
                width: 32px !important;
                height: 32px !important;
                font-size: 0.85rem !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                visibility: visible !important;
            }
            
            .header-actions {
                gap: 0.25rem !important;
                display: flex !important;
                visibility: visible !important;
            }
            
            .mobile-menu-btn {
                display: flex !important;
                width: 32px !important;
                height: 32px !important;
                visibility: visible !important;
            }
        }

        /* Small-phone structural safety (covers 320–430px and nearby sizes) */
        @media (max-width: 480px) {
            .header-container {
                padding: 0 0.5rem !important;
                gap: 0.5rem !important;
                justify-content: space-between !important;
            }

            /* Always collapse the left region and keep only logo + actions */
            .header-left {
                display: none !important;
            }

            .header-center {
                /* Allow the center (logo) to shrink so actions stay in view */
                flex: 0 1 auto !important;
            }

            .header-right {
                /* Use intrinsic width; don't stretch beyond what icons need */
                flex: 0 0 auto !important;
                gap: 0.5rem !important;
            }

            /* Force brand text off on smallest devices to avoid pushing icons */
            .brand-identity {
                display: none !important;
            }

            .brand-logo-wrapper {
                width: 40px !important;
                height: 40px !important;
            }

            .brand-logo-img {
                width: 36px !important;
                height: 36px !important;
            }

            .header-actions {
                gap: 0.25rem !important;
            }

            .action-icon {
                width: 30px !important;
                height: 30px !important;
                font-size: 0.9rem !important;
            }

            .mobile-menu-btn {
                display: flex !important;
                width: 30px !important;
                height: 30px !important;
            }
        }

        @media (max-width: 768px) {
            .voltronix-header {
                height: 68px !important;
            }

            .header-container {
                padding: 0 0.95rem !important;
                gap: 0.75rem !important;
                height: 100% !important;
            }

            .header-center {
                flex: 1 1 auto !important;
                min-width: 0;
                justify-content: flex-start !important;
            }

            .header-right {
                flex: 0 0 auto !important;
                gap: 0.55rem !important;
            }

            [dir="rtl"] .header-container {
                flex-direction: row-reverse !important;
            }

            [dir="rtl"] .header-center {
                justify-content: flex-end !important;
            }

            .brand-section {
                gap: 0.75rem !important;
                min-width: 0;
            }

            .brand-identity {
                display: flex !important;
                min-width: 0;
                max-width: clamp(140px, 38vw, 220px);
            }

            .brand-title {
                display: inline-flex !important;
                font-size: 1.2rem !important;
                line-height: 1 !important;
            }

            .brand-title[data-brand-script="arabic"] {
                display: inline-block !important;
                font-size: 1.14rem !important;
            }

            .brand-logo-wrapper {
                width: 58px !important;
                height: 58px !important;
            }

            .brand-logo-img {
                width: 50px !important;
                height: 50px !important;
            }

            .header-actions {
                gap: 0.45rem !important;
            }

            .action-icon,
            .action-btn-login,
            .mobile-menu-btn {
                width: 38px !important;
                height: 38px !important;
                min-width: 38px !important;
                min-height: 38px !important;
                border-radius: 12px !important;
            }

            .action-btn-login {
                padding: 0 !important;
                gap: 0 !important;
                box-shadow: 0 4px 15px rgba(0, 127, 255, 0.22) !important;
            }

            .action-btn-login span {
                display: none !important;
            }

            .mobile-menu-btn {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 4px !important;
                padding: 0 !important;
                background: rgba(0, 127, 255, 0.08) !important;
                border: 1px solid rgba(0, 127, 255, 0.18) !important;
                border-radius: 12px !important;
            }

            .mobile-menu-btn span {
                width: 16px !important;
            }
        }

        @media (max-width: 480px) {
            .voltronix-header {
                height: 64px !important;
            }

            .header-container {
                padding: 0 0.75rem !important;
                gap: 0.5rem !important;
                height: 100% !important;
            }

            .brand-identity {
                max-width: min(41vw, 150px);
            }

            .brand-title {
                font-size: 1.04rem !important;
            }

            .brand-title[data-brand-script="arabic"] {
                font-size: 1rem !important;
            }

            .brand-logo-wrapper {
                width: 52px !important;
                height: 52px !important;
            }

            .brand-logo-img {
                width: 46px !important;
                height: 46px !important;
            }

            .header-actions {
                gap: 0.35rem !important;
            }

            .action-icon,
            .action-btn-login,
            .mobile-menu-btn {
                width: 34px !important;
                height: 34px !important;
                min-width: 34px !important;
                min-height: 34px !important;
                font-size: 0.95rem !important;
            }

            .mobile-menu-btn span {
                width: 14px !important;
            }
        }

        @media (max-width: 360px) {
            .header-container {
                padding: 0 0.65rem !important;
                gap: 0.35rem !important;
                height: 100% !important;
            }

            .brand-identity {
                display: none !important;
            }

            .brand-logo-wrapper {
                width: 46px !important;
                height: 46px !important;
            }

            .brand-logo-img {
                width: 40px !important;
                height: 40px !important;
            }

            .action-icon,
            .action-btn-login,
            .mobile-menu-btn {
                width: 32px !important;
                height: 32px !important;
                min-width: 32px !important;
                min-height: 32px !important;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .electric-arc,
            .spark,
            .text-arc,
            .brand-title::before,
            .logo-lightning-accent {
                animation: none !important;
                opacity: 0 !important;
            }
        }
        
        /* ========================================
           VOLTRONIX HEADER MINIMAL ANIMATION OVERRIDES
           ======================================== */

        /* Remove external glowing/border/canvas effects around logo and text */
        .electric-border-top,
        .electric-border-bottom,
        .voltage-canvas,
        .logo-glow-ring,
        .electric-arc-container,
        .electric-arc,
        .spark,
        .logo-lightning-accent,
        .text-lightning-svg,
        .text-arc {
            display: none !important;
            animation: none !important;
            box-shadow: none !important;
            filter: none !important;
        }

        /* Clean brand text: solid readable color; electric motion handled per-letter */
        .brand-title {
            color: #f5f8ff;
            display: inline-flex;
            align-items: center;
            gap: 0.02em;
            background: none !important;
            background-image: none !important;
            -webkit-background-clip: border-box !important;
            background-clip: border-box !important;
            -webkit-text-fill-color: #f5f8ff !important;
        }

        .brand-title::before {
            content: none;
        }

        .brand-title[data-brand-script="arabic"] {
            display: inline-block;
            font-family: 'Tajawal', 'Noto Sans Arabic', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: 0;
            word-spacing: 0;
            line-height: 1.08;
            white-space: nowrap;
            direction: rtl;
            unicode-bidi: isolate;
            text-align: right;
            text-rendering: optimizeLegibility;
            isolation: isolate;
            background: linear-gradient(135deg, #f5f8ff 0%, #9adfff 52%, #23efff 100%) !important;
            background-image: linear-gradient(135deg, #f5f8ff 0%, #9adfff 52%, #23efff 100%) !important;
            background-size: 220% 100% !important;
            background-position: 0% 50% !important;
            -webkit-background-clip: text !important;
            background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            text-shadow: 0 0 18px rgba(0, 127, 255, 0.18);
            animation: arabicBrandGlow 3.6s ease-in-out infinite, arabicBrandShimmer 7.2s linear infinite;
        }

        .brand-title[data-brand-script="arabic"]::before {
            content: attr(data-text);
            position: absolute;
            inset: 0;
            z-index: -1;
            font: inherit;
            direction: inherit;
            unicode-bidi: inherit;
            white-space: inherit;
            color: rgba(154, 223, 255, 0.28);
            text-shadow: 0 0 16px rgba(0, 127, 255, 0.22),
                0 0 28px rgba(35, 239, 255, 0.16);
            filter: blur(0.55px);
            opacity: 0.72;
            pointer-events: none;
            animation: arabicBrandAura 3.6s ease-in-out infinite;
        }

        .brand-title[data-brand-script="arabic"]::after {
            content: attr(data-text);
            position: absolute;
            inset: 0;
            z-index: 1;
            font: inherit;
            direction: inherit;
            unicode-bidi: inherit;
            white-space: inherit;
            background: linear-gradient(112deg,
                transparent 16%,
                rgba(255, 255, 255, 0.12) 43%,
                rgba(255, 255, 255, 0.96) 50%,
                rgba(35, 239, 255, 0.82) 54%,
                transparent 72%);
            background-size: 240% 100%;
            background-position: 120% 50%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            opacity: 0.55;
            mix-blend-mode: screen;
            filter: drop-shadow(0 0 8px rgba(35, 239, 255, 0.18));
            pointer-events: none;
            animation: arabicBrandCurrent 6.2s cubic-bezier(0.42, 0, 0.28, 1) infinite;
        }

        .mobile-brand-name[lang="ar"] {
            display: inline-block;
            font-family: 'Tajawal', 'Noto Sans Arabic', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: 0;
            word-spacing: 0;
            line-height: 1.08;
            white-space: nowrap;
            direction: rtl;
            unicode-bidi: isolate;
            text-rendering: optimizeLegibility;
            background: linear-gradient(135deg, #f5f8ff 0%, #9adfff 52%, #23efff 100%);
            background-size: 220% 100%;
            background-position: 0% 50%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: arabicBrandGlow 4.2s ease-in-out infinite, arabicBrandShimmer 9s linear infinite;
        }

        /* Per-letter spans for internal electric behavior */
        .brand-char {
            display: inline-block;
            position: relative;
            color: inherit;
            animation: charJitter 1.8s ease-in-out infinite;
            animation-delay: calc(var(--char-index, 0) * 55ms);
        }

        .brand-char.char-flicker {
            animation: charFlicker 0.26s steps(3, end) 1;
        }

        .brand-char.char-glitch {
            animation: charGlitch 0.24s linear 1;
        }

        .brand-char.char-strike {
            animation: charStrike 0.22s linear 1;
        }

        .brand-char.char-bolt::after {
            content: '\26A1'; /* ⚡ */
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%) scale(0.7);
            font-size: 0.7em;
            pointer-events: none;
            color: #23efff;
            animation: boltFlash 0.12s linear 1;
        }

        /* Optional internal blue electric gradient sweep when supported */
        @supports (-webkit-background-clip: text) or (background-clip: text) {
            .brand-char {
                background-image: linear-gradient(120deg,
                    #b9d8ff 0%,
                    #23efff 25%,
                    #ffffff 50%,
                    #23efff 75%,
                    #6ab0ff 100%);
                background-size: 260% 100%;
                -webkit-background-clip: text;
                background-clip: text;
                -webkit-text-fill-color: transparent;
                animation-name: charJitter, brandCharSweep;
                animation-duration: 1.8s, 4.8s;
                animation-timing-function: ease-in-out, linear;
                animation-iteration-count: infinite, infinite;
                animation-delay: calc(var(--char-index, 0) * 55ms), calc(var(--char-index, 0) * 80ms);
            }
        }

        /* Logo: continuous internal electric pulse + breathing scale (no outer glow) */
        .brand-logo-img {
            position: relative;
            filter: none;
            animation: logoBreath 2.1s ease-in-out infinite, logoCharge 4s ease-in-out infinite;
            transform-origin: center center;
        }

        .brand-logo-img.logo-flicker {
            animation: logoFlicker 0.32s steps(2, end) 1;
        }

        .brand-logo-img.logo-bolt::after {
            content: '\26A1'; /* ⚡ */
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%) scale(0.9);
            font-size: 0.7em;
            color: #23efff;
            pointer-events: none;
            animation: boltFlash 0.12s linear 1;
        }

        @keyframes charJitter {
            0%, 100% {
                transform: translate(0, 0) skewX(0deg);
            }
            20% {
                transform: translate(0.7px, -0.7px) skewX(-0.6deg);
            }
            40% {
                transform: translate(-0.9px, 0.5px) skewX(0.8deg);
            }
            60% {
                transform: translate(0.6px, 0.6px) skewX(-0.5deg);
            }
            80% {
                transform: translate(-0.7px, -0.5px) skewX(0.6deg);
            }
        }

        @keyframes charFlicker {
            0% {
                opacity: 1;
                transform: translate(0, 0);
            }
            40% {
                opacity: 0.2;
                transform: translate(0.3px, -0.3px);
            }
            80% {
                opacity: 1;
                transform: translate(-0.3px, 0.2px);
            }
            100% {
                opacity: 1;
                transform: translate(0, 0);
            }
        }

        @keyframes charGlitch {
            0% {
                transform: translateX(0);
            }
            25% {
                transform: translateX(-1.8px);
            }
            50% {
                transform: translateX(2.1px);
            }
            75% {
                transform: translateX(-1.4px);
            }
            100% {
                transform: translateX(0);
            }
        }

        @keyframes charStrike {
            0% {
                opacity: 1;
                transform: translate(0, 0) skewX(0deg);
            }
            20% {
                opacity: 0.6;
                transform: translate(0.5px, -0.5px) skewX(-2deg);
            }
            50% {
                opacity: 1;
                transform: translate(-1px, 0.4px) skewX(2.5deg);
            }
            80% {
                opacity: 0.7;
                transform: translate(0.6px, 0.6px) skewX(-1.5deg);
            }
            100% {
                opacity: 1;
                transform: translate(0, 0) skewX(0deg);
            }
        }

        @keyframes brandCharSweep {
            0% {
                background-position: -100% 50%;
            }
            50% {
                background-position: 0% 50%;
            }
            100% {
                background-position: 100% 50%;
            }
        }

        @keyframes arabicBrandGlow {
            0%, 100% {
                text-shadow: 0 0 12px rgba(0, 127, 255, 0.14);
                filter: brightness(0.98) saturate(1);
            }
            42% {
                text-shadow: 0 0 26px rgba(35, 239, 255, 0.22);
                filter: brightness(1.08) saturate(1.08);
            }
            50% {
                text-shadow: 0 0 18px rgba(255, 255, 255, 0.16),
                    0 0 32px rgba(35, 239, 255, 0.18);
                filter: brightness(1.12) saturate(1.1);
            }
            58% {
                text-shadow: 0 0 14px rgba(0, 127, 255, 0.16);
                filter: brightness(1.02) saturate(1.02);
            }
        }

        @keyframes arabicBrandShimmer {
            0% {
                background-position: 0% 50%;
            }
            100% {
                background-position: 220% 50%;
            }
        }

        @keyframes arabicBrandAura {
            0%, 100% {
                opacity: 0.46;
                transform: translateY(0);
            }
            50% {
                opacity: 0.84;
                transform: translateY(-0.4px);
            }
        }

        @keyframes arabicBrandCurrent {
            0%,
            14% {
                background-position: 130% 50%;
                opacity: 0;
            }
            20% {
                opacity: 0.68;
            }
            33% {
                background-position: -35% 50%;
                opacity: 0.22;
            }
            100% {
                background-position: -35% 50%;
                opacity: 0;
            }
        }

        @keyframes logoCharge {
            0%, 100% {
                filter: none;
            }
            35% {
                filter: brightness(1.06) contrast(1.04) saturate(1.08);
            }
            50% {
                filter: brightness(1.1) contrast(1.08) saturate(1.12);
            }
            65% {
                filter: brightness(1.06) contrast(1.04) saturate(1.08);
            }
        }

        @keyframes logoFlicker {
            0% {
                filter: brightness(1.2) contrast(1.1) saturate(1.15);
            }
            50% {
                filter: brightness(0.55) contrast(0.85) saturate(0.9);
            }
            100% {
                filter: brightness(1.1) contrast(1.08) saturate(1.1);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .brand-title {
                animation: none !important;
                background-position: 0 50%;
            }
            .brand-title[data-brand-script="arabic"]::before,
            .brand-title[data-brand-script="arabic"]::after {
                animation: none !important;
                opacity: 0 !important;
            }
            .brand-char {
                animation: none !important;
                transform: none !important;
            }
            .brand-logo-img {
                animation: none !important;
                filter: none !important;
                transform: none !important;
            }
            .mobile-brand-name[lang="ar"] {
                animation: none !important;
                background-position: 0 50% !important;
            }
        }
        
        /* ========================================
           VOLTAGE CANVAS BACKGROUND
           ======================================== */
        
        .voltage-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            opacity: 0.25;
            z-index: 0;
        }
        
        .voltronix-navbar.scrolled .voltage-canvas {
            opacity: 0.15;
        }
        
        /* ========================================
           NAVBAR BASE STYLES - MOVED TO navbar-premium.css
           ======================================== */
        
        /* All .voltronix-header styles have been moved to external CSS
           to prevent inline style conflicts and stacking context issues */
            margin: 0 auto;
            padding: 0 2rem;
            height: 100%;
            gap: 1rem;
            position: relative;
            z-index: 1;
        }

        /* Navbar Sections - Prevent Overflow */
        .navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-shrink: 0;
            min-width: 0;
            position: relative;
            z-index: 10;
        }

        .navbar-center {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            min-width: 0;
            position: relative;
            z-index: 10;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-shrink: 0;
            min-width: 0;
            position: relative;
            z-index: 10;
        }

        /* Navbar Controls */
        .navbar-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            z-index: 15;
        }

        .control-dropdown {
            position: relative;
            z-index: 20;
        }

        .control-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            position: relative;
            z-index: 21;
            padding: 8px 12px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(0, 127, 255, 0.2);
            border-radius: 8px;
            color: white;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .control-btn:hover {
            background: rgba(0, 127, 255, 0.2);
            border-color: rgba(0, 127, 255, 0.4);
        }

        /* Main Navigation */
        .main-navigation {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 16px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .nav-link:hover,
        .nav-link.active {
            color: white;
            background: rgba(0, 127, 255, 0.2);
            text-decoration: none;
        }

        /* Action Buttons */
        .action-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 12px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(0, 127, 255, 0.2);
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .action-btn:hover,
        .action-btn.active {
            color: white;
            background: rgba(0, 127, 255, 0.3);
            border-color: rgba(0, 127, 255, 0.5);
            text-decoration: none;
            transform: translateY(-1px);
        }

        .btn-icon {
            position: relative;
            display: flex;
            align-items: center;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: linear-gradient(135deg, #ff4757 0%, #ff3742 100%);
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 3px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
            line-height: 1.2;
            box-shadow: 0 4px 12px rgba(255, 71, 87, 0.4);
            display: none;
        }
        
        @keyframes cartBadgeBounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        /* Mobile Menu Toggle */
        .mobile-toggle {
            display: none;
            flex-direction: column;
            gap: 4px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1051;
        }
        
        /* Show mobile toggle only on mobile */
        @media (max-width: 992px) {
            .mobile-toggle {
                display: flex;
            }
            
            .main-navigation {
                display: none;
            }
        }

        .toggle-line {
            width: 24px;
            height: 2px;
            background: white;
            transition: all 0.3s ease;
        }
        /* Logo Section */
        .navbar-brand-section {
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: white;
            font-weight: 700;
            font-family: 'Orbitron', sans-serif;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            color: var(--voltronix-secondary);
            text-decoration: none;
        }

        .navbar-logo {
            height: 40px;
            width: auto;
            margin-right: 12px;
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover .navbar-logo {
            transform: scale(1.05);
        }

        .navbar-title {
            font-size: 1.5rem;
            background: var(--voltronix-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Navigation Links */
        .navbar-nav-section {
            display: flex;
            align-items: center;
            flex: 1;
            justify-content: center;
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: var(--border-radius-sm);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--voltronix-gradient);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .nav-link:hover,
        .nav-link.active {
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }

        .nav-link:hover::before,
        .nav-link.active::before {
            opacity: 0.15;
        }

        .nav-link.active {
            background: rgba(0, 127, 255, 0.1);
            border: 1px solid rgba(0, 127, 255, 0.3);
        }

        .nav-link i {
            font-size: 1.1rem;
        }

        /* Premium Search Styles */
        .premium-search-container {
            position: relative;
        }
        
        .search-trigger-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 127, 255, 0.2);
            border-radius: 25px;
            padding: 10px 16px;
            color: white;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            cursor: pointer;
        }
        
        .search-trigger-btn:hover {
            background: rgba(0, 127, 255, 0.2);
            border-color: rgba(0, 127, 255, 0.4);
            transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(0, 127, 255, 0.3);
        }
        
        .search-trigger-btn i {
            font-size: 16px;
        }
        
        .search-hint {
            opacity: 0.8;
        }
        
        /* Premium Search Overlay */
        .premium-search-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        
        .premium-search-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .search-overlay-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        
        .search-overlay-content {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 80px 20px 20px;
        }
        
        .search-container {
            width: 100%;
            max-width: 600px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 127, 255, 0.2);
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            transform: translateY(-50px);
            transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        
        .premium-search-overlay.active .search-container {
            transform: translateY(0);
        }
        
        .search-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 25px;
            border-bottom: 1px solid rgba(0, 127, 255, 0.1);
            background: linear-gradient(135deg, rgba(0, 127, 255, 0.05), rgba(35, 239, 255, 0.02));
        }
        
        .search-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .search-brand-icon {
            font-size: 20px;
            color: #007fff;
        }
        
        .search-brand-text {
            font-family: 'Orbitron', sans-serif;
            font-weight: 600;
            font-size: 18px;
            background: linear-gradient(135deg, #007fff, #23efff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .search-close-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: rgba(0, 0, 0, 0.05);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .search-close-btn:hover {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            transform: scale(1.1);
        }
        
        .search-input-container {
            padding: 25px;
        }
        
        .search-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            background: rgba(0, 127, 255, 0.05);
            border: 2px solid rgba(0, 127, 255, 0.1);
            border-radius: 20px;
            padding: 0 20px;
            transition: all 0.3s ease;
        }
        
        .search-input-wrapper:focus-within {
            border-color: #007fff;
            box-shadow: 0 0 0 4px rgba(0, 127, 255, 0.1);
        }
        
        .search-input-icon {
            color: #007fff;
            font-size: 18px;
            margin-right: 15px;
        }
        
        .premium-search-input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 18px 0;
            font-size: 16px;
            color: #333;
            outline: none;
        }
        
        .premium-search-input::placeholder {
            color: rgba(51, 51, 51, 0.5);
        }
        
        .search-submit-btn {
            border: none;
            background: linear-gradient(135deg, #007fff, #23efff);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-left: 10px;
        }
        
        .search-submit-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(0, 127, 255, 0.4);
        }
        
        /* Scrollable Search Results Area */
        .search-results-scrollable {
            max-height: 500px;
            overflow-y: auto;
            overflow-x: hidden;
            margin-top: 0;
            padding: 0 25px 25px 25px;
            border-radius: 0;
        }
        
        /* Custom Voltronix Scrollbar for Results Area */
        .search-results-scrollable::-webkit-scrollbar {
            width: 6px;
        }
        
        .search-results-scrollable::-webkit-scrollbar-track {
            background: rgba(0, 127, 255, 0.05);
            border-radius: 3px;
        }
        
        .search-results-scrollable::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #007fff, #23efff);
            border-radius: 3px;
            transition: all 0.3s ease;
            opacity: 0;
        }
        
        .search-results-scrollable:hover::-webkit-scrollbar-thumb {
            opacity: 1;
        }
        
        .search-results-scrollable::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #0056cc, #1bc7e8);
            box-shadow: 0 0 10px rgba(0, 127, 255, 0.5);
        }
        
        /* Firefox scrollbar for Results Area */
        .search-results-scrollable {
            scrollbar-width: thin;
            scrollbar-color: #007fff rgba(0, 127, 255, 0.05);
        }

        /* Search Suggestions */
        .search-suggestions-container {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            padding-bottom: 20px;
        }
        
        .search-suggestions-container.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        
        .suggestions-loading {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 15px;
            padding: 30px;
            color: #666;
        }
        
        /* Voltronix Skeleton Loader */
        .skeleton-container {
            padding: 15px 0;
        }
        
        .skeleton-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            margin-bottom: 8px;
        }
        
        .skeleton-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: linear-gradient(90deg, rgba(0, 127, 255, 0.1) 25%, rgba(0, 127, 255, 0.2) 50%, rgba(0, 127, 255, 0.1) 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        .skeleton-content {
            flex: 1;
        }
        
        .skeleton-title {
            height: 16px;
            background: linear-gradient(90deg, rgba(0, 127, 255, 0.1) 25%, rgba(0, 127, 255, 0.2) 50%, rgba(0, 127, 255, 0.1) 75%);
            background-size: 200% 100%;
            border-radius: 8px;
            margin-bottom: 6px;
            animation: shimmer 1.5s infinite;
        }
        
        .skeleton-meta {
            height: 12px;
            width: 60%;
            background: linear-gradient(90deg, rgba(0, 127, 255, 0.08) 25%, rgba(0, 127, 255, 0.15) 50%, rgba(0, 127, 255, 0.08) 75%);
            background-size: 200% 100%;
            border-radius: 6px;
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }
        
        .suggestions-section {
            margin-bottom: 25px;
        }
        
        .suggestions-section:last-child {
            margin-bottom: 0;
        }
        
        .suggestions-section-title {
            font-family: 'Orbitron', sans-serif;
            font-weight: 600;
            font-size: 14px;
            color: #007fff;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .suggestions-list {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        
        .suggestion-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 12px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }
        
        .suggestion-item:hover {
            background: rgba(0, 127, 255, 0.1);
            color: #007fff;
            text-decoration: none;
            transform: translateX(5px);
        }
        
        .suggestion-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: rgba(0, 127, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #007fff;
            font-size: 16px;
            flex-shrink: 0;
        }
        
        .suggestion-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .suggestion-content {
            flex: 1;
        }
        
        .suggestion-title {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 2px;
        }
        
        .suggestion-meta {
            font-size: 12px;
            color: #666;
        }
        
        .suggestions-empty,
        .suggestions-error {
            text-align: center;
            padding: 40px 20px;
            color: #666;
            font-style: italic;
            background: rgba(0, 127, 255, 0.02);
            border-radius: 15px;
            margin: 20px 0;
        }
        
        .suggestions-empty i,
        .suggestions-error i {
            font-size: 2rem;
            color: rgba(0, 127, 255, 0.3);
            margin-bottom: 10px;
            display: block;
        }
        
        /* Quick Search Section */
        .quick-search-section {
            padding: 25px 0 0 0;
            border-top: 1px solid rgba(0, 127, 255, 0.1);
            background: rgba(0, 127, 255, 0.02);
            margin-top: 20px;
        }
        
        .quick-search-title {
            font-family: 'Orbitron', sans-serif;
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin-bottom: 15px;
        }
        
        .quick-search-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .quick-tag {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            background: rgba(0, 127, 255, 0.1);
            border: 1px solid rgba(0, 127, 255, 0.2);
            border-radius: 20px;
            text-decoration: none;
            color: #007fff;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .quick-tag:hover {
            background: rgba(0, 127, 255, 0.2);
            border-color: #007fff;
            color: #007fff;
            text-decoration: none;
            transform: translateY(-2px);
        }
        
        .quick-tag i {
            font-size: 14px;
        }
        
        /* Body class when search is open */
        body.search-overlay-open {
            overflow: hidden;
        }
        
        /* Voltronix Toast Styling */
        .voltronix-toast {
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
            border: 1px solid rgba(0, 127, 255, 0.2) !important;
            border-radius: 15px !important;
            box-shadow: 0 8px 32px rgba(0, 127, 255, 0.2) !important;
        }
        
        .voltronix-toast .swal2-title {
            font-family: 'Orbitron', sans-serif !important;
            font-weight: 600 !important;
            color: #007fff !important;
            font-size: 14px !important;
        }
        
        .voltronix-toast .swal2-timer-progress-bar {
            background: linear-gradient(135deg, #007fff, #23efff) !important;
        }

        /* Navbar Actions */
        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        .navbar-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: var(--border-radius-sm);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .navbar-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--voltronix-gradient);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .navbar-btn:hover,
        .navbar-btn.active {
            color: white;
            text-decoration: none;
            border-color: rgba(0, 127, 255, 0.5);
            transform: translateY(-2px);
        }

        .navbar-btn:hover::before,
        .navbar-btn.active::before {
            opacity: 0.2;
        }

        .navbar-btn i {
            font-size: 1.1rem;
        }

        /* Cart Button */
        .cart-btn {
            position: relative;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--voltronix-gradient);
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 50px;
            min-width: 20px;
            text-align: center;
            animation: pulse 2s infinite;
        }

        /* Dropdowns */
        .navbar-dropdown {
            position: relative;
            z-index: 20;
        }
        
        .action-dropdown {
            position: relative;
            z-index: 20;
        }
        
        .user-dropdown {
            position: relative;
            z-index: 20;
        }

        .dropdown-menu {
            background: rgba(13, 20, 33, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 127, 255, 0.2);
            border-radius: var(--border-radius-md);
            box-shadow: 0 20px 60px rgba(0, 127, 255, 0.15);
            padding: 8px;
            margin-top: 8px;
            min-width: 200px;
            position: absolute;
            z-index: 9999;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: var(--border-radius-sm);
            transition: all 0.3s ease;
            border: none;
            background: transparent;
            width: 100%;
            cursor: pointer;
            position: relative;
            z-index: 9999;
        }

        .dropdown-item:hover,
        .dropdown-item.active {
            background: rgba(0, 127, 255, 0.1);
            color: white;
            text-decoration: none;
        }

        .dropdown-item i {
            font-size: 1rem;
            width: 16px;
        }

        .flag {
            font-size: 1.2rem;
        }

        /* Currency Dropdown */
        .currency-info {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .currency-symbol {
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--voltronix-secondary);
        }

        .currency-details {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .currency-code {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .currency-name {
            font-size: 0.8rem;
            opacity: 0.7;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            flex-direction: column;
            gap: 4px;
            background: transparent;
            border: none;
            padding: 8px;
            cursor: pointer;
        }

        .hamburger-line {
            width: 24px;
            height: 2px;
            background: white;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .mobile-menu-toggle.active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .mobile-menu-toggle.active .hamburger-line:nth-child(2) {
            opacity: 0;
        }
        .mobile-menu-toggle.active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* Mobile Menu Overlay */
        .mobile-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1039;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .mobile-menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Mobile Menu */
        .mobile-menu {
            position: fixed;
            top: var(--navbar-height-desktop);
            left: 0;
            width: 100%;
            height: calc(100vh - var(--navbar-height-desktop));
            background: rgba(13, 20, 33, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            transform: translateX(-100%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1040;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .mobile-menu.active {
            transform: translateX(0);
        }
        
        /* RTL Support for Mobile Menu */
        [dir="rtl"] .mobile-menu {
            left: auto;
            right: 0;
            transform: translateX(100%);
        }
        
        [dir="rtl"] .mobile-menu.active {
            transform: translateX(0);
        }

        .mobile-menu-content {
            padding: 2rem;
            height: 100%;
            overflow-y: auto;
        }

        .mobile-nav-section {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: var(--border-radius-md);
            transition: all 0.3s ease;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .mobile-nav-link:hover,
        .mobile-nav-link.active {
            background: rgba(0, 127, 255, 0.1);
            color: white;
            text-decoration: none;
        }

        .mobile-nav-link i {
            font-size: 1.3rem;
            width: 24px;
        }

        .mobile-actions-section {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 2rem;
        }

        .mobile-action-btn {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 20px;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: var(--border-radius-md);
            transition: all 0.3s ease;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
        }

        .mobile-action-btn:hover {
            background: rgba(0, 127, 255, 0.1);
            color: white;
            text-decoration: none;
            border-color: rgba(0, 127, 255, 0.3);
        }

        .mobile-action-btn i {
            font-size: 1.2rem;
            width: 20px;
        }

        /* RTL Support */
        [dir="rtl"] .search-input-icon {
            margin-right: 0;
            margin-left: 15px;
        }
        
        [dir="rtl"] .suggestion-item:hover {
            transform: translateX(-5px);
        }
        
        [dir="rtl"] .search-brand {
            flex-direction: row-reverse;
        }
        
        [dir="rtl"] .search-brand-text {
            font-family: 'Noto Sans Arabic', sans-serif;
        }
        
        [dir="rtl"] .premium-search-input {
            text-align: right;
            font-family: 'Noto Sans Arabic', sans-serif;
        }
        
        [dir="rtl"] .quick-search-tags {
            justify-content: flex-end;
        }
        
        [dir="rtl"] .quick-tag {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .navbar-actions {
            direction: rtl;
        }

        [dir="rtl"] .cart-badge {
            right: auto;
            left: -8px;
        }
        
        /* RTL Search Modal Enhancements */
        [dir="rtl"] .suggestions-section-title {
            text-align: right;
        }
        
        [dir="rtl"] .suggestions-section-title i {
            margin-right: 0;
            margin-left: 8px;
        }
        
        [dir="rtl"] .suggestion-content {
            text-align: right;
        }
        
        [dir="rtl"] .search-submit-btn {
            margin-left: 0;
            margin-right: 10px;
        }
        
        [dir="rtl"] .search-close-btn {
            left: 20px;
            right: auto;
        }
        
        [dir="rtl"] .quick-search-title {
            text-align: right;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .btn-text {
                display: none;
            }
        }

        @media (max-width: 992px) {
            .voltronix-navbar {
                height: var(--navbar-height-mobile);
            }
            
            .navbar-container {
                padding: 0 1rem;
                gap: 0.5rem;
            }

            .navbar-left {
                gap: 0.5rem;
            }

            .navbar-controls {
                gap: 0.25rem;
            }

            .control-btn {
                padding: 6px 8px;
                font-size: 0.8rem;
            }

            .control-menu {
                display: none;
                position: absolute;
                z-index: 9999;
            }
            
            .control-dropdown.show .control-menu {
                display: block;
                z-index: 9999;
            }
            
            .user-menu {
                position: absolute;
                z-index: 9999;
            }

            .control-label {
                display: none;
            }

            .main-navigation {
                display: none;
            }

            .mobile-toggle {
                display: flex;
            }

            .navbar-right {
                gap: 0.25rem;
            }

            .action-btn {
                padding: 8px 10px;
                font-size: 0.8rem;
            }

            .btn-label {
                display: none !important;
            }

            .search-hint {
                display: none !important;
            }
        }

        @media (max-width: 768px) {
            .navbar-container {
                padding: 0 0.75rem;
            }

            .brand-text {
                display: none;
            }

            .navbar-controls .control-dropdown:last-child {
                display: none;
            }
        }
            
            .navbar-nav-section {
                display: none;
            }
            
            .navbar-actions .navbar-btn:not(.cart-btn) {
                display: none;
            }
            
            .mobile-menu-toggle {
                display: flex;
            }
            
            .mobile-menu {
                top: var(--navbar-height-mobile);
            }
        }

        @media (max-width: 576px) {
            .navbar-container {
                padding: 0 1rem;
            }
            
            .navbar-title {
                font-size: 1.2rem;
            }
            
            .navbar-logo {
                height: 32px;
            }
        }

        /* Animations */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        /* Accessibility */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* Focus States */
        .nav-link:focus,
        .navbar-btn:focus,
        .dropdown-item:focus,
        .mobile-nav-link:focus,
        .mobile-action-btn:focus {
            outline: 2px solid var(--voltronix-secondary);
            outline-offset: 2px;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Preloader -->
    <div class="preloader" id="preloader">
        <div class="preloader-content">
            <div class="preloader-logo">
                <img src="{{ asset('images/logo nt.png') }}" alt="{{ __('app.brand.name', ['default' => 'Voltronix']) }}" onerror="this.style.display='none'">
            </div>
            <div class="preloader-text">{{ __('app.brand.name', ['default' => 'Voltronix']) }}</div>
            <div class="preloader-spinner">
                <div class="spinner-ring"></div>
            </div>
        </div>
    </div>
    
    <!-- Top Progress Bar -->
    <div class="progress-bar-container" id="progressBarContainer">
        <div class="progress-bar" id="progressBar"></div>
    </div>
    
    <!-- Page Transition Overlay -->
    <div class="page-transition" id="pageTransition">
        <div class="page-transition-content">
            <div class="page-transition-spinner"></div>
            <div class="page-transition-text">{{ __('app.common.loading', ['default' => 'Loading...']) }}</div>
        </div>
    </div>
    
    <!-- Main Content Wrapper -->
    <div class="content-wrapper" id="contentWrapper">
        <!-- Modern Voltronix Navigation -->
        <div class="navbar-sticky-wrapper">
            <x-navbar />
        </div>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

    <!-- Footer -->
    <footer class="footer-voltronix">
        <div class="footer-content">
            <div class="volt-container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="footer-section">
                            <div class="footer-brand">
                                <img src="{{ asset('images/logo.png') }}" alt="{{ __('app.brand.name') }}" class="footer-logo">
                                <span class="footer-brand-text">{{ __('app.brand.name') }}</span>
                            </div>
                            <p class="footer-description">
                                {{ __('app.footer.description') }}
                            </p>
                            <div class="social-links">
                                @if(setting('facebook_url'))
                                    <a href="{{ setting('facebook_url') }}" class="social-link" title="{{ __('app.social.facebook') }}" target="_blank" rel="noopener"><i class="bi bi-facebook"></i></a>
                                @endif
                                @if(setting('twitter_url'))
                                    <a href="{{ setting('twitter_url') }}" class="social-link" title="{{ __('app.social.x') }}" target="_blank" rel="noopener">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                        </svg>
                                    </a>
                                @endif
                                @if(setting('instagram_url'))
                                    <a href="{{ setting('instagram_url') }}" class="social-link" title="{{ __('app.social.instagram') }}" target="_blank" rel="noopener"><i class="bi bi-instagram"></i></a>
                                @endif
                                @if(setting('whatsapp_number'))
                                    <a href="https://wa.me/{{ setting('whatsapp_number') }}" class="social-link" title="{{ __('app.social.whatsapp') }}" target="_blank" rel="noopener"><i class="bi bi-whatsapp"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-6 mb-3">
                        <div class="footer-section">
                            <h6 class="footer-title">{{ __('app.footer.quick_links') }}</h6>
                            <ul class="footer-links">
                                <li><a href="{{ url('/') }}"><i class="bi bi-house"></i>{{ __('app.nav.home') }}</a></li>
                                <li><a href="{{ route('categories.index') }}"><i class="bi bi-grid"></i>{{ __('app.nav.categories') }}</a></li>
                                <li><a href="{{ route('products.index') }}"><i class="bi bi-box"></i>{{ __('app.nav.products') }}</a></li>
                                <li><a href="{{ route('offers.index') }}"><i class="bi bi-lightning"></i>{{ __('app.nav.offers') }}</a></li>
                                <li><a href="{{ route('contact') }}"><i class="bi bi-envelope"></i>{{ __('app.nav.contact') }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-6 mb-3">
                        <div class="footer-section">
                            <h6 class="footer-title">{{ __('app.footer.legal') }}</h6>
                            <ul class="footer-links">
                                <li><a href="{{ route('terms') }}"><i class="bi bi-file-text"></i>{{ __('app.footer.terms') }}</a></li>
                                <li><a href="{{ route('privacy') }}"><i class="bi bi-shield-check"></i>{{ __('app.footer.privacy') }}</a></li>
                                <li><a href="{{ route('refund') }}"><i class="bi bi-arrow-return-left"></i>{{ __('app.footer.refund') }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 mb-3">
                        <div class="footer-section">
                            <h6 class="footer-title">{{ __('app.footer.connect') }}</h6>
                            <div class="contact-info">
                                @if(setting('contact_email'))
                                    <div class="contact-item">
                                        <i class="bi bi-envelope"></i>
                                        <span>{{ setting('contact_email') }}</span>
                                    </div>
                                @endif
                                @if(setting('contact_phone'))
                                    <div class="contact-item">
                                        <i class="bi bi-telephone"></i>
                                        <span>{{ setting('contact_phone') }}</span>
                                    </div>
                                @endif
                                @if(setting('contact_address_' . app()->getLocale()))
                                    <div class="contact-item">
                                        <i class="bi bi-geo-alt"></i>
                                        <span>{{ setting('contact_address_' . app()->getLocale()) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="footer-divider">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="footer-copyright">
                            {{ __('app.footer.copyright') }}
                        </p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="footer-made">
                            {{ __('app.footer.made_with') }} <i class="bi bi-heart-fill text-danger"></i> {{ __('app.footer.for_excellence') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Update cart badge on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartBadge();
        });

        function updateCartBadge() {
            fetch('/cart/count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('cartBadge');
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'inline';
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(error => console.log('Cart count error:', error));
        }

        // Cart functionality is now handled by cart.js

        /* ========================================
           GLOBAL VOLTRONIX SPINNER SYSTEM
           ======================================== */
        
        // Global Spinner Management
        window.VoltronixSpinner = {
            overlay: null,
            isVisible: false,
            
            // Initialize spinner system
            init() {
                this.createOverlay();
            },
            
            // Create the spinner overlay element
            createOverlay() {
                if (this.overlay) return;
                
                this.overlay = document.createElement('div');
                this.overlay.className = 'voltronix-loading-overlay';
                this.overlay.setAttribute('role', 'status');
                this.overlay.setAttribute('aria-live', 'polite');
                this.overlay.setAttribute('aria-label', 'Loading');
                
                document.body.appendChild(this.overlay);
            },
            
            // Show spinner with custom message
            show(message = null, subtext = null, type = 'circle') {
                if (!this.overlay) this.createOverlay();
                
                const defaultMessage = '{{ __("app.common.loading") }}';
                const finalMessage = message || defaultMessage;
                
                let spinnerHTML = '';
                
                if (type === 'dots') {
                    spinnerHTML = `
                        <div class="voltronix-spinner-container">
                            <div class="voltronix-spinner-content">
                                <div class="voltronix-dots-spinner">
                                    <div class="voltronix-dot"></div>
                                    <div class="voltronix-dot"></div>
                                    <div class="voltronix-dot"></div>
                                </div>
                                <p class="voltronix-spinner-text">${finalMessage}</p>
                                ${subtext ? `<p class="voltronix-spinner-subtext">${subtext}</p>` : ''}
                            </div>
                        </div>
                    `;
                } else {
                    spinnerHTML = `
                        <div class="voltronix-spinner-container">
                            <div class="voltronix-spinner-content">
                                <div class="voltronix-spinner">
                                    <div class="voltronix-spinner-circle"></div>
                                </div>
                                <p class="voltronix-spinner-text">${finalMessage}</p>
                                ${subtext ? `<p class="voltronix-spinner-subtext">${subtext}</p>` : ''}
                            </div>
                        </div>
                    `;
                }
                
                this.overlay.innerHTML = spinnerHTML;
                this.overlay.classList.add('show');
                this.isVisible = true;
                
                // Prevent body scrolling
                document.body.style.overflow = 'hidden';
            },
            
            // Hide spinner
            hide() {
                if (!this.overlay || !this.isVisible) return;
                
                this.overlay.classList.remove('show');
                this.isVisible = false;
                
                // Restore body scrolling
                document.body.style.overflow = '';
                
                // Hide after animation
                setTimeout(() => {
                    if (this.overlay && !this.isVisible) {
                        this.overlay.style.display = 'none';
                    }
                }, 300);
            },
            
            // Update spinner message
            updateMessage(message, subtext = null) {
                if (!this.overlay || !this.isVisible) return;
                
                const textElement = this.overlay.querySelector('.voltronix-spinner-text');
                const subtextElement = this.overlay.querySelector('.voltronix-spinner-subtext');
                
                if (textElement) {
                    textElement.textContent = message;
                }
                
                if (subtextElement && subtext) {
                    subtextElement.textContent = subtext;
                } else if (subtext && !subtextElement) {
                    const content = this.overlay.querySelector('.voltronix-spinner-content');
                    const newSubtext = document.createElement('p');
                    newSubtext.className = 'voltronix-spinner-subtext';
                    newSubtext.textContent = subtext;
                    content.appendChild(newSubtext);
                }
            }
        };
        
        // Global convenience functions
        window.showSpinner = function(message, subtext, type) {
            VoltronixSpinner.show(message, subtext, type);
        };
        
        window.hideSpinner = function() {
            VoltronixSpinner.hide();
        };
        
        window.updateSpinnerMessage = function(message, subtext) {
            VoltronixSpinner.updateMessage(message, subtext);
        };
        
        // Initialize spinner system when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            VoltronixSpinner.init();
            // Currency switcher now uses direct links like language switcher - no JS needed
            initializeNavbar();
        });
        
        // Premium Navbar initialization function
        function initializeNavbar() {
            initVoltageCanvas();
            
            // Mobile menu elements
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
            const mobileMenuClose = document.getElementById('mobileMenuClose');
            
            // Mobile menu toggle functionality
            if (mobileMenuToggle && mobileMenu && mobileMenuOverlay) {
                function openMobileMenu() {
                    mobileMenuToggle.classList.add('active');
                    mobileMenu.classList.add('active');
                    mobileMenuOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
                
                function closeMobileMenu() {
                    mobileMenuToggle.classList.remove('active');
                    mobileMenu.classList.remove('active');
                    mobileMenuOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
                
                // Toggle button click
                mobileMenuToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (mobileMenu.classList.contains('active')) {
                        closeMobileMenu();
                    } else {
                        openMobileMenu();
                    }
                });
                
                // Close button click
                if (mobileMenuClose) {
                    mobileMenuClose.addEventListener('click', closeMobileMenu);
                }
                
                // Overlay click to close
                mobileMenuOverlay.addEventListener('click', closeMobileMenu);
                
                // Close on escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                        closeMobileMenu();
                    }
                });
                
                // Close menu when clicking nav links
                const mobileNavLinks = mobileMenu.querySelectorAll('.mobile-nav-link, .mobile-action-link');
                mobileNavLinks.forEach(link => {
                    link.addEventListener('click', closeMobileMenu);
                });
            }
            
            // Navbar scroll effect
            const navbar = document.getElementById('mainNavbar');
            if (navbar) {
                let ticking = false;
                
                function updateNavbar() {
                    if (window.scrollY > 50) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }
                    ticking = false;
                }
                
                window.addEventListener('scroll', function() {
                    if (!ticking) {
                        requestAnimationFrame(updateNavbar);
                        ticking = true;
                    }
                });
            }
            const currencyOptions = document.querySelectorAll('.currency-option');
            
            currencyOptions.forEach(option => {
                option.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const currencyCode = this.getAttribute('data-currency');
                    const currencySymbol = this.getAttribute('data-symbol');
                    
                    if (currencyCode) {
                        switchCurrency(currencyCode, currencySymbol);
                    }
                });
            });
        }
        
        function switchCurrency(currencyCode, currencySymbol) {
            console.log('💱 switchCurrency called with:', { currencyCode, currencySymbol });
            
            // Prevent multiple switches with enhanced checking
            const now = Date.now();
            if (currencySwitchInProgress || (now - lastCurrencySwitch) < 1000) {
                console.log('⛔ Switch prevented - already in progress');
                return;
            }
            
            currencySwitchInProgress = true;
            lastCurrencySwitch = now;
            
            console.log('🚀 Starting currency switch...');
            
            // Show loading
            showSpinner('{{ __("app.currency.switching") }}...', '{{ __("app.common.please_wait") }}');
            
            const switchUrl = '{{ route("currency.switch") }}';
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            console.log('📡 Making fetch request to:', switchUrl);
            console.log('🔐 CSRF Token:', csrfToken ? 'Found' : 'Missing');
            console.log('📦 Request payload:', { currency: currencyCode });
            
            // Check if CSRF token exists
            if (!csrfToken) {
                console.error('❌ CSRF token not found! Falling back to form submission...');
                fallbackCurrencySwitch(currencyCode);
                return;
            }
            
            fetch(switchUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    currency: currencyCode
                })
            })
            .then(response => {
                console.log('📨 Fetch response status:', response.status);
                console.log('📨 Fetch response headers:', response.headers);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                return response.json();
            })
            .then(data => {
                console.log('✅ Response data:', data);
                hideSpinner();
                currencySwitchInProgress = false;
                
                if (data.success) {
                    // Update current currency display
                    const currentDisplay = document.getElementById('currentCurrencyDisplay');
                    if (currentDisplay) {
                        currentDisplay.textContent = `${currencySymbol} ${currencyCode}`;
                    }
                    
                    // Update active states
                    document.querySelectorAll('.currency-option').forEach(option => {
                        option.classList.remove('active');
                        const checkIcon = option.querySelector('.bi-check-circle');
                        if (checkIcon) {
                            checkIcon.remove();
                        }
                    });
                    
                    // Add active state to selected currency
                    const selectedOption = document.querySelector(`[data-currency="${currencyCode}"]`);
                    if (selectedOption) {
                        selectedOption.classList.add('active');
                        const checkIcon = document.createElement('i');
                        checkIcon.className = 'bi bi-check-circle text-success {{ app()->getLocale() == "ar" ? "me-2" : "ms-2" }}';
                        selectedOption.appendChild(checkIcon);
                    }
                    
                    // Update all prices dynamically first
                    updateAllPrices(currencyCode, currencySymbol);
                    
                    // Show smooth success notification (only once)
                    if (typeof Swal !== 'undefined') {
                        // Clear any existing toasts first
                        Swal.close();
                        
                        // Prevent multiple notifications
                        if (window.currencyNotificationTimeout) {
                            clearTimeout(window.currencyNotificationTimeout);
                        }
                        
                        window.currencyNotificationTimeout = setTimeout(() => {
                            const locale = document.documentElement.lang || 'en';
                            const message = locale === 'ar' ? 
                                `تم تغيير العملة إلى ${currencyCode} بنجاح` : 
                                `Currency switched to ${currencyCode} successfully`;
                            
                            Swal.fire({
                                icon: 'success',
                                title: message,
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end',
                                background: 'linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(248, 249, 250, 0.95))',
                                backdrop: false,
                                customClass: {
                                    popup: 'voltronix-toast'
                                },
                                didOpen: () => {
                                    // Ensure only one notification is visible
                                    const existingToasts = document.querySelectorAll('.swal2-container');
                                    if (existingToasts.length > 1) {
                                        for (let i = 0; i < existingToasts.length - 1; i++) {
                                            existingToasts[i].remove();
                                        }
                                    }
                                }
                            });
                        }, 100);
                    }
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __("admin.error") }}',
                            text: data.message
                        });
                    } else {
                        alert(data.message);
                    }
                }
            })
            .catch(error => {
                console.error('❌ AJAX currency switch failed:', error);
                console.log('🔄 Attempting fallback form submission...');
                
                hideSpinner();
                currencySwitchInProgress = false;
                
                // Try fallback form submission
                fallbackCurrencySwitch(currencyCode);
            });
        }
        
        // Fallback currency switching using form submission
        function fallbackCurrencySwitch(currencyCode) {
            console.log('📝 Creating fallback form for currency:', currencyCode);
            
            // Create a hidden form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("currency.switch") }}';
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            form.appendChild(csrfInput);
            
            // Add currency input
            const currencyInput = document.createElement('input');
            currencyInput.type = 'hidden';
            currencyInput.name = 'currency';
            currencyInput.value = currencyCode;
            form.appendChild(currencyInput);
            
            // Add redirect input to stay on current page
            const redirectInput = document.createElement('input');
            redirectInput.type = 'hidden';
            redirectInput.name = 'redirect_back';
            redirectInput.value = '1';
            form.appendChild(redirectInput);
            
            // Append form to body and submit
            document.body.appendChild(form);
            console.log('📤 Submitting fallback form...');
            form.submit();
        }
        
        // Initialize Currency Switcher
        let currencySwitchInProgress = false;
        let lastCurrencySwitch = 0;
        
        function initializeCurrencySwitcher() {
            console.log('🔄 Initializing currency switcher...');
            
            // Wait for navbar to be fully loaded
            const waitForNavbar = () => {
                const currencyOptions = document.querySelectorAll('.currency-option');
                console.log('🔍 Found currency options:', currencyOptions.length);
                
                if (currencyOptions.length === 0) {
                    console.log('⏳ Navbar not ready yet, retrying in 100ms...');
                    setTimeout(waitForNavbar, 100);
                    return;
                }
                
                console.log('✅ Currency options found, binding events...');
                
                currencyOptions.forEach((option, index) => {
                    console.log(`📌 Binding event to option ${index + 1}:`, {
                        currency: option.getAttribute('data-currency'),
                        symbol: option.getAttribute('data-symbol'),
                        element: option
                    });
                    
                    option.addEventListener('click', function(e) {
                        e.preventDefault();
                        console.log('🖱️ Currency option clicked:', {
                            currency: this.getAttribute('data-currency'),
                            symbol: this.getAttribute('data-symbol'),
                            element: this
                        });
                        
                        // Prevent multiple rapid clicks and rate limiting
                        const now = Date.now();
                        if (currencySwitchInProgress || (now - lastCurrencySwitch) < 1000) {
                            console.log('⛔ Currency switch prevented - in progress or rate limited');
                            return;
                        }
                        lastCurrencySwitch = now;
                        
                        const currencyCode = this.getAttribute('data-currency');
                        const currencySymbol = this.getAttribute('data-symbol');
                        console.log('💱 Currency data extracted:', { currencyCode, currencySymbol });
                        
                        if (currencyCode) {
                            console.log('🚀 Calling switchCurrency...');
                            switchCurrency(currencyCode, currencySymbol);
                        } else {
                            console.error('❌ No currency code found!');
                        }
                    });
                });
                
                console.log('✅ Currency switcher initialization complete!');
            };
            
            waitForNavbar();
        }
        
        // Manual test function for debugging (can be called from browser console)
        window.testCurrencySwitch = function(currencyCode = 'SAR') {
            console.log('🧪 Manual test: switching to', currencyCode);
            switchCurrency(currencyCode, 'ر.س');
        };
        
        // Debug function to check current state
        window.debugCurrency = function() {
            console.log('🔍 Currency Debug Info:');
            console.log('- Currency options found:', document.querySelectorAll('.currency-option').length);
            console.log('- CSRF token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ? 'Found' : 'Missing');
            console.log('- Current currency display:', document.getElementById('currentCurrencyDisplay')?.textContent);
            console.log('- Switch in progress:', currencySwitchInProgress);
            
            document.querySelectorAll('.currency-option').forEach((option, index) => {
                console.log(`- Option ${index + 1}:`, {
                    currency: option.getAttribute('data-currency'),
                    symbol: option.getAttribute('data-symbol'),
                    active: option.classList.contains('active')
                });
            });
        };
        
        // Enhanced dynamic price update function
        function updateAllPrices(currencyCode, currencySymbol) {
            // Get exchange rate for the new currency
            fetch(`/api/currency/rate/${currencyCode}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const rate = data.rate;
                        
                        // Update all price elements with comprehensive selectors
                        const priceSelectors = [
                            '[data-price]',
                            '.product-price',
                            '.trending-current-price',
                            '.trending-new-price',
                            '.trending-old-price',
                            '.offer-pricing .original-price',
                            '.offer-pricing .discounted-price',
                            '.cart-total',
                            '.cart-subtotal',
                            '.price-display',
                            '.currency-amount'
                        ];
                        
                        priceSelectors.forEach(selector => {
                            document.querySelectorAll(selector).forEach(element => {
                                let originalPrice;
                                
                                // Get original price from data attribute or text content
                                if (element.dataset.price) {
                                    originalPrice = parseFloat(element.dataset.price);
                                } else if (element.dataset.originalPrice) {
                                    originalPrice = parseFloat(element.dataset.originalPrice);
                                } else {
                                    // Extract price from text content
                                    const priceText = element.textContent.replace(/[^0-9.]/g, '');
                                    originalPrice = parseFloat(priceText);
                                    // Store original price for future conversions
                                    if (!element.dataset.originalPrice && !isNaN(originalPrice)) {
                                        element.dataset.originalPrice = originalPrice.toString();
                                    }
                                }
                                
                                if (!isNaN(originalPrice) && originalPrice > 0) {
                                    const convertedPrice = originalPrice * rate;
                                    const formattedPrice = formatCurrency(convertedPrice, currencySymbol);
                                    
                                    // Update the text content with animation
                                    element.style.transform = 'scale(1.05)';
                                    element.style.transition = 'transform 0.2s ease';
                                    
                                    setTimeout(() => {
                                        element.textContent = formattedPrice;
                                        element.style.transform = 'scale(1)';
                                    }, 100);
                                }
                            });
                        });
                        
                        // Update specific cart elements
                        updateCartPrices(rate, currencySymbol);
                        
                        // Update trending product prices
                        updateTrendingPrices(rate, currencySymbol);
                        
                        // Update special offers prices
                        updateOfferPrices(rate, currencySymbol);
                        
                        // Store current currency for future reference
                        localStorage.setItem('currentCurrency', JSON.stringify({
                            code: currencyCode,
                            symbol: currencySymbol,
                            rate: rate
                        }));
                        
                    }
                })
                .catch(error => {
                    console.error('Error updating prices:', error);
                    // Fallback: reload page after 3 seconds
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                });
        }
        
        // Helper function to update cart prices
        function updateCartPrices(rate, currencySymbol) {
            const cartElements = [
                '#cartTotal',
                '#cartSubtotal',
                '.cart-item-price',
                '.cart-summary-total'
            ];
            
            cartElements.forEach(selector => {
                const element = document.querySelector(selector);
                if (element && element.dataset.originalPrice) {
                    const originalPrice = parseFloat(element.dataset.originalPrice);
                    const convertedPrice = originalPrice * rate;
                    element.textContent = formatCurrency(convertedPrice, currencySymbol);
                }
            });
        }
        
        // Helper function to update trending product prices
        function updateTrendingPrices(rate, currencySymbol) {
            document.querySelectorAll('.trending-product-card').forEach(card => {
                const currentPrice = card.querySelector('.trending-current-price');
                const newPrice = card.querySelector('.trending-new-price');
                const oldPrice = card.querySelector('.trending-old-price');
                
                [currentPrice, newPrice, oldPrice].forEach(priceElement => {
                    if (priceElement && priceElement.dataset.originalPrice) {
                        const originalPrice = parseFloat(priceElement.dataset.originalPrice);
                        const convertedPrice = originalPrice * rate;
                        priceElement.textContent = formatCurrency(convertedPrice, currencySymbol);
                    }
                });
            });
        }
        
        // Helper function to update special offers prices
        function updateOfferPrices(rate, currencySymbol) {
            document.querySelectorAll('.offer-card').forEach(card => {
                const originalPrice = card.querySelector('.original-price');
                const discountedPrice = card.querySelector('.discounted-price');
                
                [originalPrice, discountedPrice].forEach(priceElement => {
                    if (priceElement && priceElement.dataset.originalPrice) {
                        const originalAmount = parseFloat(priceElement.dataset.originalPrice);
                        const convertedAmount = originalAmount * rate;
                        priceElement.textContent = formatCurrency(convertedAmount, currencySymbol);
                    }
                });
            });
        }
        
        // Currency formatting helper
        function formatCurrency(amount, symbol) {
            const formattedAmount = new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(amount);
            
            return `${symbol}${formattedAmount}`;
        }
        
        // Premium Search System
        let searchTimeout;
        let isSearchOverlayActive = false;
        
        function initializePremiumSearch() {
            const searchTrigger = document.getElementById('searchTrigger');
            const searchOverlay = document.getElementById('premiumSearchOverlay');
            const searchInput = document.getElementById('premiumSearchInput');
            const searchCloseBtn = document.getElementById('searchCloseBtn');
            const searchBackdrop = document.getElementById('searchOverlayBackdrop');
            const suggestionsContainer = document.getElementById('searchSuggestionsContainer');
            
            if (!searchTrigger || !searchOverlay) return;
            
            // Open search overlay
            searchTrigger.addEventListener('click', function(e) {
                e.preventDefault();
                openPremiumSearch();
            });
            
            // Close search overlay
            if (searchCloseBtn) {
                searchCloseBtn.addEventListener('click', closePremiumSearch);
            }
            
            if (searchBackdrop) {
                searchBackdrop.addEventListener('click', closePremiumSearch);
            }
            
            // Handle search input
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.trim();
                    clearTimeout(searchTimeout);
                    
                    if (query.length >= 2) {
                        searchTimeout = setTimeout(() => fetchPremiumSuggestions(query), 300);
                    } else {
                        hidePremiumSuggestions();
                    }
                });
                
                // Handle keyboard shortcuts
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closePremiumSearch();
                    }
                });
            }
            
            // Global keyboard shortcut (Ctrl/Cmd + K)
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    openPremiumSearch();
                }
            });
        }
        
        function openPremiumSearch() {
            const searchOverlay = document.getElementById('premiumSearchOverlay');
            const searchInput = document.getElementById('premiumSearchInput');
            
            if (searchOverlay) {
                isSearchOverlayActive = true;
                searchOverlay.classList.add('active');
                document.body.classList.add('search-overlay-open');
                
                setTimeout(() => {
                    if (searchInput) {
                        searchInput.focus();
                    }
                }, 300);
            }
        }
        
        function closePremiumSearch() {
            const searchOverlay = document.getElementById('premiumSearchOverlay');
            const searchInput = document.getElementById('premiumSearchInput');
            
            if (searchOverlay) {
                isSearchOverlayActive = false;
                searchOverlay.classList.remove('active');
                document.body.classList.remove('search-overlay-open');
                
                if (searchInput) {
                    searchInput.value = '';
                }
                hidePremiumSuggestions();
            }
        }
        
        function fetchPremiumSuggestions(query) {
            const suggestionsContainer = document.getElementById('searchSuggestionsContainer');
            const suggestionsContent = document.getElementById('suggestionsContent');
            
            if (!suggestionsContainer || !suggestionsContent) return;
            
            // Show skeleton loading state
            showSkeletonLoader();
            suggestionsContainer.classList.add('active');
            
            // Ensure the scrollable area is visible
            const scrollableArea = document.getElementById('searchResultsScrollable');
            if (scrollableArea) {
                scrollableArea.scrollTop = 0; // Reset scroll position
            }
            
            const url = `{{ route('search.ajax') }}?q=${encodeURIComponent(query)}`;
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    displayPremiumSuggestions(data);
                })
                .catch(error => {
                    console.error('Search error:', error);
                    suggestionsContent.innerHTML = `<div class="suggestions-error"><i class="bi bi-exclamation-triangle"></i>${searchTranslations.errorLoading}</div>`;
                });
        }
        
        function showSkeletonLoader() {
            const suggestionsContent = document.getElementById('suggestionsContent');
            if (!suggestionsContent) return;
            
            const skeletonHTML = `
                <div class="skeleton-container">
                    <div class="skeleton-item">
                        <div class="skeleton-icon"></div>
                        <div class="skeleton-content">
                            <div class="skeleton-title"></div>
                            <div class="skeleton-meta"></div>
                        </div>
                    </div>
                    <div class="skeleton-item">
                        <div class="skeleton-icon"></div>
                        <div class="skeleton-content">
                            <div class="skeleton-title"></div>
                            <div class="skeleton-meta"></div>
                        </div>
                    </div>
                    <div class="skeleton-item">
                        <div class="skeleton-icon"></div>
                        <div class="skeleton-content">
                            <div class="skeleton-title"></div>
                            <div class="skeleton-meta"></div>
                        </div>
                    </div>
                </div>
            `;
            
            suggestionsContent.innerHTML = skeletonHTML;
        }
        
        function displayPremiumSuggestions(data) {
            const suggestionsContent = document.getElementById('suggestionsContent');
            if (!suggestionsContent) return;
            
            let html = '';
            
            if (data.products && data.products.length > 0) {
                html += '<div class="suggestions-section">';
                const iconClass = document.documentElement.dir === 'rtl' ? 'ms-2' : 'me-2';
                html += `<h6 class="suggestions-section-title"><i class="bi bi-box-seam ${iconClass}"></i>${searchTranslations.products}</h6>`;
                html += '<div class="suggestions-list">';
                
                data.products.forEach(product => {
                    html += `
                        <a href="${product.url}" class="suggestion-item">
                            <div class="suggestion-icon">
                                ${product.thumbnail ? `<img src="${product.thumbnail}" alt="${product.name}">` : '<i class="bi bi-box-seam"></i>'}
                            </div>
                            <div class="suggestion-content">
                                <div class="suggestion-title">${product.name}</div>
                                <div class="suggestion-meta">${product.price} • ${product.category || 'Product'}</div>
                            </div>
                        </a>
                    `;
                });
                
                html += '</div></div>';
            }
            
            if (data.categories && data.categories.length > 0) {
                html += '<div class="suggestions-section">';
                const iconClass = document.documentElement.dir === 'rtl' ? 'ms-2' : 'me-2';
                html += `<h6 class="suggestions-section-title"><i class="bi bi-grid-3x3-gap ${iconClass}"></i>${searchTranslations.categories}</h6>`;
                html += '<div class="suggestions-list">';
                
                data.categories.forEach(category => {
                    html += `
                        <a href="${category.url}" class="suggestion-item">
                            <div class="suggestion-icon">
                                ${category.thumbnail ? `<img src="${category.thumbnail}" alt="${category.name}">` : '<i class="bi bi-grid-3x3-gap"></i>'}
                            </div>
                            <div class="suggestion-content">
                                <div class="suggestion-title">${category.name}</div>
                                <div class="suggestion-meta">${category.products_count} ${searchTranslations.products}</div>
                            </div>
                        </a>
                    `;
                });
                
                html += '</div></div>';
            }
            
            if (html === '') {
                html = `<div class="suggestions-empty"><i class="bi bi-search"></i><div>${searchTranslations.noSuggestions}</div></div>`;
            }
            
            suggestionsContent.innerHTML = html;
        }
        
        function hidePremiumSuggestions() {
            const suggestionsContainer = document.getElementById('searchSuggestionsContainer');
            if (suggestionsContainer) {
                suggestionsContainer.classList.remove('active');
            }
        }
        
        function displaySuggestions(data) {
            const searchSuggestions = document.getElementById('searchSuggestions');
            if (!searchSuggestions) return;
            
            if (data.total === 0) {
                searchSuggestions.innerHTML = '<div class="search-no-results">No results found</div>';
                return;
            }
            
            let html = '';
            
            // Add products
            data.products.forEach(item => {
                html += `
                    <a href="${item.url}" class="search-suggestion-item">
                        <img src="${item.thumbnail || '{{ asset("images/placeholder.png") }}'}" 
                             alt="${item.name}" class="search-suggestion-thumbnail">
                        <div class="search-suggestion-info">
                            <div class="search-suggestion-name">${item.name}</div>
                            <div class="search-suggestion-meta">${item.category || 'Product'}</div>
                        </div>
                        <div class="search-suggestion-price">${item.price}</div>
                    </a>
                `;
            });
            
            // Add categories
            data.categories.forEach(item => {
                html += `
                    <a href="${item.url}" class="search-suggestion-item">
                        <img src="${item.thumbnail || '{{ asset("images/placeholder.png") }}'}" 
                             alt="${item.name}" class="search-suggestion-thumbnail">
                        <div class="search-suggestion-info">
                            <div class="search-suggestion-name">${item.name}</div>
                            <div class="search-suggestion-meta">${item.products_count} products</div>
                        </div>
                    </a>
                `;
            });
            
            searchSuggestions.innerHTML = html;
            searchSuggestions.classList.add('active');
        }
        
        function hideSuggestions() {
            const searchSuggestions = document.getElementById('searchSuggestions');
            if (searchSuggestions) {
                searchSuggestions.classList.remove('active');
            }
        }
        
        // Translation variables for JavaScript
        const searchTranslations = {
            products: '{{ __("app.search.products") }}',
            categories: '{{ __("app.search.categories") }}',
            noSuggestions: '{{ __("app.search.no_suggestions") }}',
            errorLoading: '{{ __("app.search.error_loading") }}'
        };

        // Internal electric animation for brand text and logo
        function initBrandElectric() {
            const prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (prefersReducedMotion) return;

            const brandTitle = document.querySelector('.brand-title');
            const logoImg = document.querySelector('.brand-logo-img');
            const brandText = brandTitle ? brandTitle.textContent.trim() : '';
            const isArabicWordmark = brandTitle
                ? brandTitle.dataset.brandScript === 'arabic' || /[\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF]/.test(brandText)
                : false;

            if (brandTitle && brandTitle.dataset && !brandTitle.dataset.electricReady) {
                if (isArabicWordmark) {
                    brandTitle.dataset.electricReady = 'true';
                } else {
                    const chars = Array.from(brandText);
                    const frag = document.createDocumentFragment();

                    chars.forEach((ch, index) => {
                        const span = document.createElement('span');
                        span.className = 'brand-char';
                        span.textContent = ch;
                        span.style.setProperty('--char-index', index.toString());
                        frag.appendChild(span);
                    });

                    brandTitle.textContent = '';
                    brandTitle.appendChild(frag);
                    brandTitle.dataset.electricReady = 'true';
                }
            }

            const letterFlickerChance = 0.28;
            const letterGlitchChance = 0.14;
            const letterStrikeChance  = 0.10;
            const letterBoltChance    = 0.06;

            function triggerLetterEvents() {
                const letters = document.querySelectorAll('.brand-title .brand-char');
                if (!letters.length) return;

                letters.forEach(letter => {
                    const r = Math.random();
                    if (r < letterFlickerChance) {
                        letter.classList.add('char-flicker');
                        setTimeout(() => letter.classList.remove('char-flicker'), 260);
                    } else if (r < letterFlickerChance + letterGlitchChance) {
                        letter.classList.add('char-glitch');
                        setTimeout(() => letter.classList.remove('char-glitch'), 240);
                    } else if (r < letterFlickerChance + letterGlitchChance + letterStrikeChance) {
                        letter.classList.add('char-strike');
                        setTimeout(() => letter.classList.remove('char-strike'), 220);
                    } else if (r < letterFlickerChance + letterGlitchChance + letterStrikeChance + letterBoltChance) {
                        letter.classList.add('char-bolt');
                        setTimeout(() => letter.classList.remove('char-bolt'), 130);
                    }
                });
            }

            if (!isArabicWordmark && !window.__voltronixLetterTimer) {
                window.__voltronixLetterTimer = setInterval(triggerLetterEvents, 520);
            }

            function triggerLogoEvent() {
                if (!logoImg) return;
                const r = Math.random();
                if (r < 0.28) {
                    logoImg.classList.add('logo-flicker');
                    setTimeout(() => logoImg.classList.remove('logo-flicker'), 320);
                } else if (r < 0.34) {
                    logoImg.classList.add('logo-bolt');
                    setTimeout(() => logoImg.classList.remove('logo-bolt'), 140);
                }
            }

            if (!window.__voltronixLogoTimer) {
                // Run the existing electric effect roughly every 0.5s
                window.__voltronixLogoTimer = setInterval(triggerLogoEvent, 500);
            }
        }

        // Initialize systems when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            initializePremiumSearch();
            initBrandElectric();
            
            // Ensure search modal scrolls properly
            const searchResultsScrollable = document.getElementById('searchResultsScrollable');
            if (searchResultsScrollable) {
                // Add smooth scrolling behavior
                searchResultsScrollable.style.scrollBehavior = 'smooth';
                
                // Ensure the scrollable area covers the full modal height
                const updateScrollableHeight = () => {
                    const searchContainer = document.querySelector('.search-container');
                    const searchHeader = document.querySelector('.search-header');
                    const searchInputContainer = document.querySelector('.search-input-container');
                    
                    if (searchContainer && searchHeader && searchInputContainer) {
                        const containerHeight = searchContainer.offsetHeight;
                        const headerHeight = searchHeader.offsetHeight;
                        const inputHeight = searchInputContainer.offsetHeight;
                        const availableHeight = containerHeight - headerHeight - inputHeight - 40; // 40px for padding
                        
                        searchResultsScrollable.style.maxHeight = `${Math.max(400, availableHeight)}px`;
                    }
                };
                
                // Update height when search modal opens
                const searchOverlay = document.getElementById('premiumSearchOverlay');
                if (searchOverlay) {
                    const observer = new MutationObserver((mutations) => {
                        mutations.forEach((mutation) => {
                            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                                if (searchOverlay.classList.contains('active')) {
                                    setTimeout(updateScrollableHeight, 100);
                                }
                            }
                        });
                    });
                    observer.observe(searchOverlay, { attributes: true });
                }
                
                // Update on window resize
                window.addEventListener('resize', updateScrollableHeight);
            }
        });

        // Legacy compatibility functions (for existing code)
        window.showLoading = function(message) {
            showSpinner(message || '{{ __("app.common.loading") }}');
        };
        
        window.hideLoading = function() {
            hideSpinner();
        };

        let lastScrollTop = 0;
        let scrollTimeout;
        const navbar = document.querySelector('.voltronix-header');
        
        function handleScroll() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            
            if (scrollTop > 200) {
                if (scrollTop > lastScrollTop && !navbar.classList.contains('hidden')) {
                    navbar.classList.add('hidden');
                    navbar.classList.remove('visible');
                } else if (scrollTop < lastScrollTop && navbar.classList.contains('hidden')) {
                    navbar.classList.remove('hidden');
                    navbar.classList.add('visible');
                }
            } else {
                navbar.classList.remove('hidden');
                navbar.classList.add('visible');
            }
            
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        }
        
        window.addEventListener('scroll', function() {
            if (scrollTimeout) {
                clearTimeout(scrollTimeout);
            }
            scrollTimeout = setTimeout(handleScroll, 10);
        }, { passive: true });
        
        handleScroll();
        
        /* ========================================
           VOLTAGE CANVAS ANIMATION SYSTEM
           ======================================== */
        
        function initVoltageCanvas() {
            const canvas = document.getElementById('voltageCanvas');
            if (!canvas) return;
            
            const ctx = canvas.getContext('2d');
            const navbar = document.getElementById('mainNavbar');
            const lightningImg = new Image();
            lightningImg.src = '/images/26A1.svg';
            
            function resizeCanvas() {
                canvas.width = navbar.offsetWidth;
                canvas.height = navbar.offsetHeight;
            }
            
            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);
            
            const particles = [];
            const particleCount = 30;
            const lightningBolts = [];
            
            class Spark {
                constructor() {
                    this.reset();
                }
                
                reset() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.vx = (Math.random() - 0.5) * 1.5;
                    this.vy = (Math.random() - 0.5) * 0.5;
                    this.size = Math.random() * 2.5 + 0.5;
                    this.opacity = Math.random() * 0.6 + 0.2;
                    this.pulseSpeed = Math.random() * 0.03 + 0.01;
                    this.pulsePhase = Math.random() * Math.PI * 2;
                    this.color = Math.random() > 0.5 ? '#007fff' : '#23efff';
                    this.trail = [];
                }
                
                update() {
                    this.trail.push({ x: this.x, y: this.y });
                    if (this.trail.length > 5) this.trail.shift();
                    
                    this.x += this.vx;
                    this.y += this.vy;
                    this.pulsePhase += this.pulseSpeed;
                    
                    if (this.x < 0 || this.x > canvas.width) this.vx *= -1;
                    if (this.y < 0 || this.y > canvas.height) this.vy *= -1;
                    
                    this.x = Math.max(0, Math.min(canvas.width, this.x));
                    this.y = Math.max(0, Math.min(canvas.height, this.y));
                }
                
                draw() {
                    const pulse = Math.sin(this.pulsePhase) * 0.4 + 0.6;
                    const currentOpacity = this.opacity * pulse;
                    
                    this.trail.forEach((point, index) => {
                        const trailOpacity = currentOpacity * (index / this.trail.length) * 0.3;
                        ctx.beginPath();
                        ctx.arc(point.x, point.y, this.size * 0.5, 0, Math.PI * 2);
                        ctx.fillStyle = this.color;
                        ctx.globalAlpha = trailOpacity;
                        ctx.fill();
                    });
                    
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fillStyle = this.color;
                    ctx.globalAlpha = currentOpacity;
                    ctx.fill();
                    
                    const gradient = ctx.createRadialGradient(this.x, this.y, 0, this.x, this.y, this.size * 5);
                    gradient.addColorStop(0, this.color);
                    gradient.addColorStop(1, 'transparent');
                    ctx.fillStyle = gradient;
                    ctx.globalAlpha = currentOpacity * 0.4;
                    ctx.fill();
                }
            }
            
            class LightningBolt {
                constructor() {
                    this.reset();
                }
                
                reset() {
                    this.x = -30;
                    this.y = Math.random() * canvas.height;
                    this.size = Math.random() * 15 + 10;
                    this.speed = Math.random() * 3 + 2;
                    this.opacity = 0;
                    this.rotation = Math.random() * 360;
                    this.active = Math.random() > 0.95;
                }
                
                update() {
                    if (!this.active) {
                        if (Math.random() > 0.985) {
                            this.active = true;
                            this.reset();
                        }
                        return;
                    }
                    
                    this.x += this.speed;
                    this.rotation += 2;
                    
                    if (this.x < canvas.width * 0.3) {
                        this.opacity = Math.min(this.opacity + 0.08, 0.7);
                    } else {
                        this.opacity = Math.max(this.opacity - 0.04, 0);
                    }
                    
                    if (this.x > canvas.width + 30 || this.opacity <= 0) {
                        this.active = false;
                    }
                }
                
                draw() {
                    if (!this.active || this.opacity <= 0) return;
                    
                    ctx.save();
                    ctx.globalAlpha = this.opacity;
                    ctx.translate(this.x, this.y);
                    ctx.rotate((this.rotation * Math.PI) / 180);
                    
                    ctx.shadowBlur = 15;
                    ctx.shadowColor = '#007fff';
                    
                    if (lightningImg.complete) {
                        ctx.drawImage(lightningImg, -this.size / 2, -this.size / 2, this.size, this.size);
                    }
                    
                    ctx.restore();
                }
            }
            
            class VoltageArc {
                constructor(index) {
                    this.y = (canvas.height / 4) * (index + 1);
                    this.phase = Math.random() * Math.PI * 2;
                    this.speed = 0.02 + Math.random() * 0.01;
                    this.amplitude = 8 + Math.random() * 5;
                    this.frequency = 0.01 + Math.random() * 0.005;
                    this.color = index % 2 === 0 ? 'rgba(0, 127, 255, 0.25)' : 'rgba(35, 239, 255, 0.25)';
                }
                
                update() {
                    this.phase += this.speed;
                }
                
                draw() {
                    ctx.beginPath();
                    ctx.strokeStyle = this.color;
                    ctx.lineWidth = 1.5;
                    ctx.globalAlpha = 0.4;
                    
                    for (let x = 0; x < canvas.width; x += 3) {
                        const y = this.y + Math.sin(x * this.frequency + this.phase) * this.amplitude;
                        if (x === 0) ctx.moveTo(x, y);
                        else ctx.lineTo(x, y);
                    }
                    
                    ctx.stroke();
                }
            }
            
            for (let i = 0; i < particleCount; i++) {
                particles.push(new Spark());
            }
            
            for (let i = 0; i < 6; i++) {
                lightningBolts.push(new LightningBolt());
            }
            
            const arcs = [new VoltageArc(0), new VoltageArc(1), new VoltageArc(2)];
            
            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                
                arcs.forEach(arc => {
                    arc.update();
                    arc.draw();
                });
                
                particles.forEach(particle => {
                    particle.update();
                    particle.draw();
                });
                
                ctx.globalAlpha = 0.1;
                for (let i = 0; i < particles.length; i++) {
                    for (let j = i + 1; j < particles.length; j++) {
                        const dx = particles[i].x - particles[j].x;
                        const dy = particles[i].y - particles[j].y;
                        const distance = Math.sqrt(dx * dx + dy * dy);
                        
                        if (distance < 100) {
                            ctx.beginPath();
                            ctx.strokeStyle = '#007fff';
                            ctx.lineWidth = 0.5;
                            ctx.globalAlpha = (1 - distance / 100) * 0.15;
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.stroke();
                        }
                    }
                }
                
                lightningBolts.forEach(bolt => {
                    bolt.update();
                    bolt.draw();
                });
                
                ctx.globalAlpha = 1;
                requestAnimationFrame(animate);
            }
            
            animate();
        }
    </script>
    
        <!-- Loading System JavaScript -->
        <script src="{{ asset('js/loading.js') }}"></script>
        
        @stack('scripts')
    </div> <!-- End Content Wrapper -->
</body>
</html>
