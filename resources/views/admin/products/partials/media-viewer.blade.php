@php
    $extractYoutubeId = static function (?string $url): ?string {
        if (!$url || !is_string($url)) {
            return null;
        }
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $matches);
        return $matches[1] ?? null;
    };

    $isImagePath = static function (?string $path): bool {
        if (!$path || !is_string($path)) {
            return false;
        }
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'], true);
    };

    $isVideoPath = static function (?string $path): bool {
        if (!$path || !is_string($path)) {
            return false;
        }
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($ext, ['mp4', 'mov', 'avi', 'wmv', 'm4v', 'webm', 'ogg'], true);
    };

    $galleryImages = collect();
    $beforeImageUrl = null;
    $afterImageUrl = null;
    $videoFiles = collect();
    $youtubeVideos = collect();

    if ($product->thumbnail_url) {
        $galleryImages->push([
            'url' => $product->thumbnail_url,
            'title' => __('admin.product.thumbnail'),
        ]);
    }

    foreach ($product->media as $media) {
        if ($media->type === 'youtube') {
            if ($media->url) {
                $youtubeVideos->push([
                    'url' => $media->url,
                    'title' => $media->title ?: __('admin.product.youtube_url'),
                    'id' => $extractYoutubeId($media->url),
                ]);
            }
            continue;
        }

        if ($media->type === 'before') {
            $beforeImageUrl = $media->media_url ?: $beforeImageUrl;
            continue;
        }

        if ($media->type === 'after') {
            $afterImageUrl = $media->media_url ?: $afterImageUrl;
            continue;
        }

        if ($media->type === 'video' || $isVideoPath($media->path)) {
            $videoFiles->push([
                'url' => $media->media_url,
                'title' => $media->title ?: __('admin.product.video_upload'),
                'poster' => isset($media->metadata['poster']) ? $product->resolveMediaUrl($media->metadata['poster']) : null,
            ]);
            continue;
        }

        if ($media->type === 'image' || $isImagePath($media->path)) {
            $galleryImages->push([
                'url' => $media->media_url,
                'title' => $media->title ?: __('admin.product.gallery_images'),
            ]);
        }
    }

    $legacyData = is_array($product->media_data) ? $product->media_data : [];

    if (isset($legacyData['images']) && is_array($legacyData['images'])) {
        foreach ($legacyData['images'] as $legacyImage) {
            $legacyUrl = $product->resolveMediaUrl($legacyImage['path'] ?? null, '');
            if ($legacyUrl) {
                $galleryImages->push([
                    'url' => $legacyUrl,
                    'title' => __('admin.product.gallery_images'),
                ]);
            }
        }
    }

    if (!$beforeImageUrl && !empty($legacyData['before_image'])) {
        $beforeImageUrl = $product->resolveMediaUrl($legacyData['before_image'], '');
    }
    if (!$afterImageUrl && !empty($legacyData['after_image'])) {
        $afterImageUrl = $product->resolveMediaUrl($legacyData['after_image'], '');
    }

    if (!empty($legacyData['video_file'])) {
        $legacyVideoUrl = $product->resolveMediaUrl($legacyData['video_file'], '');
        if ($legacyVideoUrl) {
            $videoFiles->push([
                'url' => $legacyVideoUrl,
                'title' => __('admin.product.video_upload'),
                'poster' => !empty($legacyData['video_poster']) ? $product->resolveMediaUrl($legacyData['video_poster']) : null,
            ]);
        }
    }

    if (!empty($legacyData['youtube_url'])) {
        $youtubeVideos->push([
            'url' => $legacyData['youtube_url'],
            'title' => __('admin.product.youtube_url'),
            'id' => $extractYoutubeId($legacyData['youtube_url']),
        ]);
    }

    $galleryImages = $galleryImages->filter(fn ($item) => !empty($item['url']))->unique('url')->values();
    $videoFiles = $videoFiles->filter(fn ($item) => !empty($item['url']))->unique('url')->values();
    $youtubeVideos = $youtubeVideos->filter(fn ($item) => !empty($item['url']))->unique('url')->values();

    $hasBeforeAfter = $beforeImageUrl || $afterImageUrl;
    $hasAnyMedia = $galleryImages->isNotEmpty() || $hasBeforeAfter || $videoFiles->isNotEmpty() || $youtubeVideos->isNotEmpty();
    $defaultTab = $galleryImages->isNotEmpty()
        ? 'gallery'
        : ($hasBeforeAfter ? 'beforeafter' : ($videoFiles->isNotEmpty() ? 'video' : 'youtube'));
@endphp

