@props([
    'title' => '',
    'subtitle' => '',
    'breadcrumbs' => [],
    'background' => 'default',
    'showSearch' => false,
    'searchRoute' => '',
    'searchPlaceholder' => ''
])

<!-- Unified Page Header -->
<div class="page-header {{ $background }}">
    <div class="page-header-background">
        <div class="gradient-overlay"></div>
        <div class="pattern-overlay"></div>
        <div class="particles-container">
            @for($i = 0; $i < 20; $i++)
                <div class="particle"></div>
            @endfor
        </div>
    </div>
    
    <div class="page-header-content">
        <div class="container">
            <!-- Breadcrumbs -->
            @if(count($breadcrumbs) > 0)
                <nav class="breadcrumb-nav" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}">
                                <i class="bi bi-house"></i>
                                <span>{{ __('app.nav.home') }}</span>
                            </a>
                        </li>
                        @foreach($breadcrumbs as $breadcrumb)
                            @if($loop->last)
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $breadcrumb['title'] }}
                                </li>
                            @else
                                <li class="breadcrumb-item">
                                    @if(isset($breadcrumb['url']))
                                        <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                                    @else
                                        {{ $breadcrumb['title'] }}
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
            @endif
            
            <!-- Header Content -->
            <div class="header-main">
                <div class="header-text">
                    @if($title)
                        <h1 class="page-title">{{ $title }}</h1>
                    @endif
                    
                    @if($subtitle)
                        <p class="page-subtitle">{{ $subtitle }}</p>
                    @endif
                </div>
                
                <!-- Search Section -->
                @if($showSearch && $searchRoute)
                    <div class="header-search">
                        <form action="{{ $searchRoute }}" method="GET" class="search-form">
                            <div class="search-input-group">
                                <input type="text" 
                                       name="search" 
                                       class="search-input" 
                                       placeholder="{{ $searchPlaceholder ?: __('app.common.search') }}"
                                       value="{{ request('search') }}"
                                       autocomplete="off">
                                <button type="submit" class="search-btn">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
            
            <!-- Additional Content Slot -->
            {{ $slot }}
        </div>
    </div>
</div>

@push('styles')
<style>
/* Page Header Styles */
.page-header {
    position: relative;
    min-height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    margin-top: var(--navbar-height-desktop);
    color: white;
}

.page-header-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--voltronix-gradient-dark);
    z-index: 1;
}

.page-header.default .page-header-background {
    background: var(--voltronix-gradient);
}

.page-header.dark .page-header-background {
    background: var(--voltronix-gradient-dark);
}

.page-header.light .page-header-background {
    background: var(--voltronix-gradient-light);
    color: var(--voltronix-accent);
}

.gradient-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0, 127, 255, 0.1) 0%, rgba(35, 239, 255, 0.05) 100%);
    z-index: 2;
}

.pattern-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
    background-size: 100px 100px;
    animation: patternMove 20s linear infinite;
    z-index: 3;
}

.particles-container {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 4;
}

.particle {
    position: absolute;
    width: 4px;
    height: 4px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    animation: float 15s infinite linear;
}

.particle:nth-child(odd) {
    animation-delay: -5s;
    background: rgba(0, 127, 255, 0.4);
}

.particle:nth-child(3n) {
    animation-delay: -10s;
    background: rgba(35, 239, 255, 0.3);
}

.particle:nth-child(1) { left: 10%; animation-duration: 12s; animation-delay: 2s; }
.particle:nth-child(2) { left: 20%; animation-duration: 15s; animation-delay: 5s; }
.particle:nth-child(3) { left: 30%; animation-duration: 18s; animation-delay: 1s; }
.particle:nth-child(4) { left: 40%; animation-duration: 14s; animation-delay: 8s; }
.particle:nth-child(5) { left: 50%; animation-duration: 16s; animation-delay: 3s; }
.particle:nth-child(6) { left: 60%; animation-duration: 13s; animation-delay: 7s; }
.particle:nth-child(7) { left: 70%; animation-duration: 17s; animation-delay: 4s; }
.particle:nth-child(8) { left: 80%; animation-duration: 19s; animation-delay: 6s; }
.particle:nth-child(9) { left: 90%; animation-duration: 11s; animation-delay: 9s; }
.particle:nth-child(10) { left: 15%; animation-duration: 20s; animation-delay: 1s; }
.particle:nth-child(11) { left: 25%; animation-duration: 12s; animation-delay: 10s; }
.particle:nth-child(12) { left: 35%; animation-duration: 16s; animation-delay: 2s; }
.particle:nth-child(13) { left: 45%; animation-duration: 14s; animation-delay: 11s; }
.particle:nth-child(14) { left: 55%; animation-duration: 18s; animation-delay: 3s; }
.particle:nth-child(15) { left: 65%; animation-duration: 13s; animation-delay: 12s; }
.particle:nth-child(16) { left: 75%; animation-duration: 17s; animation-delay: 4s; }
.particle:nth-child(17) { left: 85%; animation-duration: 15s; animation-delay: 13s; }
.particle:nth-child(18) { left: 95%; animation-duration: 19s; animation-delay: 5s; }
.particle:nth-child(19) { left: 5%; animation-duration: 11s; animation-delay: 14s; }
.particle:nth-child(20) { left: 95%; animation-duration: 16s; animation-delay: 6s; }

.page-header-content {
    position: relative;
    z-index: 10;
    width: 100%;
    padding: 3rem 0;
}

/* Breadcrumbs */
.breadcrumb-nav {
    margin-bottom: 2rem;
}

