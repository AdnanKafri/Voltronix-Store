<div class="simple-media">
    <div class="main-image">
        @if($product->thumbnail)
            <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                 alt="{{ $product->getTranslation('name') }}"
                 class="img-fluid">
        @else
            <div class="media-placeholder">
                <i class="bi bi-image"></i>
                <span>{{ __('products.no_image') }}</span>
            </div>
        @endif
    </div>
    
    @if($product->media->count() > 0)
    <div class="media-navigation">
        <div class="thumbnail-grid">
            <!-- Main thumbnail -->
            <div class="thumbnail-item active" onclick="changeMainImage('{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : '' }}')">
                @if($product->thumbnail)
                    <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="Main">
                @else
                    <div class="thumbnail-placeholder">
                        <i class="bi bi-image"></i>
                    </div>
                @endif
            </div>
            
            <!-- Additional media -->
            @foreach($product->media->take(7) as $media)
                @if($media->isImage())
                <div class="thumbnail-item" onclick="changeMainImage('{{ $media->media_url }}')">
                    <img src="{{ $media->media_url }}" alt="{{ $media->title }}">
                </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
function changeMainImage(src) {
    if (!src) return;
    
    const mainImg = document.querySelector('.simple-media .main-image img');
    if (mainImg) {
        mainImg.src = src;
    }
    
    // Update active thumbnail
    document.querySelectorAll('.simple-media .thumbnail-item').forEach(thumb => {
        thumb.classList.remove('active');
    });
    event.target.closest('.thumbnail-item').classList.add('active');
}
</script>

<style>
.simple-media .main-image {
    height: 400px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.simple-media .main-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.simple-media .main-image:hover img {
    transform: scale(1.05);
}

.thumbnail-placeholder {
    width: 100%;
    height: 100%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.thumbnail-placeholder i {
    font-size: 1.5rem;
}
</style>
