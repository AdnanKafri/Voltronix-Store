@extends('admin.layouts.app')

@push('styles')
<style>
.reviews-dashboard {
    padding: 2rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
    border: 2px solid rgba(0, 127, 255, 0.1);
    border-radius: 20px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #007fff, #23efff);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 127, 255, 0.15);
    border-color: #007fff;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: white;
}

.stat-icon.pending { background: linear-gradient(135deg, #ffc107, #ffb300); }
.stat-icon.approved { background: linear-gradient(135deg, #28a745, #20c997); }
.stat-icon.rejected { background: linear-gradient(135deg, #dc3545, #e74c3c); }
.stat-icon.total { background: linear-gradient(135deg, #007fff, #23efff); }

.stat-number {
    font-family: 'Orbitron', monospace;
    font-size: 2.5rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #6c757d;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.9rem;
}

.reviews-table-container {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 2px solid rgba(0, 127, 255, 0.1);
    overflow: hidden;
    width: 100%;
    max-width: 100%;
}

.table-header {
    background: linear-gradient(135deg, #007fff, #23efff);
    color: white;
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.table-title {
    font-family: 'Orbitron', monospace;
    font-weight: 700;
    font-size: 1.25rem;
    margin: 0;
}

.reviews-table {
    width: 100%;
    margin: 0;
    min-width: 800px;
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.reviews-table th {
    background: #f8f9fa;
    color: #1a1a1a;
    font-weight: 600;
    padding: 1rem;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
}

.reviews-table td {
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
}

.reviews-table tbody tr:hover {
    background: rgba(0, 127, 255, 0.05);

.review-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    white-space: nowrap;
}

.rating-stars {
    display: flex;
    gap: 2px;
    flex-shrink: 0;
}

.rating-stars i {
    font-size: 0.9rem;
}

.rating-number {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
    flex-shrink: 0;
}

.review-comment {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.user-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.user-name {
    font-weight: 600;
    color: #1a1a1a;
}

.user-email {
    font-size: 0.85rem;
    color: #6c757d;
}

.product-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.product-thumbnail {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    object-fit: cover;
}

.product-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.product-name {
    font-weight: 600;
    color: #1a1a1a;
}

.product-id {
    font-size: 0.75rem;
    color: #6c757d;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.btn-action {
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    border: none;
    font-size: 0.8rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.btn-approve {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
}

.btn-approve:hover {
    background: linear-gradient(135deg, #1e7e34, #17a2b8);
    transform: translateY(-1px);
    color: white;
}

.btn-reject {
    background: linear-gradient(135deg, #ffc107, #ffb300);
    color: #212529;
}

.btn-reject:hover {
    background: linear-gradient(135deg, #e0a800, #e6a100);
    transform: translateY(-1px);
    color: #212529;
}

.btn-reply {
    background: linear-gradient(135deg, #007fff, #23efff);
    color: white;
}

.btn-reply:hover {
    background: linear-gradient(135deg, #0056b3, #1bb3e6);
    transform: translateY(-1px);
    color: white;
}

.btn-delete {
    background: linear-gradient(135deg, #dc3545, #e74c3c);
    color: white;
}

.btn-delete:hover {
    background: linear-gradient(135deg, #c82333, #dc2626);
    transform: translateY(-1px);
    color: white;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.pagination-wrapper {
    padding: 1.5rem 2rem;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

/* Card Voltronix Styles */
.card-voltronix {
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
    border: 1px solid rgba(13, 110, 253, 0.1);
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(13, 110, 253, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-voltronix:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(13, 110, 253, 0.12);
}

.title-orbitron {
    font-family: 'Orbitron', sans-serif;
    font-weight: 900;
    background: linear-gradient(135deg, #007fff, #23efff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.table th {
    font-weight: 600;
    color: #1a1a1a;
    border-bottom: 2px solid rgba(13, 110, 253, 0.1);
    background: linear-gradient(145deg, #f8f9fa, #e9ecef);
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
    transform: scale(1.01);
}

.btn-group .btn {
    border-radius: 8px;
    margin: 0 2px;
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
}

.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
    border-radius: 10px;
}

/* RTL Support */
[dir="rtl"] .action-buttons {
    flex-direction: row-reverse;
}

[dir="rtl"] .product-info {
    flex-direction: row-reverse;
}

[dir="rtl"] .user-info {
    text-align: right;
}

/* Responsive Design */
@media (max-width: 768px) {
    .reviews-dashboard {
        padding: 1rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .stat-card {
        padding: 1.5rem;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group .btn {
        padding: 0.375rem 0.5rem;
    }
    
    .table tbody tr:hover {
        transform: none;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.3rem;
    }
    
    .btn-action {
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .user-info {
        font-size: 0.8rem;
    }
    
    .review-comment {
        max-width: 150px;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 title-orbitron">
                <i class="bi bi-star-fill {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.reviews_page.management') }}
            </h1>
            <p class="text-muted mb-0">{{ __('admin.reviews_page.manage_customer_reviews') }}</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="bi bi-star-fill"></i>
                </div>
                <div class="stat-number">{{ $stats['total'] }}</div>
                <div class="stat-label">{{ __('admin.reviews_page.total_reviews') }}</div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-icon pending">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-number">{{ $stats['pending'] }}</div>
                <div class="stat-label">{{ __('admin.common.pending') }}</div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-icon approved">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-number">{{ $stats['approved'] }}</div>
                <div class="stat-label">{{ __('admin.common.approved') }}</div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-icon rejected">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stat-number">{{ $stats['rejected'] }}</div>
                <div class="stat-label">{{ __('admin.common.rejected') }}</div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-funnel {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.reviews_page.filters') }}
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reviews.index') }}">
                <div class="filter-row">
                    <div class="filter-col">
                        <label for="status" class="form-label-enhanced">{{ __('admin.common.status') }}</label>
                        <select name="status" id="status" class="form-control form-control-enhanced">
                            <option value="">{{ __('admin.reviews_page.all_statuses') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                {{ __('admin.common.pending') }}
                            </option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                {{ __('admin.common.approved') }}
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                {{ __('admin.common.rejected') }}
                            </option>
                        </select>
                    </div>

                    <div class="filter-col">
                        <label for="product_id" class="form-label-enhanced">{{ __('admin.reviews_page.product') }}</label>
                        <select name="product_id" id="product_id" class="form-control form-control-enhanced">
                            <option value="">{{ __('admin.reviews_page.all_products') }}</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->getTranslation('name', app()->getLocale()) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-col">
                        <label for="rating" class="form-label-enhanced">{{ __('admin.reviews_page.rating') }}</label>
                        <select name="rating" id="rating" class="form-control form-control-enhanced">
                            <option value="">{{ __('admin.reviews_page.all_ratings') }}</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                    {{ $i }} {{ __('admin.reviews_page.stars') }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="filter-col">
                        <label for="search" class="form-label-enhanced">{{ __('admin.common.search') }}</label>
                        <div class="search-input-group">
                            <input type="text" 
                                   class="form-control form-control-enhanced" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="{{ __('admin.reviews_page.search_placeholder') }}">
                            <button type="submit" class="btn search-btn">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="filter-col filter-col-auto">
                        <div class="filter-actions">
                            <button type="submit" class="filter-btn btn-filter">
                                <i class="bi bi-funnel"></i>
                                {{ __('admin.reviews_page.filter') }}
                            </button>
                            <a href="{{ route('admin.reviews.index') }}" class="filter-btn btn-clear">
                                <i class="bi bi-arrow-clockwise"></i>
                                {{ __('admin.reviews_page.clear') }}
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reviews Table -->
    <div class="admin-table">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-list-ul {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('admin.reviews_page.reviews_list') }}
            </h5>
            <span class="badge bg-primary">{{ $reviews->total() }} {{ __('admin.reviews.title') }}</span>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 8%;">ID</th>
                            <th style="width: 25%;">{{ __('admin.reviews_page.product') }}</th>
                            <th style="width: 20%;">{{ __('admin.reviews_page.user') }}</th>
                            <th style="width: 12%;">{{ __('admin.reviews_page.rating') }}</th>
                            <th style="width: 20%;">{{ __('admin.reviews_page.comment') }}</th>
                            <th style="width: 10%;">{{ __('admin.common.status') }}</th>
                            <th style="width: 12%;">{{ __('admin.common.date') }}</th>
                            <th style="width: 13%;">{{ __('admin.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr id="review-{{ $review->id }}">
                                <td>{{ str_pad($review->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <div class="fw-bold">{{ $review->product->getTranslation('name', app()->getLocale()) }}</div>
                                        <small class="text-muted">{{ $review->product->category->getTranslation('name', app()->getLocale()) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <div class="fw-bold">{{ $review->user->name }}</div>
                                        <small class="text-muted">{{ $review->user->email }}</small>
                                        @if($review->user->email_verified_at)
                                            <span class="badge bg-success">{{ __('admin.reviews_page.verified') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('admin.reviews_page.unverified') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="review-rating d-flex align-items-center gap-2">
                                        <div class="rating-stars d-flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                @else
                                                    <i class="bi bi-star text-muted"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="rating-number text-nowrap">{{ $review->rating }}/5</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="review-comment" style="max-width: 200px;">
                                        {{ Str::limit($review->comment, 80) }}
                                    </div>
                                </td>
                                <td>
                                    @if($review->status === 'approved')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i>
                                            {{ __('admin.common.approved') }}
                                        </span>
                                    @elseif($review->status === 'rejected')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle"></i>
                                            {{ __('admin.common.rejected') }}
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="bi bi-clock-history"></i>
                                            {{ __('admin.common.pending') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>{{ $review->created_at->format('M d, Y') }}</span>
                                        <small class="text-muted">{{ $review->created_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                            @if($review->status !== 'approved')
                                                <button type="button" 
                                                        class="action-btn btn-edit"
                                                        title="{{ __('admin.reviews_page.approve') }}"
                                                        data-bs-toggle="tooltip"
                                                        onclick="updateReviewStatus({{ $review->id }}, 'approve')">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                            @endif
                                            
                                            @if($review->status !== 'rejected')
                                                <button type="button" 
                                                        class="action-btn btn-warning-action"
                                                        title="{{ __('admin.reviews_page.reject') }}"
                                                        data-bs-toggle="tooltip"
                                                        onclick="updateReviewStatus({{ $review->id }}, 'reject')">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            @endif
                                            
                                            <button type="button" 
                                                    class="action-btn btn-delete"
                                                    title="{{ __('admin.reviews_page.delete') }}"
                                                    data-bs-toggle="tooltip"
                                                    onclick="deleteReview({{ $review->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                        <p class="mb-0">{{ __('admin.reviews_page.no_reviews_found') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($reviews->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
{{ __('admin.common.showing') }} {{ $reviews->firstItem() }} {{ __('admin.common.to') }} {{ $reviews->lastItem() }} {{ __('admin.common.of') }} {{ $reviews->total() }} {{ __('admin.common.results') }}
                        </div>
                        {{ $reviews->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Update Review Status
function updateReviewStatus(reviewId, action) {
    const actionText = action === 'approve' ? 'Approve' : 'Reject';
    
    Swal.fire({
        title: `${actionText} Review?`,
        text: 'This action will change the review status.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: action === 'approve' ? '#28a745' : '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Yes, ${actionText}`,
        cancelButtonText: 'Cancel',
        reverseButtons: {{ app()->getLocale() == 'ar' ? 'true' : 'false' }}
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Processing...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Make AJAX request
            fetch(`/admin/reviews/${reviewId}/${action}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#007fff'
                    }).then(() => {
                        // Update the status badge and buttons
                        const row = document.querySelector(`#review-${reviewId}`);
                        const statusBadge = row.querySelector('.badge');
                        const actionButtons = row.querySelector('.btn-group');
                        
                        // Update status badge
                        if (data.status === 'approved') {
                            statusBadge.className = 'badge bg-success';
                            statusBadge.innerHTML = '<i class="bi bi-check-circle"></i> Approved';
                        } else if (data.status === 'rejected') {
                            statusBadge.className = 'badge bg-danger';
                            statusBadge.innerHTML = '<i class="bi bi-x-circle"></i> Rejected';
                        }
                        
                        // Reload page to update buttons properly
                        setTimeout(() => location.reload(), 1000);
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonColor: '#007fff'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while processing your request.',
                    icon: 'error',
                    confirmButtonColor: '#007fff'
                });
            });
        }
    });
}

// Delete Review
function deleteReview(reviewId) {
    Swal.fire({
        title: 'Delete Review?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        reverseButtons: {{ app()->getLocale() == 'ar' ? 'true' : 'false' }}
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Deleting...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Make AJAX request
            fetch(`/admin/reviews/${reviewId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Deleted!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#007fff'
                    }).then(() => {
                        // Remove the row and reload page
                        document.querySelector(`#review-${reviewId}`).remove();
                        setTimeout(() => location.reload(), 1000);
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonColor: '#007fff'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while deleting the review.',
                    icon: 'error',
                    confirmButtonColor: '#007fff'
                });
            });
        }
    });
}
</script>
@endpush
