@extends('layouts.app')

@section('title', __('app.profile.title') . ' - Voltronix')

@push('styles')
<style>
    /* Profile Page Styling - Consistent with Voltronix Theme */
    .profile-header {
        background: linear-gradient(135deg, var(--voltronix-primary), var(--voltronix-secondary));
        color: white;
        padding: 4rem 0 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 3rem;
    }
    
    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        animation: pulse 4s ease-in-out infinite alternate;
    }
    
    .profile-header .volt-container {
        position: relative;
        z-index: 2;
    }
    
    .profile-header h1 {
        font-family: 'Orbitron', sans-serif;
        font-weight: 900;
        color: #ffffff !important;
        margin-bottom: 0;
        text-shadow: 0 4px 0px rgba(255, 255, 255, 0.6), 0 2px 10px rgba(0, 0, 0, 0.8) !important;
        letter-spacing: 2px;
        font-size: 3.5rem;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        filter: drop-shadow(0 0 20px rgba(255, 255, 255, 0.8));
    }
    
    .profile-header h1 i {
        color: #ffffff !important;
        text-shadow: 0 4px 0px rgba(0, 127, 255, 0.8), 0 2px 10px rgba(0, 0, 0, 0.9) !important;
        filter: drop-shadow(0 0 15px rgba(255, 255, 255, 0.9));
    }
    
    .profile-header .lead {
        color: #ffffff !important;
        font-weight: 600;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.8), 0 1px 5px rgba(0, 127, 255, 0.4) !important;
        font-size: 1.3rem;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.6));
    }
    
    /* Enhanced breadcrumb visibility */
    .profile-header .breadcrumb-voltronix .breadcrumb-item,
    .profile-header .breadcrumb-voltronix .breadcrumb-item a {
        color: #ffffff !important;
        text-shadow: none;
        font-weight: 700;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    
    .profile-header .breadcrumb-voltronix .breadcrumb-item.active {
        color: #ffffff !important;
        font-weight: 700;
        text-shadow: none;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    
    /* Enhanced Breadcrumb Styling for Profile Page */
    .profile-header .breadcrumb-voltronix {
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 0.75rem 1.25rem;
        margin-bottom: 2rem;
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }
    
    .profile-header .breadcrumb-voltronix .breadcrumb-item {
        font-weight: 500;
        font-size: 0.9rem;
    }
    
    .profile-header .breadcrumb-voltronix .breadcrumb-item a {
        color: #ffffff !important;
        text-decoration: none;
        transition: all 0.3s ease;
        opacity: 1;
        text-shadow: none;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    
    .profile-header .breadcrumb-voltronix .breadcrumb-item a:hover {
        color: #23efff !important;
        opacity: 1;
        text-shadow: none;
        transform: translateY(-1px);
    }
    
    .profile-header .breadcrumb-voltronix .breadcrumb-item.active {
        color: #ffffff !important;
        font-weight: 800;
        opacity: 1;
        text-shadow: none;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    
    .profile-header .breadcrumb-voltronix .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: var(--voltronix-secondary) !important;
        font-weight: bold;
        margin: 0 0.75rem;
        opacity: 0.8;
        text-shadow: none;
    }
    
    /* RTL Support for Breadcrumbs */
    [dir="rtl"] .profile-header .breadcrumb-voltronix .breadcrumb-item + .breadcrumb-item::before {
        content: "‹";
    }
    
    .profile-nav {
        background: white;
        border-radius: 15px;
        padding: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }
    
    .profile-nav .nav-pills .nav-link {
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        color: #6c757d;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .profile-nav .nav-pills .nav-link.active {
        background: var(--voltronix-gradient);
        color: white;
        border-color: var(--voltronix-primary);
        box-shadow: 0 5px 15px rgba(0, 127, 255, 0.3);
    }
    
    .profile-nav .nav-pills .nav-link:hover:not(.active) {
        background: var(--voltronix-light);
        color: var(--voltronix-primary);
        border-color: var(--voltronix-primary);
    }
    
    .profile-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 2px solid rgba(0, 127, 255, 0.1);
        margin-bottom: 2rem;
    }
    
    .profile-card h3 {
        font-family: 'Orbitron', sans-serif;
        color: var(--voltronix-accent);
        font-weight: 700;
        margin-bottom: 2rem;
    }
    
    .form-label-modern {
        font-weight: 600;
        color: var(--voltronix-accent);
        margin-bottom: 0.5rem;
    }
    
    .form-control-modern {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control-modern:focus {
        border-color: var(--voltronix-primary);
        box-shadow: 0 0 0 0.2rem rgba(0, 127, 255, 0.25);
    }
    
    .btn-voltronix {
        background: var(--voltronix-gradient);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-voltronix:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 127, 255, 0.3);
        color: white;
    }
    
    .btn-outline-voltronix {
        background: transparent;
        border: 2px solid var(--voltronix-primary);
        color: var(--voltronix-primary);
        padding: 0.5rem 1rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-outline-voltronix:hover {
        background: var(--voltronix-primary);
        color: white;
        transform: translateY(-2px);
    }
    
    /* RTL Support */
    [dir="rtl"] .profile-nav .nav-pills .nav-link i {
        margin-left: 0.5rem;
        margin-right: 0;
    }
    
    @media (max-width: 768px) {
        .profile-header {
            padding: 3rem 0 1.5rem;
        }
        
        .profile-header h1 {
            font-size: 2rem;
        }
        
        .profile-card {
            padding: 1.5rem;
        }
        
        .profile-nav .nav-pills .nav-link {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Profile Header -->
<section class="profile-header">
    <div class="volt-container">
        <br>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-voltronix">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">{{ __('app.nav.home') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ __('app.profile.title') }}
                </li>
            </ol>
        </nav>
        
        <h1 class="display-4 fw-bold mb-3 title-orbitron">
            <i class="bi bi-person-circle {{ app()->getLocale() == 'ar' ? 'ms-3' : 'me-3' }}"></i>
            {{ __('app.profile.title') }}
        </h1>
        <p class="lead mb-0">{{ __('app.profile.manage_orders') }}</p>
    </div>
</section>

<div class="volt-container py-5">

    <!-- Profile Navigation -->
    <div class="row">
        <div class="col-12">
            <div class="profile-nav">
                <ul class="nav nav-pills" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="personal-tab" data-bs-toggle="pill" data-bs-target="#personal" type="button" role="tab" aria-controls="personal" aria-selected="true">
                            <i class="bi bi-person {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('app.profile.personal_info') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="orders-tab" data-bs-toggle="pill" data-bs-target="#orders" type="button" role="tab" aria-controls="orders" aria-selected="false">
                            <i class="bi bi-bag-check {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('app.profile.order_history') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="settings-tab" data-bs-toggle="pill" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">
                            <i class="bi bi-gear {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('app.profile.settings') }}
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="row">
        <div class="col-12">
            <div class="tab-content" id="profileTabsContent">
                <!-- Personal Information Tab -->
                <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                    <div class="profile-card">
                        <h3 class="mb-4">{{ __('app.profile.edit_profile') }}</h3>
                        
                        <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
                            @csrf
                            @method('patch')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label form-label-modern">{{ __('app.auth.name') }}</label>
                                    <input type="text" 
                                           class="form-control form-control-modern @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $user->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label form-label-modern">{{ __('app.auth.email') }}</label>
                                    <input type="email" 
                                           class="form-control form-control-modern @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label form-label-modern">{{ __('app.auth.phone') }}</label>
                                    <input type="tel" 
                                           class="form-control form-control-modern @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label form-label-modern">{{ __('app.profile.member_since') }}</label>
                                    <input type="text" 
                                           class="form-control form-control-modern" 
                                           value="{{ $user->created_at->format('F j, Y') }}" 
                                           readonly>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-voltronix">
                                    <i class="bi bi-check-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                    {{ __('app.profile.update_profile') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Order History Tab -->
                <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                    <div class="profile-card">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="mb-0">{{ __('app.profile.order_history') }}</h3>
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-voltronix btn-sm">
                                {{ __('app.common.view_all') }}
                            </a>
                        </div>

                        @if($orders->count() > 0)
                            <div class="row">
                                @foreach($orders as $order)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title mb-0">{{ $order->order_number }}</h6>
                                                    <span class="badge {{ $order->status_badge_class }}">
                                                        {{ $order->localized_status }}
                                                    </span>
                                                </div>
                                                <p class="card-text text-muted small mb-2">
                                                    {{ $order->created_at->format('M j, Y') }}
                                                </p>
                                                <p class="card-text">
                                                    <strong>{{ $order->formatted_total }}</strong>
                                                    <small class="text-muted">
                                                        ({{ $order->items->count() }} {{ __('app.common.items') }})
                                                    </small>
                                                </p>
                                                <a href="{{ route('orders.show', $order->order_number) }}" class="btn btn-sm btn-outline-primary">
                                                    {{ __('app.profile.view_order') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-bag-x display-1 text-muted mb-3"></i>
                                <h5 class="text-muted">{{ __('app.profile.no_orders') }}</h5>
                                <p class="text-muted">{{ __('app.orders.start_shopping') }}</p>
                                <a href="{{ route('products.index') }}" class="btn btn-voltronix">
                                    {{ __('app.nav.products') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Settings Tab -->
                <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                    <div class="profile-card">
                        <h3 class="mb-4">{{ __('app.profile.change_password') }}</h3>
                        
                        <form method="POST" action="{{ route('profile.password') }}" id="passwordForm">
                            @csrf
                            @method('patch')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="current_password" class="form-label form-label-modern">{{ __('app.profile.current_password') }}</label>
                                    <input type="password" 
                                           class="form-control form-control-modern @error('current_password') is-invalid @enderror" 
                                           id="current_password" 
                                           name="current_password" 
                                           required>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6"></div>

                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label form-label-modern">{{ __('app.profile.new_password') }}</label>
                                    <input type="password" 
                                           class="form-control form-control-modern @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label form-label-modern">{{ __('app.profile.confirm_password') }}</label>
                                    <input type="password" 
                                           class="form-control form-control-modern @error('password_confirmation') is-invalid @enderror" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-voltronix">
                                    <i class="bi bi-shield-check {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                    {{ __('app.profile.change_password') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Handle form submissions
document.getElementById('profileForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>{{ __("app.common.loading") }}...';
});

document.getElementById('passwordForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>{{ __("app.common.loading") }}...';
});

// Show success messages
@if (session('status') == 'profile-updated')
    Swal.fire({
        title: '{{ __("app.common.success") }}',
        text: '{{ __("app.profile.profile_updated") }}',
        icon: 'success',
        confirmButtonColor: '#0d6efd',
        confirmButtonText: '{{ __("app.common.ok") }}'
    });
@endif

@if (session('status') == 'password-updated')
    Swal.fire({
        title: '{{ __("app.common.success") }}',
        text: '{{ __("app.profile.password_updated") }}',
        icon: 'success',
        confirmButtonColor: '#0d6efd',
        confirmButtonText: '{{ __("app.common.ok") }}'
    });
@endif

// Show errors with SweetAlert2 if any
@if ($errors->any())
    Swal.fire({
        title: '{{ __("app.common.error") }}',
        html: '@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach',
        icon: 'error',
        confirmButtonColor: '#0d6efd',
        confirmButtonText: '{{ __("app.common.ok") }}'
    });
@endif
</script>
@endpush
@endsection
