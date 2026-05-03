<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Voltronix Digital Store')</title>

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
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @if(app()->getLocale() == 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --voltronix-primary: #0d6efd;
            --voltronix-secondary: #000000;
            --voltronix-accent: #1a1a1a;
            --voltronix-light: #f8f9fa;
            --voltronix-dark: #212529;
        }
        
        body {
            font-family: {{ app()->getLocale() == 'ar' ? "'Noto Sans Arabic', sans-serif" : "'Poppins', sans-serif" }};
            background-color: var(--voltronix-light);
        }
        
        [dir="rtl"] .hero-content {
            text-align: right;
        }
        
        [dir="ltr"] .hero-content {
            text-align: left;
        }
        
        /* Navbar Styles */
        .navbar-voltronix {
            background: rgba(26, 26, 26, 0.95) !important;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            padding: 0.75rem 0;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-voltronix.scrolled {
            background: rgba(26, 26, 26, 0.98) !important;
            backdrop-filter: blur(15px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.15);
            padding: 0.5rem 0;
        }
        
        .navbar-brand-voltronix {
            font-size: 1.8rem;
            font-weight: 700;
            color: white !important;
            text-decoration: none;
            background: linear-gradient(45deg, var(--voltronix-primary), #00d4ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .nav-link-voltronix {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            margin: 0 0.2rem;
        }
        
        .nav-link-voltronix:hover {
            color: white !important;
            background: rgba(13, 110, 253, 0.1);
            transform: translateY(-1px);
        }
        
        .btn-voltronix {
            background: linear-gradient(45deg, var(--voltronix-primary), #0056b3);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-voltronix:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);
            color: white;
        }
        
        .btn-outline-voltronix {
            border: 2px solid var(--voltronix-primary);
            color: var(--voltronix-primary);
            background: transparent;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-outline-voltronix:hover {
            background: var(--voltronix-primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);
        }
        
        /* Auth Pages Styles */
        .auth-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }
        
        .auth-card {
            background: white;
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
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .auth-card {
                margin: 0 0.5rem;
                border-radius: 15px;
            }
            
            .auth-header, .auth-body {
                padding: 1.5rem;
            }
            
            .navbar-brand-voltronix {
                font-size: 1.5rem;
            }
        }
        
        /* RTL Support */
        [dir="rtl"] .navbar-nav {
            margin-right: auto;
            margin-left: 0;
        }
        
        [dir="rtl"] .dropdown-menu {
            right: 0;
            left: auto;
        }
    </style>
    
    @stack('styles')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-voltronix fixed-top">
        <div class="container">
            <a class="navbar-brand navbar-brand-voltronix" href="{{ url('/') }}">
                <i class="bi bi-lightning-charge-fill {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.hero.title') }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav {{ app()->getLocale() == 'ar' ? 'ms-auto' : 'me-auto' }}">
                    <li class="nav-item">
                        <a class="nav-link nav-link-voltronix" href="{{ url('/') }}">
                            <i class="bi bi-house {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>{{ __('app.nav.home') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-voltronix" href="{{ route('categories.index') }}">
                            <i class="bi bi-grid {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>{{ __('app.nav.categories') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-voltronix" href="{{ route('products.index') }}">
                            <i class="bi bi-box {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>{{ __('app.products.title') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-voltronix" href="{{ route('offers.index') }}">
                            <i class="bi bi-fire {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>{{ __('app.offers.title') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-voltronix" href="#about">
                            <i class="bi bi-info-circle {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>{{ __('app.nav.about') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-voltronix" href="#contact">
                            <i class="bi bi-envelope {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>{{ __('app.nav.contact') }}
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <!-- Cart (Always visible) -->
                    <li class="nav-item">
                        <a class="nav-link nav-link-voltronix position-relative" href="{{ route('cart.index') }}">
                            <i class="bi bi-cart3"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartBadge" style="display: none;">
                                0
                            </span>
                        </a>
                    </li>
                    
                    @auth
                        <!-- Orders (Authenticated users only) -->
                        <li class="nav-item">
                            <a class="nav-link nav-link-voltronix" href="{{ route('orders.index') }}">
                                <i class="bi bi-bag-check"></i>
                            </a>
                        </li>
                        
                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link nav-link-voltronix dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-{{ app()->getLocale() == 'ar' ? 'start' : 'end' }}">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person me-2"></i>{{ __('app.nav.profile') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('orders.index') }}">
                                        <i class="bi bi-bag-check me-2"></i>{{ __('app.orders.title') }}
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item" onclick="confirmLogout(event)">
                                            <i class="bi bi-box-arrow-right me-2"></i>{{ __('app.nav.logout') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <!-- Login/Register (Guest users only) -->
                        <li class="nav-item">
                            <a class="nav-link nav-link-voltronix" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>{{ __('app.nav.login') }}
                            </a>
                        </li>
                    @endauth
                    
                    <!-- Language Switcher -->
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-voltronix dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-globe {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                            {{ app()->isLocale('ar') ? __('app.language.arabic') : __('app.language.english') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-{{ app()->getLocale() == 'ar' ? 'start' : 'end' }}">
                            <li>
                                <a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('locale.switch', 'en') }}">
                                    🇺🇸 {{ __('app.language.english') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ app()->getLocale() == 'ar' ? 'active' : '' }}" href="{{ route('locale.switch', 'ar') }}">
                                    🇸🇦 {{ __('app.language.arabic') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main style="padding-top: 100px;">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Global JavaScript Functions -->
    <script>
        // Update cart badge on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartBadge();
        });

        // Update cart badge
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

        // Show notification using SweetAlert2
        function showNotification(message, type = 'success') {
            const Toast = Swal.mixin({
                toast: true,
                position: '{{ app()->getLocale() == "ar" ? "top-start" : "top-end" }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: type,
                title: message
            });
        }

        // Confirm logout
        function confirmLogout(event) {
            event.preventDefault();
            
            Swal.fire({
                title: '{{ __("app.auth.logout_confirm") }}',
                text: '{{ __("app.auth.logout_message") }}',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '{{ __("app.nav.logout") }}',
                cancelButtonText: '{{ __("app.common.cancel") }}',
                reverseButtons: {{ app()->getLocale() == 'ar' ? 'true' : 'false' }}
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.closest('form').submit();
                }
            });
        }

        // Show auth required notification
        function showAuthRequired() {
            Swal.fire({
                title: '{{ __("app.auth.login_required") }}',
                text: '{{ __("app.auth.login_message") }}',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '{{ __("app.nav.login") }}',
                cancelButtonText: '{{ __("app.common.cancel") }}',
                reverseButtons: {{ app()->getLocale() == 'ar' ? 'true' : 'false' }}
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("login") }}';
                }
            });
        }

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-voltronix');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
