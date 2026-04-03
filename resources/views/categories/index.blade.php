@extends('layouts.app')

@section('title', __('app.categories.title') . ' - ' . __('app.hero.title'))
@section('description', __('app.categories.all_categories') . ' - ' . __('app.footer.description'))



@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="volt-container">
        {{-- Removed <br> to fix spacing --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-voltronix">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">{{ __('app.nav.home') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ __('app.categories.title') }}
                </li>
            </ol>
        </nav>
        <h1 class="display-4 fw-bold mb-3">{{ __('app.categories.all_categories') }}</h1>
        <p class="lead mb-0">{{ __('app.categories.browse_category') }}</p>
    </div>
</section>

<!-- Categories Grid -->
<section class="py-5" style="background: var(--voltronix-light);">
    <div class="volt-container">
        @if($categories->count() > 0)
            <div class="row g-4">
                @foreach($categories as $category)
                    <div class="col-lg-4 col-md-6">
                        <div class="category-card">
                            <div class="category-image">
                                @if($category->thumbnail)
                                    <img src="{{ asset('storage/' . $category->thumbnail) }}" 
                                         alt="{{ $category->getTranslation('name') }}"
                                         loading="lazy">
                                @else
                                    <div class="category-icon">
                                        <i class="bi bi-grid-3x3-gap-fill"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="category-content">
                                <h3 class="category-title">{{ $category->getTranslation('name') }}</h3>
                                
                                @if($category->getTranslation('description'))
                                    <p class="category-description">
                                        {{ Str::limit($category->getTranslation('description'), 120) }}
                                    </p>
                                @endif
                                
                                <div class="category-meta">
                                    <span class="products-count">
                                        {{ __('app.categories.product_count', ['count' => $category->active_products_count]) }}
                                    </span>
                                </div>
                                
                                <a href="{{ route('categories.show', $category->slug) }}" 
                                   class="btn-category stretched-link">
                                    <i class="bi bi-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }} {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                    {{ __('app.categories.view_products') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-grid-3x3-gap"></i>
                <h3>{{ __('app.categories.no_categories') }}</h3>
                <p class="mb-4">{{ __('app.common.coming_soon') }}</p>
                <a href="{{ url('/') }}" class="btn btn-voltronix">
                    <i class="bi bi-house {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('app.nav.home') }}
                </a>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Add scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all category cards
    document.querySelectorAll('.category-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
</script>
@endpush
