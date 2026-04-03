<!-- Advanced Product Media Gallery -->
<div class="media-gallery">
    @php
        $galleryImages = $product->media()->ofType('image')->get();
        $beforeImage = $product->media()->ofType('before')->first();
        $afterImage = $product->media()->ofType('after')->first();
        $videos = $product->media()->whereIn('type', ['video', 'youtube'])->get();
        
        $hasGallery = $galleryImages->count() > 0;
        $hasBeforeAfter = $beforeImage && $afterImage;
        $hasVideos = $videos->count() > 0;
        $hasThumbnail = $product->thumbnail;
        
        $mediaTypes = [];
        if ($hasThumbnail) $mediaTypes[] = 'thumbnail';
        if ($hasGallery) $mediaTypes[] = 'gallery';
        if ($hasBeforeAfter) $mediaTypes[] = 'before-after';
        if ($hasVideos) $mediaTypes[] = 'videos';
    @endphp

    @if(count($mediaTypes) > 1)
        <!-- Media Type Tabs -->
        <div class="media-tabs">
            @if($hasThumbnail)
                <button class="media-tab active" onclick="switchMediaTab('thumbnail', this)">
                    <i class="bi bi-image me-2"></i>{{ __('app.products.main_image') }}
                </button>
            @endif
            @if($hasGallery)
                <button class="media-tab" onclick="switchMediaTab('gallery', this)">
                    <i class="bi bi-images me-2"></i>{{ __('app.products.gallery') }} ({{ $galleryImages->count() }})
                </button>
            @endif
            @if($hasBeforeAfter)
                <button class="media-tab" onclick="switchMediaTab('before-after', this)">
                    <i class="bi bi-arrow-left-right me-2"></i>{{ __('app.products.before_after') }}
                </button>
            @endif
            @if($hasVideos)
                <button class="media-tab" onclick="switchMediaTab('videos', this)">
                    <i class="bi bi-play-circle me-2"></i>{{ __('app.products.videos') }} ({{ $videos->count() }})
                </button>
            @endif
        </div>
    @endif

    <!-- Thumbnail/Main Image -->
    <div id="thumbnail-content" class="media-content {{ count($mediaTypes) <= 1 || $hasThumbnail ? 'active' : '' }}">
        <div class="gallery-main">
            @if($product->thumbnail)
                <a href="{{ asset('storage/' . $product->thumbnail) }}" 
                   class="glightbox" 
                   data-gallery="product-gallery"
                   data-title="{{ $product->getTranslation('name') }}">
                    <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                         alt="{{ $product->getTranslation('name') }}" 
                         class="main-image">
                </a>
                <div class="zoom-indicator">
                    <i class="bi bi-zoom-in"></i>
                </div>
            @else
                <div class="no-image-placeholder">
                    <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-2">{{ __('app.products.no_image') }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Image Gallery -->
    @if($hasGallery)
        <div id="gallery-content" class="media-content">
            <div class="gallery-main" id="galleryMain">
                <a href="{{ $galleryImages->first()->media_url }}" 
                   class="glightbox" 
                   data-gallery="product-gallery"
                   data-title="{{ $galleryImages->first()->title ?: $product->getTranslation('name') }}">
                    <img src="{{ $galleryImages->first()->media_url }}" 
                         alt="{{ $galleryImages->first()->title ?: $product->getTranslation('name') }}" 
                         class="main-image">
                </a>
                <div class="zoom-indicator">
                    <i class="bi bi-zoom-in"></i>
                </div>
            </div>
            
            <div class="gallery-thumbnails">
                @foreach($galleryImages as $index => $image)
                    <div class="gallery-thumb {{ $index === 0 ? 'active' : '' }}" 
                         onclick="showGalleryImage('{{ $image->media_url }}', '{{ $image->title ?: $product->getTranslation('name') }}', this)">
                        <img src="{{ $image->media_url }}" alt="{{ $image->title }}">
                    </div>
                    @if($index > 0)
                        <a href="{{ $image->media_url }}" 
                           class="glightbox d-none" 
                           data-gallery="product-gallery"
                           data-title="{{ $image->title ?: $product->getTranslation('name') }}"></a>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    <!-- Before/After Comparison -->
    @if($hasBeforeAfter)
        <div id="before-after-content" class="media-content">
            <div class="before-after-comparison">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="comparison-image-container">
                            <div class="comparison-label">
                                <span class="badge bg-primary">{{ __('app.products.before') }}</span>
                            </div>
                            <div class="comparison-image">
                                <a href="{{ $beforeImage->media_url }}" 
                                   class="glightbox" 
                                   data-gallery="before-after"
                                   data-title="{{ __('app.products.before') }} - {{ $product->getTranslation('name') }}">
                                    <img src="{{ $beforeImage->media_url }}" 
                                         alt="{{ __('app.products.before') }}" 
                                         class="img-fluid rounded">
                                </a>
                                <div class="zoom-indicator">
                                    <i class="bi bi-zoom-in"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="comparison-image-container">
                            <div class="comparison-label">
                                <span class="badge bg-success">{{ __('app.products.after') }}</span>
                            </div>
                            <div class="comparison-image">
                                <a href="{{ $afterImage->media_url }}" 
                                   class="glightbox" 
                                   data-gallery="before-after"
                                   data-title="{{ __('app.products.after') }} - {{ $product->getTranslation('name') }}">
                                    <img src="{{ $afterImage->media_url }}" 
                                         alt="{{ __('app.products.after') }}" 
                                         class="img-fluid rounded">
                                </a>
                                <div class="zoom-indicator">
                                    <i class="bi bi-zoom-in"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Comparison Description -->
                @if($beforeImage->description || $afterImage->description)
                    <div class="comparison-description mt-3">
                        <div class="row">
                            @if($beforeImage->description)
                                <div class="col-md-6">
                                    <p class="text-muted small">{{ $beforeImage->description }}</p>
                                </div>
                            @endif
                            @if($afterImage->description)
                                <div class="col-md-6">
                                    <p class="text-muted small">{{ $afterImage->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Videos -->
    @if($hasVideos)
        <div id="videos-content" class="media-content">
            @foreach($videos as $video)
                <div class="mb-4">
                    @if($video->title)
                        <h6 class="mb-3">{{ $video->title }}</h6>
                    @endif
                    
                    @if($video->type === 'youtube')
                        <div class="youtube-container">
                            <iframe src="https://www.youtube.com/embed/{{ $video->youtube_id }}" 
                                    frameborder="0" 
                                    allowfullscreen
                                    title="{{ $video->title ?: $product->getTranslation('name') }}">
                            </iframe>
                        </div>
                    @else
                        <div class="video-container">
                            <video controls 
                                   @if(isset($video->metadata['poster']))
                                       poster="{{ asset('storage/' . $video->metadata['poster']) }}"
                                   @endif>
                                <source src="{{ $video->media_url }}" type="video/mp4">
                                {{ __('app.products.video_not_supported') }}
                            </video>
                        </div>
                    @endif
                    
                    @if($video->description)
                        <p class="text-muted mt-2">{{ $video->description }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    @if(!$hasThumbnail && !$hasGallery && !$hasBeforeAfter && !$hasVideos)
        <!-- No Media Placeholder -->
        <div class="gallery-main">
            <div class="no-image-placeholder">
                <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                <p class="text-muted mt-2">{{ __('app.products.no_media') }}</p>
            </div>
        </div>
    @endif
</div>
