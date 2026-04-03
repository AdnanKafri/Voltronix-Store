@extends('admin.layouts.app')

@section('title', __('admin.category.create'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 title-orbitron">
                <i class="fas fa-plus-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.category.create') }}
            </h1>
            <p class="text-muted mb-0">{{ __('admin.category.create') }}</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
            {{ __('admin.back') }}
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Main Form Card -->
            <div class="card card-voltronix">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.category.create') }}
                    </h5>
                </div>
                
                <div class="card-body">
                    <form id="categoryForm" method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
                        @csrf
                        
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
                                                   value="{{ old('name_en') }}" 
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
                                                   value="{{ old('slug') }}"
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
                                              rows="3">{{ old('description_en') }}</textarea>
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
                                           value="{{ old('name_ar') }}" 
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
                                              dir="rtl">{{ old('description_ar') }}</textarea>
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
                            <div class="upload-area" id="uploadArea">
                                <input type="file" 
                                       class="form-control d-none @error('thumbnail') is-invalid @enderror" 
                                       id="thumbnail" 
                                       name="thumbnail" 
                                       accept="image/*">
                                <div class="upload-content text-center py-4">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                    <h6>{{ __('admin.category.upload_thumbnail') }}</h6>
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
                                           value="{{ old('sort_order', 0) }}" 
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
                                               {{ old('is_active', true) ? 'checked' : '' }}>
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
                                {{ __('admin.category.create') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
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
                            {{ __('admin.category.slug') }} auto-generated
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
    // Auto-generate slug from English name
    const nameEnInput = document.getElementById('name_en');
    const slugInput = document.getElementById('slug');
    
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
        this.dataset.autoGenerated = 'false';
    });
    
    // File Upload Handling
    const uploadArea = document.getElementById('uploadArea');
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
            submitBtn.innerHTML = '<i class="fas fa-save {{ app()->getLocale() == "ar" ? "ms-2" : "me-2" }}"></i> {{ __("admin.category.create") }}';
            
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
}
</style>
@endpush
