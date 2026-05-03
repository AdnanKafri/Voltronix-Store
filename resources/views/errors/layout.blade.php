<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $code }} - {{ __('app.errors.title') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=orbitron:700,900|poppins:400,500,600,700" rel="stylesheet" />
    @if(app()->getLocale() === 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @endif
    <style>
        :root {
            --voltronix-primary: #007fff;
            --voltronix-secondary: #23efff;
            --voltronix-accent: #121826;
            --voltronix-muted: #5b6472;
            --voltronix-light: #f6f8fc;
            --voltronix-card: #ffffff;
            --voltronix-border: #e5eaf1;
            --voltronix-gradient: linear-gradient(135deg, #007fff 0%, #23efff 100%);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: {{ app()->getLocale() === 'ar' ? "'Noto Sans Arabic', sans-serif" : "'Poppins', sans-serif" }};
            background:
                radial-gradient(circle at 14% 20%, rgba(0,127,255,0.15), transparent 38%),
                radial-gradient(circle at 90% 86%, rgba(35,239,255,0.18), transparent 42%),
                var(--voltronix-light);
            color: var(--voltronix-accent);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .error-shell {
            width: 100%;
            max-width: 760px;
            background: var(--voltronix-card);
            border: 1px solid var(--voltronix-border);
            border-radius: 22px;
            box-shadow: 0 20px 50px rgba(0, 33, 82, 0.12);
            padding: clamp(1.25rem, 4vw, 2.6rem);
            text-align: center;
        }

        .error-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 62px;
            height: 62px;
            border-radius: 16px;
            background: var(--voltronix-gradient);
            color: #fff;
            font-size: 1.55rem;
            margin-bottom: 0.95rem;
            box-shadow: 0 10px 22px rgba(0,127,255,0.3);
        }

        .error-code {
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            font-size: clamp(3rem, 12vw, 5.5rem);
            line-height: 1;
            letter-spacing: 2px;
            background: var(--voltronix-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .error-title {
            margin: 0.85rem 0 0.45rem;
            font-size: clamp(1.25rem, 3vw, 1.85rem);
            font-weight: 700;
        }

        .error-description {
            margin: 0 auto 1.5rem;
            max-width: 560px;
            color: var(--voltronix-muted);
            font-size: 1rem;
            line-height: 1.65;
        }

        .error-actions {
            display: flex;
            gap: 0.7rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .error-btn {
            border: 0;
            text-decoration: none;
            border-radius: 11px;
            padding: 0.72rem 1rem;
            min-width: 152px;
            font-size: 0.95rem;
            font-weight: 700;
            transition: transform 0.18s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .error-btn:hover {
            transform: translateY(-1px);
        }

        .error-btn-primary {
            background: var(--voltronix-gradient);
            color: #fff;
            box-shadow: 0 10px 18px rgba(0,127,255,0.22);
        }

        .error-btn-secondary {
            background: #edf5ff;
            color: #0056b3;
        }

        .error-btn-ghost {
            background: #fff;
            color: #3b4758;
            border: 1px solid var(--voltronix-border);
        }

        .error-footer {
            margin-top: 1.15rem;
            color: #7a8596;
            font-size: 0.86rem;
        }

        @media (max-width: 640px) {
            .error-shell { border-radius: 16px; }
            .error-actions { flex-direction: column; }
            .error-btn { width: 100%; }
        }
    </style>
</head>
<body>
    <main class="error-shell" role="main" aria-labelledby="error-title">
        <div class="error-badge" aria-hidden="true">
            <i class="bi bi-shield-exclamation"></i>
            !
        </div>
        <h1 class="error-code">{{ $code }}</h1>
        <h2 id="error-title" class="error-title">{{ $title }}</h2>
        <p class="error-description">{{ $description }}</p>

        <div class="error-actions">
            <a class="error-btn error-btn-primary" href="{{ route('home') }}">{{ __('app.errors.actions.home') }}</a>
            <a class="error-btn error-btn-secondary" href="{{ route('products.index') }}">{{ __('app.errors.actions.products') }}</a>
            <a class="error-btn error-btn-ghost" href="{{ route('contact') }}">{{ __('app.errors.actions.support') }}</a>
        </div>

        <div class="error-footer">{{ __('app.errors.footer') }}</div>
    </main>
</body>
</html>