<div class="media-card">
    <div class="card-header-modern">
        <h5 class="card-title-modern">
            <i class="bi bi-images"></i>
            {{ __('admin.product.media_type') }}
        </h5>
    </div>

    @if($hasAnyMedia)
        <div class="card-body-modern">
            <div class="admin-media-tabs">
                @if($galleryImages->isNotEmpty())
                    <button type="button" class="admin-media-tab {{ $defaultTab === 'gallery' ? 'active' : '' }}" data-target="gallery">
                        <i class="bi bi-images"></i> {{ __('admin.product.gallery_images') }}
                    </button>
                @endif
                @if($hasBeforeAfter)
                    <button type="button" class="admin-media-tab {{ $defaultTab === 'beforeafter' ? 'active' : '' }}" data-target="beforeafter">
                        <i class="bi bi-arrow-left-right"></i> {{ __('admin.product.before_image') }} / {{ __('admin.product.after_image') }}
                    </button>
                @endif
                @if($videoFiles->isNotEmpty())
                    <button type="button" class="admin-media-tab {{ $defaultTab === 'video' ? 'active' : '' }}" data-target="video">
                        <i class="bi bi-file-play"></i> {{ __('admin.product.video_upload') }}
                    </button>
                @endif
                @if($youtubeVideos->isNotEmpty())
                    <button type="button" class="admin-media-tab {{ $defaultTab === 'youtube' ? 'active' : '' }}" data-target="youtube">
                        <i class="bi bi-youtube"></i> {{ __('admin.product.youtube_url') }}
                    </button>
                @endif
            </div>

            @if($galleryImages->isNotEmpty())
                <div class="admin-media-panel {{ $defaultTab === 'gallery' ? 'active' : '' }}" data-panel="gallery">
                    <div class="admin-media-main">
                        <img
                            src="{{ $galleryImages->first()['url'] }}"
                            alt="{{ $product->getTranslation('name') }}"
                            class="admin-media-main-image"
                            data-main-image
                            onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';"
                            onclick="openImageModal(this.src)"
                        >
                    </div>
                    <div class="admin-media-thumbnails">
                        @foreach($galleryImages as $index => $image)
                            <button
                                type="button"
                                class="admin-media-thumb {{ $index === 0 ? 'active' : '' }}"
                                data-image-url="{{ $image['url'] }}"
                                data-image-title="{{ $image['title'] }}"
                            >
                                <img src="{{ $image['url'] }}" alt="{{ $image['title'] }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';">
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($hasBeforeAfter)
                <div class="admin-media-panel {{ $defaultTab === 'beforeafter' ? 'active' : '' }}" data-panel="beforeafter">
                    <div class="before-after-container">
                        <div class="comparison-item">
                            <div class="comparison-label">
                                <span class="comparison-badge before">{{ __('admin.product.before_image') }}</span>
                            </div>
                            @if($beforeImageUrl)
                                <img src="{{ $beforeImageUrl }}" alt="{{ __('admin.product.before_image') }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';" onclick="openImageModal(this.src)">
                            @else
                                <div class="media-placeholder"><i class="bi bi-image"></i></div>
                            @endif
                        </div>
                        <div class="comparison-item">
                            <div class="comparison-label">
                                <span class="comparison-badge after">{{ __('admin.product.after_image') }}</span>
                            </div>
                            @if($afterImageUrl)
                                <img src="{{ $afterImageUrl }}" alt="{{ __('admin.product.after_image') }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';" onclick="openImageModal(this.src)">
                            @else
                                <div class="media-placeholder"><i class="bi bi-image"></i></div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if($videoFiles->isNotEmpty())
                <div class="admin-media-panel {{ $defaultTab === 'video' ? 'active' : '' }}" data-panel="video">
                    <div class="admin-media-video-grid">
                        @foreach($videoFiles as $video)
                            <div class="admin-video-card">
                                <video controls preload="metadata" @if(!empty($video['poster'])) poster="{{ $video['poster'] }}" @endif>
                                    <source src="{{ $video['url'] }}" type="video/mp4">
                                </video>
                                <p class="text-muted small mb-0 mt-2">{{ $video['title'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($youtubeVideos->isNotEmpty())
                <div class="admin-media-panel {{ $defaultTab === 'youtube' ? 'active' : '' }}" data-panel="youtube">
                    <div class="admin-media-video-grid">
                        @foreach($youtubeVideos as $youtube)
                            <div class="admin-video-card">
                                @if($youtube['id'])
                                    <div class="admin-youtube-wrapper">
                                        <iframe
                                            src="https://www.youtube.com/embed/{{ $youtube['id'] }}"
                                            title="{{ $youtube['title'] }}"
                                            frameborder="0"
                                            allowfullscreen
                                        ></iframe>
                                    </div>
                                @else
                                    <div class="alert alert-warning mb-2">
                                        {{ __('admin.product.invalid_youtube_url') }}
                                    </div>
                                    <a href="{{ $youtube['url'] }}" target="_blank" class="small">{{ $youtube['url'] }}</a>
                                @endif
                                <p class="text-muted small mb-0 mt-2">{{ $youtube['title'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="card-body-modern text-center py-5">
            <div class="media-placeholder">
                <i class="bi bi-image"></i>
            </div>
            <p class="text-muted mt-3">{{ __('admin.product.no_media') }}</p>
        </div>
    @endif
</div>
