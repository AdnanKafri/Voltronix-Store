<div class="reviews-section">
    @if($product->reviews_count > 0)
    <!-- Rating Summary -->
    <div class="rating-summary">
        <div class="row">
            <div class="col-md-4">
                <div class="overall-rating">
                    <div class="rating-number">{{ number_format($product->average_rating, 1) }}</div>
                    <div class="rating-stars">{!! $product->stars_html !!}</div>
                    <div class="rating-text">{{ __('products.out_of_5') }}</div>
                    <div class="total-reviews">{{ $product->reviews_count }} {{ __('products.total_reviews') }}</div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="rating-distribution">
                    <h6>{{ __('products.rating_distribution') }}</h6>
                    @for($i = 5; $i >= 1; $i--)
                        @php $dist = $ratingDistribution[$i] ?? ['count' => 0, 'percentage' => 0]; @endphp
                        <div class="rating-bar">
                            <span class="rating-label">{{ $i }} <i class="bi bi-star-fill"></i></span>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $dist['percentage'] }}%"></div>
                            </div>
                            <span class="rating-count">{{ $dist['count'] }}</span>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Write Review Section -->
    @auth
        @if($canReview)
        <div class="write-review-section">
            <h5>{{ __('products.write_review') }}</h5>
            <form id="reviewForm" class="review-form">
                @csrf
                <div class="rating-input mb-3">
                    <label class="form-label">{{ __('products.your_rating') }} *</label>
                    <div class="star-rating">
                        @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star rating-star" data-rating="{{ $i }}" onclick="setRating({{ $i }})"></i>
                        @endfor
                    </div>
                    <input type="hidden" id="ratingValue" name="rating" required>
                </div>
                
                <div class="mb-3">
                    <label for="reviewComment" class="form-label">{{ __('products.your_review') }}</label>
                    <textarea class="form-control" id="reviewComment" name="comment" rows="4" 
                              placeholder="{{ __('products.comment_placeholder') }}"
                              minlength="10" maxlength="1000"></textarea>
                    <div class="form-text">{{ __('products.comment_min_length') }}</div>
                </div>
                
                <button type="submit" class="btn btn-voltronix-primary">
                    <i class="bi bi-send me-2"></i>
                    {{ __('products.submit_review') }}
                </button>
            </form>
        </div>
        @elseif($userReview)
        <div class="user-review-section">
            <h5>{{ __('products.your_review') }}</h5>
            <div class="user-review-card">
                <div class="review-header">
                    <div class="review-rating">{!! $userReview->stars_html !!}</div>
                    <div class="review-date">{{ $userReview->formatted_date }}</div>
                </div>
                @if($userReview->comment)
                <div class="review-comment">{{ $userReview->comment }}</div>
                @endif
                @if(!$userReview->approved)
                <div class="review-status">
                    <i class="bi bi-clock text-warning me-2"></i>
                    {{ __('products.review_pending_approval') }}
                </div>
                @endif
                <div class="review-actions">
                    <button class="btn btn-sm btn-outline-primary" onclick="editReview({{ $userReview->id }})">
                        <i class="bi bi-pencil me-1"></i>
                        {{ __('products.edit_review') }}
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteReview({{ $userReview->id }})">
                        <i class="bi bi-trash me-1"></i>
                        {{ __('products.delete_review') }}
                    </button>
                </div>
            </div>
        </div>
        @else
        <div class="review-restriction">
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                {{ __('products.already_reviewed') }}
            </div>
        </div>
        @endif
    @else
    <div class="review-restriction">
        <div class="alert alert-info">
            <i class="bi bi-person-plus me-2"></i>
            {{ __('products.login_to_review') }}
            <a href="{{ route('login') }}" class="btn btn-sm btn-primary ms-2">{{ __('auth.login') }}</a>
        </div>
    </div>
    @endauth

    <!-- Reviews List -->
    @if($product->approvedReviews->count() > 0)
    <div class="reviews-list">
        <h5>{{ __('products.customer_reviews') }}</h5>
        <div id="reviewsList">
            @foreach($product->approvedReviews as $review)
            <div class="review-item" data-review-id="{{ $review->id }}" data-rating="{{ $review->rating }}">
                <div class="review-header">
                    <div class="reviewer-info">
                        <div class="reviewer-name">
                            {{ $review->user->name }}
                            <span class="verified-badge">
                                <i class="bi bi-patch-check-fill text-success"></i>
                                {{ __('products.verified_purchase') }}
                            </span>
                        </div>
                        <div class="review-meta">
                            <div class="review-rating">{!! $review->stars_html !!}</div>
                            <div class="review-date">{{ $review->formatted_date }}</div>
                        </div>
                    </div>
                </div>
                
                @if($review->comment)
                <div class="review-comment">{{ $review->comment }}</div>
                @endif
                
                @if($review->admin_reply)
                <div class="admin-reply">
                    <div class="reply-header">
                        <i class="bi bi-reply me-2"></i>
                        <strong>{{ __('products.admin_response') }}</strong>
                    </div>
                    <div class="reply-content">{{ $review->admin_reply }}</div>
                </div>
                @endif
                
                <div class="review-actions">
                    @auth
                        @if($review->user_id === auth()->id())
                            <button class="btn btn-sm btn-outline-primary" onclick="editReview({{ $review->id }})">
                                <i class="bi bi-pencil me-1"></i>
                                {{ __('products.edit_review') }}
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteReview({{ $review->id }})">
                                <i class="bi bi-trash me-1"></i>
                                {{ __('products.delete_review') }}
                            </button>
                        @else

                            <button class="btn btn-sm btn-outline-secondary" onclick="reportReview({{ $review->id }})">
                                <i class="bi bi-flag me-1"></i>
                                {{ __('products.report_review') }}
                            </button>
                        @endif
                    @else
                        <button class="btn btn-sm btn-outline-secondary" onclick="reportReview({{ $review->id }})">
                            <i class="bi bi-flag me-1"></i>
                            {{ __('products.report_review') }}
                        </button>
                    @endauth
                </div>
            </div>
            @endforeach
        </div>
        
        @if($product->reviews()->approved()->count() > 10)
        <div class="load-more-section">
            <button class="btn btn-outline-primary" id="loadMoreReviews" onclick="loadMoreReviews()">
                <i class="bi bi-arrow-down me-2"></i>
                {{ __('products.load_more_reviews') }}
            </button>
        </div>
        @endif
    </div>
    @elseif($product->reviews_count == 0)
    <div class="no-reviews">
        <div class="text-center py-5">
            <i class="bi bi-chat-square-text display-4 text-muted mb-3"></i>
            <h5>{{ __('products.no_reviews') }}</h5>
            <p class="text-muted">{{ __('products.be_first_review') }}</p>
        </div>
    </div>
    @endif
