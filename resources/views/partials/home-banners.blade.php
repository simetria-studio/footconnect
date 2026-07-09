@if(isset($banners) && $banners->isNotEmpty())
    <div class="fc-home-banners mb-4">
        <div id="fcHomeBannerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5500">
            @if($banners->count() > 1)
                <div class="carousel-indicators">
                    @foreach($banners as $index => $banner)
                        <button type="button" data-bs-target="#fcHomeBannerCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-label="Banner {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            @endif

            <div class="carousel-inner">
                @foreach($banners as $index => $banner)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        @if($banner->link_url)
                            <a href="{{ $banner->link_url }}" target="_blank" rel="noopener noreferrer" class="fc-home-banner {{ $banner->image_url ? 'fc-home-banner--image' : '' }}">
                                @include('partials.home-banner-inner', ['banner' => $banner])
                            </a>
                        @else
                            <div class="fc-home-banner {{ $banner->image_url ? 'fc-home-banner--image' : '' }}">
                                @include('partials.home-banner-inner', ['banner' => $banner])
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            @if($banners->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#fcHomeBannerCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#fcHomeBannerCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Próximo</span>
                </button>
            @endif
        </div>
    </div>
@endif
