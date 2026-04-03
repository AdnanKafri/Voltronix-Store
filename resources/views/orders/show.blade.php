@extends('layouts.app')

@section('title', __('orders.order_details') . ' - ' . $order->order_number)

@push('styles')
<style>
.order-invoice-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1rem;
    background: #f8f9fa;
    min-height: calc(100vh - 80px);
    margin-top: 2rem;
}

.invoice-header {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
    margin-top: 0;
}

.invoice-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    align-items: start;
}

.invoice-main {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.invoice-sidebar {
    position: sticky;
    top: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.invoice-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 127, 255, 0.1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.invoice-card:hover {
    box-shadow: 0 8px 30px rgba(0, 127, 255, 0.15);
    transform: translateY(-2px);
}

.card-header-invoice {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem 2rem;
    border-bottom: 2px solid rgba(0, 127, 255, 0.1);
}

.section-title-invoice {
    font-family: 'Orbitron', monospace;
    font-size: 1.1rem;
    font-weight: 600;
    color: #007fff;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.card-body-invoice {
    padding: 2rem;
}

/* Mobile Responsive */
@media (max-width: 1199px) {
    .invoice-content {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .invoice-sidebar {
        position: static;
        order: -1;
    }
}

@media (max-width: 768px) {
    .order-invoice-container {
        padding: 1rem 0.5rem;
    }
    
    .invoice-content {
        gap: 1rem;
    }
    
    .invoice-main {
        gap: 1rem;
    }
    
    .card-header-invoice {
        padding: 1rem 1.5rem;
    }
    
    .card-body-invoice {
        padding: 1.5rem;
    }
}

/* Print Styles */
@media print {
    .invoice-sidebar .btn,
    .no-print {
        display: none !important;
    }
    
    .order-invoice-container {
        background: white;
        padding: 0;
    }
    
    .invoice-content {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}
</style>
@endpush

@section('content')
<div class="order-invoice-container">
    <!-- Invoice Header -->
    <div class="invoice-header">
        @include('orders.partials.header', ['order' => $order])
    </div>
    
    <!-- Invoice Content -->
    <div class="invoice-content">
        <!-- Main Content (Left Column) -->
        <div class="invoice-main">
            <!-- Customer Information -->
            <div class="invoice-card">
                <div class="card-header-invoice">
                    <h3 class="section-title-invoice">
                        <i class="bi bi-person-circle"></i>
                        {{ __('orders.customer_information') }}
                    </h3>
                </div>
                <div class="card-body-invoice">
                    @include('orders.partials.customer-content', ['order' => $order])
                </div>
            </div>
            
            <!-- Payment Information -->
            <div class="invoice-card">
                <div class="card-header-invoice">
                    <h3 class="section-title-invoice">
                        <i class="bi bi-credit-card"></i>
                        {{ __('orders.payment_information') }}
                    </h3>
                </div>
                <div class="card-body-invoice">
                    @include('orders.partials.payment-content', ['order' => $order])
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="invoice-card">
                <div class="card-header-invoice">
                    <h3 class="section-title-invoice">
                        <i class="bi bi-bag-check"></i>
                        {{ __('orders.order_items') }}
                    </h3>
                </div>
                <div class="card-body-invoice p-0">
                    @include('orders.partials.items-content', ['order' => $order])
                </div>
            </div>
        </div>
        
        <!-- Sidebar (Right Column) -->
        <div class="invoice-sidebar">
            <!-- Order Summary -->
            <div class="invoice-card">
                <div class="card-header-invoice">
                    <h3 class="section-title-invoice">
                        <i class="bi bi-receipt"></i>
                        {{ __('orders.order_summary') }}
                    </h3>
                </div>
                <div class="card-body-invoice">
                    @include('orders.partials.summary-content', ['order' => $order])
                </div>
            </div>
            
            <!-- Order Actions -->
            <div class="invoice-card no-print">
                <div class="card-header-invoice">
                    <h3 class="section-title-invoice">
                        <i class="bi bi-gear"></i>
                        {{ __('orders.actions') }}
                    </h3>
                </div>
                <div class="card-body-invoice">
                    @include('orders.partials.actions', ['order' => $order])
                </div>
            </div>
            
            <!-- Order Timeline -->
            <div class="invoice-card">
                <div class="card-header-invoice">
                    <h3 class="section-title-invoice">
                        <i class="bi bi-clock-history"></i>
                        {{ __('orders.order_timeline') }}
                    </h3>
                </div>
                <div class="card-body-invoice">
                    @include('orders.partials.timeline', ['order' => $order])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // View payment proof function
    function viewPaymentProof(url, orderNumber) {
        // Check if it's a PDF by URL or file extension
        const isPdf = url.toLowerCase().includes('.pdf') || url.includes('pdf');
        
        if (isPdf) {
            // For PDF files, open in new tab
            window.open(url, '_blank');
        } else {
            // For images, show in modal with zoom functionality
            Swal.fire({
                title: `{{ __('app.orders.payment_proof') }} - ${orderNumber}`,
                html: `
                    <div style="text-align: center; position: relative;">
                        <div class="image-zoom-container" style="position: relative; display: inline-block; max-width: 100%; overflow: hidden; border-radius: 10px;">
                            <img id="zoomableImage" src="${url}" 
                                 style="max-width: 100%; max-height: 500px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); transition: transform 0.3s ease; cursor: zoom-in;" 
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div style="display: none; padding: 2rem;">
                                <i class="bi bi-exclamation-triangle" style="font-size: 3rem; color: #dc3545;"></i>
                                <p style="margin-top: 1rem;">{{ __('app.orders.file_not_found') }}</p>
                            </div>
                        </div>
                        <div class="zoom-controls" style="margin-top: 1rem; display: flex; justify-content: center; gap: 0.5rem;">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="zoomImage('in')" title="{{ __('orders.zoom_in') }}">
                                <i class="bi bi-zoom-in"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="zoomImage('out')" title="{{ __('orders.zoom_out') }}">
                                <i class="bi bi-zoom-out"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="resetZoom()" title="{{ __('orders.reset_zoom') }}">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                    </div>
                `,
                showCloseButton: true,
                showConfirmButton: false,
                width: '800px',
                customClass: {
                    popup: 'payment-proof-modal'
                },
                didOpen: () => {
                    // Initialize zoom functionality
                    window.currentZoom = 1;
                    const image = document.getElementById('zoomableImage');
                    
                    // Add click to zoom functionality
                    image.addEventListener('click', function() {
                        if (window.currentZoom === 1) {
                            zoomImage('in');
                        } else {
                            resetZoom();
                        }
                    });
                }
            });
        }
    }
    
    // Zoom functionality
    let currentZoom = 1;
    
    function zoomImage(direction) {
        const image = document.getElementById('zoomableImage');
        if (!image) return;
        
        if (direction === 'in') {
            currentZoom = Math.min(currentZoom * 1.5, 4); // Max 4x zoom
            image.style.cursor = 'zoom-out';
        } else if (direction === 'out') {
            currentZoom = Math.max(currentZoom / 1.5, 0.5); // Min 0.5x zoom
            if (currentZoom === 1) {
                image.style.cursor = 'zoom-in';
            }
        }
        
        image.style.transform = `scale(${currentZoom})`;
        
        // Update container overflow for zoomed images
        const container = image.parentElement;
        if (currentZoom > 1) {
            container.style.overflow = 'auto';
            container.style.maxHeight = '500px';
        } else {
            container.style.overflow = 'hidden';
            container.style.maxHeight = 'none';
        }
    }
    
    function resetZoom() {
        const image = document.getElementById('zoomableImage');
        if (!image) return;
        
        currentZoom = 1;
        image.style.transform = 'scale(1)';
        image.style.cursor = 'zoom-in';
        
        const container = image.parentElement;
        container.style.overflow = 'hidden';
        container.style.maxHeight = 'none';
    }
</script>
@endpush
