<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notícias — FootConnect</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --fc-bg: #020617;
            --fc-card: #0f172a;
            --fc-green: #22c55e;
            --fc-text: #f8fafc;
            --fc-muted: #94a3b8;
            --fc-border: rgba(148, 163, 184, 0.2);
        }
        body { font-family: Inter, sans-serif; background: var(--fc-bg); color: var(--fc-text); min-height: 100vh; }
        .fc-logo {
            width: 42px; height: 42px; border-radius: 11px;
            background: linear-gradient(135deg, var(--fc-green), #16a34a);
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; color: #000;
        }
        .fc-card {
            background: var(--fc-card); border: 1px solid var(--fc-border);
            border-radius: 16px; overflow: hidden; height: 100%;
            transition: transform 0.2s, border-color 0.2s;
        }
        a:hover .fc-card { transform: translateY(-3px); border-color: rgba(34,197,94,0.35); }
        .fc-news-img { width: 100%; height: 160px; object-fit: cover; }
        .fc-btn-green {
            background: linear-gradient(135deg, var(--fc-green), #16a34a);
            color: #000; font-weight: 600; padding: 0.5rem 1.1rem; border-radius: 10px; text-decoration: none;
        }
        .fc-btn-ghost {
            border: 1px solid var(--fc-border); color: var(--fc-text); padding: 0.5rem 1.1rem;
            border-radius: 10px; text-decoration: none;
        }
    </style>
</head>
<body>
    <nav class="py-3 border-bottom" style="border-color: var(--fc-border) !important;">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="{{ route('landing') }}" class="d-flex align-items-center gap-2 text-decoration-none text-white">
                <div class="fc-logo">FC</div>
                <strong>FootConnect</strong>
            </a>
            <div class="d-flex gap-2">
                <a href="{{ route('landing') }}#noticias" class="fc-btn-ghost">Voltar</a>
                <a href="{{ route('onboarding.user-type') }}" class="fc-btn-green">Criar conta</a>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="mb-4">
            <h1 class="h3 fw-bold mb-1">Notícias</h1>
            <p class="mb-0" style="color: var(--fc-muted);">Novidades, campanhas e comunicados do FootConnect</p>
        </div>

        <div class="row g-3">
            @forelse($posts as $post)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('news.show', $post->slug) }}" class="text-decoration-none text-white">
                        <article class="fc-card">
                            @if($post->image_url)
                                <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="fc-news-img">
                            @endif
                            <div class="p-3">
                                <p class="small mb-1" style="color: var(--fc-muted);">
                                    {{ optional($post->published_at)->format('d/m/Y') ?? $post->created_at->format('d/m/Y') }}
                                </p>
                                <h2 class="h6 fw-bold mb-2">{{ $post->title }}</h2>
                                <p class="small mb-0" style="color: var(--fc-muted);">{{ $post->excerpt_or_body }}</p>
                            </div>
                        </article>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="fc-card p-5 text-center" style="color: var(--fc-muted);">
                        Nenhuma notícia publicada no momento.
                    </div>
                </div>
            @endforelse
        </div>

        @if($posts->hasPages())
            <div class="mt-4 d-flex justify-content-center">{{ $posts->links() }}</div>
        @endif
    </main>
</body>
</html>