.breadcrumb {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: var(--border-radius-lg);
    padding: 12px 24px;
    margin: 0;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.breadcrumb-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: '';
    width: 6px;
    height: 6px;
    background: currentColor;
    border-radius: 50%;
    margin: 0 12px;
    opacity: 0.6;
}

.breadcrumb-item a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.breadcrumb-item a:hover {
    color: white;
    text-decoration: none;
}

.breadcrumb-item.active {
    color: white;
    font-weight: 600;
}

.breadcrumb-item i {
    font-size: 0.9rem;
}

/* Header Main Content */
.header-main {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 3rem;
    flex-wrap: wrap;
}

.header-text {
    flex: 1;
    min-width: 300px;
}

.page-title {
    font-family: 'Orbitron', sans-serif;
    font-size: 3.5rem;
    font-weight: 900;
    margin: 0 0 1rem 0;
    line-height: 1.2;
    background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 0 4px 20px rgba(0, 127, 255, 0.3);
    animation: titleGlow 3s ease-in-out infinite alternate;
}

.page-subtitle {
    font-size: 1.25rem;
    font-weight: 400;
    margin: 0;
    opacity: 0.9;
    line-height: 1.6;
    max-width: 600px;
}

/* Search Section */
.header-search {
    flex-shrink: 0;
    min-width: 300px;
}

.search-form {
    width: 100%;
}

.search-input-group {
    position: relative;
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-radius: var(--border-radius-lg);
    border: 1px solid rgba(255, 255, 255, 0.2);
    overflow: hidden;
    transition: all 0.3s ease;
}

.search-input-group:focus-within {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.4);
    box-shadow: 0 8px 32px rgba(0, 127, 255, 0.2);
}

.search-input {
    flex: 1;
    background: transparent;
    border: none;
    padding: 16px 20px;
    color: white;
    font-size: 1rem;
    font-weight: 500;
    outline: none;
}

.search-input::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.search-btn {
    background: var(--voltronix-gradient);
    border: none;
    padding: 16px 20px;
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.search-btn:hover {
    background: var(--voltronix-gradient-reverse);
    transform: scale(1.05);
}

.search-btn i {
    font-size: 1.2rem;
}

/* Light Theme Adjustments */
.page-header.light {
    color: var(--voltronix-accent);
}

.page-header.light .breadcrumb {
    background: rgba(0, 0, 0, 0.05);
    border-color: rgba(0, 0, 0, 0.1);
}

.page-header.light .breadcrumb-item a {
    color: rgba(0, 0, 0, 0.7);
}

.page-header.light .breadcrumb-item a:hover {
    color: var(--voltronix-accent);
}

.page-header.light .breadcrumb-item.active {
    color: var(--voltronix-accent);
}

.page-header.light .page-title {
    background: var(--voltronix-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.page-header.light .search-input-group {
    background: rgba(0, 0, 0, 0.05);
    border-color: rgba(0, 0, 0, 0.1);
}

.page-header.light .search-input {
    color: var(--voltronix-accent);
}

.page-header.light .search-input::placeholder {
    color: rgba(0, 0, 0, 0.5);
}

/* RTL Support */
[dir="rtl"] .breadcrumb-item + .breadcrumb-item::before {
    margin: 0 12px 0 12px;
}

[dir="rtl"] .header-main {
    direction: rtl;
}

[dir="rtl"] .search-input-group {
    direction: ltr;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .page-title {
        font-size: 3rem;
    }
    
    .header-main {
        gap: 2rem;
    }
}

@media (max-width: 992px) {
    .page-header {
        margin-top: var(--navbar-height-mobile);
        min-height: 250px;
    }
    
    .page-header-content {
        padding: 2rem 0;
    }
    
    .page-title {
        font-size: 2.5rem;
    }
    
    .header-main {
        flex-direction: column;
        align-items: flex-start;
        gap: 2rem;
    }
    
    .header-search {
        width: 100%;
        min-width: auto;
    }
}

@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .page-subtitle {
        font-size: 1.1rem;
    }
    
    .breadcrumb {
        padding: 10px 16px;
        font-size: 0.9rem;
    }
}

@media (max-width: 576px) {
    .page-header {
        min-height: 200px;
    }
    
    .page-title {
        font-size: 1.75rem;
    }
    
    .breadcrumb-item span,
    .breadcrumb-item a span {
        display: none;
    }
    
    .breadcrumb-item i {
        font-size: 1rem;
    }
}

/* Animations */
@keyframes patternMove {
    0% { transform: translate(0, 0); }
    100% { transform: translate(50px, 50px); }
}

@keyframes float {
    0% { 
        transform: translateY(100vh) rotate(0deg);
        opacity: 0;
    }
    10% {
        opacity: 1;
    }
    90% {
        opacity: 1;
    }
    100% { 
        transform: translateY(-100px) rotate(360deg);
        opacity: 0;
    }
}

@keyframes titleGlow {
    0% { text-shadow: 0 4px 20px rgba(0, 127, 255, 0.3); }
    100% { text-shadow: 0 4px 30px rgba(35, 239, 255, 0.4); }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    .particle,
    .pattern-overlay,
    .page-title {
        animation: none;
    }
}

/* Focus States */
.search-input:focus,
.search-btn:focus {
    outline: 2px solid rgba(255, 255, 255, 0.5);
    outline-offset: 2px;
}

.breadcrumb-item a:focus {
    outline: 2px solid rgba(255, 255, 255, 0.5);
    outline-offset: 2px;
    border-radius: 4px;
}
</style>
@endpush
