@props([
    'type' => 'card',
    'count' => 1,
    'height' => null,
    'width' => null,
    'class' => ''
])

<!-- Skeleton Loader Component -->
<div class="skeleton-container {{ $class }}">
    @for($i = 0; $i < $count; $i++)
        @if($type === 'card')
            <div class="skeleton-card">
                <div class="skeleton-image"></div>
                <div class="skeleton-content">
                    <div class="skeleton-title"></div>
                    <div class="skeleton-text"></div>
                    <div class="skeleton-text short"></div>
                    <div class="skeleton-price"></div>
                </div>
            </div>
        @elseif($type === 'text')
            <div class="skeleton-text-block" style="{{ $height ? 'height: ' . $height : '' }}; {{ $width ? 'width: ' . $width : '' }}"></div>
        @elseif($type === 'circle')
            <div class="skeleton-circle" style="{{ $height ? 'width: ' . $height . '; height: ' . $height : '' }}"></div>
        @elseif($type === 'button')
            <div class="skeleton-button"></div>
        @elseif($type === 'table-row')
            <div class="skeleton-table-row">
                <div class="skeleton-cell"></div>
                <div class="skeleton-cell"></div>
                <div class="skeleton-cell"></div>
                <div class="skeleton-cell"></div>
            </div>
        @elseif($type === 'hero')
            <div class="skeleton-hero">
                <div class="skeleton-hero-title"></div>
                <div class="skeleton-hero-subtitle"></div>
                <div class="skeleton-hero-buttons">
                    <div class="skeleton-button"></div>
                    <div class="skeleton-button"></div>
                </div>
            </div>
        @endif
    @endfor
</div>

