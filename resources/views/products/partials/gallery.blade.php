<div class="gallery-media">
    <div class="media-header">
        <i class="bi bi-images me-2"></i>
        {{ __('products.gallery') }}
    </div>
    
    <div class="gallery-main">
        <div class="main-image" id="galleryMainImage">
            @if($product->thumbnail)
                <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                     alt="{{ $product->getTranslation('name') }}"
                     class="img-fluid main-gallery-image"
                     onclick="openLightbox(this.src)">
            @else
                <div class="media-placeholder">
                    <i class="bi bi-images"></i>
                    <span>{{ __('products.no_images') }}</span>
                </div>
            @endif
        </div>
        
        <div class="gallery-controls">
            <button class="gallery-btn" onclick="previousImage()" title="{{ __('products.previous') }}">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="gallery-btn" onclick="nextImage()" title="{{ __('products.next') }}">
                <i class="bi bi-chevron-right"></i>
            </button>
            <button class="gallery-btn" onclick="openLightbox()" title="{{ __('products.fullscreen') }}">
                <i class="bi bi-arrows-fullscreen"></i>
            </button>
        </div>
    </div>
    
    @if($product->getGalleryImages()->count() > 0 || $product->thumbnail)
    <div class="media-navigation">
        <div class="thumbnail-grid">
            <!-- Main thumbnail -->
            @if($product->thumbnail)
            <div class="thumbnail-item active" data-image="{{ asset('storage/' . $product->thumbnail) }}" onclick="selectGalleryImage(this)">
                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="Main">
            </div>
            @endif
            
            <!-- Gallery images -->
            @foreach($product->getGalleryImages() as $image)
            <div class="thumbnail-item" data-image="{{ $image->media_url }}" onclick="selectGalleryImage(this)">
                <img src="{{ $image->media_url }}" alt="{{ $image->title }}">
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Lightbox Modal -->
<div class="lightbox-modal" id="lightboxModal" onclick="closeLightbox()">
    <div class="lightbox-content">
        <img src="" alt="" id="lightboxImage">
        <button class="lightbox-close" onclick="closeLightbox()">
            <i class="bi bi-x-lg"></i>
        </button>
        <button class="lightbox-prev" onclick="lightboxPrevious()">
            <i class="bi bi-chevron-left"></i>
        </button>
        <button class="lightbox-next" onclick="lightboxNext()">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>
</div>

<script>
let currentImageIndex = 0;
let galleryImages = [];

document.addEventListener('DOMContentLoaded', function() {
    // Collect all gallery images
    const thumbnails = document.querySelectorAll('.gallery-media .thumbnail-item');
    galleryImages = Array.from(thumbnails).map(thumb => thumb.dataset.image);
});

function selectGalleryImage(thumbnail) {
    const imageUrl = thumbnail.dataset.image;
    const mainImg = document.querySelector('.main-gallery-image');
    
    if (mainImg) {
        mainImg.src = imageUrl;
    }
    
    // Update active thumbnail
    document.querySelectorAll('.gallery-media .thumbnail-item').forEach(thumb => {
        thumb.classList.remove('active');
    });
    thumbnail.classList.add('active');
    
    // Update current index
    currentImageIndex = Array.from(document.querySelectorAll('.gallery-media .thumbnail-item')).indexOf(thumbnail);
}

function previousImage() {
    if (galleryImages.length === 0) return;
    
    currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
    updateMainImage();
}

function nextImage() {
    if (galleryImages.length === 0) return;
    
    currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
    updateMainImage();
}

function updateMainImage() {
    const mainImg = document.querySelector('.main-gallery-image');
    const thumbnails = document.querySelectorAll('.gallery-media .thumbnail-item');
    
    if (mainImg && galleryImages[currentImageIndex]) {
        mainImg.src = galleryImages[currentImageIndex];
        
        // Update active thumbnail
        thumbnails.forEach((thumb, index) => {
            thumb.classList.toggle('active', index === currentImageIndex);
        });
    }
}

function openLightbox(imageSrc = null) {
    const modal = document.getElementById('lightboxModal');
    const lightboxImg = document.getElementById('lightboxImage');
    const mainImg = document.querySelector('.main-gallery-image');
    
    const srcToUse = imageSrc || (mainImg ? mainImg.src : '');
    
    if (srcToUse) {
        lightboxImg.src = srcToUse;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeLightbox() {
    const modal = document.getElementById('lightboxModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function lightboxPrevious() {
    previousImage();
    const mainImg = document.querySelector('.main-gallery-image');
    if (mainImg) {
        document.getElementById('lightboxImage').src = mainImg.src;
    }
}

function lightboxNext() {
    nextImage();
    const mainImg = document.querySelector('.main-gallery-image');
    if (mainImg) {
        document.getElementById('lightboxImage').src = mainImg.src;
    }
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('lightboxModal');
    if (modal.style.display === 'flex') {
        switch(e.key) {
            case 'Escape':
                closeLightbox();
                break;
            case 'ArrowLeft':
                lightboxPrevious();
                break;
            case 'ArrowRight':
                lightboxNext();
                break;
        }
    }
});
</script>

<style>
.gallery-media .gallery-main {
    position: relative;
}

.gallery-media .main-image {
    height: 400px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    cursor: pointer;
}

.gallery-media .main-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-media .main-image:hover img {
    transform: scale(1.02);
}

.gallery-controls {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    transform: translateY(-50%);
    display: flex;
    justify-content: space-between;
    padding: 0 1rem;
    pointer-events: none;
}

.gallery-btn {
    background: rgba(0, 0, 0, 0.7);
    color: white;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    pointer-events: auto;
    opacity: 0;
}

.gallery-main:hover .gallery-btn {
    opacity: 1;
}

.gallery-btn:hover {
    background: rgba(0, 127, 255, 0.8);
    transform: scale(1.1);
}

.gallery-controls .gallery-btn:last-child {
    position: absolute;
    top: 1rem;
    right: 1rem;
    transform: none;
}

/* Lightbox Styles */
.lightbox-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.lightbox-content {
    position: relative;
    max-width: 90%;
    max-height: 90%;
}

.lightbox-content img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.lightbox-close,
.lightbox-prev,
.lightbox-next {
    position: absolute;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: none;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1.2rem;
}

.lightbox-close {
    top: -60px;
    right: 0;
}

.lightbox-prev {
    left: -60px;
    top: 50%;
    transform: translateY(-50%);
}

.lightbox-next {
    right: -60px;
    top: 50%;
    transform: translateY(-50%);
}

.lightbox-close:hover,
.lightbox-prev:hover,
.lightbox-next:hover {
    background: rgba(0, 127, 255, 0.8);
    transform: scale(1.1);
}

.lightbox-prev:hover {
    transform: translateY(-50%) scale(1.1);
}

.lightbox-next:hover {
    transform: translateY(-50%) scale(1.1);
}

@media (max-width: 768px) {
    .lightbox-close {
        top: 10px;
        right: 10px;
    }
    
    .lightbox-prev {
        left: 10px;
        bottom: 10px;
        top: auto;
        transform: none;
    }
    
    .lightbox-next {
        right: 10px;
        bottom: 10px;
        top: auto;
        transform: none;
    }
    
    .lightbox-prev:hover,
    .lightbox-next:hover {
        transform: scale(1.1);
    }
}
</style>
