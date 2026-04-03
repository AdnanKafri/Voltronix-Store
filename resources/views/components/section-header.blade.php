@props([
    'title',
    'subtitle' => null,
    'icon' => null,
    'centered' => true
])

<div class="section-header {{ $centered ? 'text-center' : '' }}">
    <h2 class="section-title-modern">
        @if($icon)
            <i class="bi bi-{{ $icon }} {{ app()->getLocale() == 'ar' ? 'ms-3' : 'me-3' }}"></i>
        @endif
        {{ $title }}
    </h2>
    
    @if($subtitle)
        <p class="section-subtitle-modern">{{ $subtitle }}</p>
    @endif
    
    <div class="section-divider">
        <span class="divider-line"></span>
        <span class="divider-dot"></span>
        <span class="divider-line"></span>
    </div>
</div>
