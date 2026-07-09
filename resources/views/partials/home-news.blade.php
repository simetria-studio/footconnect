@if(isset($news) && $news->isNotEmpty())
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3 gap-2">
            <h2 class="h5 fw-bold fc-text-primary mb-0">Notícias</h2>
            <div class="fc-divider flex-grow-1 ms-2"></div>
            <a href="{{ route('news.index') }}" class="btn btn-sm btn-outline-success flex-shrink-0">Ver todas</a>
        </div>

        <div class="row g-3">
            @foreach($news as $post)
                <div class="col-12 col-md-6">
                    <a href="{{ route('news.show', $post->slug) }}" class="text-decoration-none">
                        <article class="card fc-card fc-card-hover h-100 fc-news-card">
                            @if($post->image_url)
                                <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="fc-news-card-img">
                            @endif
                            <div class="card-body">
                                <p class="small text-muted mb-1">
                                    {{ optional($post->published_at)->format('d/m/Y') ?? $post->created_at->format('d/m/Y') }}
                                </p>
                                <h3 class="h6 fw-bold fc-text-primary mb-2">{{ $post->title }}</h3>
                                <p class="small fc-text-secondary mb-0">{{ $post->excerpt_or_body }}</p>
                            </div>
                        </article>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif
