@extends('admin.layouts.app')

@section('title', __('admin.users.create'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 title-orbitron">
                <i class="bi bi-person-plus {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.users.create') }}
            </h1>
            <p class="text-muted mb-0">{{ __('admin.users.create_new_user') }}</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
            {{ __('admin.back') }}
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="admin-table">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.users.user_information') }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf
                        
                        <!-- Basic Information Section -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="bi bi-person-badge {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('admin.users.basic_information') }}
                            </h6>
                            <div class="row">
                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label-enhanced">
                                    {{ __('admin.users.name') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control form-control-enhanced @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required 
                                       autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label-enhanced">
                                    {{ __('admin.users.email') }} <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control form-control-enhanced @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label-enhanced">
                                    {{ __('admin.users.phone') }}
                                </label>
                                <input type="tel" 
                                       class="form-control form-control-enhanced @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Role -->
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label-enhanced">
                                    {{ __('admin.users.role_label') }} <span class="text-danger">*</span>
                                </label>
                                <select class="form-control form-control-enhanced @error('role') is-invalid @enderror" 
                                        id="role" 
                                        name="role" 
                                        required>
                                    <option value="">{{ __('admin.users.select_role') }}</option>
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>
                                        {{ __('admin.users.role.user') }}
                                    </option>
                                    <option value="moderator" {{ old('role') == 'moderator' ? 'selected' : '' }}>
                                        {{ __('admin.users.role.moderator') }}
                                    </option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                        {{ __('admin.users.role.admin') }}
                                    </option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        </div>

                        <!-- Account Settings Section -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="bi bi-gear {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('admin.users.account_settings') }}
                            </h6>
                            <div class="row">
                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label-enhanced">
                                    {{ __('admin.common.status') }} <span class="text-danger">*</span>
                                </label>
                                <select class="form-control form-control-enhanced @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="">{{ __('admin.users.select_status') }}</option>
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                        {{ __('admin.users.status.active') }}
                                    </option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                        {{ __('admin.users.status.inactive') }}
                                    </option>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                        {{ __('admin.users.status.pending') }}
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Verification -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label-enhanced">{{ __('admin.users.email_verification') }}</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="email_verified" 
                                           name="email_verified" 
                                           value="1"
                                           {{ old('email_verified') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_verified">
                                        {{ __('admin.users.mark_as_verified') }}
                                    </label>
                                </div>
                                <small class="text-muted">{{ __('admin.users.verification_help') }}</small>
                            </div>
                        </div>
                        </div>

                        <!-- Security Section -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="bi bi-shield-lock {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('admin.users.security_settings') }}
                            </h6>
                            <div class="row">
                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label-enhanced">
                                    {{ __('admin.users.password') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control form-control-enhanced @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                        <i class="bi bi-eye" id="password-icon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">{{ __('admin.users.password_help') }}</small>
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label-enhanced">
                                    {{ __('admin.users.confirm_password') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control form-control-enhanced" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                        <i class="bi bi-eye" id="password_confirmation-icon"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Generate Password Button -->
                            <div class="mt-3 pt-3 border-top">
                                <button type="button" class="btn btn-outline-info" onclick="generatePassword()">
                                    <i class="bi bi-key {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                    {{ __('admin.users.generate_password') }}
                                </button>
                                <small class="text-muted {{ app()->getLocale() == 'ar' ? 'me-3' : 'ms-3' }}">
                                    {{ __('admin.users.generate_password_help') }}
                                </small>
                            </div>
                        </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-3 justify-content-end pt-4 mt-4 border-top">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('admin.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-voltronix">
                                <i class="bi bi-check-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('admin.users.create') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

// Generate random password
function generatePassword() {
    const length = 12;
    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
    let password = "";
    
    // Ensure at least one character from each category
    password += "ABCDEFGHIJKLMNOPQRSTUVWXYZ"[Math.floor(Math.random() * 26)]; // Uppercase
    password += "abcdefghijklmnopqrstuvwxyz"[Math.floor(Math.random() * 26)]; // Lowercase
    password += "0123456789"[Math.floor(Math.random() * 10)]; // Number
    password += "!@#$%^&*"[Math.floor(Math.random() * 8)]; // Special
    
    // Fill the rest
    for (let i = password.length; i < length; i++) {
        password += charset[Math.floor(Math.random() * charset.length)];
    }
    
    // Shuffle the password
    password = password.split('').sort(() => Math.random() - 0.5).join('');
    
    // Set the password fields
    document.getElementById('password').value = password;
    document.getElementById('password_confirmation').value = password;
    
    // Show the password temporarily
    document.getElementById('password').type = 'text';
    document.getElementById('password_confirmation').type = 'text';
    document.getElementById('password-icon').className = 'bi bi-eye-slash';
    document.getElementById('password_confirmation-icon').className = 'bi bi-eye-slash';
    
    // Show success message
    Swal.fire({
        icon: 'success',
        title: '{{ __("admin.users.password_generated") }}',
        text: '{{ __("admin.users.password_generated_message") }}',
        timer: 3000,
        showConfirmButton: false
    });
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const nameField = document.getElementById('name');
    const emailField = document.getElementById('email');
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('password_confirmation');
    
    // Real-time name validation
    nameField.addEventListener('input', function() {
        if (this.value.length < 2) {
            this.classList.add('is-invalid');
            showFieldError(this, '{{ __("admin.users.name_too_short") }}');
        } else {
            this.classList.remove('is-invalid');
            hideFieldError(this);
        }
    });
    
    // Real-time email validation
    emailField.addEventListener('input', function() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (this.value && !emailRegex.test(this.value)) {
            this.classList.add('is-invalid');
            showFieldError(this, '{{ __("admin.users.invalid_email") }}');
        } else {
            this.classList.remove('is-invalid');
            hideFieldError(this);
            
            // Check email uniqueness
            if (this.value && emailRegex.test(this.value)) {
                checkEmailUniqueness(this.value, this);
            }
        }
    });
    
    // Real-time password validation
    passwordField.addEventListener('input', function() {
        const password = this.value;
        if (password.length > 0 && password.length < 8) {
            this.classList.add('is-invalid');
            showFieldError(this, '{{ __("admin.users.password_too_short") }}');
        } else {
            this.classList.remove('is-invalid');
            hideFieldError(this);
        }
        
        // Check confirmation match
        if (confirmPasswordField.value && confirmPasswordField.value !== password) {
            confirmPasswordField.classList.add('is-invalid');
            showFieldError(confirmPasswordField, '{{ __("admin.users.passwords_do_not_match") }}');
        } else if (confirmPasswordField.value) {
            confirmPasswordField.classList.remove('is-invalid');
            hideFieldError(confirmPasswordField);
        }
    });
    
    // Real-time password confirmation validation
    confirmPasswordField.addEventListener('input', function() {
        if (this.value && this.value !== passwordField.value) {
            this.classList.add('is-invalid');
            showFieldError(this, '{{ __("admin.users.passwords_do_not_match") }}');
        } else {
            this.classList.remove('is-invalid');
            hideFieldError(this);
        }
    });
    
    // Helper functions
    function showFieldError(field, message) {
        let errorDiv = field.parentNode.querySelector('.validation-error');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'validation-error text-danger small mt-1';
            field.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
    }
    
    function hideFieldError(field) {
        const errorDiv = field.parentNode.querySelector('.validation-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
    
    function checkEmailUniqueness(email, field) {
        // Debounce the request
        clearTimeout(field.emailCheckTimeout);
        field.emailCheckTimeout = setTimeout(() => {
            fetch('/admin/users/check-email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.available) {
                    field.classList.add('is-invalid');
                    showFieldError(field, '{{ __("admin.users.email_already_taken") }}');
                } else {
                    field.classList.remove('is-invalid');
                    hideFieldError(field);
                }
            })
            .catch(() => {
                // Ignore network errors for validation
            });
        }, 500);
    }
});
</script>

<style>
.form-label-enhanced {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control-enhanced {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control-enhanced:focus {
    border-color: #007fff;
    box-shadow: 0 0 0 0.2rem rgba(0, 127, 255, 0.25);
}

.input-group .btn {
    border: 2px solid #e9ecef;
    border-left: none;
    border-radius: 0 10px 10px 0;
}

.input-group .form-control-enhanced {
    border-right: none;
    border-radius: 10px 0 0 10px;
}

.input-group .form-control-enhanced:focus + .btn {
    border-color: #007fff;
}
</style>
@endpush
@endsection
