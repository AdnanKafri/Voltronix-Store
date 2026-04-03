@extends('admin.layouts.app')

@section('title', __('admin.category.edit'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 title-orbitron">
                <i class="fas fa-edit {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.category.edit') }}
            </h1>
            <p class="text-muted mb-0">{{ $category->getTranslation('name') }}</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-outline-info">
                <i class="fas fa-eye {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.view') }}
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.back') }}
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Main Form Card -->
            <div class="card card-voltronix">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.category.edit') }}
                    </h5>
                </div>
                
                <div class="card-body">
                    <form id="categoryForm" method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Language Tabs -->
                        <ul class="nav nav-tabs mb-4" id="languageTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="english-tab" data-bs-toggle="tab" data-bs-target="#english" type="button" role="tab">
                                    <i class="fas fa-globe {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                    English
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="arabic-tab" data-bs-toggle="tab" data-bs-target="#arabic" type="button" role="tab">
                                    <i class="fas fa-globe {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                    العربية
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="languageTabContent">
                            <!-- English Tab -->
                            <div class="tab-pane fade show active" id="english" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name_en" class="form-label">
                                                {{ __('admin.category.name_en') }} <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('name_en') is-invalid @enderror" 
                                                   id="name_en" 
                                                   name="name_en" 
                                                   value="{{ old('name_en', $category->getTranslation('name', 'en')) }}" 
                                                   required>
                                            @error('name_en')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="slug" class="form-label">
                                                {{ __('admin.category.slug') }}
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('slug') is-invalid @enderror" 
                                                   id="slug" 
                                                   name="slug" 
                                                   value="{{ old('slug', $category->slug) }}"
                                                   placeholder="{{ __('admin.category.slug') }}">
                                            <div class="form-text">{{ __('admin.category.slug') }}</div>
                                            @error('slug')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description_en" class="form-label">
                                        {{ __('admin.category.description_en') }}
                                    </label>
                                    <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                              id="description_en" 
                                              name="description_en" 
                                              rows="3">{{ old('description_en', $category->getTranslation('description', 'en')) }}</textarea>
                                    @error('description_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Arabic Tab -->
                            <div class="tab-pane fade" id="arabic" role="tabpanel">
                                <div class="mb-3">
                                    <label for="name_ar" class="form-label">
                                        {{ __('admin.category.name_ar') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name_ar') is-invalid @enderror" 
                                           id="name_ar" 
                                           name="name_ar" 
                                           value="{{ old('name_ar', $category->getTranslation('name', 'ar')) }}" 
                                           required
                                           dir="rtl">
                                    @error('name_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description_ar" class="form-label">
                                        {{ __('admin.category.description_ar') }}
                                    </label>
                                    <textarea class="form-control @error('description_ar') is-invalid @enderror" 
                                              id="description_ar" 
                                              name="description_ar" 
                                              rows="3"
                                              dir="rtl">{{ old('description_ar', $category->getTranslation('description', 'ar')) }}</textarea>
                                    @error('description_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Thumbnail Upload -->
                        <div class="mb-4">
                            <label for="thumbnail" class="form-label">
                                {{ __('admin.category.thumbnail') }}
                            </label>
                            
                            @if($category->thumbnail)
                                <div class="current-thumbnail mb-3">
                                    <label class="form-label">{{ __('admin.category.current_thumbnail') }}</label>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ Storage::url($category->thumbnail) }}" 
                                             alt="{{ $category->getTranslation('name') }}"
                                             class="img-thumbnail me-3"
                                             style="width: 100px; height: 100px; object-fit: cover;">
                                        <button type="button" class="btn btn-sm btn-outline-danger" id="removeThumbnail">
                                            <i class="fas fa-trash {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                            {{ __('admin.category.remove_thumbnail') }}
                                        </button>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="upload-area" id="uploadArea">
                                <input type="file" 
                                       class="form-control d-none @error('thumbnail') is-invalid @enderror" 
                                       id="thumbnail" 
                                       name="thumbnail" 
                                       accept="image/*">
                                <div class="upload-content text-center py-4">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                    <h6>{{ $category->thumbnail ? __('admin.category.upload_thumbnail') : __('admin.category.upload_thumbnail') }}</h6>
                                    <p class="text-muted mb-0">{{ __('admin.category.upload_thumbnail') }}</p>
                                    <small class="text-muted">JPG, PNG, GIF (Max: 2MB)</small>
                                </div>
                                <div class="upload-preview d-none">
                                    <img id="previewImage" src="" alt="Preview" class="img-thumbnail">
                                    <button type="button" class="btn btn-sm btn-danger remove-image">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            @error('thumbnail')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Settings -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">
                                        {{ __('admin.category.sort_order') }}
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" 
                                           name="sort_order" 
                                           value="{{ old('sort_order', $category->sort_order) }}" 
                                           min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('admin.common.status') }}</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1" 
                                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            {{ __('admin.category.is_active') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('admin.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-voltronix-primary" id="submitBtn">
                                <i class="fas fa-save {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('admin.update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Category Info Card -->
            <div class="card card-voltronix mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.category.view') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-0">{{ $category->products()->count() }}</h4>
                                <small class="text-muted">{{ __('admin.category.products_count') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="mb-0">
                                <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $category->is_active ? __('admin.common.active') : __('admin.common.inactive') }}
                                </span>
                            </h4>
                            <small class="text-muted">{{ __('admin.common.status') }}</small>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="small text-muted">
                        <div class="d-flex justify-content-between mb-1">
                            <span>{{ __('admin.category.created_at') }}:</span>
                            <span>{{ $category->created_at->format('Y-m-d') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>{{ __('admin.category.updated_at') }}:</span>
                            <span>{{ $category->updated_at->format('Y-m-d') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card card-voltronix">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.common.tips') }}
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('admin.category.name') }} {{ __('admin.validation.required') }}
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('admin.category.slug') }} auto-updated
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('admin.category.thumbnail') }} optional
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('admin.category.sort_order') }} for ordering
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from English name (only if slug is empty or auto-generated)
    const nameEnInput = document.getElementById('name_en');
    const slugInput = document.getElementById('slug');
    const originalSlug = slugInput.value;
    
    nameEnInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.autoGenerated !== 'false') {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            slugInput.value = slug;
            slugInput.dataset.autoGenerated = 'true';
        }
    });
    
    // Mark slug as manually edited
    slugInput.addEventListener('input', function() {
        if (this.value !== originalSlug) {
            this.dataset.autoGenerated = 'false';
        }
    });
    
    // Remove current thumbnail
    const removeThumbnailBtn = document.getElementById('removeThumbnail');
    if (removeThumbnailBtn) {
        removeThumbnailBtn.addEventListener('click', function() {
            Swal.fire({
                title: '{{ __("admin.category.remove_thumbnail") }}?',
                text: '{{ __("admin.confirm_delete") }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '{{ __("admin.yes") }}',
                cancelButtonText: '{{ __("admin.cancel") }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Add hidden input to remove thumbnail
                    const form = document.getElementById('categoryForm');
                    const removeInput = document.createElement('input');
                    removeInput.type = 'hidden';
                    removeInput.name = 'remove_thumbnail';
                    removeInput.value = '1';
                    form.appendChild(removeInput);
                    
                    // Hide current thumbnail
                    document.querySelector('.current-thumbnail').style.display = 'none';
                    
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __("admin.success") }}',
                        text: '{{ __("admin.category.removed_successfully") }}',
                        timer: 2000,
                        showConfirmButton: false
                    });
            }
            });
        });
    }
    
    // File Upload Handling
    const fileInput = document.getElementById('thumbnail');
    const uploadContent = uploadArea.querySelector('.upload-content');
    const uploadPreview = uploadArea.querySelector('.upload-preview');
    const previewImage = document.getElementById('previewImage');
    const removeImageBtn = uploadArea.querySelector('.remove-image');
    
    // Click to upload
    uploadArea.addEventListener('click', function(e) {
        if (!e.target.closest('.remove-image')) {
            fileInput.click();
        }
    });
    
    // Drag and drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('drag-over');
    });
    
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('drag-over');
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('drag-over');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });
    
    // File input change
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            handleFileSelect(this.files[0]);
        }
    });
    
    // Remove image
    removeImageBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        fileInput.value = '';
        uploadContent.classList.remove('d-none');
        uploadPreview.classList.add('d-none');
    });
    
    function handleFileSelect(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                uploadContent.classList.add('d-none');
                uploadPreview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    }
    
    // Form submission with loading state
    const form = document.getElementById('categoryForm');
    const submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin {{ app()->getLocale() == "ar" ? "ms-2" : "me-2" }}"></i> {{ __("admin.loading") }}';
    });
    
    // Form validation
    form.addEventListener('submit', function(e) {
        const nameEn = document.getElementById('name_en').value.trim();
        const nameAr = document.getElementById('name_ar').value.trim();
        
        if (!nameEn || !nameAr) {
            e.preventDefault();
            
            Swal.fire({
                icon: 'error',
                title: '{{ __("admin.validation_error") }}',
                text: '{{ __("admin.category.name") }} {{ __("admin.validation.required") }}'
            });
            
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save {{ app()->getLocale() == "ar" ? "ms-2" : "me-2" }}"></i> {{ __("admin.update") }}';
            
            return false;
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 15px;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.upload-area:hover,
.upload-area.drag-over {
    border-color: #007fff;
    background-color: rgba(0, 127, 255, 0.05);
}

.upload-preview {
    position: relative;
    text-align: center;
    padding: 1rem;
}

.upload-preview img {
    max-width: 200px;
    max-height: 200px;
    border-radius: 10px;
}

.remove-image {
    position: absolute;
    top: 10px;
    right: 10px;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.current-thumbnail img {
    border: 2px solid rgba(13, 110, 253, 0.1);
    border-radius: 12px;
}

.nav-tabs .nav-link {
    border-radius: 10px 10px 0 0;
    border: none;
    color: #6c757d;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    background: linear-gradient(135deg, #007fff, #23efff);
    color: white;
    border: none;
}

.form-check-input:checked {
    background-color: #007fff;
    border-color: #007fff;
}

.form-control:focus {
    border-color: #007fff;
    box-shadow: 0 0 0 0.2rem rgba(0, 127, 255, 0.25);
}

@media (max-width: 768px) {
    .upload-preview img {
        max-width: 150px;
        max-height: 150px;
    }
    
    .current-thumbnail img {
        width: 80px !important;
        height: 80px !important;
    }
}
</style>
@endpush
