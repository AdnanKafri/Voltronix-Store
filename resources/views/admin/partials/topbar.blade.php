<div class="admin-topbar">
    <div class="d-flex align-items-center">
        <button class="btn d-md-none me-3" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
        <h1 class="topbar-title mb-0">@yield('page-title', 'Dashboard')</h1>
    </div>

    <div class="topbar-actions">
        <!-- Language Switcher -->
        <div class="dropdown me-3">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-translate me-1"></i>
                {{ app()->getLocale() == 'ar' ? 'العربية' : 'English' }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}" 
                       href="{{ route('locale.switch', 'en') }}">
                        <i class="bi bi-check me-2 {{ app()->getLocale() == 'en' ? '' : 'invisible' }}"></i>
                        English
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ app()->getLocale() == 'ar' ? 'active' : '' }}" 
                       href="{{ route('locale.switch', 'ar') }}">
                        <i class="bi bi-check me-2 {{ app()->getLocale() == 'ar' ? '' : 'invisible' }}"></i>
                        العربية
                    </a>
                </li>
            </ul>
        </div>

        <!-- Notifications -->
        <div class="dropdown">
            <button class="notification-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-bell"></i>
                @php
                    $totalNotifications = \App\Models\Order::where('status', 'pending')->count();
                    // Add ProductReview notifications when model is implemented
                    // + \App\Models\ProductReview::where('status', 'pending')->count();
                @endphp
                @if($totalNotifications > 0)
                    <span class="notification-badge">{{ $totalNotifications > 99 ? '99+' : $totalNotifications }}</span>
                @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                <li class="dropdown-header">
                    <strong>{{ __('admin.notifications') }}</strong>
                </li>
                <li><hr class="dropdown-divider"></li>
                
                @php
                    $pendingOrders = \App\Models\Order::where('status', 'pending')->latest()->take(3)->get();
                    // $pendingReviews = \App\Models\ProductReview::where('status', 'pending')->latest()->take(3)->get();
                    $pendingReviews = collect(); // Empty collection until ProductReview model is implemented
                @endphp

                @forelse($pendingOrders as $order)
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.orders.show', $order) }}">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-receipt text-primary me-2"></i>
                                <div>
                                    <div class="fw-bold">{{ __('admin.new_order') }}</div>
                                    <small class="text-muted">{{ $order->order_number }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                @empty
                @endforelse

                @forelse($pendingReviews as $review)
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.reviews.show', $review) }}">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-star text-warning me-2"></i>
                                <div>
                                    <div class="fw-bold">{{ __('admin.new_review') }}</div>
                                    <small class="text-muted">{{ Str::limit($review->product->getTranslation('name'), 30) }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                @empty
                @endforelse

                @if($totalNotifications == 0)
                    <li class="dropdown-item text-center text-muted py-3">
                        {{ __('admin.no_notifications') }}
                    </li>
                @endif

                @if($totalNotifications > 0)
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-center" href="#">
                            {{ __('admin.view_all_notifications') }}
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        <!-- Profile Dropdown -->
        <div class="dropdown">
            <div class="admin-profile dropdown-toggle" data-bs-toggle="dropdown">
                <div class="profile-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="d-none d-md-block">
                    <div class="fw-bold">{{ auth()->user()->name }}</div>
                    <small class="text-muted">{{ __('admin.administrator') }}</small>
                </div>
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li class="dropdown-header">
                    <strong>{{ auth()->user()->name }}</strong><br>
                    <small class="text-muted">{{ auth()->user()->email }}</small>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="bi bi-person me-2"></i>
                        {{ __('admin.profile') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                        <i class="bi bi-gear me-2"></i>
                        {{ __('admin.settings') }}
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            {{ __('admin.nav.logout') }}
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
