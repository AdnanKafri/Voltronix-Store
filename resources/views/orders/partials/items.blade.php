<!-- Order Items -->
<div class="info-card">
    <div class="card-header">
        <h3 class="section-title">
            <i class="bi bi-bag-check"></i>
            {{ __('orders.order_items') }}
        </h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-header">
                    <tr>
                        <th>{{ __('orders.product') }}</th>
                        <th class="text-center">{{ __('orders.quantity') }}</th>
                        <th class="text-end">{{ __('orders.price') }}</th>
                        <th class="text-end">{{ __('orders.subtotal') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr class="item-row">
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
                                    <i class="bi bi-box"></i>
                                </div>
                                @endif
                                <div class="product-details">
                                    <div class="product-name">{{ $item->getTranslation() }}</div>
                                    @if($item->product)
                                    <div class="product-category">
                                        {{ $item->product->category->getTranslation('name') ?? __('orders.uncategorized') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="quantity-badge">{{ $item->quantity }}</span>
                        </td>
                        <td class="text-end">
                            <span class="price-text">{{ $item->formatted_price }}</span>
                        </td>
                        <td class="text-end">
                            <span class="subtotal-text">{{ $item->formatted_subtotal }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-footer">
                    <tr>
                        <td colspan="3" class="text-end total-label">
                            <strong>{{ __('orders.total') }}:</strong>
                        </td>
                        <td class="text-end">
                            <strong class="total-amount">{{ $order->formatted_total }}</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@push('styles')
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

.item-row:hover {
    background-color: #f8f9fa;
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
</style>
@endpush
