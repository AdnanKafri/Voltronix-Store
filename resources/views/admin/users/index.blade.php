@extends('admin.layouts.app')

@section('title', __('admin.users.title'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 title-orbitron">
                <i class="bi bi-people {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.users.title') }}
            </h1>
            <p class="text-muted mb-0">{{ __('admin.users.management') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.statistics') }}" class="btn btn-outline-info">
                <i class="bi bi-graph-up {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.users.statistics') }}
            </a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-voltronix">
                <i class="bi bi-plus-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.users.create') }}
            </a>
        </div>
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
            <form method="GET" action="{{ route('admin.users.index') }}">
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
                                   placeholder="{{ __('admin.users.search_placeholder') }}">
                            <button type="submit" class="btn search-btn">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="filter-col">
                        <label for="status" class="form-label-enhanced">{{ __('admin.common.status') }}</label>
                        <select name="status" id="status" class="form-control form-control-enhanced">
                            <option value="">{{ __('admin.all') }}</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                {{ __('admin.users.status.active') }}
                            </option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                {{ __('admin.users.status.inactive') }}
                            </option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>
                                {{ __('admin.users.status.suspended') }}
                            </option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                {{ __('admin.users.status.pending') }}
                            </option>
                        </select>
                    </div>

                    <!-- Role Filter -->
                    <div class="filter-col">
                        <label for="role" class="form-label-enhanced">{{ __('admin.users.role_label') }}</label>
                        <select name="role" id="role" class="form-control form-control-enhanced">
                            <option value="">{{ __('admin.all') }}</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>
                                {{ __('admin.users.role.admin') }}
                            </option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>
                                {{ __('admin.users.role.user') }}
                            </option>
                            <option value="moderator" {{ request('role') == 'moderator' ? 'selected' : '' }}>
                                {{ __('admin.users.role.moderator') }}
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
                            <a href="{{ route('admin.users.index') }}" class="filter-btn btn-clear">
                                <i class="bi bi-arrow-clockwise"></i>
                                {{ __('admin.common.clear') }}
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="delivery-stat-card">
                <div class="delivery-stat-content">
                    <div class="delivery-stat-icon bg-primary bg-opacity-10">
                        <i class="bi bi-people text-primary fs-4"></i>
                    </div>
                    <div class="delivery-stat-text">
                        <h6>{{ __('admin.users.total_users') }}</h6>
                        <h4>{{ $stats['total'] }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="delivery-stat-card">
                <div class="delivery-stat-content">
                    <div class="delivery-stat-icon bg-success bg-opacity-10">
                        <i class="bi bi-check-circle text-success fs-4"></i>
                    </div>
                    <div class="delivery-stat-text">
                        <h6>{{ __('admin.users.active_users') }}</h6>
                        <h4>{{ $stats['active'] }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="delivery-stat-card">
                <div class="delivery-stat-content">
                    <div class="delivery-stat-icon bg-warning bg-opacity-10">
                        <i class="bi bi-x-circle text-warning fs-4"></i>
                    </div>
                    <div class="delivery-stat-text">
                        <h6>{{ __('admin.users.inactive_users') }}</h6>
                        <h4>{{ $stats['inactive'] }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="delivery-stat-card">
                <div class="delivery-stat-content">
                    <div class="delivery-stat-icon bg-info bg-opacity-10">
                        <i class="bi bi-person-plus text-info fs-4"></i>
                    </div>
                    <div class="delivery-stat-text">
                        <h6>{{ __('admin.users.new_this_month') }}</h6>
                        <h4>{{ $stats['new_this_month'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="admin-card bulk-actions-card mb-4" id="bulkActions" style="display: none;">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.bulk-action') }}" id="bulkForm">
                @csrf
                <div class="row align-items-center g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-square text-warning fs-5"></i>
                            <span class="fw-bold text-dark">{{ __('admin.with_selected') }}:</span>
                            <span id="selectedCount" class="badge bg-warning text-dark fs-6">0</span>
                            <span class="text-muted">{{ __('admin.users.title') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 text-{{ app()->getLocale() == 'ar' ? 'start' : 'end' }}">
                        <div class="d-flex gap-2 justify-content-{{ app()->getLocale() == 'ar' ? 'start' : 'end' }} flex-wrap">
                            <select name="action" class="form-select" style="width: auto; min-width: 200px;" required>
                                <option value="">{{ __('admin.bulk_actions') }}</option>
                                <option value="activate">{{ __('admin.users.bulk_activate') }}</option>
                                <option value="suspend">{{ __('admin.users.bulk_suspend') }}</option>
                                <option value="delete">{{ __('admin.users.bulk_delete') }}</option>
                            </select>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-lightning"></i>
                                {{ __('admin.apply') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="admin-table">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                            </div>
                        </th>
                        <th style="width: 60px;">#</th>
                        <th>{{ __('admin.users.name') }}</th>
                        <th>{{ __('admin.users.email') }}</th>
                        <th>{{ __('admin.users.phone') }}</th>
                        <th>{{ __('admin.users.role_label') }}</th>
                        <th>{{ __('admin.common.status') }}</th>
                        <th>{{ __('admin.users.orders_count') }}</th>
                        <th>{{ __('admin.users.joined_date') }}</th>
                        <th>{{ __('admin.users.last_login') }}</th>
                        <th style="width: 180px;">{{ __('admin.users.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input user-checkbox" type="checkbox" 
                                       name="users[]" value="{{ $user->id }}" 
                                       id="user_{{ $user->id }}">
                            </div>
                        </td>
                        <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar {{ app()->getLocale() == 'ar' ? 'ms-3' : 'me-3' }}">
                                    <div class="avatar-circle">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $user->name }}</div>
                                    @if($user->email_verified_at)
                                        <small class="text-success">
                                            <i class="bi bi-check-circle {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                            {{ __('admin.users.verified') }}
                                        </small>
                                    @else
                                        <small class="text-warning">
                                            <i class="bi bi-exclamation-circle {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                            {{ __('admin.users.unverified') }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                {{ $user->email }}
                            </a>
                        </td>
                        <td>
                            @if($user->phone)
                                <a href="tel:{{ $user->phone }}" class="text-decoration-none">
                                    {{ $user->phone }}
                                </a>
                            @else
                                <span class="text-muted">{{ __('admin.users.no_phone') }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'moderator' ? 'warning' : 'secondary') }}">
                                {{ $user->role_text }}
                            </span>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       id="status_{{ $user->id }}"
                                       {{ $user->isActive() ? 'checked' : '' }}
                                       onchange="toggleUserStatus({{ $user->id }})"
                                       {{ $user->isAdmin() ? 'disabled' : '' }}>
                                <label class="form-check-label" for="status_{{ $user->id }}">
                                    <span class="badge {{ $user->status_badge }}">
                                        {{ $user->status_text }}
                                    </span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $user->orders_count }}</span>
                        </td>
                        <td>
                            <div class="text-muted small">
                                <i class="bi bi-calendar {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                {{ $user->formatted_join_date }}
                            </div>
                        </td>
                        <td>
                            <div class="text-muted small">
                                <i class="bi bi-clock {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                {{ $user->formatted_last_login }}
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="action-btn btn-view" 
                                   title="{{ __('admin.users.view') }}"
                                   data-bs-toggle="tooltip">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="action-btn btn-edit" 
                                   title="{{ __('admin.users.edit') }}"
                                   data-bs-toggle="tooltip">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @unless($user->isAdmin())
                                    @if($user->isActive())
                                        <button class="action-btn btn-warning-action" 
                                                onclick="suspendUser({{ $user->id }})"
                                                title="{{ __('admin.users.suspend') }}"
                                                data-bs-toggle="tooltip">
                                            <i class="bi bi-pause-circle"></i>
                                        </button>
                                    @else
                                        <button class="action-btn btn-success-action" 
                                                onclick="activateUser({{ $user->id }})"
                                                title="{{ __('admin.users.activate') }}"
                                                data-bs-toggle="tooltip">
                                            <i class="bi bi-play-circle"></i>
                                        </button>
                                    @endif
                                    @if(!$user->orders()->exists())
                                        <button class="action-btn btn-delete" 
                                                onclick="deleteUser({{ $user->id }})"
                                                title="{{ __('admin.users.delete') }}"
                                                data-bs-toggle="tooltip">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                @endunless
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-people display-4 d-block mb-2"></i>
                                <p class="mb-0">{{ __('admin.users.no_users') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    {{ __('admin.showing') }} {{ $users->firstItem() }} {{ __('admin.to') }} {{ $users->lastItem() }} 
                    {{ __('admin.of') }} {{ $users->total() }} {{ __('admin.users.title') }}
                </div>
                {{ $users->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateBulkActions();
});

// Individual checkbox functionality
document.querySelectorAll('.user-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateBulkActions);
});

function updateBulkActions() {
    const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    
    if (checkedBoxes.length > 0) {
        bulkActions.style.display = 'block';
        selectedCount.textContent = checkedBoxes.length;
    } else {
        bulkActions.style.display = 'none';
    }
}

// Toggle user status
function toggleUserStatus(userId) {
    fetch(`/admin/users/${userId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
            });
            setTimeout(() => location.reload(), 2000);
        } else {
            Swal.fire({
                icon: 'error',
                title: '{{ __("admin.error") }}',
                text: data.message
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: '{{ __("admin.error") }}',
            text: '{{ __("admin.something_went_wrong") }}'
        });
    });
}

// Suspend user
function suspendUser(userId) {
    Swal.fire({
        title: '{{ __("admin.users.confirm_suspend") }}',
        text: '{{ __("admin.users.suspend_warning") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f39c12',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("admin.users.suspend") }}',
        cancelButtonText: '{{ __("admin.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/users/${userId}/suspend`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                    });
                    setTimeout(() => location.reload(), 2000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("admin.error") }}',
                        text: data.message
                    });
                }
            });
        }
    });
}

// Activate user
function activateUser(userId) {
    fetch(`/admin/users/${userId}/activate`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
            });
            setTimeout(() => location.reload(), 2000);
        } else {
            Swal.fire({
                icon: 'error',
                title: '{{ __("admin.error") }}',
                text: data.message
            });
        }
    });
}

