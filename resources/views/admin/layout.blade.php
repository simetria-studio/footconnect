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
        html, body { background: #0a1f14 !important; min-height: 100vh; }
        .fc-admin-shell { display: flex; min-height: 100vh; }
        .fc-admin-sidebar {
            width: 260px;
            flex-shrink: 0;
            background: #071a10;
            border-right: 1px solid rgba(148, 163, 184, 0.15);
            display: flex;
            flex-direction: column;
        }
        .fc-admin-brand {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.12);
        }
        .fc-admin-brand h1 { font-size: 1rem; font-weight: 700; margin: 0; color: #f9fafb; }
        .fc-admin-brand p { font-size: 0.7rem; color: #6b7280; margin: 0.15rem 0 0; text-transform: uppercase; letter-spacing: 0.08em; }
        .fc-admin-nav { padding: 1rem 0.75rem; flex: 1; overflow-y: auto; }
        .fc-admin-nav-section {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #6b7280;
            padding: 0.75rem 0.75rem 0.35rem;
            font-weight: 600;
        }
        .fc-admin-nav-link {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.6rem 0.75rem;
            border-radius: 10px;
            color: #9ca3af;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.15rem;
            transition: all 0.15s ease;
        }
        .fc-admin-nav-link:hover { background: rgba(34, 197, 94, 0.1); color: #e5e7eb; }
        .fc-admin-nav-link.active {
            background: rgba(34, 197, 94, 0.18);
            color: #22c55e;
            font-weight: 600;
        }
        .fc-admin-nav-link svg { flex-shrink: 0; opacity: 0.85; }
        .fc-admin-footer {
            padding: 1rem 0.75rem;
            border-top: 1px solid rgba(148, 163, 184, 0.12);
        }
        .fc-admin-main { flex: 1; display: flex; flex-direction: column; min-width: 0; }
        .fc-admin-topbar {
            padding: 0.85rem 1.5rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.12);
            background: rgba(7, 26, 16, 0.6);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .fc-admin-content { padding: 1.5rem; flex: 1; }
        .fc-stat-card { border: 1px solid rgba(148, 163, 184, 0.15); }
        .fc-stat-card .stat-value { font-size: 1.75rem; font-weight: 700; line-height: 1.2; }
        .fc-stat-card .stat-label { font-size: 0.75rem; color: #9ca3af; }
        .fc-stat-card .stat-trend { font-size: 0.7rem; }
        @media (max-width: 991.98px) {
            .fc-admin-shell { flex-direction: column; }
            .fc-admin-sidebar { width: 100%; border-right: none; border-bottom: 1px solid rgba(148, 163, 184, 0.12); }
            .fc-admin-nav { display: flex; flex-wrap: wrap; gap: 0.25rem; padding: 0.5rem; }
            .fc-admin-nav-section { display: none; }
            .fc-admin-nav-link { padding: 0.45rem 0.65rem; font-size: 0.8rem; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="fc-admin-shell">
    <aside class="fc-admin-sidebar">
        <div class="fc-admin-brand">
            <h1>FootConnect</h1>
            <p>Painel administrativo</p>
        </div>

        <nav class="fc-admin-nav">
            <div class="fc-admin-nav-section">Principal</div>
            <a href="{{ route('admin.dashboard') }}" class="fc-admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dashboard
            </a>

            <div class="fc-admin-nav-section">Gestão</div>
            <a href="{{ route('admin.users') }}" class="fc-admin-nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Usuários
            </a>
            <a href="{{ route('admin.subscriptions') }}" class="fc-admin-nav-link {{ request()->routeIs('admin.subscriptions') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Assinaturas
            </a>
            <a href="{{ route('admin.plan-prices') }}" class="fc-admin-nav-link {{ request()->routeIs('admin.plan-prices*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Planos e preços
            </a>

            <div class="fc-admin-nav-section">Conteúdo</div>
            <a href="{{ route('admin.banners.index') }}" class="fc-admin-nav-link {{ request()->routeIs('admin.banners*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Banners
            </a>
            <a href="{{ route('admin.news.index') }}" class="fc-admin-nav-link {{ request()->routeIs('admin.news*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/></svg>
                Notícias
            </a>

            <div class="fc-admin-nav-section">Indique e Ganhe</div>
            <a href="{{ route('admin.referrals') }}" class="fc-admin-nav-link {{ request()->routeIs('admin.referrals') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                Afiliados
            </a>
            <a href="{{ route('admin.referrals.withdrawals') }}" class="fc-admin-nav-link {{ request()->routeIs('admin.referrals.withdrawals') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Saques PIX
            </a>
        </nav>

        <div class="fc-admin-footer">
            <a href="{{ route('home') }}" class="fc-admin-nav-link mb-1">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Voltar ao app
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="fc-admin-nav-link w-100 border-0 bg-transparent text-start">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sair
                </button>
            </form>
        </div>
    </aside>

    <div class="fc-admin-main">
        <header class="fc-admin-topbar">
            <div>
                <h2 class="h6 fw-bold fc-text-primary mb-0">@yield('page-title', 'Painel')</h2>
                @hasSection('page-subtitle')
                    <p class="small fc-text-secondary mb-0">@yield('page-subtitle')</p>
                @endif
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="small fc-text-secondary d-none d-md-inline">{{ auth()->user()->email }}</span>
                <span class="badge bg-warning text-dark">Admin</span>
            </div>
        </header>

        <main class="fc-admin-content">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
