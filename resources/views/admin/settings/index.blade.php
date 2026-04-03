@extends('admin.layouts.app')

@section('title', __('admin.site_settings.general_settings'))

@push('styles')
<style>
.settings-container {
    padding: 2rem;
}

.settings-card {
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
    border: 2px solid rgba(0, 127, 255, 0.1);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    transition: all 0.3s ease;
}

.settings-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #007fff, #23efff);
}

.settings-section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid rgba(0, 127, 255, 0.1);
}

.settings-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.section-title {
    font-family: 'Orbitron', monospace;
    font-weight: 700;
    color: #007fff;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

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

.image-upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.image-upload-area:hover {
    border-color: #007fff;
    background-color: rgba(0, 127, 255, 0.05);
}

.current-image {
    max-width: 100px;
    max-height: 100px;
    border-radius: 10px;
    margin-bottom: 1rem;
}

.payment-methods {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.payment-method {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.payment-method:hover {
    border-color: #007fff;
    background-color: rgba(0, 127, 255, 0.05);
}

.payment-method input:checked + label {
    color: #007fff;
    font-weight: 600;
}

.social-links {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.btn-voltronix {
    background: linear-gradient(135deg, #007fff, #23efff);
    border: none;
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-voltronix:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 127, 255, 0.3);
    color: white;
}
</style>
@endpush

@section('content')
<div class="settings-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-gear {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.site_settings.general_settings') }}
            </h1>
            <p class="text-muted mt-1">{{ __('admin.site_settings.manage_site_configuration') }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Contact Information -->
        <div class="settings-card position-relative">
            <div class="settings-section">
                <h5 class="section-title">
                    <i class="bi bi-envelope"></i>
                    {{ __('admin.site_settings.contact_information') }}
                </h5>
                <p class="text-muted mb-4">{{ __('admin.site_settings.contact_description') }}</p>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="contact_email" class="form-label-enhanced">
                            {{ __('admin.site_settings.contact_email') }} <span class="text-danger">*</span>
                        </label>
                        <input type="email" 
                               class="form-control form-control-enhanced @error('contact_email') is-invalid @enderror" 
                               id="contact_email" 
                               name="contact_email" 
                               value="{{ old('contact_email', $settings['contact_email']) }}" 
                               required>
                        @error('contact_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="contact_phone" class="form-label-enhanced">
                            {{ __('admin.site_settings.contact_phone') }}
                        </label>
                        <input type="text" 
                               class="form-control form-control-enhanced @error('contact_phone') is-invalid @enderror" 
                               id="contact_phone" 
                               name="contact_phone" 
                               value="{{ old('contact_phone', $settings['contact_phone']) }}">
                        @error('contact_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="contact_address_en" class="form-label-enhanced">
                            {{ __('admin.site_settings.contact_address_en') }}
                        </label>
                        <textarea class="form-control form-control-enhanced @error('contact_address_en') is-invalid @enderror" 
                                  id="contact_address_en" 
                                  name="contact_address_en" 
                                  rows="3">{{ old('contact_address_en', $settings['contact_address_en']) }}</textarea>
                        @error('contact_address_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="contact_address_ar" class="form-label-enhanced">
                            {{ __('admin.site_settings.contact_address_ar') }}
                        </label>
                        <textarea class="form-control form-control-enhanced @error('contact_address_ar') is-invalid @enderror" 
                                  id="contact_address_ar" 
                                  name="contact_address_ar" 
                                  rows="3">{{ old('contact_address_ar', $settings['contact_address_ar']) }}</textarea>
                        @error('contact_address_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Social Media Links -->
            <div class="settings-section">
                <h5 class="section-title">
                    <i class="bi bi-share"></i>
                    {{ __('admin.site_settings.social_media') }}
                </h5>
                
                <div class="social-links">
                    <div class="mb-3">
                        <label for="facebook_url" class="form-label-enhanced">
                            <i class="bi bi-facebook text-primary {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                            {{ __('admin.site_settings.facebook_url') }}
                        </label>
                        <input type="url" 
                               class="form-control form-control-enhanced @error('facebook_url') is-invalid @enderror" 
                               id="facebook_url" 
                               name="facebook_url" 
                               value="{{ old('facebook_url', $settings['facebook_url']) }}">
                        @error('facebook_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="twitter_url" class="form-label-enhanced">
                            <i class="bi bi-twitter text-info {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                            {{ __('admin.site_settings.twitter_url') }}
                        </label>
                        <input type="url" 
                               class="form-control form-control-enhanced @error('twitter_url') is-invalid @enderror" 
                               id="twitter_url" 
                               name="twitter_url" 
                               value="{{ old('twitter_url', $settings['twitter_url']) }}">
                        @error('twitter_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="instagram_url" class="form-label-enhanced">
                            <i class="bi bi-instagram text-danger {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                            {{ __('admin.site_settings.instagram_url') }}
                        </label>
                        <input type="url" 
                               class="form-control form-control-enhanced @error('instagram_url') is-invalid @enderror" 
                               id="instagram_url" 
                               name="instagram_url" 
                               value="{{ old('instagram_url', $settings['instagram_url']) }}">
                        @error('instagram_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="whatsapp_number" class="form-label-enhanced">
                            <i class="bi bi-whatsapp text-success {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                            {{ __('admin.site_settings.whatsapp_number') }}
                        </label>
                        <input type="text" 
                               class="form-control form-control-enhanced @error('whatsapp_number') is-invalid @enderror" 
                               id="whatsapp_number" 
                               name="whatsapp_number" 
                               value="{{ old('whatsapp_number', $settings['whatsapp_number']) }}"
                               placeholder="+1234567890">
                        @error('whatsapp_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Payment & Commerce section temporarily disabled — reserved for future e-commerce expansion --}}
            {{-- 
            <div class="settings-section">
                <h5 class="section-title">
                    <i class="bi bi-credit-card"></i>
                    {{ __('admin.site_settings.payment_commerce') }}
                </h5>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="currency" class="form-label-enhanced">
                            {{ __('admin.site_settings.currency') }}
                        </label>
                        <select class="form-control form-control-enhanced @error('currency') is-invalid @enderror" 
                                id="currency" 
                                name="currency">
                            <option value="USD" {{ old('currency', $settings['currency']) == 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="EUR" {{ old('currency', $settings['currency']) == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                            <option value="SAR" {{ old('currency', $settings['currency']) == 'SAR' ? 'selected' : '' }}>SAR (ر.س)</option>
                            <option value="AED" {{ old('currency', $settings['currency']) == 'AED' ? 'selected' : '' }}>AED (د.إ)</option>
                        </select>
                        @error('currency')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="tax_rate" class="form-label-enhanced">
                            {{ __('admin.site_settings.tax_rate') }} (%)
                        </label>
                        <input type="number" 
                               class="form-control form-control-enhanced @error('tax_rate') is-invalid @enderror" 
                               id="tax_rate" 
                               name="tax_rate" 
                               value="{{ old('tax_rate', $settings['tax_rate']) }}"
                               min="0" 
                               max="100" 
                               step="0.01">
                        @error('tax_rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="shipping_fee" class="form-label-enhanced">
                            {{ __('admin.site_settings.shipping_fee') }}
                        </label>
                        <input type="number" 
                               class="form-control form-control-enhanced @error('shipping_fee') is-invalid @enderror" 
                               id="shipping_fee" 
                               name="shipping_fee" 
                               value="{{ old('shipping_fee', $settings['shipping_fee']) }}"
                               min="0" 
                               step="0.01">
                        @error('shipping_fee')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="free_shipping_threshold" class="form-label-enhanced">
                        {{ __('admin.site_settings.free_shipping_threshold') }}
                    </label>
                    <input type="number" 
                           class="form-control form-control-enhanced @error('free_shipping_threshold') is-invalid @enderror" 
                           id="free_shipping_threshold" 
                           name="free_shipping_threshold" 
                           value="{{ old('free_shipping_threshold', $settings['free_shipping_threshold']) }}"
                           min="0" 
                           step="0.01">
                    <small class="text-muted">{{ __('admin.site_settings.free_shipping_help') }}</small>
                    @error('free_shipping_threshold')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
            --}}

            <!-- Payment Methods -->
            <div class="settings-section">
                <h5 class="section-title">
                    <i class="bi bi-credit-card"></i>
                    {{ __('admin.site_settings.payment_methods') }}
                </h5>
                <p class="text-muted mb-4">{{ __('admin.site_settings.payment_description') }}</p>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-enhanced">{{ __('admin.site_settings.available_methods') }}</label>
                        <div class="payment-methods-grid">
                            @php
                                $availableMethods = [
                                    'bank_transfer' => __('admin.site_settings.bank_transfer'),
                                    'crypto_usdt' => __('admin.site_settings.crypto_usdt'),
                                    'crypto_btc' => __('admin.site_settings.crypto_btc'),
                                    'mtn_cash' => __('admin.site_settings.mtn_cash'),
                                    'syriatel_cash' => __('admin.site_settings.syriatel_cash'),
                                ];
                                $selectedMethods = $settings['payment_methods'] ?? ['bank_transfer'];
                            @endphp
                            
                            @foreach($availableMethods as $method => $label)
                                <div class="form-check payment-method-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="payment_{{ $method }}" 
                                           name="payment_methods[]" 
                                           value="{{ $method }}"
                                           {{ in_array($method, $selectedMethods) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="payment_{{ $method }}">
                                        @if($method == 'bank_transfer')
                                            <i class="bi bi-bank text-primary me-2"></i>
                                        @elseif($method == 'crypto_usdt')
                                            <i class="bi bi-currency-dollar text-success me-2"></i>
                                        @elseif($method == 'crypto_btc')
                                            <i class="bi bi-currency-bitcoin text-warning me-2"></i>
                                        @elseif($method == 'mtn_cash')
                                            <img src="{{ asset('images/payment-logos/mtn-cash.svg') }}" 
                                                 alt="MTN Cash" 
                                                 class="payment-logo me-2" 
                                                 style="height: 20px; width: auto; vertical-align: middle;"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                                            <i class="bi bi-phone text-info me-2" style="display: none;"></i>
                                        @elseif($method == 'syriatel_cash')
                                            <img src="{{ asset('images/payment-logos/syriatel-cash.svg') }}" 
                                                 alt="Syriatel Cash" 
                                                 class="payment-logo me-2" 
                                                 style="height: 20px; width: auto; vertical-align: middle;"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                                            <i class="bi bi-phone text-danger me-2" style="display: none;"></i>
                                        @endif
                                        {{ $label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="currency" class="form-label-enhanced">{{ __('admin.site_settings.currency') }}</label>
                        <select class="form-select form-control-enhanced @error('currency') is-invalid @enderror" 
                                id="currency" 
                                name="currency">
                            <option value="USD" {{ ($settings['currency'] ?? 'USD') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="EUR" {{ ($settings['currency'] ?? 'USD') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                            <option value="GBP" {{ ($settings['currency'] ?? 'USD') == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                            <option value="SAR" {{ ($settings['currency'] ?? 'USD') == 'SAR' ? 'selected' : '' }}>SAR (ر.س)</option>
                            <option value="AED" {{ ($settings['currency'] ?? 'USD') == 'AED' ? 'selected' : '' }}>AED (د.إ)</option>
                        </select>
                        @error('currency')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="payment-details mt-4">
                    <h6 class="mb-3">{{ __('admin.site_settings.payment_details') }}</h6>
                    
                    <!-- Bank Transfer Details -->
                    <div class="payment-detail-section mb-4" id="bank-transfer-details">
                        <h6 class="text-primary">
                            <i class="bi bi-bank me-2"></i>
                            {{ __('admin.site_settings.bank_transfer_details') }}
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bank_name" class="form-label-enhanced">{{ __('admin.site_settings.bank_name') }}</label>
                                <input type="text" 
                                       class="form-control form-control-enhanced" 
                                       id="bank_name" 
                                       name="bank_name" 
                                       value="{{ old('bank_name', $settings['bank_name'] ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="account_name" class="form-label-enhanced">{{ __('admin.site_settings.account_name') }}</label>
                                <input type="text" 
                                       class="form-control form-control-enhanced" 
                                       id="account_name" 
                                       name="account_name" 
                                       value="{{ old('account_name', $settings['account_name'] ?? '') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="account_number" class="form-label-enhanced">{{ __('admin.site_settings.account_number') }}</label>
                                <input type="text" 
                                       class="form-control form-control-enhanced" 
                                       id="account_number" 
                                       name="account_number" 
                                       value="{{ old('account_number', $settings['account_number'] ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="iban" class="form-label-enhanced">{{ __('admin.site_settings.iban') }}</label>
                                <input type="text" 
                                       class="form-control form-control-enhanced" 
                                       id="iban" 
                                       name="iban" 
                                       value="{{ old('iban', $settings['iban'] ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Crypto USDT Details -->
                    <div class="payment-detail-section mb-4" id="crypto-usdt-details">
                        <h6 class="text-success">
                            <i class="bi bi-currency-dollar me-2"></i>
                            {{ __('admin.site_settings.crypto_usdt_details') }}
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usdt_wallet" class="form-label-enhanced">{{ __('admin.site_settings.usdt_wallet') }}</label>
                                <input type="text" 
                                       class="form-control form-control-enhanced" 
                                       id="usdt_wallet" 
                                       name="usdt_wallet" 
                                       value="{{ old('usdt_wallet', $settings['usdt_wallet'] ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="usdt_network" class="form-label-enhanced">{{ __('admin.site_settings.usdt_network') }}</label>
                                <select class="form-select form-control-enhanced" id="usdt_network" name="usdt_network">
                                    <option value="TRC20" {{ ($settings['usdt_network'] ?? 'TRC20') == 'TRC20' ? 'selected' : '' }}>TRC20 (Tron)</option>
                                    <option value="ERC20" {{ ($settings['usdt_network'] ?? 'TRC20') == 'ERC20' ? 'selected' : '' }}>ERC20 (Ethereum)</option>
                                    <option value="BEP20" {{ ($settings['usdt_network'] ?? 'TRC20') == 'BEP20' ? 'selected' : '' }}>BEP20 (BSC)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Crypto BTC Details -->
                    <div class="payment-detail-section mb-4" id="crypto-btc-details">
                        <h6 class="text-warning">
                            <i class="bi bi-currency-bitcoin me-2"></i>
                            {{ __('admin.site_settings.crypto_btc_details') }}
                        </h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="btc_wallet" class="form-label-enhanced">{{ __('admin.site_settings.btc_wallet') }}</label>
                                <input type="text" 
                                       class="form-control form-control-enhanced" 
                                       id="btc_wallet" 
                                       name="btc_wallet" 
                                       value="{{ old('btc_wallet', $settings['btc_wallet'] ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <!-- MTN Cash Mobile Details -->
                    <div class="payment-detail-section mb-4" id="mtn-cash-details">
                        <h6 class="text-info">
                            <img src="{{ asset('images/payment-logos/mtn-cash.svg') }}" 
                                 alt="MTN Cash" 
                                 class="payment-logo me-2" 
                                 style="height: 24px; width: auto; vertical-align: middle;"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                            <i class="bi bi-phone me-2" style="display: none;"></i>
                            {{ __('admin.site_settings.mtn_cash_details') }}
                        </h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="mtn_cash_phone" class="form-label-enhanced">{{ __('admin.site_settings.mtn_cash_phone') }}</label>
                                <input type="text" 
                                       class="form-control form-control-enhanced" 
                                       id="mtn_cash_phone" 
                                       name="mtn_cash_phone" 
                                       placeholder="{{ __('admin.site_settings.phone_placeholder') }}"
                                       value="{{ old('mtn_cash_phone', $settings['mtn_cash_phone'] ?? '') }}">
                                <small class="form-text text-muted">{{ __('admin.site_settings.mtn_cash_help') }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Syriatel Cash Details -->
                    <div class="payment-detail-section mb-4" id="syriatel-cash-details">
                        <h6 class="text-danger">
                            <img src="{{ asset('images/payment-logos/syriatel-cash.svg') }}" 
                                 alt="Syriatel Cash" 
                                 class="payment-logo me-2" 
                                 style="height: 24px; width: auto; vertical-align: middle;"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                            <i class="bi bi-phone me-2" style="display: none;"></i>
                            {{ __('admin.site_settings.syriatel_cash_details') }}
                        </h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="syriatel_cash_phone" class="form-label-enhanced">{{ __('admin.site_settings.syriatel_cash_phone') }}</label>
                                <input type="text" 
                                       class="form-control form-control-enhanced" 
                                       id="syriatel_cash_phone" 
                                       name="syriatel_cash_phone" 
                                       placeholder="{{ __('admin.site_settings.phone_placeholder') }}"
                                       value="{{ old('syriatel_cash_phone', $settings['syriatel_cash_phone'] ?? '') }}">
                                <small class="form-text text-muted">{{ __('admin.site_settings.syriatel_cash_help') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex gap-3 justify-content-end pt-4 mt-4 border-top">
                <button type="submit" class="btn btn-voltronix">
                    <i class="bi bi-check-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.site_settings.save_settings') }}
                </button>
            </div>
        </div>
    </form>

    <!-- Hero Management Section -->
    <div class="settings-card position-relative mt-4">
        <div class="settings-section">
            <h5 class="section-title">
                <i class="bi bi-star"></i>
                {{ __('admin.homepage.hero_section') }}
            </h5>
            <p class="text-muted mb-4">{{ __('admin.homepage.hero_description') }}</p>
            
            <!-- Add New Hero Button -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="mb-0">{{ __('admin.homepage.manage_hero_sections') }}</h6>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#heroModal">
                    <i class="bi bi-plus-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('admin.homepage.add_hero') }}
                </button>
            </div>

            <!-- Hero Sections List -->
            @if($heroSections->count() > 0)
                <div class="hero-sections-list">
                    @foreach($heroSections as $hero)
                        <div class="hero-item border rounded p-3 mb-3" data-hero-id="{{ $hero->id }}">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    @if($hero->hasImage())
                                        <img src="{{ $hero->image_url }}" alt="Hero Image" class="img-fluid rounded" style="max-height: 60px;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 60px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-1">{{ $hero->getTranslation('title') ?: __('admin.homepage.hero_slide') }}</h6>
                                    <p class="text-muted mb-0 small">{{ $hero->getTranslation('subtitle') }}</p>
                                </div>
                                <div class="col-md-2 text-center">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               {{ $hero->is_active ? 'checked' : '' }}
                                               onchange="toggleHeroStatus({{ $hero->id }})">
                                        <label class="form-check-label small">
                                            {{ $hero->is_active ? __('admin.active') : __('admin.inactive') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" 
                                            onclick="editHero({{ $hero->id }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteHero({{ $hero->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-star fs-1 text-muted"></i>
                    <h6 class="text-muted mt-2">{{ __('admin.homepage.no_hero_sections') }}</h6>
                    <p class="text-muted">{{ __('admin.homepage.create_first_hero') }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Hero Modal -->
    <div class="modal fade" id="heroModal" tabindex="-1" aria-labelledby="heroModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="heroModalLabel">{{ __('admin.homepage.add_hero') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="heroForm" method="POST" action="{{ route('admin.settings.hero.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="heroId" name="hero_id">
                    <input type="hidden" id="heroMethod" name="_method" value="POST">
                    
                    <div class="modal-body">
                        <!-- Language Tabs -->
                        <ul class="nav nav-tabs mb-3" id="heroLanguageTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="hero-en-tab" data-bs-toggle="tab" data-bs-target="#hero-en" type="button" role="tab">
                                    {{ __('admin.site_settings.english') }}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="hero-ar-tab" data-bs-toggle="tab" data-bs-target="#hero-ar" type="button" role="tab">
                                    {{ __('admin.site_settings.arabic') }}
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="heroLanguageTabsContent">
                            <!-- English Tab -->
                            <div class="tab-pane fade show active" id="hero-en" role="tabpanel">
                                <div class="mb-3">
                                    <label for="title_en" class="form-label">{{ __('admin.homepage.title_en') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title_en" name="title_en" required>
                                </div>
                                <div class="mb-3">
                                    <label for="subtitle_en" class="form-label">{{ __('admin.homepage.subtitle_en') }}</label>
                                    <input type="text" class="form-control" id="subtitle_en" name="subtitle_en">
                                </div>
                                <div class="mb-3">
                                    <label for="description_en" class="form-label">{{ __('admin.homepage.description_en') }}</label>
                                    <textarea class="form-control" id="description_en" name="description_en" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="button_text_en" class="form-label">{{ __('admin.homepage.button_text_en') }}</label>
                                    <input type="text" class="form-control" id="button_text_en" name="button_text_en">
                                </div>
                            </div>

                            <!-- Arabic Tab -->
                            <div class="tab-pane fade" id="hero-ar" role="tabpanel">
                                <div class="mb-3">
                                    <label for="title_ar" class="form-label">{{ __('admin.homepage.title_ar') }}</label>
                                    <input type="text" class="form-control" id="title_ar" name="title_ar">
                                </div>
                                <div class="mb-3">
                                    <label for="subtitle_ar" class="form-label">{{ __('admin.homepage.subtitle_ar') }}</label>
                                    <input type="text" class="form-control" id="subtitle_ar" name="subtitle_ar">
                                </div>
                                <div class="mb-3">
                                    <label for="description_ar" class="form-label">{{ __('admin.homepage.description_ar') }}</label>
                                    <textarea class="form-control" id="description_ar" name="description_ar" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="button_text_ar" class="form-label">{{ __('admin.homepage.button_text_ar') }}</label>
                                    <input type="text" class="form-control" id="button_text_ar" name="button_text_ar">
                                </div>
                            </div>
                        </div>

                        <!-- Common Fields -->
                        <div class="mb-3">
                            <label for="link_url" class="form-label">{{ __('admin.homepage.link_url') }}</label>
                            <input type="url" class="form-control" id="link_url" name="link_url">
                        </div>

                        <div class="mb-3">
                            <label for="hero_image" class="form-label">{{ __('admin.homepage.image') }}</label>
                            <input type="file" class="form-control" id="hero_image" name="image" accept="image/*">
                            <div class="form-text">{{ __('admin.homepage.image_requirements') }}</div>
                            <div id="heroImagePreview" class="mt-2" style="display: none;">
                                <img id="heroImagePreviewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">{{ __('admin.homepage.sort_order') }}</label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order" value="0" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch mt-4">
                                        <input type="hidden" name="is_active" value="0">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                        <label class="form-check-label" for="is_active">
                                            {{ __('admin.homepage.is_active') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            <span id="heroSubmitText">{{ __('admin.save') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Image preview functionality
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

document.getElementById('logo').addEventListener('change', function() {
    previewImage(this, 'logoPreview');
});

document.getElementById('favicon').addEventListener('change', function() {
    previewImage(this, 'faviconPreview');
});

// Hero image preview
document.getElementById('hero_image').addEventListener('change', function() {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('heroImagePreviewImg').src = e.target.result;
            document.getElementById('heroImagePreview').style.display = 'block';
        };
        reader.readAsDataURL(this.files[0]);
    }
});

// Hero management functions
function toggleHeroStatus(heroId) {
    fetch(`/admin/settings/hero/${heroId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the label
            const heroItem = document.querySelector(`[data-hero-id="${heroId}"]`);
            const label = heroItem.querySelector('.form-check-label');
            label.textContent = data.is_active ? '{{ __("admin.active") }}' : '{{ __("admin.inactive") }}';
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: '{{ __("admin.success") }}',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
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
            text: '{{ __("admin.homepage.toggle_failed") }}'
        });
    });
}

function editHero(heroId) {
    // Fetch hero data and populate modal
    fetch(`/admin/settings/hero/${heroId}/edit`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const hero = data.hero;
            
            // Update modal title and form
            document.getElementById('heroModalLabel').textContent = '{{ __("admin.homepage.edit_hero") }}';
            document.getElementById('heroSubmitText').textContent = '{{ __("admin.update") }}';
            document.getElementById('heroId').value = heroId;
            document.getElementById('heroMethod').value = 'PUT';
            document.getElementById('heroForm').action = `/admin/settings/hero/${heroId}`;
            
            // Populate form fields
            document.getElementById('title_en').value = hero.content.title?.en || '';
            document.getElementById('title_ar').value = hero.content.title?.ar || '';
            document.getElementById('subtitle_en').value = hero.content.subtitle?.en || '';
            document.getElementById('subtitle_ar').value = hero.content.subtitle?.ar || '';
            document.getElementById('description_en').value = hero.content.description?.en || '';
            document.getElementById('description_ar').value = hero.content.description?.ar || '';
            document.getElementById('button_text_en').value = hero.content.button_text?.en || '';
            document.getElementById('button_text_ar').value = hero.content.button_text?.ar || '';
            document.getElementById('link_url').value = hero.link_url || '';
            document.getElementById('sort_order').value = hero.sort_order || 0;
            document.getElementById('is_active').checked = hero.is_active;
            
            // Show current image if exists
            if (hero.image_url) {
                document.getElementById('heroImagePreviewImg').src = hero.image_url;
                document.getElementById('heroImagePreview').style.display = 'block';
            }
            
            // Show modal
            new bootstrap.Modal(document.getElementById('heroModal')).show();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: '{{ __("admin.error") }}',
            text: '{{ __("admin.homepage.load_failed") }}'
        });
    });
}

function deleteHero(heroId) {
    Swal.fire({
        title: '{{ __("admin.homepage.delete_section") }}',
        text: '{{ __("admin.homepage.delete_confirmation") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("admin.yes_delete") }}',
        cancelButtonText: '{{ __("admin.cancel") }}',
        reverseButtons: {{ app()->getLocale() == 'ar' ? 'true' : 'false' }}
    }).then((result) => {
        if (result.isConfirmed) {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/settings/hero/${heroId}`;
            
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

// Reset modal when closed
document.getElementById('heroModal').addEventListener('hidden.bs.modal', function () {
    // Reset form
    document.getElementById('heroForm').reset();
    document.getElementById('heroModalLabel').textContent = '{{ __("admin.homepage.add_hero") }}';
    document.getElementById('heroSubmitText').textContent = '{{ __("admin.save") }}';
    document.getElementById('heroId').value = '';
    document.getElementById('heroMethod').value = 'POST';
    document.getElementById('heroForm').action = '{{ route("admin.settings.hero.store") }}';
    document.getElementById('heroImagePreview').style.display = 'none';
    document.getElementById('is_active').checked = true;
});
</script>
@endpush
