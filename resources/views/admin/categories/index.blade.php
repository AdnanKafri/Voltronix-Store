@extends('admin.layouts.app')

@section('title', __('admin.category.title'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 title-orbitron">
                <i class="fas fa-layer-group {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.category.title') }}
            </h1>
            <p class="text-muted mb-0">{{ __('admin.category.list') }}</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-voltronix">
            <i class="bi bi-plus-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
            {{ __('admin.category.create') }}
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
            <form method="GET" action="{{ route('admin.categories.index') }}">
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
                                   placeholder="{{ __('admin.category.search_placeholder') }}">
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
                                {{ __('admin.common.active') }}
                            </option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                {{ __('admin.common.inactive') }}
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
                            <a href="{{ route('admin.categories.index') }}" class="filter-btn btn-clear">
                                <i class="bi bi-arrow-clockwise"></i>
                                {{ __('admin.common.clear') }}
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="admin-table">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-grid {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.category.list') }}
            </h5>
            <span class="badge bg-primary">{{ $categories->total() }} {{ __('admin.category.title') }}</span>
        </div>
        
        @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th style="width: 80px;">{{ __('admin.category.thumbnail') }}</th>
                                <th>{{ __('admin.category.name') }}</th>
                                <th>{{ __('admin.category.description') }}</th>
                                <th style="width: 120px;">{{ __('admin.category.products_count') }}</th>
                                <th style="width: 100px;">{{ __('admin.common.status') }}</th>
                                <th style="width: 120px;">{{ __('admin.category.sort_order') }}</th>
                                <th style="width: 150px;">{{ __('admin.category.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td class="text-muted">{{ $category->id }}</td>
                                    
                                    <!-- Thumbnail -->
                                    <td>
                                        @if($category->thumbnail)
                                            <img src="{{ Storage::url($category->thumbnail) }}" 
                                                 alt="{{ $category->getTranslation('name', app()->getLocale()) ?? $category->name ?? 'Category' }}"
                                                 class="img-thumbnail"
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px; border-radius: 8px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <!-- Name -->
                                    <td>
                                        <div class="fw-bold">{{ $category->getTranslation('name', 'en') ?? $category->name ?? 'N/A' }}</div>
                                        @if(app()->getLocale() == 'ar' || $category->getTranslation('name', 'ar'))
                                            <small class="text-muted">{{ $category->getTranslation('name', 'ar') ?? 'غير متوفر' }}</small>
                                        @endif
                                    </td>
                                    
                                    <!-- Description -->
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;">
                                            {{ Str::limit($category->getTranslation('description', app()->getLocale()) ?? $category->description ?? 'No description', 50) }}
                                        </div>
                                    </td>
                                    
                                    <!-- Products Count -->
                                    <td>
                                        <span class="badge bg-info">{{ $category->products()->count() }}</span>
                                    </td>
                                    
                                    <!-- Status -->
                                    <td>
                                        <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm {{ $category->is_active ? 'btn-success' : 'btn-secondary' }} toggle-status-btn"
                                                    data-category-id="{{ $category->id }}"
                                                    data-current-status="{{ $category->is_active ? 'active' : 'inactive' }}">
                                                <i class="fas fa-{{ $category->is_active ? 'check' : 'times' }}"></i>
                                                {{ $category->is_active ? __('admin.category.active') : __('admin.category.inactive') }}
                                            </button>
                                        </form>
                                    </td>
                                    
                                    <!-- Sort Order -->
                                    <td>
                                        <span class="badge bg-secondary">{{ $category->sort_order }}</span>
                                    </td>
                                    
                                    <!-- Actions -->
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.categories.show', $category) }}" 
                                               class="action-btn btn-view"
                                               title="{{ __('admin.category.view') }}"
                                               data-bs-toggle="tooltip">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $category) }}" 
                                               class="action-btn btn-edit"
                                               title="{{ __('admin.category.edit') }}"
                                               data-bs-toggle="tooltip">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" 
                                                    class="action-btn btn-delete delete-category-btn"
                                                    data-category-id="{{ $category->id }}"
                                                    data-category-name="{{ $category->getTranslation('name', app()->getLocale()) ?? $category->name ?? 'Category' }}"
                                                    data-products-count="{{ $category->products()->count() }}"
                                                    title="{{ __('admin.category.delete') }}"
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
                @if($categories->hasPages())
                    <div class="card-footer">
                        {{ $categories->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-layer-group fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted">{{ __('admin.category.no_categories') }}</h5>
                    <p class="text-muted mb-4">{{ __('admin.category.no_categories') }}</p>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-voltronix-primary">
                        <i class="fas fa-plus {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.category.create') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin.category.delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="deleteMessage"></p>
                <div id="warningMessage" class="alert alert-warning d-none">
                    <i class="fas fa-exclamation-triangle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.category.cannot_delete_with_products') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('admin.cancel') }}
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.category.yes_delete') }}
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
    // Delete Category Functionality
    const deleteButtons = document.querySelectorAll('.delete-category-btn');
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteCategoryModal'));
    const deleteMessage = document.getElementById('deleteMessage');
    const warningMessage = document.getElementById('warningMessage');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    
    let categoryToDelete = null;
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const categoryId = this.dataset.categoryId;
            const categoryName = this.dataset.categoryName;
            const productsCount = parseInt(this.dataset.productsCount);
            
            categoryToDelete = categoryId;
            
            deleteMessage.textContent = `{{ __('admin.category.confirm_delete') }} "${categoryName}"?`;
            
            if (productsCount > 0) {
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
        if (categoryToDelete && !this.disabled) {
            // Show loading state
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin {{ app()->getLocale() == "ar" ? "ms-2" : "me-2" }}"></i> {{ __("admin.loading") }}';
            this.disabled = true;
            
            // Send AJAX delete request
            fetch(`/admin/categories/${categoryToDelete}`, {
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
            
            const categoryId = this.dataset.categoryId;
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
                        this.className = 'btn btn-sm btn-success toggle-status-btn';
                        this.innerHTML = '<i class="fas fa-check"></i> {{ __("admin.category.active") }}';
                    } else {
                        this.className = 'btn btn-sm btn-secondary toggle-status-btn';
                        this.innerHTML = '<i class="fas fa-times"></i> {{ __("admin.category.inactive") }}';
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
                this.innerHTML = originalContent;
                
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("admin.error") }}',
                    text: error.message || '{{ __("admin.error") }}'
                });
            })
            .finally(() => {
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

.img-thumbnail {
    border: 2px solid rgba(13, 110, 253, 0.1);
    border-radius: 12px;
    transition: all 0.2s ease;
}

.img-thumbnail:hover {
    border-color: rgba(13, 110, 253, 0.3);
    transform: scale(1.05);
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
