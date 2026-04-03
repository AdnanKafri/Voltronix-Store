@props([
    'src' => '',
    'alt' => '',
    'class' => '',
    'width' => null,
    'height' => null,
    'placeholder' => null,
    'loading' => 'lazy'
])

<!-- Lazy Loading Image Component -->
<div class="lazy-image-container {{ $class }}" 
     style="{{ $width ? 'width: ' . $width . ';' : '' }} {{ $height ? 'height: ' . $height . ';' : '' }}">
    
    <!-- Placeholder/Skeleton -->
    <div class="lazy-image-placeholder" id="placeholder-{{ Str::random(8) }}">
        @if($placeholder)
            <img src="{{ $placeholder }}" alt="{{ $alt }}" class="placeholder-img">
        @else
            <div class="skeleton-placeholder">
                <div class="skeleton-shimmer"></div>
                <i class="bi bi-image placeholder-icon"></i>
            </div>
        @endif
    </div>
    
    <!-- Actual Image -->
    <img src="{{ $src }}" 
         alt="{{ $alt }}" 
         class="lazy-image"
         loading="{{ $loading }}"
         {{ $width ? 'width=' . $width : '' }}
         {{ $height ? 'height=' . $height : '' }}
         onload="this.parentElement.classList.add('loaded')"
         onerror="this.parentElement.classList.add('error')">
    
    <!-- Error State -->
    <div class="lazy-image-error">
        <i class="bi bi-exclamation-triangle"></i>
        <span>{{ __('app.common.image_load_error') }}</span>
    </div>
</div>

@push('styles')
<style>
/* Lazy Image Styles */
.lazy-image-container {
    position: relative;
    overflow: hidden;
    border-radius: var(--border-radius-sm);
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}

.lazy-image-placeholder {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    transition: opacity 0.3s ease;
    z-index: 2;
}

.lazy-image-container.loaded .lazy-image-placeholder {
    opacity: 0;
    pointer-events: none;
}

.placeholder-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: blur(5px);
    transform: scale(1.1);
}

.skeleton-placeholder {
    position: relative;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 2s infinite;
    display: flex;
    align-items: center;
    justify-content: center;
}

.skeleton-shimmer {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent 25%, rgba(255, 255, 255, 0.4) 50%, transparent 75%);
    background-size: 200% 100%;
    animation: shimmer 2s infinite;
}

.placeholder-icon {
    font-size: 2rem;
    color: rgba(0, 0, 0, 0.3);
    z-index: 1;
}

.lazy-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: opacity 0.3s ease, transform 0.3s ease;
    opacity: 0;
    transform: scale(1.05);
}

.lazy-image-container.loaded .lazy-image {
    opacity: 1;
    transform: scale(1);
}

.lazy-image-error {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    color: #6c757d;
    font-size: 0.875rem;
    text-align: center;
    padding: 1rem;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 3;
}

.lazy-image-container.error .lazy-image-error {
    opacity: 1;
}

.lazy-image-error i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    color: #dc3545;
}

/* Voltronix Theme */
.voltronix-theme .lazy-image-container {
    background: rgba(0, 127, 255, 0.05);
    border: 1px solid rgba(0, 127, 255, 0.1);
}

.voltronix-theme .skeleton-placeholder {
    background: linear-gradient(90deg, rgba(0, 127, 255, 0.1) 25%, rgba(35, 239, 255, 0.1) 50%, rgba(0, 127, 255, 0.1) 75%);
    background-size: 200% 100%;
}

.voltronix-theme .skeleton-shimmer {
    background: linear-gradient(90deg, transparent 25%, rgba(0, 127, 255, 0.2) 50%, transparent 75%);
    background-size: 200% 100%;
}

.voltronix-theme .placeholder-icon {
    color: var(--voltronix-primary);
}

/* Hover Effects */
.lazy-image-container:hover .lazy-image {
    transform: scale(1.05);
}

.lazy-image-container.loaded:hover .lazy-image {
    transform: scale(1.1);
}

/* Aspect Ratios */
.lazy-image-container.aspect-square {
    aspect-ratio: 1 / 1;
}

.lazy-image-container.aspect-video {
    aspect-ratio: 16 / 9;
}

.lazy-image-container.aspect-photo {
    aspect-ratio: 4 / 3;
}

.lazy-image-container.aspect-portrait {
    aspect-ratio: 3 / 4;
}

/* Sizes */
.lazy-image-container.size-sm {
    width: 80px;
    height: 80px;
}

.lazy-image-container.size-md {
    width: 120px;
    height: 120px;
}

.lazy-image-container.size-lg {
    width: 200px;
    height: 200px;
}

.lazy-image-container.size-xl {
    width: 300px;
    height: 300px;
}

/* Responsive */
@media (max-width: 768px) {
    .placeholder-icon {
        font-size: 1.5rem;
    }
    
    .lazy-image-error {
        font-size: 0.75rem;
        padding: 0.5rem;
    }
    
    .lazy-image-error i {
        font-size: 1.5rem;
    }
}

/* Dark Theme */
[data-theme="dark"] .lazy-image-container {
    background: #2a2a2a;
}

[data-theme="dark"] .lazy-image-placeholder {
    background: linear-gradient(135deg, #2a2a2a 0%, #3a3a3a 100%);
}

[data-theme="dark"] .skeleton-placeholder {
    background: linear-gradient(90deg, #2a2a2a 25%, #3a3a3a 50%, #2a2a2a 75%);
    background-size: 200% 100%;
}

[data-theme="dark"] .skeleton-shimmer {
    background: linear-gradient(90deg, transparent 25%, rgba(255, 255, 255, 0.1) 50%, transparent 75%);
    background-size: 200% 100%;
}

[data-theme="dark"] .placeholder-icon {
    color: rgba(255, 255, 255, 0.3);
}

[data-theme="dark"] .lazy-image-error {
    background: #2a2a2a;
    color: #adb5bd;
}

/* Animation */
@keyframes shimmer {
    0% {
        background-position: -200% 0;
    }
    100% {
        background-position: 200% 0;
    }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    .skeleton-placeholder,
    .skeleton-shimmer {
        animation: none;
    }
    
    .lazy-image,
    .lazy-image-placeholder {
        transition: none;
    }
}

/* Focus States */
.lazy-image-container:focus-within {
    outline: 2px solid var(--voltronix-primary);
    outline-offset: 2px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Intersection Observer for lazy loading
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const container = entry.target;
                    const img = container.querySelector('.lazy-image');
                    
                    if (img && img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(container);
                    }
                }
            });
        }, {
            rootMargin: '50px 0px',
            threshold: 0.01
        });

        // Observe all lazy image containers
        document.querySelectorAll('.lazy-image-container').forEach(container => {
            imageObserver.observe(container);
        });
    }
});
</script>
@endpush
