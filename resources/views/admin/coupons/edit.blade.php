@extends('admin.layouts.app')

@section('title', __('admin.coupon.edit') . ' - ' . $coupon->code)

@section('content')
<div class="container-fluid">
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="title-orbitron mb-2">{{ __('admin.coupon.edit') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">{{ __('admin.nav.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.coupons.index') }}">{{ __('admin.coupon.title') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.coupons.show', $coupon) }}">{{ $coupon->code }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('admin.coupon.edit') }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-outline-info">
                    <i class="fas fa-eye {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.coupon.view') }}
                </a>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.back') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Edit Form -->
        <div class="col-lg-8">
            <div class="card-voltronix">
                <div class="card-body">
                    <form id="editCouponForm" action="{{ route('admin.coupons.update', $coupon) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- General Information -->
                        <div class="section-card mb-4">
                            <h5 class="section-title">
                                <i class="fas fa-info-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('admin.coupon.general_info') }}
                            </h5>
                            
                            <div class="row">
                                <!-- Coupon Code -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">{{ __('admin.coupon.code') }}</label>
                                    <div class="input-group">
                                        <input type="text" name="code" id="couponCode" class="form-control @error('code') is-invalid @enderror" 
                                               value="{{ old('code', $coupon->code) }}" placeholder="{{ __('admin.coupon.code') }}" maxlength="50" required>
                                        <button type="button" id="generateCodeBtn" class="btn btn-outline-primary">
                                            <i class="fas fa-magic"></i>
                                            {{ __('admin.coupon.generate_code') }}
                                        </button>
                                    </div>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.coupon.status') }}</label>
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="is_active" value="0">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
                                               {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isActive">
                                            {{ __('admin.coupon.is_active') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Bilingual Name -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">{{ __('admin.coupon.name_en') }}</label>
                                    <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" 
                                           value="{{ old('name_en', $coupon->getTranslation('name', 'en')) }}" placeholder="{{ __('admin.coupon.name_en') }}" maxlength="255" required>
                                    @error('name_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">{{ __('admin.coupon.name_ar') }}</label>
                                    <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" 
                                           value="{{ old('name_ar', $coupon->getTranslation('name', 'ar')) }}" placeholder="{{ __('admin.coupon.name_ar') }}" maxlength="255" required>
                                    @error('name_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Bilingual Description -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.coupon.description_en') }}</label>
                                    <textarea name="description_en" class="form-control @error('description_en') is-invalid @enderror" 
                                              rows="3" placeholder="{{ __('admin.coupon.description_en') }}" maxlength="1000">{{ old('description_en', $coupon->getTranslation('description', 'en')) }}</textarea>
                                    @error('description_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.coupon.description_ar') }}</label>
                                    <textarea name="description_ar" class="form-control @error('description_ar') is-invalid @enderror" 
                                              rows="3" placeholder="{{ __('admin.coupon.description_ar') }}" maxlength="1000">{{ old('description_ar', $coupon->getTranslation('description', 'ar')) }}</textarea>
                                    @error('description_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Discount Settings -->
                        <div class="section-card mb-4">
                            <h5 class="section-title">
                                <i class="fas fa-percent {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('admin.coupon.discount_settings') }}
                            </h5>
                            
                            <div class="row">
                                <!-- Discount Type -->
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required">{{ __('admin.coupon.type') }}</label>
                                    <select name="type" id="discountType" class="form-select @error('type') is-invalid @enderror" required>
                                        <option value="">{{ __('admin.select') }}</option>
                                        <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>
                                            {{ __('admin.coupon.type_percentage') }}
                                        </option>
                                        <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>
                                            {{ __('admin.coupon.type_fixed') }}
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Discount Value -->
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required">{{ __('admin.coupon.value') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="valuePrefix">{{ $coupon->type == 'percentage' ? '%' : '$' }}</span>
                                        <input type="number" name="value" id="discountValue" class="form-control @error('value') is-invalid @enderror" 
                                               value="{{ old('value', $coupon->value) }}" placeholder="0.00" step="0.01" min="0" max="{{ $coupon->type == 'percentage' ? '100' : '999999.99' }}" required>
                                    </div>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Max Discount (for percentage) -->
                                <div class="col-md-4 mb-3" id="maxDiscountField" style="display: {{ $coupon->type == 'percentage' ? 'block' : 'none' }};">
                                    <label class="form-label">{{ __('admin.coupon.max_discount') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="max_discount" class="form-control @error('max_discount') is-invalid @enderror" 
                                               value="{{ old('max_discount', $coupon->max_discount) }}" placeholder="0.00" step="0.01" min="0" max="999999.99">
                                    </div>
                                    @error('max_discount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Min Order Value -->
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">{{ __('admin.coupon.min_order_value') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="min_order_value" class="form-control @error('min_order_value') is-invalid @enderror" 
                                               value="{{ old('min_order_value', $coupon->min_order_value) }}" placeholder="0.00" step="0.01" min="0" max="999999.99">
                                    </div>
                                    @error('min_order_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Usage Limits -->
                        <div class="section-card mb-4">
                            <h5 class="section-title">
                                <i class="fas fa-users {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('admin.coupon.usage_limits') }}
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.coupon.usage_limit') }}</label>
                                    <input type="number" name="usage_limit" class="form-control @error('usage_limit') is-invalid @enderror" 
                                           value="{{ old('usage_limit', $coupon->usage_limit) }}" placeholder="{{ __('admin.unlimited') }}" min="1" max="999999">
                                    <div class="form-text">{{ __('admin.coupon.usage_limit_help') }}</div>
                                    @error('usage_limit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">{{ __('admin.coupon.per_user_limit') }}</label>
                                    <input type="number" name="per_user_limit" class="form-control @error('per_user_limit') is-invalid @enderror" 
                                           value="{{ old('per_user_limit', $coupon->per_user_limit) }}" placeholder="1" min="1" max="999" required>
                                    @error('per_user_limit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Date Restrictions -->
                        <div class="section-card mb-4">
                            <h5 class="section-title">
                                <i class="fas fa-calendar {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('admin.coupon.date_restrictions') }}
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.coupon.start_date') }}</label>
                                    <input type="datetime-local" name="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                                           value="{{ old('start_date', $coupon->start_date ? $coupon->start_date->format('Y-m-d\TH:i') : '') }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.coupon.expiry_date') }}</label>
                                    <input type="datetime-local" name="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror" 
                                           value="{{ old('expiry_date', $coupon->expiry_date ? $coupon->expiry_date->format('Y-m-d\TH:i') : '') }}">
                                    @error('expiry_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- User Restrictions -->
                        <div class="section-card mb-4">
                            <h5 class="section-title">
                                <i class="fas fa-user-lock {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('admin.coupon.user_restrictions') }}
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.coupon.target_user') }}</label>
                                    <select name="target_user_id" class="form-select @error('target_user_id') is-invalid @enderror">
                                        <option value="">{{ __('admin.coupon.all_users') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('target_user_id', $coupon->target_user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('target_user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch mt-4">
                                        <input type="hidden" name="first_time_only" value="0">
                                        <input class="form-check-input" type="checkbox" name="first_time_only" id="firstTimeOnly" value="1"
                                               {{ old('first_time_only', $coupon->first_time_only) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="firstTimeOnly">
                                            {{ __('admin.coupon.first_time_only') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('admin.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-voltronix-primary" id="submitBtn">
                                <i class="fas fa-save {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('admin.coupon.update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Stats -->
        <div class="col-lg-4">
            <!-- Current Usage Stats -->
            <div class="card-voltronix mb-4">
                <div class="card-header">
                    <h5 class="section-title mb-0">
                        <i class="fas fa-chart-bar {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.coupon.current_stats') }}
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

            <!-- Warning Messages -->
            @if($coupon->used_count > 0)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    <strong>{{ __('admin.warning') }}:</strong>
                    {{ __('admin.coupon.edit_warning') }}
                </div>
            @endif

            @if($coupon->expiry_date && $coupon->expiry_date->isPast())
                <div class="alert alert-danger">
                    <i class="fas fa-clock {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    <strong>{{ __('admin.coupon.expired') }}:</strong>
                    {{ __('admin.coupon.expired_message') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const discountType = document.getElementById('discountType');
    const discountValue = document.getElementById('discountValue');
    const valuePrefix = document.getElementById('valuePrefix');
    const maxDiscountField = document.getElementById('maxDiscountField');
    const generateCodeBtn = document.getElementById('generateCodeBtn');
    const couponCode = document.getElementById('couponCode');
    const form = document.getElementById('editCouponForm');
    const submitBtn = document.getElementById('submitBtn');

    // Handle discount type change
    discountType.addEventListener('change', function() {
        if (this.value === 'percentage') {
            valuePrefix.textContent = '%';
            discountValue.max = '100';
            maxDiscountField.style.display = 'block';
        } else if (this.value === 'fixed') {
            valuePrefix.textContent = '$';
            discountValue.max = '999999.99';
            maxDiscountField.style.display = 'none';
        }
    });

    // Generate coupon code
    generateCodeBtn.addEventListener('click', function() {
        const btn = this;
        const originalContent = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("admin.loading") }}';
        btn.disabled = true;

        fetch('{{ route("admin.coupons.generate-code") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                couponCode.value = data.code;
                Swal.fire({
                    icon: 'success',
                    title: '{{ __("admin.success") }}',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
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
        })
        .finally(() => {
            btn.innerHTML = originalContent;
            btn.disabled = false;
        });
    });

    // Form submission with AJAX
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        const originalContent = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin {{ app()->getLocale() == "ar" ? "ms-2" : "me-2" }}"></i> {{ __("admin.loading") }}';
        submitBtn.disabled = true;
        
        // Create FormData object
        const formData = new FormData(this);
        
        // Submit via AJAX
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.redirected) {
                // Handle redirect (success case)
                window.location.href = response.url;
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success === true) {
                // Handle success case
                Swal.fire({
                    icon: 'success',
                    title: '{{ __("admin.success") }}',
                    text: data.message,
                    confirmButtonColor: '#007fff',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                });
            } else if (data && data.errors) {
                // Handle validation errors
                let errorMessage = '{{ __("admin.validation_failed") }}:\n';
                Object.values(data.errors).forEach(errors => {
                    errors.forEach(error => {
                        errorMessage += '• ' + error + '\n';
                    });
                });
                
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("admin.validation_failed") }}',
                    text: errorMessage,
                    confirmButtonColor: '#dc3545'
                });
            } else if (data && data.message) {
                // Handle other errors
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("admin.error") }}',
                    text: data.message,
                    confirmButtonColor: '#dc3545'
                });
            }
        })
        .catch(error => {
            console.error('Form submission error:', error);
            Swal.fire({
                icon: 'error',
                title: '{{ __("admin.error") }}',
                text: '{{ __("admin.coupon.update_failed") }}',
                confirmButtonColor: '#dc3545'
            });
        })
        .finally(() => {
            // Restore button state
            submitBtn.innerHTML = originalContent;
            submitBtn.disabled = false;
        });
    });

    // Initialize discount type
    if (discountType.value) {
        discountType.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush

@push('styles')
<style>
.section-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(13, 110, 253, 0.1);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.section-title {
    font-family: 'Orbitron', sans-serif;
    font-weight: 700;
    color: var(--voltronix-primary);
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid rgba(13, 110, 253, 0.1);
}

.form-label.required::after {
    content: ' *';
    color: #dc3545;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--voltronix-primary);
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.btn-voltronix-primary {
    background: linear-gradient(135deg, #007fff, #23efff);
    border: none;
    color: white;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    border-radius: 15px;
    transition: all 0.3s ease;
}

.btn-voltronix-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 127, 255, 0.3);
    color: white;
}

.card-voltronix {
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
    border: 1px solid rgba(13, 110, 253, 0.1);
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(13, 110, 253, 0.08);
}

.card-header {
    background: rgba(13, 110, 253, 0.05);
    border-bottom: 1px solid rgba(13, 110, 253, 0.1);
    border-radius: 20px 20px 0 0 !important;
    padding: 1.25rem 1.5rem;
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

.progress {
    height: 8px;
    border-radius: 10px;
    background-color: rgba(13, 110, 253, 0.1);
}

.progress-bar {
    border-radius: 10px;
}
</style>
@endpush