</div>

<script>
let currentRating = 0;
let reviewsPage = 1;

// Rating functionality
function setRating(rating) {
    currentRating = rating;
    document.getElementById('ratingValue').value = rating;
    
    const stars = document.querySelectorAll('.rating-star');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('bi-star');
            star.classList.add('bi-star-fill');
            star.style.color = '#ffc107';
        } else {
            star.classList.remove('bi-star-fill');
            star.classList.add('bi-star');
            star.style.color = '#dee2e6';
        }
    });
}

// Review form submission
document.addEventListener('DOMContentLoaded', function() {
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (currentRating === 0) {
                Swal.fire({
                    title: '{{ __("products.rating_required") }}',
                    icon: 'warning',
                    confirmButtonColor: '#007fff'
                });
                return;
            }
            
            const formData = new FormData(this);
            
            // Show loading
            Swal.fire({
                title: '{{ __("products.processing") }}',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch('{{ route("products.reviews.store", $product->slug) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '{{ __("app.common.success") }}',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#007fff'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                Swal.fire({
                    title: '{{ __("app.common.error") }}',
                    text: error.message || '{{ __("products.review_error") }}',
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
            });
        });
    }
});

function loadMoreReviews() {
    reviewsPage++;
    
    fetch(`{{ route("products.reviews.load", $product->slug) }}?page=${reviewsPage}`)
    .then(response => response.json())
    .then(data => {
        if (data.success && data.reviews.length > 0) {
            const reviewsList = document.getElementById('reviewsList');
            
            data.reviews.forEach(review => {
                const reviewHtml = createReviewHtml(review);
                reviewsList.insertAdjacentHTML('beforeend', reviewHtml);
            });
            
            if (!data.has_more) {
                document.getElementById('loadMoreReviews').style.display = 'none';
            }
        }
    })
    .catch(error => {
        console.error('Error loading reviews:', error);
    });
}

