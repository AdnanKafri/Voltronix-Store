<div class="admin-sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            VOLTRONIX
        </div>
        <div class="sidebar-subtitle">{{ __('admin.nav.dashboard') }}</div>
        <button class="sidebar-toggle" onclick="toggleSidebarCollapse()" title="{{ __('admin.nav.toggle_sidebar') }}">
            <i class="bi bi-chevron-double-left"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" 
                   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>{{ __('admin.nav.dashboard') }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.products.index') }}" 
                   class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i>
                    <span>{{ __('admin.nav.products') }}</span>
                </a>
            </li>


            <!-- Users -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
                   href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people"></i>
                    <span>{{ __('admin.users.title') }}</span>
                    @php
                        $newUsersThisWeek = \App\Models\User::where('created_at', '>=', now()->subWeek())->count();
                    @endphp
                    @if($newUsersThisWeek > 0)
                        <span class="badge bg-info rounded-pill {{ app()->getLocale() == 'ar' ? 'me-auto' : 'ms-auto' }}">{{ $newUsersThisWeek }}</span>
                    @endif
                </a>
            </li>

            
            <li class="nav-item">
                <a href="{{ route('admin.orders.index') }}" 
                   class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i>
                    <span>{{ __('admin.nav.orders') }}</span>
                    @php
                        $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
                    @endphp
                    @if($pendingOrders > 0)
                        <span class="badge bg-danger rounded-pill {{ app()->getLocale() == 'ar' ? 'me-auto' : 'ms-auto' }}">{{ $pendingOrders }}</span>
                    @endif
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.deliveries.index') }}" 
                   class="nav-link {{ request()->routeIs('admin.deliveries.*') || request()->routeIs('admin.orders.deliveries.*') ? 'active' : '' }}">
                    <i class="bi bi-truck"></i>
                    <span>{{ __('admin.nav.deliveries') }}</span>
                    @php
                        $activeDeliveries = \App\Models\OrderDelivery::where('revoked', false)->count();
                    @endphp
                    @if($activeDeliveries > 0)
                        <span class="badge bg-success rounded-pill {{ app()->getLocale() == 'ar' ? 'me-auto' : 'ms-auto' }}">{{ $activeDeliveries }}</span>
                    @endif
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.reviews.index') }}" 
                   class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <i class="bi bi-star"></i>
                    <span>{{ __('admin.nav.reviews') }}</span>
                    @php
                        // Note: ProductReview model doesn't exist yet, so we'll comment this out
                        // $pendingReviews = \App\Models\ProductReview::where('status', 'pending')->count();
                        $pendingReviews = 0; // Placeholder until ProductReview model is implemented
                    @endphp
                    @if($pendingReviews > 0)
                        <span class="badge bg-warning rounded-pill {{ app()->getLocale() == 'ar' ? 'me-auto' : 'ms-auto' }}">{{ $pendingReviews }}</span>
                    @endif
                </a>
            </li>

            <!-- Categories -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" 
                   href="{{ route('admin.categories.index') }}">
                    <i class="bi bi-tags"></i>
                    <span>{{ __('admin.category.title') }}</span>
                </a>
            </li>

            <!-- Coupons -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}" 
                   href="{{ route('admin.coupons.index') }}">
                    <i class="bi bi-ticket-perforated"></i>
                    <span>{{ __('admin.coupon.title') }}</span>
                </a>
            </li>

            <!-- Currencies -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.currencies.*') ? 'active' : '' }}" 
                   href="{{ route('admin.currencies.index') }}">
                    <i class="bi bi-currency-exchange"></i>
                    <span>{{ __('admin.currency.title') }}</span>
                </a>
            </li>




            <li class="nav-item">
                <a href="{{ route('admin.settings.index') }}" 
                   class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    <span>{{ __('admin.nav.settings') }}</span>
                </a>
            </li>

            <li class="nav-item mt-4">
                <hr class="text-white-50">
            </li>

            <li class="nav-item">
                <a href="{{ route('home') }}" 
                   class="nav-link" 
                   target="_blank">
                    <i class="bi bi-arrow-up-right-square"></i>
                    <span>{{ __('admin.nav.view_site') }}</span>
                </a>
            </li>

            <li class="nav-item">
                <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>{{ __('admin.nav.logout') }}</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</div>
