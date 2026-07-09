@if($banner->image_url)
    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="fc-home-banner-img">
@endif
<div class="fc-home-banner-overlay">
    <div class="fc-home-banner-copy">
        <span class="fc-home-banner-kicker">Destaque</span>
        <h2 class="fc-home-banner-title">{{ $banner->title }}</h2>
        @if($banner->subtitle)
            <p class="fc-home-banner-subtitle">{{ $banner->subtitle }}</p>
        @endif
        @if($banner->link_url && $banner->cta_label)
            <span class="fc-home-banner-cta">{{ $banner->cta_label }}</span>
        @endif
    </div>
</div>
