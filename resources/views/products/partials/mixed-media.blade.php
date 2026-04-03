<div class="mixed-media">
    <div class="media-header">
        <i class="bi bi-collection me-2"></i>
        {{ __('products.mixed_media') }}
    </div>
    
    <div class="mixed-content">
        @php
            $allMedia = $product->media;
            $images = $allMedia->where('type', 'image');
            $videos = $allMedia->whereIn('type', ['video', 'youtube']);
            $beforeAfter = $product->getBeforeAfterImages();
        @endphp
        
        <!-- Main Featured Media -->
        @if($product->getFeaturedMedia())
            @php $featuredMedia = $product->getFeaturedMedia(); @endphp
            <div class="featured-media">
                <h6 class="section-title">{{ __('products.featured_content') }}</h6>
                
                @if($featuredMedia->isImage())
                    <div class="featured-image">
                        <img src="{{ $featuredMedia->media_url }}" 
                             alt="{{ $featuredMedia->title }}"
                             onclick="openMediaModal('{{ $featuredMedia->media_url }}', 'image')">
                    </div>
                @elseif($featuredMedia->isVideo())
                    <div class="featured-video">
                        @if($featuredMedia->type === 'youtube')
                            <div class="video-wrapper">
                                <iframe 
                                    src="https://www.youtube.com/embed/{{ $featuredMedia->youtube_id }}?rel=0&showinfo=0" 
                                    frameborder="0" 
                                    allowfullscreen>
                                </iframe>
                            </div>
                        @else
                            <video controls class="video-element">
                                <source src="{{ $featuredMedia->media_url }}" type="video/mp4">
                            </video>
                        @endif
                    </div>
                @endif
                
                @if($featuredMedia->title || $featuredMedia->description)
                <div class="media-info">
                    @if($featuredMedia->title)
                    <h6>{{ $featuredMedia->title }}</h6>
                    @endif
                    @if($featuredMedia->description)
                    <p>{{ $featuredMedia->description }}</p>
                    @endif
                </div>
                @endif
            </div>
        @endif
        
        <!-- Image Gallery Section -->
        @if($images->count() > 0)
        <div class="media-section">
            <h6 class="section-title">
                <i class="bi bi-images me-2"></i>
                {{ __('products.gallery') }} ({{ $images->count() }})
            </h6>
            <div class="media-grid">
                @foreach($images->take(6) as $image)
                <div class="media-item image-item" onclick="openMediaModal('{{ $image->media_url }}', 'image')">
                    <img src="{{ $image->media_url }}" alt="{{ $image->title }}">
                    <div class="media-overlay">
                        <i class="bi bi-zoom-in"></i>
                    </div>
                    @if($image->title)
                    <div class="media-caption">{{ $image->title }}</div>
                    @endif
                </div>
                @endforeach
                
                @if($images->count() > 6)
                <div class="media-item more-items" onclick="showAllImages()">
                    <div class="more-content">
                        <i class="bi bi-plus-lg"></i>
                        <span>+{{ $images->count() - 6 }} {{ __('products.more') }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Before/After Section -->
        @if($beforeAfter['before'] && $beforeAfter['after'])
        <div class="media-section">
            <h6 class="section-title">
                <i class="bi bi-arrow-left-right me-2"></i>
                {{ __('products.before_after') }}
            </h6>
            <div class="mini-comparison" onclick="openComparisonModal()">
                <div class="comparison-preview">
                    <div class="before-preview">
                        <img src="{{ $beforeAfter['before']->media_url }}" alt="{{ __('products.before') }}">
                        <span class="preview-label">{{ __('products.before') }}</span>
                    </div>
                    <div class="after-preview">
                        <img src="{{ $beforeAfter['after']->media_url }}" alt="{{ __('products.after') }}">
                        <span class="preview-label">{{ __('products.after') }}</span>
                    </div>
                </div>
                <div class="comparison-cta">
                    <i class="bi bi-arrows-expand me-2"></i>
                    {{ __('products.view_comparison') }}
                </div>
            </div>
        </div>
        @endif
        
        <!-- Video Section -->
        @if($videos->count() > 0)
        <div class="media-section">
            <h6 class="section-title">
                <i class="bi bi-play-circle me-2"></i>
                {{ __('products.videos') }} ({{ $videos->count() }})
            </h6>
            <div class="video-grid">
                @foreach($videos->take(3) as $video)
                <div class="video-preview" onclick="openVideoModal('{{ $video->type }}', '{{ $video->type === 'youtube' ? $video->youtube_id : $video->media_url }}', '{{ $video->title }}')">
                    <div class="video-thumbnail">
                        @if($video->type === 'youtube')
                            <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/mqdefault.jpg" alt="{{ $video->title }}">
                        @else
                            <div class="video-thumb-placeholder">
                                <i class="bi bi-play-fill"></i>
                            </div>
                        @endif
                        <div class="play-button">
                            <i class="bi bi-play-fill"></i>
                        </div>
                    </div>
                    <div class="video-title">{{ $video->title ?: __('products.video') }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Media Modal -->
<div class="media-modal" id="mediaModal" onclick="closeMediaModal()">
    <div class="modal-content" onclick="event.stopPropagation()">
        <button class="modal-close" onclick="closeMediaModal()">
            <i class="bi bi-x-lg"></i>
        </button>
        <div class="modal-body" id="modalBody">
            <!-- Content will be inserted here -->
        </div>
    </div>
</div>

<script>
function openMediaModal(url, type) {
    const modal = document.getElementById('mediaModal');
    const modalBody = document.getElementById('modalBody');
    
    let content = '';
    if (type === 'image') {
        content = `<img src="${url}" alt="" style="max-width: 100%; max-height: 90vh; object-fit: contain;">`;
    }
    
    modalBody.innerHTML = content;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function openVideoModal(type, url, title) {
    const modal = document.getElementById('mediaModal');
    const modalBody = document.getElementById('modalBody');
    
    let content = '';
    if (type === 'youtube') {
        content = `
            <div class="video-wrapper">
                <iframe 
                    src="https://www.youtube.com/embed/${url}?autoplay=1&rel=0&showinfo=0" 
                    frameborder="0" 
                    allowfullscreen
                    title="${title}">
                </iframe>
            </div>
        `;
    } else {
        content = `
            <video controls autoplay style="max-width: 100%; max-height: 90vh;">
                <source src="${url}" type="video/mp4">
            </video>
        `;
    }
    
    modalBody.innerHTML = content;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function openComparisonModal() {
    // This would open the before/after comparison in a modal
    // For now, we'll just show an alert
    Swal.fire({
        title: '{{ __("products.before_after") }}',
        text: '{{ __("products.comparison_feature_coming_soon") }}',
        icon: 'info',
        confirmButtonColor: '#007fff'
    });
}

function closeMediaModal() {
    const modal = document.getElementById('mediaModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    
    // Stop any videos
    const modalBody = document.getElementById('modalBody');
    modalBody.innerHTML = '';
}

function showAllImages() {
    // This would show all images in a gallery modal
    Swal.fire({
        title: '{{ __("products.gallery") }}',
        text: '{{ __("products.full_gallery_coming_soon") }}',
        icon: 'info',
        confirmButtonColor: '#007fff'
    });
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('mediaModal');
    if (modal.style.display === 'flex' && e.key === 'Escape') {
        closeMediaModal();
    }
});
</script>

<style>
.mixed-media .mixed-content {
    padding: 1.5rem;
}

.section-title {
    color: #1a1a1a;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid rgba(0, 127, 255, 0.1);
    font-family: 'Orbitron', monospace;
}

.media-section {
    margin-bottom: 2rem;
}

.media-section:last-child {
    margin-bottom: 0;
}

/* Featured Media */
.featured-media {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid rgba(0, 127, 255, 0.1);
}

.featured-image {
    border-radius: 15px;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.featured-image:hover {
    transform: scale(1.02);
}

.featured-image img {
    width: 100%;
    height: auto;
    max-height: 400px;
    object-fit: cover;
}

.featured-video .video-wrapper {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%;
    border-radius: 15px;
    overflow: hidden;
}

.featured-video iframe,
.featured-video .video-element {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

/* Media Grid */
.media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.media-item {
    position: relative;
    aspect-ratio: 1;
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
}

.media-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 127, 255, 0.2);
}

.media-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.media-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.media-item:hover .media-overlay {
    opacity: 1;
}

.media-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
    color: white;
    padding: 1rem 0.75rem 0.75rem;
    font-size: 0.85rem;
    font-weight: 500;
}

.more-items {
    background: linear-gradient(135deg, #007fff, #23efff);
    display: flex;
    align-items: center;
    justify-content: center;
}

.more-content {
    text-align: center;
    color: white;
    font-weight: 600;
}

.more-content i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    display: block;
}

/* Before/After Preview */
.mini-comparison {
    border: 2px solid rgba(0, 127, 255, 0.2);
    border-radius: 15px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
}

.mini-comparison:hover {
    border-color: #007fff;
    box-shadow: 0 5px 20px rgba(0, 127, 255, 0.2);
}

.comparison-preview {
    display: grid;
    grid-template-columns: 1fr 1fr;
    height: 200px;
}

.before-preview,
.after-preview {
    position: relative;
    overflow: hidden;
}

.before-preview img,
.after-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preview-label {
    position: absolute;
    top: 0.5rem;
    left: 0.5rem;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
}

.comparison-cta {
    padding: 1rem;
    text-align: center;
    background: rgba(0, 127, 255, 0.05);
    color: #007fff;
    font-weight: 600;
}

/* Video Grid */
.video-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.video-preview {
    cursor: pointer;
    transition: transform 0.3s ease;
}

.video-preview:hover {
    transform: translateY(-5px);
}

.video-thumbnail {
    position: relative;
    aspect-ratio: 16/9;
    border-radius: 10px;
    overflow: hidden;
    background: #000;
}

.video-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.video-thumb-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 2rem;
}

.play-button {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 60px;
    height: 60px;
    background: rgba(0, 0, 0, 0.7);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    transition: all 0.3s ease;
}

.video-preview:hover .play-button {
    background: rgba(0, 127, 255, 0.9);
    transform: translate(-50%, -50%) scale(1.1);
}

.video-title {
    padding: 0.75rem 0;
    font-weight: 500;
    color: #1a1a1a;
    text-align: center;
}

/* Media Modal */
.media-modal {
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
    padding: 2rem;
}

.modal-content {
    position: relative;
    max-width: 90%;
    max-height: 90%;
    background: white;
    border-radius: 15px;
    overflow: hidden;
}

.modal-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
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
    z-index: 10;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: rgba(220, 53, 69, 0.8);
}

.modal-body {
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-body .video-wrapper {
    width: 80vw;
    max-width: 800px;
    height: 0;
    padding-bottom: 45vw;
    max-height: 450px;
}

@media (max-width: 768px) {
    .mixed-content {
        padding: 1rem;
    }
    
    .media-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .video-grid {
        grid-template-columns: 1fr;
    }
    
    .comparison-preview {
        height: 150px;
    }
    
    .media-modal {
        padding: 1rem;
    }
}
</style>
