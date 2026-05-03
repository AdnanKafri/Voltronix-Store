@extends('admin.layouts.app')

@section('title', __('admin.deliveries.create_delivery'))

@push('styles')
<style>
.delivery-form-container {
    max-width: 95%;
    margin: 0 auto;
    padding: 2rem 0;
}

.form-header {
    background: linear-gradient(135deg, #007fff, #23efff);
    color: white;
    padding: 2rem;
    border-radius: 20px;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0, 127, 255, 0.2);
}

.form-header h1 {
    font-family: 'Orbitron', monospace;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.card-voltronix {
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
    border: 2px solid rgba(0, 127, 255, 0.1);
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    margin-bottom: 2rem;
}

.card-voltronix:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(0, 127, 255, 0.15);
    border-color: #007fff;
}

.card-header-voltronix {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-bottom: 2px solid rgba(0, 127, 255, 0.1);
    padding: 1.5rem;
    border-radius: 18px 18px 0 0;
}

.card-header-voltronix h6 {
    font-family: 'Orbitron', monospace;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}

.form-control-voltronix {
    background: rgba(255, 255, 255, 0.9);
    border: 2px solid rgba(0, 127, 255, 0.1);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control-voltronix:focus {
    background: rgba(255, 255, 255, 1);
    border-color: #007fff;
    box-shadow: 0 0 0 0.2rem rgba(0, 127, 255, 0.25);
}

.btn-voltronix-primary {
    background: linear-gradient(135deg, #007fff, #23efff);
    border: none;
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-voltronix-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 127, 255, 0.3);
    color: white;
}

.btn-voltronix-secondary {
    background: transparent;
    border: 2px solid #007fff;
    color: #007fff;
    padding: 0.75rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-voltronix-secondary:hover {
    background: #007fff;
    color: white;
    transform: translateY(-2px);
}

.delivery-type-selector {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.delivery-type-card {
    background: rgba(255, 255, 255, 0.9);
    border: 2px solid rgba(0, 127, 255, 0.1);
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.delivery-type-card:hover {
    border-color: #007fff;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 127, 255, 0.15);
}

.delivery-type-card.active {
    background: linear-gradient(135deg, #007fff, #23efff);
    color: white;
    border-color: #007fff;
}

.delivery-type-card i {
    font-size: 2rem;
    margin-bottom: 1rem;
    display: block;
}

.conditional-field {
    display: none;
}

.conditional-field.active {
    display: block;
}
</style>
@endpush

@section('content')
<div class="delivery-form-container">
    <!-- Header -->
    <div class="form-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>{{ __('admin.deliveries.create_delivery') }}</h1>
                <p class="mb-0 opacity-90">{{ __('admin.deliveries.create_description') }}</p>
            </div>
            <div>
                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-light">
                    <i class="fas fa-arrow-left {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.common.back_to_order') }}
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.orders.deliveries.store', $order) }}" method="POST" enctype="multipart/form-data" id="deliveryForm">
        @csrf
        <input type="hidden" name="order_item_id" value="{{ request('item_id') }}">
        
        <!-- Order Information -->
        <div class="card-voltronix">
            <div class="card-header-voltronix">
                <h6><i class="fas fa-shopping-cart {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('admin.orders.order_information') }}</h6>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>{{ __('admin.orders.order_number') }}:</strong> {{ $order->order_number }}</p>
                        <p><strong>{{ __('admin.orders.customer') }}:</strong> {{ $order->customer_name }}</p>
                        <p><strong>{{ __('admin.orders.email') }}:</strong> {{ $order->customer_email }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>{{ __('admin.orders.total_amount') }}:</strong> {{ $order->formatted_total }}</p>
                        <p><strong>{{ __('admin.orders.status') }}:</strong> 
                            <span class="badge bg-{{ $order->status == 'approved' ? 'success' : ($order->status == 'pending' ? 'warning' : 'danger') }}">
                                {{ __('admin.orders.status_' . $order->status) }}
                            </span>
                        </p>
                        <p><strong>{{ __('admin.orders.created_at') }}:</strong> {{ local_datetime($order->created_at, 'Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivery Type Selection -->
        <div class="card-voltronix">
            <div class="card-header-voltronix">
                <h6><i class="fas fa-truck {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('admin.deliveries.delivery_type') }}</h6>
            </div>
            <div class="card-body p-4">
                <div class="delivery-type-selector">
                    <div class="delivery-type-card" data-type="file">
                        <i class="fas fa-file-download"></i>
                        <h6>{{ __('admin.deliveries.type_file') }}</h6>
                        <p class="small text-muted">{{ __('admin.deliveries.type_file_desc') }}</p>
                    </div>
                    <div class="delivery-type-card" data-type="credentials">
                        <i class="fas fa-key"></i>
                        <h6>{{ __('admin.deliveries.type_credentials') }}</h6>
                        <p class="small text-muted">{{ __('admin.deliveries.type_credentials_desc') }}</p>
                    </div>
                    <div class="delivery-type-card" data-type="license">
                        <i class="fas fa-certificate"></i>
                        <h6>{{ __('admin.deliveries.type_license') }}</h6>
                        <p class="small text-muted">{{ __('admin.deliveries.type_license_desc') }}</p>
                    </div>
                </div>
                <input type="hidden" name="type" id="deliveryType" required>
                <input type="hidden" name="credentials_type" id="credentialsType">
                @error('type')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Basic Information -->
        <div class="card-voltronix">
            <div class="card-header-voltronix">
                <h6><i class="fas fa-info-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('admin.deliveries.basic_information') }}</h6>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('admin.deliveries.title') }} <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-voltronix @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('admin.deliveries.status') }}</label>
                        <select name="status" class="form-control form-control-voltronix @error('status') is-invalid @enderror">
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>{{ __('admin.deliveries.status_active') }}</option>
                            <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>{{ __('admin.deliveries.status_expired') }}</option>
                            <option value="revoked" {{ old('status') == 'revoked' ? 'selected' : '' }}>{{ __('admin.deliveries.status_revoked') }}</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">{{ __('admin.deliveries.description') }}</label>
                    <textarea name="description" class="form-control form-control-voltronix @error('description') is-invalid @enderror" 
                              rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- File Upload (for file type) -->
        <div class="card-voltronix conditional-field" id="fileFields">
            <div class="card-header-voltronix">
                <h6><i class="fas fa-upload {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('admin.deliveries.file_upload') }}</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('admin.deliveries.upload_file') }} <span class="text-danger">*</span></label>
                    <input type="file" name="delivery_file" class="form-control form-control-voltronix @error('delivery_file') is-invalid @enderror">
                    <div class="form-text">{{ __('admin.deliveries.file_upload_help') }}</div>
                    @error('delivery_file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Credentials (for credentials type) -->
        <div class="card-voltronix conditional-field" id="credentialsFields">
            <div class="card-header-voltronix">
                <h6><i class="fas fa-key {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('admin.deliveries.credentials_information') }}</h6>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('admin.deliveries.username') }}</label>
                        <input type="text" name="credentials[username]" class="form-control form-control-voltronix @error('credentials.username') is-invalid @enderror" 
                               value="{{ old('credentials.username') }}">
                        @error('credentials.username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('admin.deliveries.password') }}</label>
                        <input type="text" name="credentials[password]" class="form-control form-control-voltronix @error('credentials.password') is-invalid @enderror" 
                               value="{{ old('credentials.password') }}">
                        @error('credentials.password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('admin.deliveries.additional_info') }}</label>
                    <textarea name="admin_notes" class="form-control form-control-voltronix @error('admin_notes') is-invalid @enderror" 
                              rows="3" placeholder="{{ __('admin.deliveries.additional_info_placeholder') }}">{{ old('admin_notes') }}</textarea>
                    @error('admin_notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- License Key (for license type) -->
        <div class="card-voltronix conditional-field" id="licenseFields">
            <div class="card-header-voltronix">
                <h6><i class="fas fa-certificate {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('admin.deliveries.license_information') }}</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('admin.deliveries.license_key') }} <span class="text-danger">*</span></label>
                    <input type="text" name="credentials[license_key]" class="form-control form-control-voltronix @error('credentials.license_key') is-invalid @enderror" 
                           value="{{ old('credentials.license_key') }}" placeholder="XXXX-XXXX-XXXX-XXXX">
                    <div class="form-text">{{ __('admin.deliveries.license_key_help') }}</div>
                    @error('credentials.license_key')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">{{ __('admin.deliveries.license_instructions') }}</label>
                    <textarea name="license_instructions" class="form-control form-control-voltronix @error('license_instructions') is-invalid @enderror" 
                              rows="3" placeholder="{{ __('admin.deliveries.license_instructions_placeholder') }}">{{ old('license_instructions') }}</textarea>
                    <div class="form-text">{{ __('admin.deliveries.license_instructions_help') }}</div>
                    @error('license_instructions')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Access Control -->
        <div class="card-voltronix">
            <div class="card-header-voltronix">
                <h6><i class="fas fa-shield-alt {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('admin.deliveries.access_control') }}</h6>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('admin.deliveries.expiry_date') }}</label>
                        <input type="datetime-local" name="expires_at" class="form-control form-control-voltronix @error('expires_at') is-invalid @enderror" 
                               value="{{ old('expires_at') }}">
                        <div class="form-text">{{ __('admin.deliveries.expiry_date_help') }}</div>
                        @error('expires_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3 file-only-field" style="display: none;">
                        <label class="form-label">{{ __('admin.deliveries.download_limit') }}</label>
                        <input type="number" name="max_downloads" class="form-control form-control-voltronix @error('max_downloads') is-invalid @enderror" 
                               value="{{ old('max_downloads') }}" min="1" max="999">
                        <div class="form-text">{{ __('admin.deliveries.download_limit_help') }}</div>
                        @error('max_downloads')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3 credentials-only-field" style="display: none;">
                        <label class="form-label">{{ __('admin.deliveries.view_limit') }}</label>
                        <input type="number" name="max_views" class="form-control form-control-voltronix @error('max_views') is-invalid @enderror" 
                               value="{{ old('max_views') }}" min="1" max="999">
                        <div class="form-text">{{ __('admin.deliveries.view_limit_help') }}</div>
                        @error('max_views')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('admin.deliveries.ip_restrictions') }}</label>
                        <input type="text" name="allowed_ips" class="form-control form-control-voltronix @error('allowed_ips') is-invalid @enderror" 
                               value="{{ old('allowed_ips') }}" placeholder="192.168.1.1, 10.0.0.1">
                        <div class="form-text">{{ __('admin.deliveries.ip_restrictions_help') }}</div>
                        @error('allowed_ips')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-voltronix-secondary">
                <i class="fas fa-times {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.common.cancel') }}
            </a>
            <button type="submit" class="btn btn-voltronix-primary" id="submitBtn">
                <i class="fas fa-save {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.deliveries.create_delivery') }}
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deliveryTypeCards = document.querySelectorAll('.delivery-type-card');
    const deliveryTypeInput = document.getElementById('deliveryType');
    const conditionalFields = document.querySelectorAll('.conditional-field');
    
    // Handle delivery type selection
    deliveryTypeCards.forEach(card => {
        card.addEventListener('click', function() {
            const type = this.dataset.type;
            
            // Update active state
            deliveryTypeCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            
            // Update hidden inputs
            deliveryTypeInput.value = type;
            
            // Set credentials_type for credentials and license types
            const credentialsTypeInput = document.getElementById('credentialsType');
            if (type === 'credentials' || type === 'license') {
                credentialsTypeInput.value = type;
            } else {
                credentialsTypeInput.value = '';
            }
            
            // Show/hide conditional fields
            conditionalFields.forEach(field => {
                field.classList.remove('active');
                if (field.id === type + 'Fields') {
                    field.classList.add('active');
                }
            });
            
            // Show/hide type-specific access control fields
            updateAccessControlFields(type);
            
            // Update required attributes
            updateRequiredFields(type);
        });
    });
    
    function updateAccessControlFields(type) {
        // Hide all type-specific fields first
        document.querySelectorAll('.file-only-field, .credentials-only-field').forEach(field => {
            field.style.display = 'none';
        });
        
        // Show relevant fields based on delivery type
        if (type === 'file') {
            document.querySelectorAll('.file-only-field').forEach(field => {
                field.style.display = 'block';
            });
        } else if (type === 'credentials') {
            document.querySelectorAll('.credentials-only-field').forEach(field => {
                field.style.display = 'block';
            });
        }
        // License type doesn't need specific fields - only common ones
    }
    
    function updateRequiredFields(type) {
        // Remove all required attributes from conditional fields
        document.querySelectorAll('.conditional-field input, .conditional-field textarea').forEach(input => {
            input.removeAttribute('required');
        });
        
        // Add required attributes based on type
        if (type === 'file') {
            const fileInput = document.querySelector('input[name="delivery_file"]');
            if (fileInput) {
                fileInput.setAttribute('required', 'required');
            }
        } else if (type === 'credentials') {
            const usernameInput = document.querySelector('input[name="credentials[username]"]');
            const passwordInput = document.querySelector('input[name="credentials[password]"]');
            if (usernameInput) {
                usernameInput.setAttribute('required', 'required');
            }
            if (passwordInput) {
                passwordInput.setAttribute('required', 'required');
            }
        } else if (type === 'license') {
            const licenseInput = document.querySelector('input[name="credentials[license_key]"]');
            if (licenseInput) {
                licenseInput.setAttribute('required', 'required');
            }
        }
    }
    
    // Form submission with AJAX
    document.getElementById('deliveryForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Clear previous validation errors
        clearValidationErrors();
        
        if (!deliveryTypeInput.value) {
            Swal.fire({
                icon: 'error',
                title: '{{ __("admin.deliveries.validation_error") }}',
                text: '{{ __("admin.deliveries.select_delivery_type") }}',
                confirmButtonColor: '#dc3545'
            });
            return false;
        }
        
        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        const originalContent = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin {{ app()->getLocale() == "ar" ? "ms-2" : "me-2" }}"></i> {{ __("admin.deliveries.creating") }}';
        submitBtn.disabled = true;
        
        try {
            const formData = new FormData(this);
            
            // Debug: Log form data
            console.log('Form action:', this.action);
            console.log('Form data entries:');
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }
            
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            console.log('Response status:', response.status);
            
            if (response.ok) {
                const data = await response.json();
                
                // Success - show notification and redirect
                Swal.fire({
                    title: '{{ __("admin.deliveries.success") }}',
                    text: data.message || '{{ __("admin.deliveries.created_successfully") }}',
                    icon: 'success',
                    confirmButtonColor: '#007fff',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = data.redirect || '{{ route("admin.orders.deliveries.show", $order) }}';
                });
            } else {
                const errorData = await response.json();
                console.log('Error response data:', errorData);
                
                if (response.status === 422) {
                    // Validation errors
                    displayValidationErrors(errorData.errors);
                    
                    Swal.fire({
                        title: '{{ __("admin.deliveries.validation_error") }}',
                        text: '{{ __("admin.deliveries.check_errors_below") }}',
                        icon: 'error',
                        confirmButtonColor: '#dc3545'
                    });
                } else {
                    // Other errors
                    Swal.fire({
                        title: '{{ __("admin.deliveries.error") }}',
                        text: errorData.message || '{{ __("admin.deliveries.creation_failed") }}',
                        icon: 'error',
                        confirmButtonColor: '#dc3545'
                    });
                }
            }
        } catch (error) {
            console.error('Form submission error:', error);
            
            Swal.fire({
                title: '{{ __("admin.deliveries.error") }}',
                text: '{{ __("admin.deliveries.network_error") }}',
                icon: 'error',
                confirmButtonColor: '#dc3545'
            });
        } finally {
            // Restore button state
            submitBtn.innerHTML = originalContent;
            submitBtn.disabled = false;
        }
    });
    
    // Validation error handling functions
    function clearValidationErrors() {
        // Remove all validation error classes and messages
        document.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
        
        document.querySelectorAll('.invalid-feedback').forEach(feedback => {
            feedback.style.display = 'none';
        });
    }
    
    function displayValidationErrors(errors) {
        Object.keys(errors).forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`) || 
                         document.querySelector(`[name="${fieldName}[]"]`) ||
                         document.querySelector(`input[name*="${fieldName}"]`);
            
            if (field) {
                field.classList.add('is-invalid');
                
                // Find or create error message element
                let errorElement = field.parentNode.querySelector('.invalid-feedback');
                if (!errorElement) {
                    errorElement = document.createElement('div');
                    errorElement.className = 'invalid-feedback';
                    field.parentNode.appendChild(errorElement);
                }
                
                errorElement.textContent = errors[fieldName][0];
                errorElement.style.display = 'block';
            }
        });
        
        // Scroll to first error
        const firstError = document.querySelector('.is-invalid');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
});
</script>
@endpush


