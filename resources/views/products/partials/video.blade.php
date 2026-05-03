<div class="video-media">
    <div class="media-header">
        <i class="bi bi-play-circle me-2"></i>
        {{ __('products.video_preview') }}
    </div>
    
    <div class="video-container">
        @php
            $videos = $product->getVideos();
            $mainVideo = $videos->first();
        @endphp
        
        @if($mainVideo)
            @if($mainVideo->type === 'youtube')
                <div class="youtube-player">
                    <div class="video-wrapper">
                        <iframe 
                            src="https://www.youtube.com/embed/{{ $mainVideo->youtube_id }}?rel=0&showinfo=0&modestbranding=1" 
                            frameborder="0" 
                            allowfullscreen
                            title="{{ $mainVideo->title ?? $product->getTranslation('name') }}">
                        </iframe>
                    </div>
                </div>
            @else
                <div class="html5-player">
                    <video 
                        controls 
                        preload="metadata"
                        poster="{{ $product->thumbnail ? $product->thumbnail_url : '' }}"
                        class="video-element">
                        <source src="{{ $mainVideo->media_url }}" type="video/mp4">
                        {{ __('products.video_not_supported') }}
                    </video>
                </div>
            @endif
            
            @if($mainVideo->title || $mainVideo->description)
            <div class="video-info">
                @if($mainVideo->title)
                <h6 class="video-title">{{ $mainVideo->title }}</h6>
                @endif
                @if($mainVideo->description)
                <p class="video-description">{{ $mainVideo->description }}</p>
                @endif
            </div>
            @endif
        @else
            <div class="media-placeholder">
                <i class="bi bi-play-circle"></i>
                <span>{{ __('products.no_video') }}</span>
            </div>
        @endif
        
        @if($videos->count() > 1)
        <div class="video-playlist">
            <h6 class="playlist-title">{{ __('products.more_videos') }}</h6>
            <div class="playlist-items">
                @foreach($videos->skip(1) as $video)
                <div class="playlist-item" onclick="switchVideo(this)" 
                     data-type="{{ $video->type }}"
                     data-url="{{ $video->type === 'youtube' ? $video->youtube_id : $video->media_url }}"
                     data-title="{{ $video->title }}">
                    <div class="playlist-thumbnail">
                        @if($video->type === 'youtube')
                            <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/mqdefault.jpg" alt="{{ $video->title }}">
                        @else
                            <div class="video-thumb-placeholder">
                                <i class="bi bi-play-fill"></i>
                            </div>
                        @endif
                        <div class="play-overlay">
                            <i class="bi bi-play-fill"></i>
                        </div>
                    </div>
                    <div class="playlist-info">
                        <div class="playlist-item-title">{{ $video->title ?: __('products.video') }}</div>
                        @if($video->description)
                        <div class="playlist-item-desc">{{ Str::limit($video->description, 60) }}</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function switchVideo(playlistItem) {
    const type = playlistItem.dataset.type;
    const url = playlistItem.dataset.url;
    const title = playlistItem.dataset.title;
    
    const container = document.querySelector('.video-container');
    let playerHtml = '';
    
    if (type === 'youtube') {
        playerHtml = `
            <div class="youtube-player">
                <div class="video-wrapper">
                    <iframe 
                        src="https://www.youtube.com/embed/${url}?rel=0&showinfo=0&modestbranding=1&autoplay=1" 
                        frameborder="0" 
                        allowfullscreen
                        title="${title}">
                    </iframe>
                </div>
            </div>
        `;
    } else {
        playerHtml = `
            <div class="html5-player">
                <video 
                    controls 
                    autoplay
                    preload="metadata"
                    class="video-element">
                    <source src="${url}" type="video/mp4">
                    {{ __('products.video_not_supported') }}
                </video>
            </div>
        `;
    }
    
    // Replace the current player
    const currentPlayer = container.querySelector('.youtube-player, .html5-player');
    if (currentPlayer) {
        currentPlayer.outerHTML = playerHtml;
    }
    
    // Update active state
    document.querySelectorAll('.playlist-item').forEach(item => {
        item.classList.remove('active');
    });
    playlistItem.classList.add('active');
    
    // Scroll to top of video
    container.scrollIntoView({ behavior: 'smooth', block: 'start' });
}
</script>

<style>
.video-media .video-container {
    padding: 1.5rem;
}

.video-wrapper {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
    overflow: hidden;
    border-radius: 15px;
    background: #000;
}

.video-wrapper iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 15px;
}

.html5-player {
    border-radius: 15px;
    overflow: hidden;
    background: #000;
}

.video-element {
    width: 100%;
    height: auto;
    max-height: 400px;
    display: block;
}

.video-info {
    margin-top: 1rem;
    padding: 1rem;
    background: rgba(0, 127, 255, 0.05);
    border-radius: 10px;
    border-left: 4px solid #007fff;
}

.video-title {
    color: #1a1a1a;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.video-description {
    color: #6c757d;
    margin: 0;
    font-size: 0.9rem;
}

.video-playlist {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(0, 127, 255, 0.1);
}

.playlist-title {
    color: #1a1a1a;
    font-weight: 600;
    margin-bottom: 1rem;
    font-family: 'Orbitron', monospace;
}

.playlist-items {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.playlist-item {
    display: flex;
    gap: 1rem;
    padding: 0.75rem;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.playlist-item:hover {
    background: rgba(0, 127, 255, 0.05);
    border-color: rgba(0, 127, 255, 0.2);
}

.playlist-item.active {
    background: rgba(0, 127, 255, 0.1);
    border-color: #007fff;
}

.playlist-thumbnail {
    position: relative;
    width: 120px;
    height: 68px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
}

.playlist-thumbnail img {
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
    font-size: 1.5rem;
}

.play-overlay {
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

.playlist-item:hover .play-overlay {
    opacity: 1;
}

.playlist-info {
    flex: 1;
    min-width: 0;
}

.playlist-item-title {
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 0.25rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.playlist-item-desc {
    color: #6c757d;
    font-size: 0.85rem;
    line-height: 1.4;
}

@media (max-width: 768px) {
    .video-container {
        padding: 1rem;
    }
    
    .playlist-item {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .playlist-thumbnail {
        width: 100%;
        height: 180px;
    }
    
    .playlist-items {
        gap: 1rem;
    }
}
</style>