@push('styles')
<style>
/* Skeleton Loader Styles */
.skeleton-container {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.skeleton-card,
.skeleton-text-block,
.skeleton-circle,
.skeleton-button,
.skeleton-table-row,
.skeleton-hero {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 2s infinite;
    border-radius: var(--border-radius-md);
}

/* Dark theme support */
[data-theme="dark"] .skeleton-card,
[data-theme="dark"] .skeleton-text-block,
[data-theme="dark"] .skeleton-circle,
[data-theme="dark"] .skeleton-button,
[data-theme="dark"] .skeleton-table-row,
[data-theme="dark"] .skeleton-hero {
    background: linear-gradient(90deg, #2a2a2a 25%, #3a3a3a 50%, #2a2a2a 75%);
    background-size: 200% 100%;
}

/* Card Skeleton */
.skeleton-card {
    width: 300px;
    min-height: 400px;
    padding: 0;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.skeleton-image {
    height: 200px;
    background: linear-gradient(90deg, #e0e0e0 25%, #d0d0d0 50%, #e0e0e0 75%);
    background-size: 200% 100%;
    animation: shimmer 2s infinite;
}

.skeleton-content {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.skeleton-title {
    height: 24px;
    background: linear-gradient(90deg, #e0e0e0 25%, #d0d0d0 50%, #e0e0e0 75%);
    background-size: 200% 100%;
    animation: shimmer 2s infinite;
    border-radius: 4px;
    width: 80%;
}

.skeleton-text {
    height: 16px;
    background: linear-gradient(90deg, #e8e8e8 25%, #d8d8d8 50%, #e8e8e8 75%);
    background-size: 200% 100%;
    animation: shimmer 2s infinite;
    border-radius: 4px;
    width: 100%;
}

.skeleton-text.short {
    width: 60%;
}

.skeleton-price {
    height: 20px;
    background: linear-gradient(90deg, #d0d0d0 25%, #c0c0c0 50%, #d0d0d0 75%);
    background-size: 200% 100%;
    animation: shimmer 2s infinite;
    border-radius: 4px;
    width: 40%;
    margin-top: auto;
}

/* Text Block Skeleton */
.skeleton-text-block {
    height: 16px;
    width: 100%;
    min-width: 100px;
}

/* Circle Skeleton */
.skeleton-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

/* Button Skeleton */
.skeleton-button {
    height: 40px;
    width: 120px;
    border-radius: var(--border-radius-sm);
}

/* Table Row Skeleton */
.skeleton-table-row {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    align-items: center;
}

.skeleton-cell {
    height: 16px;
    flex: 1;
    background: linear-gradient(90deg, #e8e8e8 25%, #d8d8d8 50%, #e8e8e8 75%);
    background-size: 200% 100%;
    animation: shimmer 2s infinite;
    border-radius: 4px;
}

/* Hero Skeleton */
.skeleton-hero {
    padding: 4rem 2rem;
    text-align: center;
    background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
}

.skeleton-hero-title {
    height: 48px;
    background: linear-gradient(90deg, #d0d0d0 25%, #c0c0c0 50%, #d0d0d0 75%);
    background-size: 200% 100%;
    animation: shimmer 2s infinite;
    border-radius: 8px;
    width: 60%;
    margin: 0 auto 2rem;
}

.skeleton-hero-subtitle {
    height: 20px;
    background: linear-gradient(90deg, #e0e0e0 25%, #d0d0d0 50%, #e0e0e0 75%);
    background-size: 200% 100%;
    animation: shimmer 2s infinite;
    border-radius: 4px;
    width: 80%;
    margin: 0 auto 3rem;
}

.skeleton-hero-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* Voltronix Theme Skeleton */
.voltronix-skeleton .skeleton-card,
.voltronix-skeleton .skeleton-text-block,
.voltronix-skeleton .skeleton-circle,
.voltronix-skeleton .skeleton-button,
.voltronix-skeleton .skeleton-table-row {
    background: linear-gradient(90deg, rgba(0, 127, 255, 0.1) 25%, rgba(35, 239, 255, 0.1) 50%, rgba(0, 127, 255, 0.1) 75%);
    background-size: 200% 100%;
    border: 1px solid rgba(0, 127, 255, 0.2);
}

.voltronix-skeleton .skeleton-image,
.voltronix-skeleton .skeleton-title,
.voltronix-skeleton .skeleton-text,
.voltronix-skeleton .skeleton-price,
.voltronix-skeleton .skeleton-cell {
    background: linear-gradient(90deg, rgba(0, 127, 255, 0.15) 25%, rgba(35, 239, 255, 0.15) 50%, rgba(0, 127, 255, 0.15) 75%);
    background-size: 200% 100%;
}

/* Responsive Design */
@media (max-width: 768px) {
    .skeleton-container {
        gap: 1rem;
    }
    
    .skeleton-card {
        width: 100%;
        min-width: 280px;
    }
    
    .skeleton-hero {
        padding: 2rem 1rem;
    }
    
    .skeleton-hero-title {
        width: 80%;
    }
    
    .skeleton-hero-subtitle {
        width: 90%;
    }
    
    .skeleton-hero-buttons {
        flex-direction: column;
        align-items: center;
    }
}

@media (max-width: 576px) {
    .skeleton-table-row {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .skeleton-cell {
        width: 100%;
    }
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
    .skeleton-card,
    .skeleton-text-block,
    .skeleton-circle,
    .skeleton-button,
    .skeleton-table-row,
    .skeleton-hero,
    .skeleton-image,
    .skeleton-title,
    .skeleton-text,
    .skeleton-price,
    .skeleton-cell {
        animation: none;
        background: #f0f0f0;
    }
    
    [data-theme="dark"] .skeleton-card,
    [data-theme="dark"] .skeleton-text-block,
    [data-theme="dark"] .skeleton-circle,
    [data-theme="dark"] .skeleton-button,
    [data-theme="dark"] .skeleton-table-row,
    [data-theme="dark"] .skeleton-hero,
    [data-theme="dark"] .skeleton-image,
    [data-theme="dark"] .skeleton-title,
    [data-theme="dark"] .skeleton-text,
    [data-theme="dark"] .skeleton-price,
    [data-theme="dark"] .skeleton-cell {
        background: #2a2a2a;
    }
}

/* Grid Layouts */
.skeleton-grid {
    display: grid;
    gap: 1.5rem;
}

.skeleton-grid-2 {
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
}

.skeleton-grid-3 {
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
}

.skeleton-grid-4 {
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
}

@media (max-width: 768px) {
    .skeleton-grid-2,
    .skeleton-grid-3,
    .skeleton-grid-4 {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush
