<nav class="voltronix-header" id="mainNavbar">
    <div class="electric-border-top"></div>
    <canvas class="voltage-canvas" id="voltageCanvas"></canvas>
    
    <div class="header-container">
        <!-- LEFT: main navigation -->
        <div class="header-left">
            <nav class="header-nav">
                <a href="{{ url('/') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i>
                    <span>{{ __('app.nav.home') }}</span>
                </a>
                <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="bi bi-grid-3x3-gap"></i>
                    <span>{{ __('app.nav.categories') }}</span>
                </a>
                <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i>
                    <span>{{ __('app.nav.products') }}</span>
                </a>
                <a href="{{ route('offers.index') }}" class="nav-item {{ request()->routeIs('offers.*') ? 'active' : '' }}">
                    <i class="bi bi-lightning-charge"></i>
                    <span>{{ __('app.nav.offers') }}</span>
                </a>
            </nav>
        </div>

        <!-- CENTER: logo + brand name -->
        <div class="header-center">
            <a href="{{ url('/') }}" class="brand-section">
                <div class="brand-logo-wrapper">
                    <div class="logo-glow-ring"></div>
                    <svg class="electric-arc-container" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <path class="electric-arc arc-1" d="M20,50 Q30,30 40,50 T60,50" />
                        <path class="electric-arc arc-2" d="M40,20 Q50,35 60,20 T80,20" />
                        <path class="electric-arc arc-3" d="M20,70 Q35,60 50,70 T80,70" />
                        <path class="electric-arc arc-4" d="M50,10 L55,25 L48,25 L53,40" />
                    </svg>
                    <img src="{{ asset('images/logo.png') }}" alt="{{ __('app.brand.name') }}" class="brand-logo-img">
                    <img src="{{ asset('images/26A1.svg') }}" class="logo-lightning-accent" alt="">
                    <div class="spark spark-1"></div>
                    <div class="spark spark-2"></div>
                    <div class="spark spark-3"></div>
                    <div class="spark spark-4"></div>
                </div>
                <div class="brand-identity">
                    <span class="brand-title"
                          lang="{{ app()->getLocale() }}"
                          dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}"
                          data-brand-script="{{ app()->isLocale('ar') ? 'arabic' : 'latin' }}"
                          data-text="{{ __('app.brand.name') }}">
                        {{ __('app.brand.name') }}
                    </span>
                    <svg class="text-lightning-svg" viewBox="0 0 300 80" xmlns="http://www.w3.org/2000/svg">
                        <path class="text-arc t-arc-1" d="M10,40 Q80,20 150,40 T290,40" />
                        <path class="text-arc t-arc-2" d="M10,50 Q80,65 150,50 T290,50" />
                    </svg>
                </div>
            </a>
        </div>

        <!-- RIGHT: search, cart, orders, account, language, currency -->
        <div class="header-right">
            <div class="header-actions">
                <button class="action-icon" id="searchTrigger">
                    <i class="bi bi-search"></i>
                </button>
                
                <a href="{{ route('cart.index') }}" class="action-icon cart-icon">
                    <i class="bi bi-bag"></i>
                    <span class="action-badge cart-badge" id="cartBadge" style="display: none;">0</span>
                </a>
                
                @auth
                <a href="{{ route('orders.index') }}" class="action-icon">
                    <i class="bi bi-receipt"></i>
                </a>
                
                <div class="control-group">
                    <button class="action-icon user-icon" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i>
                    </button>
                    <ul class="dropdown-menu ctrl-dropdown user-dropdown">
                        <li class="dropdown-header">
                            <div class="user-info-header">
                                <i class="bi bi-person-circle"></i>
                                <div>
                                    <div class="user-name-text">{{ Auth::user()->name }}</div>
                                    <div class="user-email-text">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person-gear"></i>{{ __('app.nav.profile') }}
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right"></i>{{ __('app.nav.logout') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @else
                <a href="{{ route('login') }}" class="action-btn-login">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>{{ __('app.nav.login') }}</span>
                </a>
                @endauth
            </div>

            <div class="header-controls">
                <div class="control-group">
                    <button class="ctrl-btn" data-bs-toggle="dropdown">
                        <i class="bi bi-translate"></i>
                        <span class="ctrl-text">{{ app()->getLocale() == 'ar' ? 'العربية' : 'English' }}</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu ctrl-dropdown">
                        <li><a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('locale.switch', 'en') }}">
                            <i class="bi bi-check-circle"></i>English
                        </a></li>
                        <li><a class="dropdown-item {{ app()->getLocale() == 'ar' ? 'active' : '' }}" href="{{ route('locale.switch', 'ar') }}">
                            <i class="bi bi-check-circle"></i>العربية
                        </a></li>
                    </ul>
                </div>
                
                <div class="control-group">
                    <button class="ctrl-btn" data-bs-toggle="dropdown">
                        <i class="bi bi-currency-exchange"></i>
                        <span class="ctrl-text">{{ current_currency()->code }}</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu ctrl-dropdown">
                        @foreach(active_currencies() as $currency)
                        <li><a class="dropdown-item {{ current_currency()->code === $currency->code ? 'active' : '' }}" href="{{ route('currency.switch.get', $currency->code) }}">
                            <i class="bi bi-check-circle"></i>{{ $currency->code }} ({{ $currency->symbol }})
                        </a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <button class="mobile-menu-btn" id="mobileMenuToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
    
    <div class="electric-border-bottom"></div>