// Delete user
function deleteUser(userId) {
    Swal.fire({
        title: '{{ __("admin.users.confirm_delete") }}',
        text: '{{ __("admin.users.delete_warning") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("admin.users.delete") }}',
        cancelButtonText: '{{ __("admin.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/users/${userId}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Bulk form submission
document.getElementById('bulkForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
    const action = document.querySelector('select[name="action"]').value;
    
    if (!action) {
        Swal.fire({
            icon: 'warning',
            title: '{{ __("admin.warning") }}',
            text: '{{ __("admin.select_action") }}'
        });
        return;
    }
    
    if (checkedBoxes.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: '{{ __("admin.warning") }}',
            text: '{{ __("admin.select_items") }}'
        });
        return;
    }
    
    const actionText = {
        'activate': '{{ __("admin.users.bulk_activate") }}',
        'suspend': '{{ __("admin.users.bulk_suspend") }}',
        'delete': '{{ __("admin.users.bulk_delete") }}'
    };
    
    Swal.fire({
        title: '{{ __("admin.confirm_bulk_action") }}',
        text: `${actionText[action]} ${checkedBoxes.length} {{ __("admin.users.title") }}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: action === 'delete' ? '#dc3545' : '#f39c12',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("admin.confirm") }}',
        cancelButtonText: '{{ __("admin.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            // Add selected user IDs to form
            checkedBoxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'users[]';
                input.value = checkbox.value;
                this.appendChild(input);
            });
            
            this.submit();
        }
    });
});

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<style>
.user-avatar .avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #007fff, #23efff);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 16px;
}

.bulk-actions-card {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 152, 0, 0.05));
    border: 1px solid rgba(255, 193, 7, 0.2);
    border-radius: 15px;
}

.action-btn.btn-success-action {
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
    border: 1px solid rgba(25, 135, 84, 0.2);
}

.action-btn.btn-success-action:hover {
    background: #198754;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
}

.action-btn.btn-warning-action {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.2);
}

.action-btn.btn-warning-action:hover {
    background: #ffc107;
    color: #000;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
}
</style>
@endpush
@endsection
