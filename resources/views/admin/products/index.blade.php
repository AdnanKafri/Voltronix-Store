@extends('admin.layouts.app')

@section('title', 'Products Management')
@section('page-title', 'Products Management')

@push('styles')
<style>
/* Modern Admin Products Styling */
.admin-header {
    background: linear-gradient(135deg, rgba(0, 127, 255, 0.05), rgba(35, 239, 255, 0.03));
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid rgba(0, 127, 255, 0.1);
}

.admin-header h4 {
    font-family: 'Orbitron', monospace;
    font-weight: 700;
    background: linear-gradient(135deg, #007fff, #23efff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
}

.admin-header p {
    color: #6c757d;
    font-size: 1rem;
    margin: 0;
}

.btn-voltronix {
    background: linear-gradient(135deg, #007fff, #23efff);
    border: none;
    color: white;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    border-radius: 15px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 127, 255, 0.3);
}

.btn-voltronix:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 127, 255, 0.4);
    color: white;
}

.admin-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 127, 255, 0.1);
    transition: all 0.3s ease;
    overflow: hidden;
}

.admin-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
}

.form-control, .form-select {
    border-radius: 12px;
    border: 2px solid #e9ecef;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.form-control:focus, .form-select:focus {
    border-color: #007fff;
    box-shadow: 0 0 0 0.2rem rgba(0, 127, 255, 0.15);
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

/* Modern Table Styling */
.table-modern {
    border-collapse: separate;
    border-spacing: 0;
}

.table-modern thead th {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: none;
    font-weight: 700;
    color: #495057;
    padding: 1rem 0.75rem;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: sticky;
    top: 0;
    z-index: 10;
}

.table-modern thead th:first-child {
    border-radius: 15px 0 0 0;
}

.table-modern thead th:last-child {
    border-radius: 0 15px 0 0;
}

.table-modern tbody tr {
    transition: all 0.2s ease;
    border: none;
}

.table-modern tbody tr:hover {
    background: rgba(0, 127, 255, 0.05);
    transform: scale(1.01);
}

.table-modern tbody td {
    padding: 1rem 0.75rem;
    border: none;
    border-bottom: 1px solid #f1f3f4;
    vertical-align: middle;
}

.table-modern tbody tr:last-child td:first-child {
    border-radius: 0 0 0 15px;
}

.table-modern tbody tr:last-child td:last-child {
    border-radius: 0 0 15px 0;
}

/* Product Thumbnail Styling */
.product-thumbnail {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    object-fit: cover;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.product-thumbnail:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.thumbnail-placeholder {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 1.5rem;
}

/* Badge Styling */
.badge-modern {
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-featured {
    background: linear-gradient(135deg, #ffc107, #ff8c00);
    color: white;
    animation: pulse 2s infinite;
}

.badge-new {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
}

.badge-sale {
    background: linear-gradient(135deg, #dc3545, #e91e63);
    color: white;
    animation: bounce 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-3px); }
    60% { transform: translateY(-2px); }
}

/* Action Buttons */
.btn-group-modern .btn {
    border-radius: 8px;
    margin: 0 2px;
    padding: 0.5rem 0.75rem;
    font-size: 0.85rem;
    transition: all 0.2s ease;
}

.btn-group-modern .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Bulk Actions */
.bulk-actions-card {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 140, 0, 0.05));
    border: 2px solid rgba(255, 193, 7, 0.2);
    border-radius: 15px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .admin-header {
        padding: 1.5rem;
        text-align: center;
    }
    
    .admin-header h4 {
        font-size: 1.5rem;
    }
    
    .table-responsive {
        border-radius: 15px;
    }
    
    .btn-group-modern {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .btn-group-modern .btn {
        margin: 0;
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
    }
    
    .admin-header {
        padding: 1.5rem;
        text-align: center;
    }
    
    .admin-header h4 {
        font-size: 1.5rem;
    }
}

/* RTL Support */
[dir="rtl"] .admin-header h4 {
    text-align: right;
}

[dir="rtl"] .btn-group-modern .btn {
    margin: 0 2px 0 0;
}
</style>
@endpush

@section('content')
<div class="admin-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h4 class="mb-1">{{ __('admin.product.list') }}</h4>
            <p class="mb-0">{{ __('admin.common.manage_digital_products_desc') }}</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-voltronix">
            <i class="bi bi-plus-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
            {{ __('admin.product.create') }}
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
        <form method="GET" action="{{ route('admin.products.index') }}">
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
                               placeholder="{{ __('admin.product.search_placeholder') }}">
                        <button type="submit" class="btn search-btn">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="filter-col">
                    <label for="category" class="form-label-enhanced">{{ __('admin.product.filter_by_category') }}</label>
                    <select name="category" id="category" class="form-control form-control-enhanced">
                        <option value="">{{ __('admin.common.all') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->getTranslation('name', app()->getLocale()) ?? $category->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="filter-col">
                    <label for="status" class="form-label-enhanced">{{ __('admin.product.filter_by_status') }}</label>
                    <select name="status" id="status" class="form-control form-control-enhanced">
                        <option value="">{{ __('admin.common.all') }}</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>
                            {{ __('admin.product.available') }}
                        </option>
                        <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>
                            {{ __('admin.product.unavailable') }}
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
                        <a href="{{ route('admin.products.index') }}" class="filter-btn btn-clear">
                            <i class="bi bi-arrow-clockwise"></i>
                            {{ __('admin.common.clear') }}
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Actions -->
<div class="admin-card bulk-actions-card mb-4" id="bulkActions" style="display: none;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.products.bulk-action') }}" id="bulkForm">
            @csrf
            <div class="row align-items-center g-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-square text-warning fs-5"></i>
                        <span class="fw-bold text-dark">{{ __('admin.with_selected') }}:</span>
                        <span id="selectedCount" class="badge bg-warning text-dark fs-6">0</span>
                        <span class="text-muted">{{ __('admin.product.title') }}</span>
                    </div>
                </div>
                <div class="col-md-6 text-{{ app()->getLocale() == 'ar' ? 'start' : 'end' }}">
                    <div class="d-flex gap-2 justify-content-{{ app()->getLocale() == 'ar' ? 'start' : 'end' }} flex-wrap">
                        <select name="action" class="form-select" style="width: auto; min-width: 200px;" required>
                            <option value="">{{ __('admin.bulk_actions') }}</option>
                            <option value="activate">{{ __('admin.product.bulk_activated_successfully') }}</option>
                            <option value="deactivate">{{ __('admin.product.bulk_deactivated_successfully') }}</option>
                            <option value="delete">{{ __('admin.delete') }}</option>
                        </select>
                        <button type="submit" class="btn btn-warning text-dark fw-bold">
                            <i class="bi bi-lightning-charge {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                            {{ __('admin.common.actions') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="admin-card">
    <div class="card-body p-0">
        @if($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th width="80">{{ __('admin.product.thumbnail') }}</th>
                            <th>{{ __('admin.product.name') }}</th>
                            <th>{{ __('admin.product.category') }}</th>
                            <th>{{ __('admin.product.price') }}</th>
                            <th>{{ __('admin.product.status') }}</th>
                            <th>{{ __('admin.common.media_type') }}</th>
                            <th>{{ __('admin.common.flags') }}</th>
                            <th width="150">{{ __('admin.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                <input type="checkbox" name="products[]" value="{{ $product->id }}" 
                                       class="form-check-input product-checkbox">
                            </td>
                            <td>
                                @if($product->thumbnail)
                                    <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                         alt="{{ $product->getTranslation('name', app()->getLocale()) ?? $product->name ?? 'Product' }}"
                                         class="product-thumbnail">
                                @else
                                    <div class="thumbnail-placeholder">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $product->getTranslation('name', app()->getLocale()) ?? $product->name ?? 'N/A' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $product->slug }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-modern" style="background: linear-gradient(135deg, #17a2b8, #20c997); color: white;">
                                    {{ $product->category->getTranslation('name', app()->getLocale()) ?? $product->category->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    @if($product->discount_price)
                                        <span class="text-decoration-line-through text-muted small">
                                            ${{ number_format($product->price, 2) }}
                                        </span>
                                        <br>
                                        <strong class="text-danger">
                                            ${{ number_format($product->discount_price, 2) }}
                                        </strong>
                                    @else
                                        <strong>${{ number_format($product->price, 2) }}</strong>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($product->status === 'available')
                                    <span class="badge badge-modern" style="background: linear-gradient(135deg, #28a745, #20c997); color: white;">
                                        <i class="bi bi-check-circle {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                        {{ __('admin.product.available') }}
                                    </span>
                                @else
                                    <span class="badge badge-modern" style="background: linear-gradient(135deg, #dc3545, #e91e63); color: white;">
                                        <i class="bi bi-x-circle {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                        {{ __('admin.product.unavailable') }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-modern" style="background: linear-gradient(135deg, #6c757d, #495057); color: white;">
                                    <i class="bi bi-camera {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                    {{ ucfirst(str_replace('_', ' ', $product->media_type)) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @if($product->is_featured)
                                        <span class="badge badge-modern badge-featured">
                                            <i class="bi bi-star-fill {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                            Featured
                                        </span>
                                    @endif
                                    @if($product->is_new)
                                        <span class="badge badge-modern badge-new">
                                            <i class="bi bi-lightning-fill {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                            New
                                        </span>
                                    @endif
                                    @if($product->discount_price)
                                        <span class="badge badge-modern badge-sale">
                                            <i class="bi bi-percent {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                            Sale
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-modern d-flex gap-1" role="group">
                                    <a href="{{ route('admin.products.show', $product) }}" 
                                       class="btn btn-sm btn-outline-info" title="{{ __('admin.view') }}">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="btn btn-sm btn-outline-primary" title="{{ __('admin.edit') }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteProduct({{ $product->id }})" title="{{ __('admin.delete') }}">
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
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted">
                    {{ __('admin.product.title') }}: {{ $products->total() }} | 
                    {{ __('admin.product.title') }} {{ $products->firstItem() }}-{{ $products->lastItem() }}
                </div>
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-box-seam text-muted" style="font-size: 4rem;"></i>
                <h4 class="text-muted mt-3">{{ __('admin.product.no_products') }}</h4>
                <p class="text-muted">{{ __('admin.product.create') }}</p>
                <a href="{{ route('admin.products.create') }}" class="btn btn-voltronix">
                    <i class="bi bi-plus-circle me-2"></i>
                    {{ __('admin.product.create') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    const bulkForm = document.getElementById('bulkForm');

    selectAllCheckbox.addEventListener('change', function() {
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        const count = checkedBoxes.length;
        
        selectedCount.textContent = count;
        bulkActions.style.display = count > 0 ? 'block' : 'none';
        
        // Update select all checkbox state
        selectAllCheckbox.indeterminate = count > 0 && count < productCheckboxes.length;
        selectAllCheckbox.checked = count === productCheckboxes.length;
    }

    // Bulk form submission
    bulkForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        const action = this.querySelector('select[name="action"]').value;
        
        if (!action) {
            showError('Please select an action');
            return;
        }

        let title, text, confirmText;
        
        switch(action) {
            case 'delete':
                title = 'Delete Products?';
                text = `Are you sure you want to delete ${checkedBoxes.length} products?`;
                confirmText = 'Yes, Delete';
                break;
            case 'activate':
                title = 'Activate Products?';
                text = `Activate ${checkedBoxes.length} products?`;
                confirmText = 'Yes, Activate';
                break;
            case 'deactivate':
                title = 'Deactivate Products?';
                text = `Deactivate ${checkedBoxes.length} products?`;
                confirmText = 'Yes, Deactivate';
                break;
        }

        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: action === 'delete' ? '#dc3545' : '#007fff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: confirmText,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Add checked product IDs to form
                checkedBoxes.forEach(checkbox => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'products[]';
                    input.value = checkbox.value;
                    this.appendChild(input);
                });
                
                this.submit();
            }
        });
    });
});

// Delete single product
function deleteProduct(productId) {
    Swal.fire({
        title: 'Delete Product?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/products/${productId}`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