</nav>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
    
    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <div class="mobile-brand">
                <img src="{{ asset('images/logo.png') }}" alt="{{ __('app.brand.name') }}" class="mobile-logo">
                <span class="mobile-brand-name"
                      lang="{{ app()->getLocale() }}"
                      dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}">
                    {{ __('app.brand.name') }}
                </span>
            </div>
            <button class="mobile-close" id="mobileMenuClose">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <div class="mobile-menu-content">
            <!-- Mobile Navigation Links -->
            <div class="mobile-nav-section">
                <h6 class="mobile-section-title">{{ __('app.nav.navigation') }}</h6>
                <a class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ url('/') }}">
                    <i class="bi bi-house-door"></i>
                    <span>{{ __('app.nav.home') }}</span>
                    <i class="bi bi-chevron-right link-arrow"></i>
                </a>
                <a class="mobile-nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                    <i class="bi bi-grid-3x3-gap"></i>
                    <span>{{ __('app.nav.categories') }}</span>
                    <i class="bi bi-chevron-right link-arrow"></i>
                </a>
                <a class="mobile-nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                    <i class="bi bi-box-seam"></i>
                    <span>{{ __('app.nav.products') }}</span>
                    <i class="bi bi-chevron-right link-arrow"></i>
                </a>
                <a class="mobile-nav-link {{ request()->routeIs('offers.*') ? 'active' : '' }}" href="{{ route('offers.index') }}">
                    <i class="bi bi-lightning"></i>
                    <span>{{ __('app.nav.offers') }}</span>
                    <i class="bi bi-chevron-right link-arrow"></i>
                </a>
            </div>

            <!-- Mobile User Actions -->
            <div class="mobile-actions-section">
                <h6 class="mobile-section-title">{{ __('app.nav.account') }}</h6>
                
                <a class="mobile-action-link" href="{{ route('cart.index') }}">
                    <i class="bi bi-bag"></i>
                    <span>{{ __('app.nav.cart') }}</span>
                    <span class="mobile-cart-badge cart-badge" id="mobileCartBadge" style="display: none;">0</span>
                </a>
                
                @auth
                    <a class="mobile-action-link" href="{{ route('orders.index') }}">
                        <i class="bi bi-receipt"></i>
                        <span>{{ __('app.nav.orders') }}</span>
                    </a>
                    <a class="mobile-action-link" href="{{ route('profile.edit') }}">
                        <i class="bi bi-person-gear"></i>
                        <span>{{ __('app.nav.profile') }}</span>
                    </a>
                    <div class="mobile-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="mobile-action-link logout-link">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>{{ __('app.nav.logout') }}</span>
                        </button>
                    </form>
                @else
                    <a class="mobile-action-link login-link" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>{{ __('app.nav.login') }}</span>
                    </a>
                @endauth
            </div>
            
            <!-- Mobile Controls -->
            <div class="mobile-controls-section">
                <h6 class="mobile-section-title">{{ __('app.nav.settings') }}</h6>
                
                <div class="mobile-control-group">
                    <label class="mobile-control-label">{{ __('app.nav.language') }}</label>
                    <div class="mobile-control-options">
                        <a class="mobile-control-option {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('locale.switch', 'en') }}">
                            <span class="option-flag">🇺🇸</span>
                            <span>{{ __('app.language.english') }}</span>
                        </a>
                        <a class="mobile-control-option {{ app()->getLocale() == 'ar' ? 'active' : '' }}" href="{{ route('locale.switch', 'ar') }}">
                            <span class="option-flag">🇸🇦</span>
                            <span>العربية</span>
                        </a>
                    </div>
                </div>
                
                <div class="mobile-control-group">
                    <label class="mobile-control-label">{{ __('app.nav.currency') }}</label>
                    <div class="mobile-control-options mobile-currency-options">
                        @foreach(active_currencies() as $currency)
                            <a class="mobile-control-option currency-option {{ current_currency()->code === $currency->code ? 'active' : '' }}" 
                               href="#" 
                               data-currency="{{ $currency->code }}"
                               data-symbol="{{ $currency->symbol }}">
                                <span class="option-symbol">{{ $currency->symbol }}</span>
                                <span>{{ $currency->code }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Premium Search Overlay -->
<div class="premium-search-overlay" id="premiumSearchOverlay">
    <div class="search-overlay-backdrop" id="searchOverlayBackdrop"></div>
    <div class="search-overlay-content">
        <div class="search-container">
            <div class="search-header">
                <div class="search-brand">
                    <i class="bi bi-search search-brand-icon"></i>
                    <span class="search-brand-text">{{ __('app.search.title') }}</span>
                </div>
                <button type="button" class="search-close-btn" id="searchCloseBtn">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            <div class="search-input-container">
                <form action="{{ route('search.index') }}" method="GET" class="premium-search-form" id="premiumSearchForm">
                    <div class="search-input-wrapper">
                        <i class="bi bi-search search-input-icon"></i>
                        <input type="text" 
                               name="q" 
                               id="premiumSearchInput" 
                               class="premium-search-input"
                               placeholder="{{ __('app.search.placeholder') }}"
                               autocomplete="off"
                               autofocus>
                        <button type="submit" class="search-submit-btn">
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Scrollable Search Results Area -->
            <div class="search-results-scrollable" id="searchResultsScrollable">
                <!-- Live Search Suggestions -->
                <div class="search-suggestions-container" id="searchSuggestionsContainer">
                    <div class="suggestions-content" id="suggestionsContent"></div>
                </div>
                
                <!-- Quick Search Categories -->
                <div class="quick-search-section">
                    <h6 class="quick-search-title">{{ __('app.search.popular_categories') }}</h6>
                    <div class="quick-search-tags">
                        <a href="{{ route('products.index', ['category' => 'software']) }}" class="quick-tag">
                            <i class="bi bi-laptop"></i>
                            <span>{{ __('app.categories.software.title') }}</span>
                        </a>
                        <a href="{{ route('products.index', ['category' => 'gaming']) }}" class="quick-tag">
                            <i class="bi bi-controller"></i>
                            <span>{{ __('app.categories.gaming.title') }}</span>
                        </a>
                        <a href="{{ route('offers.index') }}" class="quick-tag">
                            <i class="bi bi-lightning"></i>
                            <span>{{ __('app.nav.offers') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
