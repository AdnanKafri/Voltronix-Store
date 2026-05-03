@extends('admin.layouts.app')

@section('title', __('admin.category.view'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 title-orbitron">
                <i class="fas fa-eye {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.category.view') }}
            </h1>
            <p class="text-muted mb-0">{{ $category->getTranslation('name') }}</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-voltronix-primary">
                <i class="fas fa-edit {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.edit') }}
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.back') }}
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Category Details -->
        <div class="col-lg-8">
            <!-- Basic Information Card -->
            <div class="card card-voltronix mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.category.view') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Thumbnail -->
                        <div class="col-md-3 text-center mb-3">
                            @if($category->thumbnail)
                                <img src="{{ Storage::url($category->thumbnail) }}" 
                                     alt="{{ $category->getTranslation('name') }}"
                                     class="img-thumbnail category-thumbnail">
                            @else
                                <div class="no-thumbnail d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Details -->
                        <div class="col-md-9">
                            <div class="row">
                                <!-- English Name -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ __('admin.category.name_en') }}</label>
                                    <p class="form-control-plaintext">{{ $category->getTranslation('name', 'en') ?: '-' }}</p>
                                </div>
                                
                                <!-- Arabic Name -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ __('admin.category.name_ar') }}</label>
                                    <p class="form-control-plaintext" dir="rtl">{{ $category->getTranslation('name', 'ar') ?: '-' }}</p>
                                </div>
                                
                                <!-- Slug -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ __('admin.category.slug') }}</label>
                                    <p class="form-control-plaintext">
                                        <code>{{ $category->slug }}</code>
                                    </p>
                                </div>
                                
                                <!-- Sort Order -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ __('admin.category.sort_order') }}</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge bg-secondary">{{ $category->sort_order }}</span>
                                    </p>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ __('admin.common.status') }}</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            <i class="fas fa-{{ $category->is_active ? 'check' : 'times' }} {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                            {{ $category->is_active ? __('admin.common.active') : __('admin.common.inactive') }}
                                        </span>
                                    </p>
                                </div>
                                
                                <!-- Products Count -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ __('admin.category.products_count') }}</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge bg-info">{{ $category->products()->count() }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Descriptions -->
                    <hr>
                    <div class="row">
                        <!-- English Description -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('admin.category.description_en') }}</label>
                            <div class="description-box">
                                {{ $category->getTranslation('description', 'en') ?: __('admin.category.description') }}
                            </div>
                        </div>
                        
                        <!-- Arabic Description -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('admin.category.description_ar') }}</label>
                            <div class="description-box" dir="rtl">
                                {{ $category->getTranslation('description', 'ar') ?: __('admin.category.description') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products in Category -->
            <div class="card card-voltronix">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-box {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.category.products_count') }}
                    </h5>
                    <span class="badge bg-primary">{{ $category->products()->count() }}</span>
                </div>
                
                <div class="card-body p-0">
                    @if($category->products()->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th style="width: 80px;">{{ __('admin.product.thumbnail') }}</th>
                                        <th>{{ __('admin.product.name') }}</th>
                                        <th style="width: 120px;">{{ __('admin.product.price') }}</th>
                                        <th style="width: 100px;">{{ __('admin.common.status') }}</th>
                                        <th style="width: 120px;">{{ __('admin.common.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->products()->ordered()->take(10)->get() as $product)
                                        <tr>
                                            <td class="text-muted">{{ $product->id }}</td>
                                            
                                            <!-- Thumbnail -->
                                            <td>
                                                @if($product->thumbnail)
                                                    <img src="{{ Storage::url($product->thumbnail) }}" 
                                                         alt="{{ $product->getTranslation('name') }}"
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
                                                <div class="fw-bold">{{ $product->getTranslation('name', 'en') }}</div>
                                                @if(app()->getLocale() == 'ar' || $product->getTranslation('name', 'ar'))
                                                    <small class="text-muted">{{ $product->getTranslation('name', 'ar') }}</small>
                                                @endif
                                            </td>
                                            
                                            <!-- Price -->
                                            <td>
                                                <span class="fw-bold text-primary">${{ number_format($product->price, 2) }}</span>
                                            </td>
                                            
                                            <!-- Status -->
                                            <td>
                                                <span class="badge {{ $product->status == 'available' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $product->status == 'available' ? __('admin.product.available') : __('admin.product.unavailable') }}
                                                </span>
                                            </td>
                                            
                                            <!-- Actions -->
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.products.show', $product) }}" 
                                                       class="btn btn-sm btn-outline-info"
                                                       title="{{ __('admin.view') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="{{ __('admin.edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($category->products()->count() > 10)
                            <div class="card-footer text-center">
                                <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                    {{ __('admin.view') }} {{ __('admin.common.all') }} ({{ $category->products()->count() }})
                                </a>
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-box fa-3x text-muted"></i>
                            </div>
                            <h6 class="text-muted">{{ __('admin.product.no_products') }}</h6>
                            <p class="text-muted mb-4">{{ __('admin.product.no_products') }}</p>
                            <a href="{{ route('admin.products.create', ['category' => $category->id]) }}" class="btn btn-voltronix-primary">
                                <i class="fas fa-plus {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('admin.product.create') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats Card -->
            <div class="card card-voltronix mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.category.view') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12 mb-3">
                            <div class="stat-item">
                                <h3 class="text-primary mb-0">{{ $category->products()->count() }}</h3>
                                <small class="text-muted">{{ __('admin.category.products_count') }}</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h5 class="text-success mb-0">{{ $category->products()->where('status', 'available')->count() }}</h5>
                                <small class="text-muted">{{ __('admin.product.available') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h5 class="text-secondary mb-0">{{ $category->products()->where('status', 'unavailable')->count() }}</h5>
                            <small class="text-muted">{{ __('admin.product.unavailable') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Details Card -->
            <div class="card card-voltronix mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.category.view') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="small text-muted">
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('admin.category.created_at') }}:</span>
                            <span>{{ local_datetime($category->created_at, 'Y-m-d H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('admin.category.updated_at') }}:</span>
                            <span>{{ local_datetime($category->updated_at, 'Y-m-d H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>{{ __('admin.category.slug') }}:</span>
                            <code class="small">{{ $category->slug }}</code>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card card-voltronix">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.common.actions') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-voltronix-primary">
                            <i class="fas fa-edit {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('admin.category.edit') }}
                        </a>
                        
                        <a href="{{ route('admin.products.create', ['category' => $category->id]) }}" class="btn btn-outline-success">
                            <i class="fas fa-plus {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('admin.product.create') }}
                        </a>
                        
                        <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-{{ $category->is_active ? 'warning' : 'success' }} w-100">
                                <i class="fas fa-{{ $category->is_active ? 'pause' : 'play' }} {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ $category->is_active ? __('admin.category.inactive') : __('admin.category.active') }}
                            </button>
                        </form>
                        
                        @if($category->products()->count() == 0)
                            <button type="button" 
                                    class="btn btn-outline-danger w-100 delete-category-btn"
                                    data-category-id="{{ $category->id }}"
                                    data-category-name="{{ $category->getTranslation('name') }}">
                                <i class="fas fa-trash {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('admin.category.delete') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete Category Functionality
    const deleteBtn = document.querySelector('.delete-category-btn');
    
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            const categoryId = this.dataset.categoryId;
            const categoryName = this.dataset.categoryName;
            
            Swal.fire({
                title: '{{ __("admin.category.delete") }}',
                text: `{{ __("admin.category.confirm_delete") }} "${categoryName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '{{ __("admin.category.yes_delete") }}',
                cancelButtonText: '{{ __("admin.cancel") }}',
                reverseButtons: {{ app()->getLocale() == 'ar' ? 'true' : 'false' }}
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit delete form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/categories/${categoryId}`;
                    
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
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.category-thumbnail {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border: 3px solid rgba(13, 110, 253, 0.1);
    border-radius: 15px;
}

.no-thumbnail {
    width: 150px;
    height: 150px;
    background: linear-gradient(145deg, #f8f9fa, #e9ecef);
    border: 3px solid rgba(13, 110, 253, 0.1);
    border-radius: 15px;
}

.description-box {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 1rem;
    min-height: 80px;
    color: #6c757d;
    font-style: italic;
}

.stat-item h3 {
    font-family: 'Orbitron', sans-serif;
    font-weight: 900;
}

.form-control-plaintext {
    padding: 0.375rem 0;
    margin-bottom: 0;
    font-size: 0.875rem;
    line-height: 1.5;
    color: #212529;
    background-color: transparent;
    border: solid transparent;
    border-width: 1px 0;
}

.table th {
    font-weight: 600;
    color: #1a1a1a;
    border-bottom: 2px solid rgba(13, 110, 253, 0.1);
}

.btn-group .btn {
    border-radius: 8px;
    margin: 0 2px;
}

.img-thumbnail {
    border: 2px solid rgba(13, 110, 253, 0.1);
    border-radius: 12px;
}

.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
    border-radius: 10px;
}

@media (max-width: 768px) {
    .category-thumbnail,
    .no-thumbnail {
        width: 100px;
        height: 100px;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group .btn {
        padding: 0.375rem 0.5rem;
    }
}
</style>
@endpush


