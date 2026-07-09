<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $post->title }} — FootConnect</title>
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
            border-radius: 16px; overflow: hidden;
        }
        .fc-hero-img { width: 100%; max-height: 320px; object-fit: cover; }
        .fc-btn-ghost {
            border: 1px solid var(--fc-border); color: var(--fc-text); padding: 0.45rem 1rem;
            border-radius: 10px; text-decoration: none; font-size: 0.875rem;
        }
        .fc-body { color: var(--fc-muted); font-size: 1rem; line-height: 1.75; }
        .fc-body p { margin-bottom: 1rem; }
        .fc-body h2, .fc-body h3 { color: var(--fc-text); font-weight: 700; margin: 1.5rem 0 0.75rem; }
        .fc-body ul, .fc-body ol { padding-left: 1.25rem; margin-bottom: 1rem; }
        .fc-body a { color: var(--fc-green); }
        .fc-body blockquote {
            border-left: 3px solid var(--fc-green);
            padding-left: 1rem; margin: 1rem 0; color: #cbd5e1;
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
            <a href="{{ route('news.index') }}" class="fc-btn-ghost">Todas as notícias</a>
        </div>
    </nav>

    <main class="container py-5" style="max-width: 820px;">
        <article class="fc-card">
            @if($post->image_url)
                <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="fc-hero-img">
            @endif
            <div class="p-4 p-md-5">
                <p class="small mb-2" style="color: var(--fc-muted);">
                    {{ optional($post->published_at)->format('d/m/Y H:i') ?? $post->created_at->format('d/m/Y H:i') }}
                </p>
                <h1 class="h3 fw-bold mb-3">{{ $post->title }}</h1>
                @if($post->excerpt)
                    <p class="mb-4" style="color: var(--fc-muted);">{{ $post->excerpt }}</p>
                @endif
                <div class="fc-body">{!! $post->body !!}</div>
            </div>
        </article>
    </main>
</body>
</html>
