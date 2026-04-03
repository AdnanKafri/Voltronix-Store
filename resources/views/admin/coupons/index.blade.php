@extends('admin.layouts.app')

@section('title', __('admin.coupon.title'))

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="title-orbitron mb-2">{{ __('admin.coupon.title') }}</h1>
            <p class="text-muted">{{ __('admin.coupon.list') }}</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-voltronix-primary">
            <i class="fas fa-plus {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
            {{ __('admin.coupon.create') }}
        </a>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-funnel {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.filter') }}
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.coupons.index') }}">
                <div class="filter-row">
                    <!-- Search -->
                    <div class="filter-col">
                        <label for="search" class="form-label-enhanced">{{ __('admin.search') }}</label>
                        <div class="search-input-group">
                            <input type="text" 
                                   class="form-control form-control-enhanced" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="{{ __('admin.coupon.search_placeholder') }}">
                            <button type="submit" class="btn search-btn">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="filter-col">
                        <label for="status" class="form-label-enhanced">{{ __('admin.coupon.filter_by_status') }}</label>
                        <select name="status" id="status" class="form-control form-control-enhanced">
                            <option value="">{{ __('admin.coupon.all_statuses') }}</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                {{ __('admin.coupon.active') }}
                            </option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                {{ __('admin.coupon.inactive') }}
                            </option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>
                                {{ __('admin.coupon.expired') }}
                            </option>
                        </select>
                    </div>

                    <!-- Type Filter -->
                    <div class="filter-col">
                        <label for="type" class="form-label-enhanced">{{ __('admin.coupon.filter_by_type') }}</label>
                        <select name="type" id="type" class="form-control form-control-enhanced">
                            <option value="">{{ __('admin.coupon.all_types') }}</option>
                            <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>
                                {{ __('admin.coupon.type_percentage') }}
                            </option>
                            <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>
                                {{ __('admin.coupon.type_fixed') }}
                            </option>
                        </select>
                    </div>

                    <!-- Filter Actions -->
                    <div class="filter-col filter-col-auto">
                        <div class="filter-actions">
                            <button type="submit" class="filter-btn btn-filter">
                                <i class="bi bi-funnel"></i>
                                {{ __('admin.filter') }}
                            </button>
                            <a href="{{ route('admin.coupons.index') }}" class="filter-btn btn-clear">
                                <i class="bi bi-arrow-clockwise"></i>
                                {{ __('admin.common.clear') }}
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Coupons Table -->
    <div class="admin-table">
        <div class="card-body p-0">
            @if($coupons->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th>{{ __('admin.coupon.code') }}</th>
                                <th>{{ __('admin.coupon.name') }}</th>
                                <th>{{ __('admin.coupon.type') }}</th>
                                <th>{{ __('admin.coupon.value') }}</th>
                                <th style="width: 100px;">{{ __('admin.coupon.used_count') }}</th>
                                <th style="width: 120px;">{{ __('admin.coupon.expiry_date') }}</th>
                                <th style="width: 100px;">{{ __('admin.common.status') }}</th>
                                <th style="width: 150px;">{{ __('admin.coupon.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupons as $coupon)
                                <tr>
                                    <td class="text-muted">{{ $coupon->id }}</td>
                                    
                                    <!-- Code -->
                                    <td>
                                        <span class="badge bg-primary fs-6">{{ $coupon->code }}</span>
                                    </td>
                                    
                                    <!-- Name -->
                                    <td>
                                        <div class="fw-bold">{{ $coupon->getTranslation('name', app()->getLocale()) ?? $coupon->name ?? 'N/A' }}</div>
                                        @if($coupon->getTranslation('description', app()->getLocale()))
                                            <small class="text-muted">{{ Str::limit($coupon->getTranslation('description', app()->getLocale()) ?? $coupon->description ?? '', 50) }}</small>
                                        @endif
                                    </td>
                                    
                                    <!-- Type -->
                                    <td>
                                        <span class="badge {{ $coupon->type == 'percentage' ? 'bg-info' : 'bg-success' }}">
                                            {{ __('admin.coupon.type_' . $coupon->type) }}
                                        </span>
                                    </td>
                                    
                                    <!-- Value -->
                                    <td>
                                        <strong>{{ $coupon->formatted_value }}</strong>
                                        @if($coupon->min_order_value)
                                            <br><small class="text-muted">Min: {{ $coupon->formatted_min_order_value }}</small>
                                        @endif
                                    </td>
                                    
                                    <!-- Usage Count -->
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $coupon->used_count }}</span>
                                        @if($coupon->usage_limit)
                                            <br><small class="text-muted">/ {{ $coupon->usage_limit }}</small>
                                        @endif
                                    </td>
                                    
                                    <!-- Expiry Date -->
                                    <td>
                                        @if($coupon->expiry_date)
                                            <div class="small">{{ $coupon->expiry_date->format('M d, Y') }}</div>
                                            <div class="text-muted small">{{ $coupon->expiry_date->format('H:i') }}</div>
                                        @else
                                            <span class="text-muted">{{ __('admin.never') }}</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Status -->
                                    <td>
                                        <form method="POST" action="{{ route('admin.coupons.toggle-status', $coupon) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm {{ $coupon->status_badge_class }} toggle-status-btn"
                                                    data-coupon-id="{{ $coupon->id }}"
                                                    data-current-status="{{ $coupon->is_active ? 'active' : 'inactive' }}">
                                                <i class="fas fa-{{ $coupon->is_active ? 'check' : 'times' }}"></i>
                                                {{ $coupon->status_text }}
                                            </button>
                                        </form>
                                    </td>
                                    
                                    <!-- Actions -->
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.coupons.show', $coupon) }}" 
                                               class="action-btn btn-view"
                                               title="{{ __('admin.coupon.view') }}"
                                               data-bs-toggle="tooltip">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                                               class="action-btn btn-edit"
                                               title="{{ __('admin.coupon.edit') }}"
                                               data-bs-toggle="tooltip">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" 
                                                    class="action-btn btn-delete delete-coupon-btn"
                                                    data-coupon-id="{{ $coupon->id }}"
                                                    data-coupon-code="{{ $coupon->code }}"
                                                    data-used-count="{{ $coupon->used_count }}"
                                                    title="{{ __('admin.coupon.delete') }}"
                                                    data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($coupons->hasPages())
                    <div class="card-footer bg-transparent">
                        {{ $coupons->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">{{ __('admin.coupon.no_coupons') }}</h5>
                    <p class="text-muted">{{ __('admin.coupon.create') }}</p>
                    <a href="{{ route('admin.coupons.create') }}" class="btn btn-voltronix-primary">
                        <i class="fas fa-plus {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.coupon.create') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCouponModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin.coupon.delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="deleteMessage"></p>
                <div id="warningMessage" class="alert alert-warning d-none">
                    <i class="fas fa-exclamation-triangle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.coupon.cannot_delete_used') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('admin.cancel') }}
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.coupon.yes_delete') }}
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Delete Coupon Functionality
    const deleteButtons = document.querySelectorAll('.delete-coupon-btn');
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteCouponModal'));
    const deleteMessage = document.getElementById('deleteMessage');
    const warningMessage = document.getElementById('warningMessage');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    
    let couponToDelete = null;
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const couponId = this.dataset.couponId;
            const couponCode = this.dataset.couponCode;
            const usedCount = parseInt(this.dataset.usedCount);
            
            couponToDelete = couponId;
            
            deleteMessage.textContent = `{{ __('admin.coupon.confirm_delete') }} "${couponCode}"?`;
            
            if (usedCount > 0) {
                warningMessage.classList.remove('d-none');
                confirmDeleteBtn.disabled = true;
                confirmDeleteBtn.classList.add('disabled');
            } else {
                warningMessage.classList.add('d-none');
                confirmDeleteBtn.disabled = false;
                confirmDeleteBtn.classList.remove('disabled');
            }
            
            deleteModal.show();
        });
    });
    
    // Confirm Delete
    confirmDeleteBtn.addEventListener('click', function() {
        if (couponToDelete && !this.disabled) {
            // Show loading state
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin {{ app()->getLocale() == "ar" ? "ms-2" : "me-2" }}"></i> {{ __("admin.loading") }}';
            this.disabled = true;
            
            // Send AJAX delete request
            fetch(`/admin/coupons/${couponToDelete}`, {
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
                    deleteModal.hide();
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __("admin.success") }}',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Reload page to update the list
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
                
                // Reset button
                this.innerHTML = originalContent;
                this.disabled = false;
            });
        }
    });
    
    // Toggle Status with AJAX
    const toggleButtons = document.querySelectorAll('.toggle-status-btn');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const couponId = this.dataset.couponId;
            const currentStatus = this.dataset.currentStatus;
            const form = this.closest('form');
            
            // Show loading state
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("admin.loading") }}';
            this.disabled = true;
            
            // Submit form via AJAX
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
                    // Update button appearance
                    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
                    this.dataset.currentStatus = newStatus;
                    
                    if (newStatus === 'active') {
                        this.className = 'btn btn-sm bg-success toggle-status-btn';
                        this.innerHTML = '<i class="fas fa-check"></i> {{ __("admin.coupon.active") }}';
                    } else {
                        this.className = 'btn btn-sm bg-secondary toggle-status-btn';
                        this.innerHTML = '<i class="fas fa-times"></i> {{ __("admin.coupon.inactive") }}';
                    }
                    
                    // Show success message
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
                
                // Reset button
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
    border: 1px solid rgba(13, 110, 253, 0.1);
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(13, 110, 253, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-voltronix:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(13, 110, 253, 0.12);
}

.title-orbitron {
    font-family: 'Orbitron', sans-serif;
    font-weight: 900;
    background: linear-gradient(135deg, #007fff, #23efff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
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

.table th {
    font-weight: 600;
    color: #1a1a1a;
    border-bottom: 2px solid rgba(13, 110, 253, 0.1);
    background: linear-gradient(145deg, #f8f9fa, #e9ecef);
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
    transform: scale(1.01);
}

.btn-group .btn {
    border-radius: 8px;
    margin: 0 2px;
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
}

.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
    border-radius: 10px;
}

.badge.bg-success {
    background: linear-gradient(135deg, #28a745, #20c997) !important;
}

.badge.bg-secondary {
    background: linear-gradient(135deg, #6c757d, #495057) !important;
}

.badge.bg-info {
    background: linear-gradient(135deg, #17a2b8, #007bff) !important;
}

.badge.bg-primary {
    background: linear-gradient(135deg, #007bff, #0056b3) !important;
}

.toggle-status-btn {
    transition: all 0.3s ease;
}

.toggle-status-btn:hover {
    transform: scale(1.05);
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group .btn {
        padding: 0.375rem 0.5rem;
    }
    
    .table tbody tr:hover {
        transform: none;
    }
}
</style>
@endpush
