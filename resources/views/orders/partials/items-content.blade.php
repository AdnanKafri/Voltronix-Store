<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-header">
            <tr>
                <th>{{ __('orders.product') }}</th>
                <th class="text-center">
                    <i class="bi bi-hash me-1"></i>
                    {{ __('orders.quantity') }}
                </th>
                <th class="text-end">
                    <i class="bi bi-currency-dollar me-1"></i>
                    {{ __('orders.price') }}
                </th>
                <th class="text-end">
                    <i class="bi bi-calculator me-1"></i>
                    {{ __('orders.subtotal') }}
                </th>
                @if($order->status === 'approved')
                <th class="text-center">
                    <i class="bi bi-download me-1"></i>
                    {{ __('app.delivery.delivery_title') }}
                </th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
            <tr class="item-row {{ $index % 2 == 0 ? 'row-even' : 'row-odd' }}">
                <td>
                    <div class="product-info">
                        @if($item->product && $item->product->thumbnail)
                        <div class="product-thumbnail">
                            <img src="{{ asset('storage/' . $item->product->thumbnail) }}" 
                                 alt="{{ $item->getTranslation() }}"
                                 class="img-fluid">
                        </div>
                        @else
                        <div class="product-thumbnail placeholder">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        @endif
                        <div class="product-details">
                            <div class="product-name">{{ $item->getTranslation() }}</div>
                            @if($item->product && $item->product->category)
                            <div class="product-category">
                                <i class="bi bi-tag-fill me-1"></i>
                                {{ $item->product->category->getTranslation('name') ?? __('orders.uncategorized') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="text-center">
                    <span class="quantity-badge">
                        <i class="bi bi-box me-1"></i>
                        {{ $item->quantity }}
                    </span>
                </td>
                <td class="text-end">
                    <span class="price-text">{{ $item->formatted_price }}</span>
                </td>
                <td class="text-end">
                    <span class="subtotal-text">{{ $item->formatted_subtotal }}</span>
                </td>
                @if($order->status === 'approved')
                <td class="text-center">
                    @if($item->delivery)
                        @php $delivery = $item->delivery; @endphp
                        <div class="delivery-actions">
                            @if($delivery->type === 'file')
                                @if(!$delivery->isExpired() && !$delivery->isRevoked() && !$delivery->isDownloadLimitReached())
                                    <a href="{{ route('delivery.download', $delivery->token) }}" 
                                       class="btn btn-sm btn-success delivery-btn"
                                       target="_blank">
                                        <i class="bi bi-download {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                        {{ __('app.delivery.download_file') }}
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary delivery-btn" disabled>
                                        <i class="bi bi-x-circle {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                        @if($delivery->isExpired())
                                            {{ __('app.delivery.access_expired') }}
                                        @elseif($delivery->isRevoked())
                                            {{ __('app.delivery.access_revoked') }}
                                        @else
                                            {{ __('app.delivery.download_limit_reached') }}
                                        @endif
                                    </button>
                                    <a href="{{ route('delivery.request', $delivery->token) }}" 
                                       class="btn btn-sm btn-outline-warning delivery-btn mt-1">
                                        <i class="bi bi-arrow-clockwise {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                        {{ __('app.delivery.request_new_access') }}
                                    </a>
                                @endif
                            @else
                                @if(!$delivery->isExpired() && !$delivery->isRevoked() && !$delivery->isViewLimitReached())
                                    <a href="{{ route('delivery.credentials', $delivery->token) }}" 
                                       class="btn btn-sm btn-primary delivery-btn">
                                        <i class="bi bi-key {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                        {{ __('app.delivery.view_credentials') }}
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary delivery-btn" disabled>
                                        <i class="bi bi-x-circle {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                        @if($delivery->isExpired())
                                            {{ __('app.delivery.access_expired') }}
                                        @elseif($delivery->isRevoked())
                                            {{ __('app.delivery.access_revoked') }}
                                        @else
                                            {{ __('app.delivery.view_limit_reached') }}
                                        @endif
                                    </button>
                                    <a href="{{ route('delivery.request', $delivery->token) }}" 
                                       class="btn btn-sm btn-outline-warning delivery-btn mt-1">
                                        <i class="bi bi-arrow-clockwise {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                        {{ __('app.delivery.request_new_access') }}
                                    </a>
                                @endif
                            @endif
                            
                            <!-- Delivery Status Info -->
                            <div class="delivery-info mt-2">
                                <small class="text-muted d-block">
                                    @if($delivery->expires_at)
                                        <i class="bi bi-clock {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                        {{ __('app.delivery.expires') }}: {{ $delivery->expires_at->format('M d, Y') }}
                                    @else
                                        <i class="bi bi-infinity {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                        {{ __('app.delivery.never_expires') }}
                                    @endif
                                </small>
                                @if($delivery->type === 'file' && $delivery->max_downloads)
                                    <small class="text-muted d-block">
                                        <i class="bi bi-download {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                        {{ $delivery->downloads_count }}/{{ $delivery->max_downloads }} {{ __('app.delivery.downloads') }}
                                    </small>
                                @elseif($delivery->type !== 'file' && $delivery->max_views)
                                    <small class="text-muted d-block">
                                        <i class="bi bi-eye {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                        {{ $delivery->views_count }}/{{ $delivery->max_views }} {{ __('app.delivery.views_used') }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    @else
                        <span class="text-muted">
                            <i class="bi bi-clock {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                            {{ __('app.delivery.pending_setup') }}
                        </span>
                    @endif
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
        <tfoot class="table-footer">
            <tr>
                <td colspan="{{ $order->status === 'approved' ? '4' : '3' }}" class="text-end total-label">
                    <strong>
                        <i class="bi bi-receipt me-2"></i>
                        {{ __('app.orders.items_subtotal') }}:
                    </strong>
                </td>
                <td class="text-end">
                    <strong class="total-amount">{{ currency_format($order->items->sum('subtotal')) }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<style>
.table-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.table-header th {
    border: none;
    padding: 1rem;
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.item-row {
    border-bottom: 1px solid #f1f3f4;
    transition: background-color 0.3s ease;
}

.item-row.row-even {
    background-color: #ffffff;
}

.item-row.row-odd {
    background-color: #f8f9fa;
}

.item-row:hover {
    background-color: #e3f2fd !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 127, 255, 0.1);
}

.item-row td {
    padding: 1.5rem 1rem;
    vertical-align: middle;
}

.product-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.product-thumbnail {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.product-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-thumbnail.placeholder {
    color: #6c757d;
    font-size: 1.5rem;
}

.product-details {
    flex: 1;
}

.product-name {
    font-weight: 600;
    color: #212529;
    margin-bottom: 0.25rem;
    line-height: 1.4;
}

.product-category {
    font-size: 0.85rem;
    color: #6c757d;
    font-style: italic;
}

.quantity-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #007fff, #23efff);
    color: white;
    border-radius: 50%;
    font-weight: 600;
    font-size: 1rem;
}

.price-text, .subtotal-text {
    font-weight: 600;
    color: #212529;
    font-size: 1.1rem;
}

.table-footer {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.table-footer td {
    padding: 1.5rem 1rem;
    border: none;
}

.total-label {
    font-size: 1.1rem;
    color: #495057;
}

.total-amount {
    font-size: 1.3rem;
    color: #007fff;
    font-family: 'Orbitron', monospace;
}

@media (max-width: 768px) {
    .product-info {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .product-thumbnail {
        width: 50px;
        height: 50px;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .item-row td {
        padding: 1rem 0.5rem;
    }
}

/* Delivery Actions Styling */
.delivery-actions {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.delivery-btn {
    min-width: 120px;
    font-size: 0.85rem;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.delivery-btn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 127, 255, 0.3);
}

.delivery-info {
    font-size: 0.75rem;
    line-height: 1.3;
}

.delivery-info small {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.25rem;
}

@media (max-width: 768px) {
    .delivery-btn {
        min-width: 100px;
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    
    .delivery-info {
        font-size: 0.7rem;
    }
}
</style>
