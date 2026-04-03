@extends('admin.layouts.app')

@section('title', __('admin.orders.order_details'))

@push('styles')
<style>
.order-details-container {
    max-width: 95%;
    margin: 0 auto;
    padding: 2rem 0;
}

.order-header {
    background: linear-gradient(135deg, #007fff, #23efff);
    color: white;
    padding: 2rem;
    border-radius: 20px;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0, 127, 255, 0.2);
}

.order-header h1 {
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
    font-size: 1.1rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.receipt-preview {
    border: 3px solid rgba(0, 127, 255, 0.2);
    border-radius: 15px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.receipt-preview:hover {
    border-color: #007fff;
    transform: scale(1.02);
    box-shadow: 0 10px 25px rgba(0, 127, 255, 0.2);
}

.action-button {
    background: linear-gradient(135deg, #007fff, #23efff);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.action-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 127, 255, 0.3);
    color: white;
}

.form-control-voltronix {
    border: 2px solid rgba(0, 127, 255, 0.2);
    border-radius: 10px;
    padding: 0.75rem;
    transition: all 0.3s ease;
}

.form-control-voltronix:focus {
    border-color: #007fff;
    box-shadow: 0 0 0 0.2rem rgba(0, 127, 255, 0.25);
}

.table-voltronix {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.table-voltronix th {
    background: linear-gradient(135deg, #007fff, #23efff);
    color: white;
    font-weight: 600;
    padding: 1rem;
    border: none;
}

.table-voltronix td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid rgba(0, 127, 255, 0.1);
}

.product-thumbnail {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 10px;
    border: 2px solid rgba(0, 127, 255, 0.2);
}

/* Modern Status Button Styles */
.status-buttons-container {
    padding: 0.5rem 0;
}

.current-status-display {
    text-align: center;
    padding: 1rem;
    background: rgba(0, 127, 255, 0.05);
    border-radius: 10px;
}

.status-actions {
    margin-top: 1rem;
}

.status-buttons-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
}

.status-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 1rem;
    border: 2px solid rgba(0, 127, 255, 0.2);
    border-radius: 12px;
    background: white;
    color: #1a1a1a;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    cursor: pointer;
    min-height: 85px;
}

.status-action-btn:hover:not(.disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 127, 255, 0.2);
}

.status-action-btn i {
    font-size: 1.5rem;
}

.status-action-btn.active {
    background: linear-gradient(135deg, #e9ecef, #f8f9fa);
    border-color: #6c757d;
    cursor: not-allowed;
    opacity: 0.7;
}

.status-action-btn.disabled {
    cursor: not-allowed;
    opacity: 0.6;
}

/* Status-specific colors */
.status-action-btn.status-pending:not(.active):hover {
    border-color: #ffc107;
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
}

.status-action-btn.status-approved:not(.active):hover {
    border-color: #28a745;
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.status-action-btn.status-rejected:not(.active):hover {
    border-color: #dc3545;
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.status-action-btn.status-cancelled:not(.active):hover {
    border-color: #6c757d;
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
}

/* Loading state for status buttons */
.status-action-btn.loading {
    pointer-events: none;
    opacity: 0.6;
}

.status-action-btn.loading::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    border: 2px solid rgba(0, 127, 255, 0.3);
    border-top-color: #007fff;
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

@media (max-width: 576px) {
    .status-buttons-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="order-details-container">
        <!-- Header -->
        <div class="order-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>{{ __('admin.orders.order_details') }}</h1>
                    <p class="mb-0 opacity-75">{{ __('admin.orders.order_number') }}: {{ $order->order_number }}</p>
                </div>
                <div>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.orders.back_to_orders') }}
                    </a>
                </div>
            </div>
        </div>

    <div class="row">
        <!-- Order Information -->
        <div class="col-lg-8">
            <!-- Order Info Card -->
            <div class="card-voltronix">
                <div class="card-header-voltronix d-flex justify-content-between align-items-center">
                    <h6>{{ __('admin.orders.order_information') }}</h6>
                    <span class="status-badge bg-{{ $order->status == 'approved' ? 'success' : ($order->status == 'rejected' ? 'danger' : 'warning') }}">
                        {{ __('admin.orders.' . $order->status) }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>{{ __('admin.orders.order_id') }}:</strong> {{ $order->id }}</p>
                            <p><strong>{{ __('admin.orders.order_number') }}:</strong> {{ $order->order_number }}</p>
                            <p><strong>{{ __('admin.orders.order_date') }}:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>{{ __('admin.orders.current_status') }}:</strong> 
                                <span class="badge badge-{{ $order->status == 'approved' ? 'success' : ($order->status == 'rejected' ? 'danger' : 'warning') }}">
                                    {{ __('admin.orders.' . $order->status) }}
                                </span>
                            </p>
                            <p><strong>{{ __('admin.orders.total_amount') }}:</strong> {{ $order->formatted_total }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card-voltronix">
                <div class="card-header-voltronix">
                    <h6>{{ __('admin.orders.customer_information') }}</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>{{ __('admin.orders.customer_name') }}:</strong> {{ $order->customer_name }}</p>
                            <p><strong>{{ __('admin.orders.customer_email') }}:</strong> {{ $order->customer_email }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>{{ __('admin.orders.customer_phone') }}:</strong> {{ $order->customer_phone ?? 'N/A' }}</p>
                            @if($order->user)
                                <p><strong>{{ __('admin.orders.registered_user') }}:</strong> {{ __('admin.common.yes') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card-voltronix">
                <div class="card-header-voltronix">
                    <h6>{{ __('admin.orders.order_items') }}</h6>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table-voltronix table">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.orders.product_name') }}</th>
                                    <th>{{ __('admin.orders.quantity') }}</th>
                                    <th>{{ __('admin.orders.unit_price') }}</th>
                                    <th>{{ __('admin.orders.subtotal') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->thumbnail)
                                                <img src="{{ Storage::url($item->product->thumbnail) }}" 
                                                     alt="{{ $item->getTranslation() ?: 'Product' }}" 
                                                     class="product-thumbnail me-3">
                                            @endif
                                            <div>
                                                <strong>{{ $item->getTranslation() ?: 'N/A' }}</strong>
                                                @if($item->product)
                                                    <br><small class="text-muted">{{ $item->product->getTranslation('name', app()->getLocale()) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->product_price, 2) }}</td>
                                    <td>${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">{{ __('admin.orders.total_amount') }}:</th>
                                    <th>${{ number_format($order->total_amount, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Delivery Management -->
            @if($order->status === 'approved')
            <div class="card-voltronix">
                <div class="card-header-voltronix d-flex justify-content-between align-items-center">
                    <h6>{{ __('admin.orders.delivery_management') }}</h6>
                    <a href="{{ route('admin.orders.deliveries.show', $order) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-gear {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                        {{ __('admin.orders.manage_deliveries') }}
                    </a>
                </div>
                <div class="card-body p-4">
                    @if($order->deliveries->count() > 0)
                        <div class="delivery-summary">
                            @foreach($order->deliveries as $delivery)
                                <div class="delivery-item mb-3 p-3 border rounded">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1">{{ $delivery->title }}</h6>
                                            <small class="text-muted">{{ ucfirst($delivery->type) }}</small>
                                        </div>
                                        <div class="delivery-status">
                                            {!! $delivery->status_badge !!}
                                        </div>
                                    </div>
                                    
                                    <div class="row g-2 text-sm">
                                        @if($delivery->type === 'file')
                                            <div class="col-6">
                                                <small class="text-muted">{{ __('admin.orders.downloads') }}:</small>
                                                <span>{{ $delivery->downloads_count }}
                                                    @if($delivery->max_downloads)
                                                        / {{ $delivery->max_downloads }}
                                                    @endif
                                                </span>
                                            </div>
                                        @else
                                            <div class="col-6">
                                                <small class="text-muted">{{ __('admin.orders.views') }}:</small>
                                                <span>{{ $delivery->views_count }}
                                                    @if($delivery->max_views)
                                                        / {{ $delivery->max_views }}
                                                    @endif
                                                </span>
                                            </div>
                                        @endif
                                        
                                        <div class="col-6">
                                            <small class="text-muted">{{ __('admin.orders.expires') }}:</small>
                                            <span class="{{ $delivery->expires_at && $delivery->expires_at->isPast() ? 'text-danger' : 'text-success' }}">
                                                @if($delivery->expires_at)
                                                    {{ $delivery->expires_at->format('M d, Y') }}
                                                @else
                                                    {{ __('admin.orders.never') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    
                                    @if($delivery->last_accessed_at)
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                {{ __('admin.orders.last_access') }}: {{ $delivery->last_accessed_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox display-6 text-muted mb-3"></i>
                            <p class="text-muted mb-3">{{ __('admin.orders.no_deliveries') }}</p>
                            <a href="{{ route('admin.orders.deliveries.create', ['order' => $order, 'item_id' => $order->items->first()->id ?? null]) }}" 
                               class="btn btn-primary">
                                <i class="bi bi-plus {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                {{ __('admin.orders.create_delivery') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Management -->
            <div class="card-voltronix">
                <div class="card-header-voltronix">
                    <h6>{{ __('admin.orders.status_management') }}</h6>
                </div>
                <div class="card-body p-4">
                    <div class="status-buttons-container">
                        <div class="current-status-display mb-3">
                            <small class="text-muted d-block mb-2">{{ __('admin.orders.current_status') }}</small>
                            <span class="status-badge bg-{{ $order->status == 'approved' ? 'success' : ($order->status == 'rejected' ? 'danger' : ($order->status == 'cancelled' ? 'secondary' : 'warning')) }}" id="currentStatusBadge">
                                {{ __('admin.orders.' . $order->status) }}
                            </span>
                        </div>
                        
                        <div class="status-actions">
                            <small class="text-muted d-block mb-3">{{ __('admin.orders.change_to') }}</small>
                            <div class="status-buttons-grid">
                                <button type="button" 
                                        class="status-action-btn status-pending {{ $order->status == 'pending' ? 'active disabled' : '' }}" 
                                        onclick="updateStatusTo('pending')"
                                        {{ $order->status == 'pending' ? 'disabled' : '' }}>
                                    <i class="bi bi-clock-history"></i>
                                    <span>{{ __('admin.orders.pending') }}</span>
                                </button>
                                
                                <button type="button" 
                                        class="status-action-btn status-approved {{ $order->status == 'approved' ? 'active disabled' : '' }}" 
                                        onclick="updateStatusTo('approved')"
                                        {{ $order->status == 'approved' ? 'disabled' : '' }}>
                                    <i class="bi bi-check-circle"></i>
                                    <span>{{ __('admin.orders.approved') }}</span>
                                </button>
                                
                                <button type="button" 
                                        class="status-action-btn status-rejected {{ $order->status == 'rejected' ? 'active disabled' : '' }}" 
                                        onclick="updateStatusTo('rejected')"
                                        {{ $order->status == 'rejected' ? 'disabled' : '' }}>
                                    <i class="bi bi-x-circle"></i>
                                    <span>{{ __('admin.orders.rejected') }}</span>
                                </button>
                                
                                <button type="button" 
                                        class="status-action-btn status-cancelled {{ $order->status == 'cancelled' ? 'active disabled' : '' }}" 
                                        onclick="updateStatusTo('cancelled')"
                                        {{ $order->status == 'cancelled' ? 'disabled' : '' }}>
                                    <i class="bi bi-ban"></i>
                                    <span>{{ __('admin.orders.cancelled') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="card-voltronix">
                <div class="card-header-voltronix">
                    <h6><i class="fas fa-credit-card {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('admin.orders.payment_information') }}</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>{{ __('admin.orders.payment_method') }}:</strong> 
                                @if($order->payment_method)
                                    {{ __('app.checkout.payment_methods.' . $order->payment_method) }}
                                @else
                                    <span class="text-muted">{{ __('admin.orders.not_specified') }}</span>
                                @endif
                            </p>
                            <p><strong>{{ __('admin.orders.total_amount') }}:</strong> 
                                {{ currency_format($order->total_amount) }}
                            </p>
                            <p><strong>{{ __('admin.orders.currency') }}:</strong> 
                                {{ $order->currency_code }} 
                                @if($order->currency_rate != 1)
                                    <small class="text-muted">({{ __('admin.orders.rate') }}: {{ $order->currency_rate }})</small>
                                @endif
                            </p>
                            @if($order->discount_amount > 0)
                            <p><strong>{{ __('admin.orders.discount_applied') }}:</strong> 
                                -{{ currency_format($order->discount_amount) }}
                                @if($order->coupon_code)
                                    <small class="text-muted">({{ $order->coupon_code }})</small>
                                @endif
                            </p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($order->payment_details)
                                <h6 class="mb-3">{{ __('admin.orders.payment_details') }}</h6>
                                @php $details = is_array($order->payment_details) ? $order->payment_details : json_decode($order->payment_details, true) @endphp
                                @if(isset($details['bank_info']))
                                    <p><strong>{{ __('admin.orders.bank_name') }}:</strong> {{ $details['bank_info']['bank_name'] ?? 'N/A' }}</p>
                                    <p><strong>{{ __('admin.orders.account_number') }}:</strong> {{ $details['bank_info']['account_number'] ?? 'N/A' }}</p>
                                @elseif(isset($details['crypto_info']))
                                    <p><strong>{{ __('admin.orders.wallet_address') }}:</strong> 
                                        <code class="small">{{ $details['crypto_info']['wallet_address'] ?? 'N/A' }}</code>
                                    </p>
                                    <p><strong>{{ __('admin.orders.network') }}:</strong> {{ $details['crypto_info']['network'] ?? 'N/A' }}</p>
                                @endif
                                @if(isset($details['timestamp']))
                                    <p><strong>{{ __('admin.orders.payment_timestamp') }}:</strong> 
                                        {{ \Carbon\Carbon::parse($details['timestamp'])->format('Y-m-d H:i:s') }}
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>
                    
                    @if($order->payment_proof_path)
                    <hr class="my-4">
                    <div class="text-center">
                        <h6 class="mb-3">{{ __('admin.orders.payment_receipt') }}</h6>
                        
                        @php
                            $fileExtension = strtolower(pathinfo($order->payment_proof_path, PATHINFO_EXTENSION));
                            $isPdf = in_array($fileExtension, ['pdf']);
                        @endphp
                        
                        @if($isPdf)
                            <!-- PDF file - show download only -->
                            <div class="pdf-receipt-container" style="padding: 2rem; background: #f8f9fa; border-radius: 10px; border: 2px solid rgba(220, 53, 69, 0.2);">
                                <i class="bi bi-file-earmark-pdf" style="font-size: 4rem; color: #dc3545; margin-bottom: 1rem;"></i>
                                <p class="mb-2"><strong>{{ basename($order->payment_proof_path) }}</strong></p>
                                <p class="text-muted mb-3">{{ __('admin.orders.pdf_file_description') }}</p>
                                <a href="{{ route('admin.orders.receipt.download', $order) }}" 
                                   class="btn btn-primary btn-sm">
                                    <i class="bi bi-download {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                    {{ __('admin.orders.download_receipt') }}
                                </a>
                            </div>
                        @else
                            <!-- Image file - show preview and download -->
                            <img src="{{ route('admin.orders.receipt.view', $order) }}" 
                                 alt="Payment Receipt" 
                                 class="receipt-preview img-fluid mb-3" 
                                 style="max-height: 200px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); cursor: pointer;"
                                 onclick="showReceiptModal('{{ route('admin.orders.receipt.view', $order) }}', '{{ $order->order_number }}')"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div style="display: none; padding: 2rem; text-align: center; background: #f8f9fa; border-radius: 10px; border: 2px dashed #dee2e6;">
                                <i class="bi bi-file-earmark-image" style="font-size: 3rem; color: #6c757d; margin-bottom: 1rem;"></i>
                                <p style="color: #6c757d; margin: 0;">{{ __('admin.orders.file_not_found') }}</p>
                            </div>
                            <br>
                            <div class="receipt-actions" style="display: flex; gap: 0.5rem; justify-content: center;">
                                <button type="button" 
                                        class="btn btn-outline-primary btn-sm"
                                        onclick="showReceiptModal('{{ route('admin.orders.receipt.view', $order) }}', '{{ $order->order_number }}')">
                                    <i class="bi bi-eye {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                    {{ __('admin.orders.view_receipt') }}
                                </button>
                                <a href="{{ route('admin.orders.receipt.download', $order) }}" 
                                   class="btn btn-primary btn-sm">
                                    <i class="bi bi-download {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                    {{ __('admin.orders.download_receipt') }}
                                </a>
                            </div>
                        @endif
                    </div>
                    @else
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('admin.orders.no_payment_proof') }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Admin Notes -->
            <div class="card-voltronix">
                <div class="card-header-voltronix">
                    <h6>{{ __('admin.orders.admin_notes') }}</h6>
                </div>
                <div class="card-body p-4">
                    @if($order->admin_notes)
                        <div class="alert alert-info">
                            {{ $order->admin_notes }}
                        </div>
                    @endif
                    
                    <form id="addNoteForm">
                        @csrf
                        <div class="form-group mb-3">
                            <textarea class="form-control-voltronix form-control" 
                                      id="note_content" 
                                      name="note_content" 
                                      rows="3" 
                                      placeholder="{{ __('admin.orders.note_content') }}"></textarea>
                        </div>
                        <button type="button" class="action-button w-100" onclick="addNote()">
                            {{ __('admin.orders.add_note') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
let isUpdatingStatus = false;

function updateStatusTo(newStatus) {
    // Prevent multiple simultaneous requests
    if (isUpdatingStatus) {
        console.log('Status update already in progress');
        return;
    }
    
    const currentStatus = '{{ $order->status }}';
    
    // Don't update if it's the same status
    if (currentStatus === newStatus) {
        return;
    }
    
    // Get status labels for display
    const statusLabels = {
        'pending': '{{ __("admin.orders.pending") }}',
        'approved': '{{ __("admin.orders.approved") }}',
        'rejected': '{{ __("admin.orders.rejected") }}',
        'cancelled': '{{ __("admin.orders.cancelled") }}'
    };
    
    Swal.fire({
        title: '{{ __("admin.orders.confirm_status_change") }}',
        html: `{{ __("admin.orders.status_change_message") }}<br><br><strong>${statusLabels[currentStatus]}</strong> \u2192 <strong>${statusLabels[newStatus]}</strong>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#007fff',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("admin.common.yes") }}',
        cancelButtonText: '{{ __("admin.common.cancel") }}',
        reverseButtons: {{ app()->getLocale() == 'ar' ? 'true' : 'false' }}
    }).then((result) => {
        if (result.isConfirmed) {
            isUpdatingStatus = true;
            
            // Show loading state
            const buttons = document.querySelectorAll('.status-action-btn');
            buttons.forEach(btn => {
                btn.classList.add('loading');
                btn.disabled = true;
            });
            
            // Show loading toast
            const loadingToast = Swal.fire({
                title: '{{ __("admin.common.loading") }}',
                text: '{{ __("admin.orders.updating_status") }}',
                icon: 'info',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch(`{{ route('admin.orders.update-status', $order) }}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                loadingToast.close();
                
                if (data.success) {
                    Swal.fire({
                        title: '{{ __("admin.common.success") }}',
                        text: data.message || '{{ __("admin.orders.status_updated") }}',
                        icon: 'success',
                        confirmButtonColor: '#007fff',
                        timer: 2000,
                        timerProgressBar: true
                    }).then(() => {
                        // Reload the page after successful update
                        window.location.reload();
                    });
                } else {
                    isUpdatingStatus = false;
                    buttons.forEach(btn => {
                        btn.classList.remove('loading');
                        btn.disabled = false;
                    });
                    
                    Swal.fire({
                        title: '{{ __("admin.common.error") }}',
                        text: data.message || '{{ __("admin.orders.status_update_failed") }}',
                        icon: 'error',
                        confirmButtonColor: '#007fff'
                    });
                }
            })
            .catch(error => {
                console.error('Error updating status:', error);
                
                loadingToast.close();
                isUpdatingStatus = false;
                
                buttons.forEach(btn => {
                    btn.classList.remove('loading');
                    btn.disabled = false;
                });
                
                Swal.fire({
                    title: '{{ __("admin.common.error") }}',
                    text: '{{ __("admin.orders.status_update_failed") }}: ' + error.message,
                    icon: 'error',
                    confirmButtonColor: '#007fff'
                });
            });
        }
    });
}

function addNote() {
    const noteContent = document.getElementById('note_content').value;
    
    if (!noteContent.trim()) {
        Swal.fire({
            title: '{{ __("admin.common.error") }}',
            text: '{{ __("admin.orders.note_content") }} {{ __("admin.common.required") }}',
            icon: 'error',
            confirmButtonColor: '#007fff'
        });
        return;
    }
    
    fetch(`{{ route('admin.orders.add-note', $order) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ note_content: noteContent })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: '{{ __("admin.common.success") }}',
                text: '{{ __("admin.orders.note_added") }}',
                icon: 'success',
                confirmButtonColor: '#007fff'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                title: '{{ __("admin.common.error") }}',
                text: '{{ __("admin.orders.note_add_failed") }}',
                icon: 'error',
                confirmButtonColor: '#007fff'
            });
        }
    });
}

function showReceiptModal(imageUrl, orderNumber) {
    // Check if it's a PDF by URL or file extension
    const isPdf = imageUrl.toLowerCase().includes('.pdf') || imageUrl.includes('pdf');
    
    if (isPdf) {
        // For PDF files, open in new tab
        window.open(imageUrl, '_blank');
    } else {
        // For images, show in modal with zoom functionality
        Swal.fire({
            title: `{{ __('admin.orders.payment_receipt') }} - ${orderNumber}`,
            html: `
                <div style="text-align: center; position: relative;">
                    <div class="image-zoom-container" style="position: relative; display: inline-block; max-width: 100%; overflow: hidden; border-radius: 10px;">
                        <img id="adminZoomableImage" src="${imageUrl}" 
                             style="max-width: 100%; max-height: 600px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); transition: transform 0.3s ease; cursor: zoom-in;" 
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div style="display: none; padding: 2rem;">
                            <i class="bi bi-exclamation-triangle" style="font-size: 3rem; color: #dc3545;"></i>
                            <p style="margin-top: 1rem;">{{ __('admin.orders.file_not_found') }}</p>
                        </div>
                    </div>
                    <div class="zoom-controls" style="margin-top: 1rem; display: flex; justify-content: center; gap: 0.5rem;">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="adminZoomImage('in')" title="{{ __('admin.orders.zoom_in') }}">
                            <i class="bi bi-zoom-in"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="adminZoomImage('out')" title="{{ __('admin.orders.zoom_out') }}">
                            <i class="bi bi-zoom-out"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="adminResetZoom()" title="{{ __('admin.orders.reset_zoom') }}">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                </div>
            `,
            showCloseButton: true,
            showConfirmButton: false,
            width: '1000px',
            customClass: {
                popup: 'payment-proof-modal'
            },
            didOpen: () => {
                // Initialize zoom functionality
                window.adminCurrentZoom = 1;
                const image = document.getElementById('adminZoomableImage');
                
                // Add click to zoom functionality
                image.addEventListener('click', function() {
                    if (window.adminCurrentZoom === 1) {
                        adminZoomImage('in');
                    } else {
                        adminResetZoom();
                    }
                });
            }
        });
    }
}

// Admin zoom functionality
let adminCurrentZoom = 1;

function adminZoomImage(direction) {
    const image = document.getElementById('adminZoomableImage');
    if (!image) return;
    
    if (direction === 'in') {
        adminCurrentZoom = Math.min(adminCurrentZoom * 1.5, 4); // Max 4x zoom
        image.style.cursor = 'zoom-out';
    } else if (direction === 'out') {
        adminCurrentZoom = Math.max(adminCurrentZoom / 1.5, 0.5); // Min 0.5x zoom
        if (adminCurrentZoom === 1) {
            image.style.cursor = 'zoom-in';
        }
    }
    
    image.style.transform = `scale(${adminCurrentZoom})`;
    
    // Update container overflow for zoomed images
    const container = image.parentElement;
    if (adminCurrentZoom > 1) {
        container.style.overflow = 'auto';
        container.style.maxHeight = '600px';
    } else {
        container.style.overflow = 'hidden';
        container.style.maxHeight = 'none';
    }
}

function adminResetZoom() {
    const image = document.getElementById('adminZoomableImage');
    if (!image) return;
    
    adminCurrentZoom = 1;
    image.style.transform = 'scale(1)';
    image.style.cursor = 'zoom-in';
    
    const container = image.parentElement;
    container.style.overflow = 'hidden';
    container.style.maxHeight = 'none';
}
</script>
@endpush
