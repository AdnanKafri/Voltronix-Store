@extends('admin.layouts.app')

@section('title', __('admin.currency.create'))

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800 font-orbitron">
                        <i class="fas fa-plus me-2 text-primary"></i>
                        {{ __('admin.currency.create') }}
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">{{ __('admin.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.currencies.index') }}">{{ __('admin.currency.title') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('admin.currency.create') }}</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('admin.currencies.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('admin.currency.back') }}
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.currencies.store') }}" method="POST" id="currencyForm">
        @csrf
        
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
                                       value="{{ old('name_en') }}" placeholder="{{ __('admin.currency.name_en') }}" maxlength="255" required>
                                <div class="form-text">{{ __('admin.currency.code_help') }}</div>
                                @error('name_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">{{ __('admin.currency.name_ar') }}</label>
                                <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" 
                                       value="{{ old('name_ar') }}" placeholder="{{ __('admin.currency.name_ar') }}" maxlength="255" required>
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
                                       value="{{ old('code') }}" placeholder="USD" maxlength="3" style="text-transform: uppercase" required>
                                <div class="form-text">{{ __('admin.currency.code_help') }}</div>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">{{ __('admin.currency.symbol') }}</label>
                                <input type="text" name="symbol" class="form-control @error('symbol') is-invalid @enderror" 
                                       value="{{ old('symbol') }}" placeholder="$" maxlength="10" required>
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
                                           value="{{ old('exchange_rate', '1.00000000') }}" step="0.00000001" min="0.00000001" required>
                                    <span class="input-group-text" id="currency-symbol">{{ __('admin.currency.code') }}</span>
                                </div>
                                <div class="form-text">{{ __('admin.currency.rate_help') }}</div>
                                @error('exchange_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Rate Examples -->
                        <div class="alert alert-info">
                            <h6><i class="fas fa-lightbulb me-2"></i>{{ __('admin.examples') }}:</h6>
                            <ul class="mb-0">
                                <li>USD ({{ __('admin.currency.base_currency') }}): 1.00000000</li>
                                <li>EUR: 0.85000000 (1 USD = 0.85 EUR)</li>
                                <li>SAR: 3.75000000 (1 USD = 3.75 SAR)</li>
                                <li>SYP: 13500.00000000 (1 USD = 13,500 SYP)</li>
                            </ul>
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
                                   value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <strong>{{ __('admin.currency.is_active') }}</strong>
                            </label>
                            <div class="form-text">{{ __('admin.currency.active_help') }}</div>
                        </div>

                        <!-- Default Currency -->
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_default" id="is_default" 
                                   value="1" {{ old('is_default') ? 'checked' : '' }}>
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
                                <i class="fas fa-save me-2"></i>{{ __('admin.currency.save') }}
                            </button>
                            <button type="button" class="btn btn-success" onclick="saveAndContinue()">
                                <i class="fas fa-plus me-2"></i>{{ __('admin.save_and_add_another') }}
                            </button>
                            <a href="{{ route('admin.currencies.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>{{ __('admin.currency.cancel') }}
                            </a>
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
        symbolSpan.textContent = this.value.toUpperCase() || '{{ __("admin.currency.code") }}';
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
});

// Save and continue function
function saveAndContinue() {
    const form = document.getElementById('currencyForm');
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'save_and_continue';
    input.value = '1';
    form.appendChild(input);
    form.submit();
}
</script>
@endpush
@endsection
