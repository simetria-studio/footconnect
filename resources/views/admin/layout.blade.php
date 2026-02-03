<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Painel Admin') — FootConnect</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body { background: #0d2818 !important; }
    </style>
    @stack('styles')
</head>
<body class="fc-bg-primary" style="background: #0d2818 !important;">
    <header class="fc-header border-bottom fc-border">
        <div class="container-fluid px-4 py-3">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none fw-bold fc-text-primary">
                        Painel Admin
                    </a>
                    <nav class="d-flex gap-2">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm {{ request()->routeIs('admin.dashboard') ? 'btn-success' : 'btn-outline-success' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.users') }}" class="btn btn-sm {{ request()->routeIs('admin.users') ? 'btn-success' : 'btn-outline-success' }}">
                            Usuários
                        </a>
                        <a href="{{ route('admin.plan-prices') }}" class="btn btn-sm {{ request()->routeIs('admin.plan-prices') ? 'btn-success' : 'btn-outline-success' }}">
                            Preços
                        </a>
                    </nav>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">Ir para o app</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary">Sair</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="container-fluid px-4 py-4" style="min-height: calc(100vh - 70px);">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
