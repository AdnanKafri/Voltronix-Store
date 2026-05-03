@extends('admin.layouts.app')

@section('title', __('admin.currency.edit'))

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800 font-orbitron">
                        <i class="fas fa-edit me-2 text-primary"></i>
                        {{ __('admin.currency.edit') }}: {{ $currency->code }}
                        @if($currency->is_default)
                            <span class="badge bg-warning ms-2">{{ __('admin.currency.default') }}</span>
                        @endif
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">{{ __('admin.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.currencies.index') }}">{{ __('admin.currency.title') }}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.currencies.show', $currency) }}">{{ $currency->code }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('admin.currency.edit') }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-group">
                    <a href="{{ route('admin.currencies.show', $currency) }}" class="btn btn-info">
                        <i class="fas fa-eye me-2"></i>{{ __('admin.currency.view') }}
                    </a>
                    <a href="{{ route('admin.currencies.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>{{ __('admin.currency.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.currencies.update', $currency) }}" method="POST" id="currencyForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Main Form -->
            <div class="col-xl-8">
                <!-- General Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ __('admin.currency.general_info') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Currency Names -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">{{ __('admin.currency.name_en') }}</label>
                                <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" 
                                       value="{{ old('name_en', $currency->getTranslation('en')) }}" placeholder="{{ __('admin.currency.name_en') }}" maxlength="255" required>
                                @error('name_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">{{ __('admin.currency.name_ar') }}</label>
                                <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" 
                                       value="{{ old('name_ar', $currency->getTranslation('ar')) }}" placeholder="{{ __('admin.currency.name_ar') }}" maxlength="255" required>
                                @error('name_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Currency Code and Symbol -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">{{ __('admin.currency.code') }}</label>
                                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                                       value="{{ old('code', $currency->code) }}" placeholder="USD" maxlength="3" style="text-transform: uppercase" required>
                                <div class="form-text">{{ __('admin.currency.code_help') }}</div>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">{{ __('admin.currency.symbol') }}</label>
                                <input type="text" name="symbol" class="form-control @error('symbol') is-invalid @enderror" 
                                       value="{{ old('symbol', $currency->symbol) }}" placeholder="$" maxlength="10" required>
                                <div class="form-text">{{ __('admin.currency.symbol_help') }}</div>
                                @error('symbol')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rate Settings -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-line me-2"></i>
                            {{ __('admin.currency.rate_settings') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label required">{{ __('admin.currency.exchange_rate') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">1 {{ __('admin.currency.base_currency') }} =</span>
                                    <input type="number" name="exchange_rate" class="form-control @error('exchange_rate') is-invalid @enderror" 
                                           value="{{ old('exchange_rate', $currency->exchange_rate) }}" step="0.00000001" min="0.00000001" required>
                                    <span class="input-group-text" id="currency-symbol">{{ $currency->code }}</span>
                                </div>
                                <div class="form-text">{{ __('admin.currency.rate_help') }}</div>
                                @error('exchange_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Current Rate Display -->
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>{{ __('admin.current') }}:</h6>
                            <p class="mb-0">1 USD = {{ $currency->formatted_rate }} {{ $currency->code }}</p>
                            <small class="text-muted">{{ __('admin.currency.last_updated') }}: {{ local_datetime($currency->updated_at, 'M d, Y H:i') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-xl-4">
                <!-- Status Settings -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-cog me-2"></i>
                            {{ __('admin.currency.status_settings') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Active Status -->
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                   value="1" {{ old('is_active', $currency->is_active) ? 'checked' : '' }}
                                   {{ $currency->is_default ? 'disabled' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <strong>{{ __('admin.currency.is_active') }}</strong>
                            </label>
                            <div class="form-text">
                                {{ $currency->is_default ? __('admin.currency.cannot_deactivate_default') : __('admin.currency.active_help') }}
                            </div>
                        </div>

                        <!-- Default Currency -->
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_default" id="is_default" 
                                   value="1" {{ old('is_default', $currency->is_default) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_default">
                                <strong>{{ __('admin.currency.is_default') }}</strong>
                            </label>
                            <div class="form-text">{{ __('admin.currency.default_help') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-bolt me-2"></i>
                            {{ __('admin.currency.quick_actions') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>{{ __('admin.currency.update') }}
                            </button>
                            <a href="{{ route('admin.currencies.show', $currency) }}" class="btn btn-info">
                                <i class="fas fa-eye me-2"></i>{{ __('admin.currency.view') }}
                            </a>
                            <a href="{{ route('admin.currencies.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>{{ __('admin.currency.cancel') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Currency Statistics -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-bar me-2"></i>
                            {{ __('admin.currency.current_stats') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="stat-mini">
                                    <div class="stat-mini-number">{{ $currency->created_at->diffForHumans() }}</div>
                                    <div class="stat-mini-label">{{ __('admin.currency.created_at') }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-mini">
                                    <div class="stat-mini-number">{{ $currency->updated_at->diffForHumans() }}</div>
                                    <div class="stat-mini-label">{{ __('admin.currency.updated_at') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Help -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-question-circle me-2"></i>
                            {{ __('admin.help') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-2">
                            <strong>{{ __('admin.currency.code') }}:</strong><br>
                            {{ __('admin.currency.code_help') }}
                        </p>
                        <p class="text-muted mb-2">
                            <strong>{{ __('admin.currency.symbol') }}:</strong><br>
                            {{ __('admin.currency.symbol_help') }}
                        </p>
                        <p class="text-muted mb-0">
                            <strong>{{ __('admin.currency.exchange_rate') }}:</strong><br>
                            {{ __('admin.currency.rate_help') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update currency symbol in rate input
    const codeInput = document.querySelector('input[name="code"]');
    const symbolSpan = document.getElementById('currency-symbol');
    
    codeInput.addEventListener('input', function() {
        symbolSpan.textContent = this.value.toUpperCase() || '{{ $currency->code }}';
    });

    // Auto-uppercase currency code
    codeInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    // Form validation
    document.getElementById('currencyForm').addEventListener('submit', function(e) {
        const code = document.querySelector('input[name="code"]').value;
        const rate = document.querySelector('input[name="exchange_rate"]').value;
        
        if (code.length !== 3) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: '{{ __("admin.validation_error") }}',
                text: '{{ __("admin.currency.validation.code_size") }}'
            });
            return;
        }
        
        if (parseFloat(rate) <= 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: '{{ __("admin.validation_error") }}',
                text: '{{ __("admin.currency.validation.rate_min") }}'
            });
            return;
        }
    });

    // Default currency warning
    const defaultCheckbox = document.getElementById('is_default');
    const activeCheckbox = document.getElementById('is_active');
    
    defaultCheckbox.addEventListener('change', function() {
        if (this.checked) {
            activeCheckbox.checked = true;
            activeCheckbox.disabled = true;
            Swal.fire({
                icon: 'info',
                title: '{{ __("admin.info") }}',
                text: '{{ __("admin.currency.default_help") }}',
                timer: 3000,
                showConfirmButton: false
            });
        } else {
            activeCheckbox.disabled = {{ $currency->is_default ? 'true' : 'false' }};
        }
    });
});
</script>
@endpush
@endsection


