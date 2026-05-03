@extends('admin.layouts.app')

@section('title', __('admin.coupon.view') . ' - ' . $coupon->code)

@section('content')
<div class="container-fluid">
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="title-orbitron mb-2">{{ __('admin.coupon.view') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">{{ __('admin.nav.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.coupons.index') }}">{{ __('admin.coupon.title') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $coupon->code }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-primary">
                    <i class="fas fa-edit {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.coupon.edit') }}
                </a>
                <button type="button" class="btn btn-outline-danger" id="deleteCouponBtn" 
                        data-coupon-id="{{ $coupon->id }}" data-coupon-code="{{ $coupon->code }}" 
                        data-used-count="{{ $coupon->used_count }}">
                    <i class="fas fa-trash {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.coupon.delete') }}
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.back') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Coupon Details -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card-voltronix mb-4">
                <div class="card-header">
                    <h5 class="section-title mb-0">
                        <i class="fas fa-info-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.coupon.general_info') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <label>{{ __('admin.coupon.code') }}</label>
                                <div class="info-value">
                                    <span class="badge bg-primary fs-6">{{ $coupon->code }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label>{{ __('admin.coupon.status') }}</label>
                                <div class="info-value">
                                    <form method="POST" action="{{ route('admin.coupons.toggle-status', $coupon) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $coupon->status_badge_class }} toggle-status-btn">
                                            <i class="fas fa-{{ $coupon->is_active ? 'check' : 'times' }}"></i>
                                            {{ $coupon->status_text }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="info-item">
                                <label>{{ __('admin.coupon.name') }} ({{ __('admin.english') }})</label>
                                <div class="info-value">{{ $coupon->getTranslation('name', 'en') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label>{{ __('admin.coupon.name') }} ({{ __('admin.arabic') }})</label>
                                <div class="info-value">{{ $coupon->getTranslation('name', 'ar') }}</div>
                            </div>
                        </div>
                    </div>

                    @if($coupon->getTranslation('description', 'en') || $coupon->getTranslation('description', 'ar'))
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label>{{ __('admin.coupon.description') }} ({{ __('admin.english') }})</label>
                                    <div class="info-value">{{ $coupon->getTranslation('description', 'en') ?: '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label>{{ __('admin.coupon.description') }} ({{ __('admin.arabic') }})</label>
                                    <div class="info-value">{{ $coupon->getTranslation('description', 'ar') ?: '-' }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Discount Settings -->
            <div class="card-voltronix mb-4">
                <div class="card-header">
                    <h5 class="section-title mb-0">
                        <i class="fas fa-percent {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.coupon.discount_settings') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-item">
                                <label>{{ __('admin.coupon.type') }}</label>
                                <div class="info-value">
                                    <span class="badge {{ $coupon->type == 'percentage' ? 'bg-info' : 'bg-success' }}">
                                        {{ __('admin.coupon.type_' . $coupon->type) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-item">
                                <label>{{ __('admin.coupon.value') }}</label>
                                <div class="info-value">
                                    <strong class="text-primary">{{ $coupon->formatted_value }}</strong>
                                </div>
                            </div>
                        </div>
                        @if($coupon->max_discount)
                            <div class="col-md-4">
                                <div class="info-item">
                                    <label>{{ __('admin.coupon.max_discount') }}</label>
                                    <div class="info-value">{{ $coupon->formatted_max_discount }}</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($coupon->min_order_value)
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label>{{ __('admin.coupon.min_order_value') }}</label>
                                    <div class="info-value">{{ $coupon->formatted_min_order_value }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Date & Usage Restrictions -->
            <div class="card-voltronix mb-4">
                <div class="card-header">
                    <h5 class="section-title mb-0">
                        <i class="fas fa-calendar {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.coupon.restrictions') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <label>{{ __('admin.coupon.start_date') }}</label>
                                <div class="info-value">
                                    @if($coupon->start_date)
                                        {{ local_datetime($coupon->start_date, 'M d, Y H:i') }}
                                    @else
                                        <span class="text-muted">{{ __('admin.immediately') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label>{{ __('admin.coupon.expiry_date') }}</label>
                                <div class="info-value">
                                    @if($coupon->expiry_date)
                                        {{ local_datetime($coupon->expiry_date, 'M d, Y H:i') }}
                                        @if($coupon->expiry_date->isPast())
                                            <span class="badge bg-danger ms-2">{{ __('admin.coupon.expired') }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">{{ __('admin.never') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="info-item">
                                <label>{{ __('admin.coupon.usage_limit') }}</label>
                                <div class="info-value">
                                    @if($coupon->usage_limit)
                                        {{ number_format($coupon->usage_limit) }}
                                    @else
                                        <span class="text-muted">{{ __('admin.unlimited') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label>{{ __('admin.coupon.per_user_limit') }}</label>
                                <div class="info-value">{{ number_format($coupon->per_user_limit) }}</div>
                            </div>
                        </div>
                    </div>

                    @if($coupon->target_user_id || $coupon->first_time_only)
                        <div class="row mt-3">
                            @if($coupon->target_user_id)
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label>{{ __('admin.coupon.target_user') }}</label>
                                        <div class="info-value">
                                            @if($coupon->targetUser)
                                                {{ $coupon->targetUser->name }} ({{ $coupon->targetUser->email }})
                                            @else
                                                <span class="text-muted">{{ __('admin.user_deleted') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($coupon->first_time_only)
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label>{{ __('admin.coupon.first_time_only') }}</label>
                                        <div class="info-value">
                                            <span class="badge bg-warning">{{ __('admin.yes') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Stats -->
        <div class="col-lg-4">
            <!-- Usage Statistics -->
            <div class="card-voltronix mb-4">
                <div class="card-header">
                    <h5 class="section-title mb-0">
                        <i class="fas fa-chart-bar {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.coupon.usage_stats') }}
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="stat-item mb-3">
                        <div class="stat-number">{{ number_format($coupon->used_count) }}</div>
                        <div class="stat-label">{{ __('admin.coupon.used_count') }}</div>
                    </div>
                    
                    @if($coupon->usage_limit)
                        <div class="progress mb-3">
                            @php
                                $percentage = min(100, ($coupon->used_count / $coupon->usage_limit) * 100);
                            @endphp
                            <div class="progress-bar bg-primary" style="width: {{ $percentage }}%"></div>
                        </div>
                        <div class="text-muted small">
                            {{ number_format($coupon->usage_limit - $coupon->used_count) }} {{ __('admin.remaining') }}
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="stat-mini">
                                <div class="stat-mini-number">{{ $coupon->orders_count ?? 0 }}</div>
                                <div class="stat-mini-label">{{ __('admin.nav.orders') }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-mini">
                                <div class="stat-mini-number">${{ number_format($coupon->total_discount ?? 0, 2) }}</div>
                                <div class="stat-mini-label">{{ __('admin.total_discount') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card-voltronix mb-4">
                <div class="card-header">
                    <h5 class="section-title mb-0">
                        <i class="fas fa-bolt {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.coupon.quick_actions') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-primary">
                            <i class="fas fa-edit {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('admin.coupon.edit') }}
                        </a>
                        
                        <form method="POST" action="{{ route('admin.coupons.toggle-status', $coupon) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-{{ $coupon->is_active ? 'warning' : 'success' }} w-100">
                                <i class="fas fa-{{ $coupon->is_active ? 'pause' : 'play' }} {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ $coupon->is_active ? __('admin.coupon.deactivate') : __('admin.coupon.activate') }}
                            </button>
                        </form>

                        <button type="button" class="btn btn-outline-danger w-100" id="deleteCouponBtn2" 
                                data-coupon-id="{{ $coupon->id }}" data-coupon-code="{{ $coupon->code }}" 
                                data-used-count="{{ $coupon->used_count }}">
                            <i class="fas fa-trash {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('admin.coupon.delete') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            @if($coupon->orders && $coupon->orders->count() > 0)
                <div class="card-voltronix">
                    <div class="card-header">
                        <h5 class="section-title mb-0">
                            <i class="fas fa-shopping-cart {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('admin.coupon.order_history') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($coupon->orders->take(5) as $order)
                            <div class="order-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $order->order_number }}</strong>
                                        <div class="text-muted small">{{ $order->customer_name }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-success">${{ number_format($order->discount_amount, 2) }}</div>
                                        <div class="text-muted small">{{ local_datetime($order->created_at, 'M d') }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($coupon->orders->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.orders.index', ['coupon' => $coupon->code]) }}" class="btn btn-sm btn-outline-primary">
                                    {{ __('admin.view_all') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete coupon functionality
    const deleteButtons = document.querySelectorAll('#deleteCouponBtn, #deleteCouponBtn2');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const couponId = this.dataset.couponId;
            const couponCode = this.dataset.couponCode;
            const usedCount = parseInt(this.dataset.usedCount);
            
            let confirmText = `{{ __('admin.coupon.confirm_delete') }} "${couponCode}"?`;
            let warningText = '';
            
            if (usedCount > 0) {
                warningText = '{{ __('admin.coupon.cannot_delete_used') }}';
                
                Swal.fire({
                    icon: 'warning',
                    title: '{{ __('admin.warning') }}',
                    text: warningText,
                    confirmButtonText: '{{ __('admin.ok') }}'
                });
                return;
            }
            
            Swal.fire({
                title: '{{ __('admin.coupon.delete') }}',
                text: confirmText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '{{ __('admin.coupon.yes_delete') }}',
                cancelButtonText: '{{ __('admin.cancel') }}',
                reverseButtons: {{ app()->getLocale() == 'ar' ? 'true' : 'false' }}
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: '{{ __('admin.loading') }}',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Send delete request
                    fetch(`/admin/coupons/${couponId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '{{ __('admin.success') }}',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = '{{ route('admin.coupons.index') }}';
                            });
                        } else {
                            throw new Error(data.message || 'Unknown error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __('admin.error') }}',
                            text: error.message || '{{ __('admin.error') }}'
                        });
                    });
                }
            });
        });
    });

    // Toggle status functionality
    const toggleButtons = document.querySelectorAll('.toggle-status-btn');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const form = this.closest('form');
            const originalContent = this.innerHTML;
            
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("admin.loading") }}';
            this.disabled = true;
            
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __("admin.success") }}',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Unknown error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("admin.error") }}',
                    text: error.message || '{{ __("admin.error") }}'
                });
                
                this.innerHTML = originalContent;
                this.disabled = false;
            });
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.card-voltronix {
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
    border: 1px solid rgba(0, 127, 255, 0.1);
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0, 127, 255, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    margin-bottom: 1.5rem;
}

.card-voltronix:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(0, 127, 255, 0.12);
}

.card-header {
    background: linear-gradient(135deg, rgba(0, 127, 255, 0.08), rgba(35, 239, 255, 0.04));
    border-bottom: 1px solid rgba(0, 127, 255, 0.1);
    border-radius: 20px 20px 0 0 !important;
    padding: 1.25rem 1.5rem;
}

.section-title {
    font-family: 'Orbitron', sans-serif;
    font-weight: 700;
    color: var(--voltronix-primary);
    margin: 0;
}

.info-item {
    margin-bottom: 1.25rem;
    padding: 0.75rem;
    background: rgba(0, 127, 255, 0.02);
    border-radius: 12px;
    border-left: 3px solid var(--voltronix-primary);
    transition: all 0.3s ease;
}

.info-item:hover {
    background: rgba(0, 127, 255, 0.04);
    border-left-color: var(--voltronix-secondary);
}

.info-item label {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
    display: block;
}

.info-value {
    font-weight: 500;
    color: #1a1a1a;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 900;
    font-family: 'Orbitron', sans-serif;
    background: linear-gradient(135deg, #007fff, #23efff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
}

.stat-label {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
}

.stat-mini {
    text-align: center;
    padding: 0.5rem;
}

.stat-mini-number {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--voltronix-primary);
    font-family: 'Orbitron', sans-serif;
}

.stat-mini-label {
    font-size: 0.75rem;
    color: #6c757d;
    font-weight: 500;
}

.order-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.order-item:last-child {
    border-bottom: none;
}

.admin-header {
    background: linear-gradient(135deg, rgba(0, 127, 255, 0.1), rgba(35, 239, 255, 0.05));
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid rgba(13, 110, 253, 0.1);
}

.title-orbitron {
    font-family: 'Orbitron', sans-serif;
    font-weight: 900;
    background: linear-gradient(135deg, #007fff, #23efff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.breadcrumb-item a {
    color: var(--voltronix-primary);
    text-decoration: none;
}

.breadcrumb-item a:hover {
    text-decoration: underline;
}

.progress {
    height: 8px;
    border-radius: 10px;
    background-color: rgba(13, 110, 253, 0.1);
}

.progress-bar {
    border-radius: 10px;
}

.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
    border-radius: 10px;
}

.toggle-status-btn {
    transition: all 0.3s ease;
}

.toggle-status-btn:hover {
    transform: scale(1.05);
}
</style>
@endpush


