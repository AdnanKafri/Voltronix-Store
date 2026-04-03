<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('admin.auth.admin_login') }} - Voltronix Digital Store</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    @if(app()->getLocale() == 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    @if(app()->getLocale() == 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --voltronix-primary: #007fff;
            --voltronix-secondary: #23efff;
            --voltronix-accent: #1a1a1a;
            --voltronix-dark: #0d1421;
            --voltronix-gradient: linear-gradient(135deg, #007fff, #23efff);
            --voltronix-dark-gradient: linear-gradient(135deg, #0d1421, #1a1a2e, #16213e);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: {{ app()->getLocale() == 'ar' ? "'Noto Sans Arabic', sans-serif" : "'Poppins', sans-serif" }};
            background: var(--voltronix-dark-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(0, 127, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(35, 239, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(255, 255, 255, 0.02) 0%, transparent 50%);
            animation: backgroundPulse 15s ease-in-out infinite;
        }

        @keyframes backgroundPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        /* Admin Login Container */
        .admin-login-container {
            background: rgba(26, 26, 46, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 127, 255, 0.2);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(0, 127, 255, 0.1) inset,
                0 0 100px rgba(0, 127, 255, 0.1);
            position: relative;
            z-index: 2;
            animation: containerFloat 8s ease-in-out infinite;
        }

        @keyframes containerFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        /* Admin Header */
        .admin-login-header {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
        }

        .admin-logo {
            width: 80px;
            height: 80px;
            background: var(--voltronix-gradient);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 127, 255, 0.3);
            animation: logoGlow 3s ease-in-out infinite alternate;
        }

        @keyframes logoGlow {
            0% { box-shadow: 0 10px 30px rgba(0, 127, 255, 0.3); }
            100% { box-shadow: 0 15px 40px rgba(0, 127, 255, 0.5); }
        }

        .admin-logo i {
            font-size: 2.5rem;
            color: white;
        }

        .admin-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem;
            font-weight: 900;
            color: white;
            margin-bottom: 0.5rem;
            text-shadow: 0 4px 20px rgba(0, 127, 255, 0.4);
        }

        .admin-subtitle {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
            font-weight: 400;
        }

        /* Form Styling */
        .admin-form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .admin-form-label {
            display: block;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .admin-form-input {
            width: 100%;
            padding: 1rem 1.25rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 127, 255, 0.3);
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .admin-form-input:focus {
            outline: none;
            border-color: var(--voltronix-primary);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(0, 127, 255, 0.2);
            transform: translateY(-2px);
        }

        .admin-form-input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .admin-form-input.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.2);
        }

        .admin-invalid-feedback {
            color: #ff6b6b;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: block;
        }

        /* Remember Me */
        .admin-form-check {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }

        .admin-form-check input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--voltronix-primary);
        }

        .admin-form-check label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            cursor: pointer;
        }

        /* Submit Button */
        .admin-submit-btn {
            width: 100%;
            padding: 1rem 2rem;
            background: var(--voltronix-gradient);
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

        .admin-submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .admin-submit-btn:hover::before {
            left: 100%;
        }

        .admin-submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(0, 127, 255, 0.4);
        }

        .admin-submit-btn:active {
            transform: translateY(0);
        }

        .admin-submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Links */
        .admin-links {
            text-align: center;
        }

        .admin-link {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .admin-link:hover {
            color: var(--voltronix-secondary);
            text-decoration: none;
        }

        /* Alert Styling */
        .admin-alert {
            background: rgba(0, 127, 255, 0.1);
            border: 1px solid rgba(0, 127, 255, 0.3);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: white;
            backdrop-filter: blur(10px);
        }

        .admin-alert.error {
            background: rgba(220, 53, 69, 0.1);
            border-color: rgba(220, 53, 69, 0.3);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .admin-login-container {
                margin: 1rem;
                padding: 2rem 1.5rem;
            }
            
            .admin-title {
                font-size: 1.6rem;
            }
            
            .admin-logo {
                width: 60px;
                height: 60px;
            }
            
            .admin-logo i {
                font-size: 2rem;
            }
        }

        /* RTL Support */
        [dir="rtl"] .admin-form-check {
            flex-direction: row-reverse;
        }

        /* Security Badge */
        .security-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(0, 127, 255, 0.2);
            border: 1px solid rgba(0, 127, 255, 0.3);
            border-radius: 8px;
            padding: 0.5rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.8rem;
            backdrop-filter: blur(10px);
        }

        [dir="rtl"] .security-badge {
            right: auto;
            left: 1rem;
        }

    </style>
</head>

<body>
    <!-- Security Badge -->
    <div class="security-badge">
        <i class="bi bi-shield-check {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
        {{ __('admin.auth.secure_access') }}
    </div>

    <!-- Admin Login Container -->
    <div class="admin-login-container">
        <!-- Admin Header -->
        <div class="admin-login-header">
            <div class="admin-logo">
                <i class="bi bi-lightning-charge"></i>
            </div>
            <h1 class="admin-title">{{ __('admin.auth.admin_access') }}</h1>
            <p class="admin-subtitle">{{ __('admin.auth.admin_subtitle') }}</p>
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('admin.login') }}" id="adminLoginForm">
            @csrf

            <!-- Session Status -->
            @if (session('status'))
                <div class="admin-alert">
                    <i class="bi bi-check-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ session('status') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if (session('error'))
                <div class="admin-alert error">
                    <i class="bi bi-exclamation-triangle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Email Address -->
            <div class="admin-form-group">
                <label for="email" class="admin-form-label">
                    <i class="bi bi-envelope {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.auth.email') }}
                </label>
                <input type="email" 
                       class="admin-form-input @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       placeholder="{{ __('admin.auth.email_placeholder') }}"
                       required 
                       autofocus 
                       autocomplete="username">
                @error('email')
                    <div class="admin-invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="admin-form-group">
                <label for="password" class="admin-form-label">
                    <i class="bi bi-lock {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.auth.password') }}
                </label>
                <input type="password" 
                       class="admin-form-input @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       placeholder="{{ __('admin.auth.password_placeholder') }}"
                       required 
                       autocomplete="current-password">
                @error('password')
                    <div class="admin-invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="admin-form-check">
                <input type="checkbox" id="remember_me" name="remember">
                <label for="remember_me">{{ __('admin.auth.remember_me') }}</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="admin-submit-btn" id="adminSubmitBtn">
                <i class="bi bi-shield-lock {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.auth.login_button') }}
            </button>

            <!-- Links -->
            <div class="admin-links">
                <a href="{{ url('/') }}" class="admin-link">
                    <i class="bi bi-arrow-left {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                    {{ __('admin.auth.back_to_site') }}
                </a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Form submission handling
        document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('adminSubmitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __("admin.common.loading") }}...';
        });

        // Show errors with SweetAlert2 if any
        @if ($errors->any())
            Swal.fire({
                title: '{{ __("admin.common.error") }}',
                html: '@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach',
                icon: 'error',
                confirmButtonColor: '#007fff',
                confirmButtonText: '{{ __("admin.common.ok") }}',
                background: 'rgba(26, 26, 46, 0.95)',
                color: 'white'
            });
        @endif

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Alt + A for admin quick access
            if (e.altKey && e.key === 'a') {
                document.getElementById('email').focus();
            }
        });

        // Security warning for demo
        console.log('%cVoltronix Admin Access', 'color: #007fff; font-size: 20px; font-weight: bold;');
        console.log('%cAuthorized personnel only. All access is logged.', 'color: #ff6b6b; font-size: 12px;');
    </script>
</body>
</html>
