<div class="before-after-media">
    <div class="media-header">
        <i class="bi bi-arrow-left-right me-2"></i>
        {{ __('products.before_after') }}
    </div>
    
    <div class="comparison-container">
        @php
            $beforeAfter = $product->getBeforeAfterImages();
            $beforeImage = $beforeAfter['before'];
            $afterImage = $beforeAfter['after'];
        @endphp
        
        @if($beforeImage && $afterImage)
        <div class="image-comparison" id="imageComparison">
            <div class="comparison-image before-image">
                <img src="{{ $beforeImage->media_url }}" alt="{{ __('products.before') }}">
                <div class="image-label before-label">{{ __('products.before') }}</div>
            </div>
            <div class="comparison-image after-image">
                <img src="{{ $afterImage->media_url }}" alt="{{ __('products.after') }}">
                <div class="image-label after-label">{{ __('products.after') }}</div>
            </div>
            <div class="comparison-slider" id="comparisonSlider">
                <div class="slider-button">
                    <i class="bi bi-arrows-expand"></i>
                </div>
            </div>
        </div>
        
        <div class="comparison-instructions">
            <i class="bi bi-hand-index me-2"></i>
            {{ __('products.drag_to_compare') }}
        </div>
        @else
        <div class="media-placeholder">
            <i class="bi bi-arrow-left-right"></i>
            <span>{{ __('products.no_comparison_images') }}</span>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const comparison = document.getElementById('imageComparison');
    const slider = document.getElementById('comparisonSlider');
    
    if (!comparison || !slider) return;
    
    let isSliding = false;
    
    function updateComparison(percentage) {
        const afterImage = comparison.querySelector('.after-image');
        afterImage.style.clipPath = `inset(0 ${100 - percentage}% 0 0)`;
        slider.style.left = `${percentage}%`;
    }
    
    function handleMove(clientX) {
        if (!isSliding) return;
        
        const rect = comparison.getBoundingClientRect();
        const percentage = Math.max(0, Math.min(100, ((clientX - rect.left) / rect.width) * 100));
        updateComparison(percentage);
    }
    
    // Mouse events
    slider.addEventListener('mousedown', function(e) {
        isSliding = true;
        e.preventDefault();
    });
    
    document.addEventListener('mousemove', function(e) {
        handleMove(e.clientX);
    });
    
    document.addEventListener('mouseup', function() {
        isSliding = false;
    });
    
    // Touch events
    slider.addEventListener('touchstart', function(e) {
        isSliding = true;
        e.preventDefault();
    });
    
    document.addEventListener('touchmove', function(e) {
        if (e.touches.length > 0) {
            handleMove(e.touches[0].clientX);
        }
    });
    
    document.addEventListener('touchend', function() {
        isSliding = false;
    });
    
    // Click to position
    comparison.addEventListener('click', function(e) {
        if (e.target === slider || slider.contains(e.target)) return;
        
        const rect = comparison.getBoundingClientRect();
        const percentage = ((e.clientX - rect.left) / rect.width) * 100;
        updateComparison(percentage);
    });
    
    // Initialize at 50%
    updateComparison(50);
});
</script>

<style>
.before-after-media .comparison-container {
    padding: 1.5rem;
}

.image-comparison {
    position: relative;
    width: 100%;
    height: 400px;
    overflow: hidden;
    border-radius: 15px;
    cursor: pointer;
    user-select: none;
}

.comparison-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.comparison-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.after-image {
    clip-path: inset(0 50% 0 0);
    transition: clip-path 0.1s ease;
}

.image-label {
    position: absolute;
    top: 1rem;
    padding: 0.5rem 1rem;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
    backdrop-filter: blur(10px);
}

.before-label {
    left: 1rem;
}

.after-label {
    right: 1rem;
}

.comparison-slider {
    position: absolute;
    top: 0;
    left: 50%;
    width: 4px;
    height: 100%;
    background: white;
    cursor: ew-resize;
    transform: translateX(-50%);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}

.slider-button {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    color: #007fff;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.slider-button:hover {
    background: #007fff;
    color: white;
    transform: translate(-50%, -50%) scale(1.1);
}

.comparison-instructions {
    text-align: center;
    margin-top: 1rem;
    color: #6c757d;
    font-size: 0.9rem;
    padding: 0.75rem;
    background: rgba(0, 127, 255, 0.05);
    border-radius: 10px;
    border: 1px solid rgba(0, 127, 255, 0.1);
}

@media (max-width: 768px) {
    .image-comparison {
        height: 300px;
    }
    
    .slider-button {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }
    
    .image-label {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
    }
}

/* Prevent text selection during dragging */
.image-comparison * {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
</style>