function createReviewHtml(review) {
    return `
        <div class="review-item">
            <div class="review-header">
                <div class="reviewer-info">
                    <div class="reviewer-name">
                        ${review.user_name}
                        <span class="verified-badge">
                            <i class="bi bi-patch-check-fill text-success"></i>
                            {{ __('products.verified_purchase') }}
                        </span>
                    </div>
                    <div class="review-meta">
                        <div class="review-rating">${review.stars_html}</div>
                        <div class="review-date">${review.created_at}</div>
                    </div>
                </div>
            </div>
            ${review.comment ? `<div class="review-comment">${review.comment}</div>` : ''}
            ${review.admin_reply ? `
                <div class="admin-reply">
                    <div class="reply-header">
                        <i class="bi bi-reply me-2"></i>
                        <strong>{{ __('products.admin_response') }}</strong>
                    </div>
                    <div class="reply-content">${review.admin_reply}</div>
                </div>
            ` : ''}
            <div class="review-actions">
                <button class="btn btn-sm btn-outline-secondary" onclick="toggleHelpful(${review.id})">
                    <i class="bi bi-hand-thumbs-up me-1"></i>
                    {{ __('products.helpful') }}
                </button>
                <button class="btn btn-sm btn-outline-secondary" onclick="reportReview(${review.id})">
                    <i class="bi bi-flag me-1"></i>
                    {{ __('products.report_review') }}
                </button>
            </div>
        </div>
    `;
}

function editReview(reviewId) {
    const reviewElement = document.querySelector(`[data-review-id="${reviewId}"]`);
    const currentRating = reviewElement.dataset.rating;
    const currentComment = reviewElement.querySelector('.review-comment').textContent.trim();
    
    Swal.fire({
        title: '{{ __("products.edit_review") }}',
        html: `
            <div class="edit-review-form">
                <div class="mb-3">
                    <label class="form-label">{{ __("products.your_rating") }}</label>
                    <div class="star-rating-edit" id="editStarRating">
                        ${[1,2,3,4,5].map(i => `<i class="bi bi-star rating-star-edit ${i <= currentRating ? 'bi-star-fill' : ''}" data-rating="${i}" onclick="setEditRating(${i})" style="color: ${i <= currentRating ? '#ffc107' : '#dee2e6'}; cursor: pointer; font-size: 1.5rem;"></i>`).join('')}
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __("products.your_review") }}</label>
                    <textarea id="editComment" class="form-control" rows="4" maxlength="1000">${currentComment}</textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '{{ __("products.update_review") }}',
        cancelButtonText: '{{ __("app.common.cancel") }}',
        confirmButtonColor: '#007fff',
        preConfirm: () => {
            const rating = window.editRatingValue || currentRating;
            const comment = document.getElementById('editComment').value;
            
            if (!rating) {
                Swal.showValidationMessage('{{ __("products.rating_required") }}');
                return false;
            }
            
            return { rating, comment };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            updateReview(reviewId, result.value.rating, result.value.comment);
        }
    });
    
    window.editRatingValue = currentRating;
}

function setEditRating(rating) {
    window.editRatingValue = rating;
    const stars = document.querySelectorAll('.rating-star-edit');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('bi-star');
            star.classList.add('bi-star-fill');
            star.style.color = '#ffc107';
        } else {
            star.classList.remove('bi-star-fill');
            star.classList.add('bi-star');
            star.style.color = '#dee2e6';
        }
    });
}

function updateReview(reviewId, rating, comment) {
    fetch(`{{ route("products.reviews.update", [$product->slug, "__REVIEW_ID__"]) }}`.replace('__REVIEW_ID__', reviewId), {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ rating, comment })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: '{{ __("app.common.success") }}',
                text: data.message,
                icon: 'success',
                confirmButtonColor: '#007fff'
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.message);
        }
    })
    .catch(error => {
        Swal.fire({
            title: '{{ __("app.common.error") }}',
            text: error.message || '{{ __("products.review_error") }}',
            icon: 'error',
            confirmButtonColor: '#dc3545'
        });
    });
}

function deleteReview(reviewId) {
    Swal.fire({
        title: '{{ __("products.delete_review") }}',
        text: '{{ __("products.delete_review_confirm") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("app.common.delete") }}',
        cancelButtonText: '{{ __("app.common.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route("products.reviews.delete", [$product->slug, "__REVIEW_ID__"]) }}`.replace('__REVIEW_ID__', reviewId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '{{ __("app.common.success") }}',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#007fff'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                Swal.fire({
                    title: '{{ __("app.common.error") }}',
                    text: error.message || '{{ __("products.review_error") }}',
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
            });
        }
    });
}

function toggleHelpful(reviewId) {
    // Implementation for helpful votes
    console.log('Toggle helpful for review:', reviewId);
}

function reportReview(reviewId) {
    Swal.fire({
        title: '{{ __("products.report_review") }}',
        text: '{{ __("products.report_reason") }}',
        input: 'textarea',
        inputPlaceholder: '{{ __("products.report_placeholder") }}',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("products.submit_report") }}',
        cancelButtonText: '{{ __("app.common.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            Swal.fire({
                title: '{{ __("products.report_submitted") }}',
                text: '{{ __("products.report_thank_you") }}',
                icon: 'success',
                confirmButtonColor: '#007fff'
            });
        }
    });
}
</script>

<style>
.reviews-section {
    padding: 0;
}

.rating-summary {
    background: rgba(0, 127, 255, 0.05);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid rgba(0, 127, 255, 0.1);
}

.overall-rating {
    text-align: center;
}

.rating-number {
    font-size: 3rem;
    font-weight: 700;
    color: #007fff;
    font-family: 'Orbitron', monospace;
}

.rating-stars {
    font-size: 1.5rem;
    margin: 0.5rem 0;
}

.rating-text {
    color: #6c757d;
    font-weight: 500;
}

.total-reviews {
    color: #6c757d;
    font-size: 0.9rem;
    margin-top: 0.5rem;
}

.rating-distribution h6 {
    color: #1a1a1a;
    font-weight: 600;
    margin-bottom: 1rem;
}

.rating-bar {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.75rem;
}

.rating-label {
    min-width: 60px;
    font-size: 0.9rem;
    color: #6c757d;
}

.progress {
    flex: 1;
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
}

.progress-bar {
    background: linear-gradient(135deg, #007fff, #23efff);
    height: 100%;
    border-radius: 4px;
    transition: width 0.3s ease;
}

.rating-count {
    min-width: 30px;
    text-align: right;
    font-size: 0.9rem;
    color: #6c757d;
}

.write-review-section,
.user-review-section {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid rgba(0, 127, 255, 0.1);
}

.star-rating {
    display: flex;
    gap: 0.25rem;
    margin-bottom: 0.5rem;
}

.rating-star {
    font-size: 1.5rem;
    color: #dee2e6;
    cursor: pointer;
    transition: all 0.2s ease;
}

.rating-star:hover {
    color: #ffc107;
    transform: scale(1.1);
}

.user-review-card {
    background: rgba(0, 127, 255, 0.05);
    border-radius: 10px;
    padding: 1.5rem;
    border-left: 4px solid #007fff;
}

.review-restriction .alert {
    border-radius: 10px;
    border: 1px solid rgba(0, 127, 255, 0.2);
}

.reviews-list {
    margin-top: 2rem;
}

.review-item {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid rgba(0, 127, 255, 0.1);
    transition: all 0.3s ease;
}

.review-item:hover {
    box-shadow: 0 5px 20px rgba(0, 127, 255, 0.1);
}

.review-header {
    margin-bottom: 1rem;
}

.reviewer-info {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.reviewer-name {
    font-weight: 600;
    color: #1a1a1a;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.verified-badge {
    font-size: 0.8rem;
    color: #28a745;
}

.review-meta {
    text-align: right;
}

.review-rating {
    margin-bottom: 0.25rem;
}

.review-date {
    font-size: 0.85rem;
    color: #6c757d;
}

.review-comment {
    color: #1a1a1a;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.admin-reply {
    background: rgba(0, 127, 255, 0.05);
    border-radius: 10px;
    padding: 1rem;
    margin: 1rem 0;
    border-left: 3px solid #007fff;
}

.reply-header {
    color: #007fff;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.reply-content {
    color: #1a1a1a;
}

.review-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

.review-actions .btn {
    font-size: 0.85rem;
}

.load-more-section {
    text-align: center;
    margin-top: 2rem;
}

.no-reviews {
    text-align: center;
    padding: 3rem 1rem;
    color: #6c757d;
}

.btn-voltronix-primary {
    background: linear-gradient(135deg, #007fff, #23efff);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-voltronix-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 127, 255, 0.3);
    color: white;
}

@media (max-width: 768px) {
    .rating-summary {
        padding: 1.5rem;
    }
    
    .reviewer-info {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .review-meta {
        text-align: left;
    }
    
    .write-review-section,
    .user-review-section {
        padding: 1.5rem;
    }
    
    .review-actions {
        flex-wrap: wrap;
    }
}
</style>
