<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FootConnect — Conectando talentos ao futebol profissional</title>
    <meta name="description" content="Plataforma profissional de networking esportivo. Perfis G1 a G4 para atletas, empresários, treinadores e clubes.">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --fc-bg: #020617;
            --fc-card: #0f172a;
            --fc-card-hover: #111827;
            --fc-green: #22c55e;
            --fc-green-dim: rgba(34, 197, 94, 0.15);
            --fc-yellow: #facc15;
            --fc-yellow-dim: rgba(250, 204, 21, 0.12);
            --fc-text: #f8fafc;
            --fc-muted: #94a3b8;
            --fc-border: rgba(148, 163, 184, 0.2);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--fc-text);
            background: var(--fc-bg);
            min-height: 100vh;
        }

        .fc-mesh {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(34, 197, 94, 0.18), transparent),
                radial-gradient(ellipse 60% 40% at 100% 50%, rgba(59, 130, 246, 0.08), transparent),
                radial-gradient(ellipse 50% 30% at 0% 80%, rgba(250, 204, 21, 0.06), transparent);
        }

        .fc-wrap { position: relative; z-index: 1; }

        .fc-nav {
            backdrop-filter: blur(12px);
            background: rgba(2, 6, 23, 0.75);
            border-bottom: 1px solid var(--fc-border);
        }

        .fc-logo {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            background: linear-gradient(135deg, var(--fc-green), #16a34a);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1rem;
            color: #000;
            box-shadow: 0 4px 16px rgba(34, 197, 94, 0.35);
        }

        .fc-nav-link {
            color: var(--fc-muted);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            transition: color 0.2s, background 0.2s;
        }

        .fc-nav-link:hover { color: var(--fc-text); background: rgba(255,255,255,0.05); }

        .fc-btn-green {
            background: linear-gradient(135deg, var(--fc-green), #16a34a);
            border: none;
            color: #000;
            font-weight: 600;
            padding: 0.6rem 1.35rem;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 20px rgba(34, 197, 94, 0.25);
        }

        .fc-btn-green:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(34, 197, 94, 0.4);
            color: #000;
        }

        .fc-btn-ghost {
            border: 1px solid var(--fc-border);
            color: var(--fc-text);
            background: transparent;
            font-weight: 500;
            padding: 0.6rem 1.35rem;
            border-radius: 10px;
            text-decoration: none;
            transition: background 0.2s, border-color 0.2s;
        }

        .fc-btn-ghost:hover {
            background: rgba(255,255,255,0.05);
            border-color: rgba(148, 163, 184, 0.4);
            color: var(--fc-text);
        }

        .fc-hero {
            padding: 4rem 0 3rem;
        }

        .fc-hero-title {
            font-size: clamp(2rem, 5vw, 3.25rem);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.02em;
            margin-bottom: 1.25rem;
        }

        .fc-hero-title span {
            background: linear-gradient(135deg, var(--fc-green), #86efac);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .fc-hero-lead {
            font-size: 1.125rem;
            color: var(--fc-muted);
            line-height: 1.7;
            max-width: 540px;
            margin-bottom: 2rem;
        }

        .fc-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 0.85rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            border: 1px solid var(--fc-border);
            background: rgba(15, 23, 42, 0.8);
            color: var(--fc-muted);
            margin-bottom: 1rem;
        }

        .fc-pill-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--fc-green);
            box-shadow: 0 0 10px var(--fc-green);
        }

        .fc-trust-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 1px solid var(--fc-border);
        }

        .fc-trust-item strong {
            display: block;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--fc-text);
        }

        .fc-trust-item span {
            font-size: 0.8rem;
            color: var(--fc-muted);
        }

        .fc-card {
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 16px;
            padding: 1.5rem;
            height: 100%;
            transition: border-color 0.2s, transform 0.2s;
        }

        .fc-card:hover {
            border-color: rgba(148, 163, 184, 0.35);
            transform: translateY(-2px);
        }

        .fc-section { padding: 4rem 0; }

        .fc-section-title {
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .fc-section-lead {
            color: var(--fc-muted);
            max-width: 560px;
            margin-bottom: 2rem;
        }

        .fc-profile-card {
            position: relative;
            overflow: hidden;
        }

        .fc-profile-card--green {
            background: radial-gradient(circle at top left, var(--fc-green-dim), transparent 55%), var(--fc-card);
        }

        .fc-profile-card--yellow {
            background: radial-gradient(circle at top left, var(--fc-yellow-dim), transparent 55%), var(--fc-card);
        }

        .fc-profile-icon {
            font-size: 1.75rem;
            margin-bottom: 0.75rem;
        }

        .fc-profile-code {
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--fc-muted);
            letter-spacing: 0.1em;
        }

        .fc-profile-name {
            font-size: 1rem;
            font-weight: 600;
            margin: 0.25rem 0 0.5rem;
        }

        .fc-profile-desc {
            font-size: 0.85rem;
            color: var(--fc-muted);
            line-height: 1.55;
            margin-bottom: 1rem;
        }

        .fc-profile-price {
            font-size: 1.1rem;
            font-weight: 700;
        }

        .fc-profile-price small {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--fc-muted);
        }

        .fc-step-num {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: var(--fc-green-dim);
            color: var(--fc-green);
            font-weight: 700;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .fc-billing-toggle {
            display: inline-flex;
            padding: 4px;
            border-radius: 12px;
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid var(--fc-border);
            margin-bottom: 2rem;
        }

        .fc-billing-btn {
            border: none;
            background: transparent;
            color: var(--fc-muted);
            font-size: 0.875rem;
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            border-radius: 9px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .fc-billing-btn.active {
            background: var(--fc-green);
            color: #000;
        }

        .fc-billing-save {
            font-size: 0.7rem;
            color: var(--fc-yellow);
            margin-left: 0.35rem;
        }

        .fc-plan-card {
            position: relative;
            padding: 1.75rem;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .fc-plan-card .fc-btn-green {
            margin-top: auto;
        }

        .fc-plan-card--featured::before {
            content: 'Mais acessível';
            position: absolute;
            top: -10px;
            left: 1.5rem;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 0.2rem 0.65rem;
            border-radius: 999px;
            background: var(--fc-green);
            color: #000;
            z-index: 1;
        }

        .fc-plan-price {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            margin: 0.75rem 0 0.25rem;
        }

        .fc-plan-interval {
            font-size: 0.85rem;
            color: var(--fc-muted);
            margin-bottom: 1rem;
        }

        .fc-plan-features {
            list-style: none;
            padding: 0;
            margin: 0 0 1.25rem;
            font-size: 0.85rem;
            color: var(--fc-muted);
        }

        .fc-plan-features li {
            padding: 0.35rem 0;
            padding-left: 1.25rem;
            position: relative;
        }

        .fc-plan-features li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--fc-green);
            font-weight: 700;
            font-size: 0.75rem;
        }

        .fc-tag-off {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.15rem 0.5rem;
            border-radius: 999px;
            background: var(--fc-yellow-dim);
            color: var(--fc-yellow);
            border: 1px solid rgba(250, 204, 21, 0.4);
            margin-left: 0.35rem;
            vertical-align: middle;
        }

        .fc-referral-banner {
            background: radial-gradient(circle at right, rgba(34, 197, 94, 0.15), transparent 60%), var(--fc-card);
            border: 1px solid rgba(34, 197, 94, 0.3);
            border-radius: 20px;
            padding: 2rem 2.5rem;
        }

        .fc-faq-item {
            border-bottom: 1px solid var(--fc-border);
            padding: 1.25rem 0;
        }

        .fc-faq-item:last-child { border-bottom: none; }

        .fc-faq-q {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.35rem;
        }

        .fc-faq-a {
            font-size: 0.9rem;
            color: var(--fc-muted);
            margin: 0;
            line-height: 1.6;
        }

        .fc-cta-band {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.12), rgba(15, 23, 42, 0.95));
            border: 1px solid rgba(34, 197, 94, 0.25);
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
        }

        footer {
            border-top: 1px solid var(--fc-border);
            padding: 2rem 0;
            margin-top: 2rem;
        }

        .fc-price-yearly, .fc-price-monthly { display: none; }
        .fc-price-yearly.active, .fc-price-monthly.active { display: block; }

        /* Modalidades — faixa de destaque */
        .fc-mod {
            position: relative;
            padding: 4.5rem 0;
            overflow: hidden;
            border-block: 1px solid rgba(34, 197, 94, 0.2);
            background:
                linear-gradient(90deg, rgba(34, 197, 94, 0.06) 0%, transparent 40%, transparent 60%, rgba(250, 204, 21, 0.05) 100%),
                repeating-linear-gradient(
                    -12deg,
                    transparent,
                    transparent 28px,
                    rgba(148, 163, 184, 0.03) 28px,
                    rgba(148, 163, 184, 0.03) 29px
                ),
                #070b14;
        }

        .fc-mod::before {
            content: 'MODALIDADES';            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            font-size: clamp(3.5rem, 14vw, 9rem);
            font-weight: 900;
            letter-spacing: -0.04em;
            color: rgba(248, 250, 252, 0.03);
            white-space: nowrap;
            pointer-events: none;
            user-select: none;
            z-index: 0;
        }

        .fc-mod .container { position: relative; z-index: 1; }

        .fc-mod-head {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.25rem;
            margin-bottom: 2.25rem;
        }

        .fc-mod-kicker {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--fc-green);
            margin-bottom: 0.5rem;
        }

        .fc-mod-title {
            font-size: clamp(1.75rem, 4vw, 2.6rem);
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin: 0;
            max-width: 16ch;
        }

        .fc-mod-title em {
            font-style: normal;
            color: var(--fc-green);
        }

        .fc-mod-genders {
            display: flex;
            gap: 0.5rem;
            align-items: stretch;
        }

        .fc-mod-gender {
            min-width: 7.5rem;
            padding: 0.65rem 0.9rem;
            border-radius: 12px;
            border: 1px solid var(--fc-border);
            background: rgba(15, 23, 42, 0.85);
            text-align: left;
        }

        .fc-mod-gender strong {
            display: block;
            font-size: 0.95rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .fc-mod-gender span {
            font-size: 0.7rem;
            color: var(--fc-muted);
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .fc-mod-gender--m {
            border-color: rgba(34, 197, 94, 0.45);
            box-shadow: inset 3px 0 0 var(--fc-green);
        }

        .fc-mod-gender--f {
            border-color: rgba(250, 204, 21, 0.45);
            box-shadow: inset 3px 0 0 var(--fc-yellow);
        }

        .fc-mod-track {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
        }

        .fc-mod-panel {
            position: relative;
            min-height: 220px;
            padding: 1.5rem 1.35rem 1.35rem;
            border-radius: 18px;
            border: 1px solid var(--fc-border);
            background: rgba(15, 23, 42, 0.72);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1), border-color 0.25s, box-shadow 0.35s;
            animation: fc-mod-in 0.7s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .fc-mod-panel:nth-child(1) { animation-delay: 0.05s; }
        .fc-mod-panel:nth-child(2) { animation-delay: 0.15s; }
        .fc-mod-panel:nth-child(3) { animation-delay: 0.25s; }

        .fc-mod-panel:hover {
            transform: translateY(-6px) scale(1.01);
            border-color: rgba(248, 250, 252, 0.28);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.45);
        }

        .fc-mod-panel::before {
            content: attr(data-num);
            position: absolute;
            top: -0.35rem;
            right: 0.6rem;
            font-size: 5.5rem;
            font-weight: 900;
            line-height: 1;
            letter-spacing: -0.06em;
            color: rgba(248, 250, 252, 0.04);
            transition: color 0.3s, transform 0.35s;
        }

        .fc-mod-panel:hover::before {
            color: rgba(248, 250, 252, 0.08);
            transform: translateY(4px);
        }

        .fc-mod-panel--campo {
            background:
                radial-gradient(ellipse 90% 70% at 10% 0%, rgba(34, 197, 94, 0.22), transparent 55%),
                rgba(15, 23, 42, 0.85);
        }

        .fc-mod-panel--futsal {
            background:
                radial-gradient(ellipse 90% 70% at 90% 0%, rgba(59, 130, 246, 0.2), transparent 55%),
                rgba(15, 23, 42, 0.85);
        }

        .fc-mod-panel--fut7 {
            background:
                radial-gradient(ellipse 90% 70% at 50% 0%, rgba(250, 204, 21, 0.18), transparent 55%),
                rgba(15, 23, 42, 0.85);
        }

        .fc-mod-code {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--fc-muted);
            margin-bottom: 0.35rem;
        }

        .fc-mod-name {
            font-size: clamp(1.35rem, 2.5vw, 1.75rem);
            font-weight: 800;
            letter-spacing: -0.03em;
            margin: 0 0 0.4rem;
            line-height: 1.1;
        }

        .fc-mod-tagline {
            font-size: 0.85rem;
            color: var(--fc-muted);
            line-height: 1.45;
            margin: 0 0 1rem;
            max-width: 22ch;
        }

        .fc-mod-mf {
            display: flex;
            gap: 0.4rem;
            margin-top: auto;
        }

        .fc-mod-mf b {
            flex: 1;
            text-align: center;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 0.4rem 0.35rem;
            border-radius: 8px;
            border: 1px solid transparent;
        }

        .fc-mod-mf b:first-child {
            color: #86efac;
            background: rgba(34, 197, 94, 0.12);
            border-color: rgba(34, 197, 94, 0.35);
        }

        .fc-mod-mf b:last-child {
            color: #fde047;
            background: rgba(250, 204, 21, 0.1);
            border-color: rgba(250, 204, 21, 0.35);
        }

        .fc-mod-foot {
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: var(--fc-muted);
            max-width: 40rem;
        }

        .fc-mod-foot strong {
            color: var(--fc-text);
            font-weight: 600;
        }

        @keyframes fc-mod-in {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 991.98px) {
            .fc-nav-links { display: none !important; }
            .fc-hero { padding: 2.5rem 0 2rem; }
            .fc-mod-track { grid-template-columns: 1fr; }
            .fc-mod-panel { min-height: 180px; }
            .fc-mod-title { max-width: none; }
        }

        @media (min-width: 768px) and (max-width: 991.98px) {
            .fc-mod-track { grid-template-columns: repeat(3, 1fr); }
            .fc-mod-panel { min-height: 200px; }
            .fc-mod-tagline { max-width: none; font-size: 0.78rem; }
        }

        @media (prefers-reduced-motion: reduce) {
            .fc-mod-panel { animation: none; }
            .fc-mod-panel:hover { transform: none; }
        }

        /* Landing banners & news */
        .fc-landing-banners { padding: 0 0 1rem; }
        .fc-landing-banners .carousel-item { border-radius: 20px; overflow: hidden; }
        .fc-landing-banner {
            position: relative;
            display: block;
            min-height: 220px;
            border-radius: 20px;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            background:
                radial-gradient(ellipse 80% 80% at 15% 20%, rgba(34, 197, 94, 0.28), transparent 55%),
                linear-gradient(135deg, #0f172a 0%, #020617 60%, #111827 100%);
            border: 1px solid var(--fc-border);
        }
        .fc-landing-banner--image { min-height: 260px; }
        .fc-landing-banner-img {
            position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;
        }
        .fc-landing-banner-overlay {
            position: relative; z-index: 1; min-height: inherit;
            display: flex; align-items: flex-end;
            padding: 1.75rem 1.75rem 1.5rem;
            background: linear-gradient(180deg, rgba(2,6,23,0.1) 0%, rgba(2,6,23,0.75) 55%, rgba(2,6,23,0.95) 100%);
        }
        .fc-landing-banner-kicker {
            display: inline-block; font-size: 0.7rem; font-weight: 700;
            letter-spacing: 0.14em; text-transform: uppercase; color: var(--fc-green); margin-bottom: 0.4rem;
        }
        .fc-landing-banner-title {
            font-size: clamp(1.35rem, 3.5vw, 2rem); font-weight: 800; margin: 0 0 0.4rem;
            letter-spacing: -0.02em; color: #fff;
        }
        .fc-landing-banner-subtitle {
            font-size: 0.95rem; color: var(--fc-muted); margin: 0 0 1rem; max-width: 36rem;
        }
        .fc-landing-banner-cta {
            display: inline-flex; align-items: center; padding: 0.5rem 1rem;
            border-radius: 999px; background: var(--fc-green); color: #000;
            font-size: 0.85rem; font-weight: 700;
        }
        .fc-landing-news-card {
            display: block; height: 100%; text-decoration: none; color: inherit;
            background: var(--fc-card); border: 1px solid var(--fc-border);
            border-radius: 16px; overflow: hidden; transition: transform 0.2s, border-color 0.2s;
        }
        .fc-landing-news-card:hover {
            transform: translateY(-3px); border-color: rgba(34, 197, 94, 0.35); color: inherit;
        }
        .fc-landing-news-img {
            width: 100%; height: 160px; object-fit: cover; display: block;
            border-bottom: 1px solid var(--fc-border);
        }
        .fc-landing-news-body { padding: 1.25rem 1.35rem 1.4rem; }
        .fc-landing-news-date {
            font-size: 0.75rem; color: var(--fc-muted); margin-bottom: 0.4rem;
        }
        .fc-landing-news-title {
            font-size: 1.05rem; font-weight: 700; margin: 0 0 0.45rem; color: var(--fc-text);
        }
        .fc-landing-news-excerpt {
            font-size: 0.88rem; color: var(--fc-muted); margin: 0; line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="fc-mesh"></div>
    <div class="fc-wrap">

        {{-- Nav --}}
        <nav class="fc-nav navbar py-3 sticky-top">
            <div class="container d-flex align-items-center justify-content-between">
                <a href="{{ route('landing') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                    <div class="fc-logo">FC</div>
                    <div>
                        <div class="fw-bold text-white" style="font-size: 1rem; line-height: 1.2;">FootConnect</div>
                        <div style="font-size: 0.7rem; color: var(--fc-muted);">Networking esportivo profissional</div>
                    </div>
                </a>
                <div class="d-flex align-items-center gap-2 gap-md-3">
                    <div class="fc-nav-links d-flex gap-1">
                        <a href="#destaques" class="fc-nav-link">Destaques</a>
                        <a href="#modalidades" class="fc-nav-link">Modalidades</a>
                        <a href="#noticias" class="fc-nav-link">Notícias</a>
                        <a href="#perfis" class="fc-nav-link">Perfis</a>
                        <a href="#planos" class="fc-nav-link">Planos</a>
                        <a href="#faq" class="fc-nav-link">FAQ</a>
                    </div>
                    <a href="{{ route('login') }}" class="fc-nav-link d-none d-sm-inline">Entrar</a>
                    <a href="{{ route('onboarding.user-type') }}" class="fc-btn-green">Criar conta</a>
                </div>
            </div>
        </nav>

        {{-- Hero --}}
        <section class="fc-hero">
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-7">
                        <div class="fc-pill">
                            <span class="fc-pill-dot"></span>
                            Plataforma exclusiva para assinantes
                        </div>
                        <h1 class="fc-hero-title">
                            O futebol profissional<br><span>começa na conexão certa</span>
                        </h1>
                        <p class="fc-hero-lead">
                            FootConnect une atletas, empresários, treinadores e clubes em um ambiente fechado e profissional.
                            Perfis completos, busca avançada, favoritos e mensagens — tudo em um só lugar.
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ route('onboarding.user-type') }}" class="fc-btn-green">Começar agora</a>
                            <a href="#planos" class="fc-btn-ghost">Ver planos e valores</a>
                        </div>
                        <div class="fc-trust-row">
                            <div class="fc-trust-item">
                                <strong>3 modalidades</strong>
                                <span>Campo, Futsal e Fut 7</span>
                            </div>
                            <div class="fc-trust-item">
                                <strong>Masc. + Fem.</strong>
                                <span>Todas as categorias</span>
                            </div>
                            <div class="fc-trust-item">
                                <strong>{{ $annualDiscount }}% OFF</strong>
                                <span>No plano anual</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="fc-card p-0 overflow-hidden" style="border-color: rgba(34,197,94,0.25);">
                            <div style="padding: 1.75rem; background: radial-gradient(circle at top, rgba(34,197,94,0.12), transparent 70%);">
                                <p class="mb-3" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--fc-muted);">Como funciona</p>
                                <div class="d-flex gap-3 mb-3">
                                    <div class="fc-step-num">1</div>
                                    <div>
                                        <strong style="font-size: 0.9rem;">Escolha seu perfil</strong>
                                        <p class="mb-0" style="font-size: 0.8rem; color: var(--fc-muted);">Atleta, empresário, treinador ou clube</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-3 mb-3">
                                    <div class="fc-step-num">2</div>
                                    <div>
                                        <strong style="font-size: 0.9rem;">Assine o plano ideal</strong>
                                        <p class="mb-0" style="font-size: 0.8rem; color: var(--fc-muted);">Mensal ou anual com pagamento seguro</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-3">
                                    <div class="fc-step-num">3</div>
                                    <div>
                                        <strong style="font-size: 0.9rem;">Conecte-se ao mercado</strong>
                                        <p class="mb-0" style="font-size: 0.8rem; color: var(--fc-muted);">Perfil, busca, favoritos e mensagens</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Banners / destaques --}}
        @if(isset($banners) && $banners->isNotEmpty())
            <section class="fc-landing-banners" id="destaques">
                <div class="container">
                    <div id="fcLandingBannerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5500">
                        @if($banners->count() > 1)
                            <div class="carousel-indicators">
                                @foreach($banners as $index => $banner)
                                    <button type="button" data-bs-target="#fcLandingBannerCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-label="Destaque {{ $index + 1 }}"></button>
                                @endforeach
                            </div>
                        @endif
                        <div class="carousel-inner">
                            @foreach($banners as $index => $banner)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    @if($banner->link_url)
                                        <a href="{{ $banner->link_url }}" target="_blank" rel="noopener noreferrer" class="fc-landing-banner {{ $banner->image_url ? 'fc-landing-banner--image' : '' }}">
                                    @else
                                        <div class="fc-landing-banner {{ $banner->image_url ? 'fc-landing-banner--image' : '' }}">
                                    @endif
                                        @if($banner->image_url)
                                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="fc-landing-banner-img">
                                        @endif
                                        <div class="fc-landing-banner-overlay">
                                            <div>
                                                <span class="fc-landing-banner-kicker">Destaque</span>
                                                <h2 class="fc-landing-banner-title">{{ $banner->title }}</h2>
                                                @if($banner->subtitle)
                                                    <p class="fc-landing-banner-subtitle">{{ $banner->subtitle }}</p>
                                                @endif
                                                @if($banner->link_url && $banner->cta_label)
                                                    <span class="fc-landing-banner-cta">{{ $banner->cta_label }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @if($banner->link_url)
                                        </a>
                                    @else
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @if($banners->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#fcLandingBannerCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#fcLandingBannerCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Próximo</span>
                            </button>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        {{-- Modalidades --}}
        <section class="fc-mod" id="modalidades" aria-labelledby="modalidades-title">
            <div class="container">
                <div class="fc-mod-head">
                    <div>
                        <p class="fc-mod-kicker">Onde o talento joga</p>
                        <h2 class="fc-mod-title" id="modalidades-title">
                            Três gramados.<br><em>Dois gêneros.</em><br>Um só mercado.
                        </h2>
                    </div>
                    <div class="fc-mod-genders" aria-label="Categorias disponíveis">
                        <div class="fc-mod-gender fc-mod-gender--m">
                            <strong>Masculino</strong>
                            <span>Ativo agora</span>
                        </div>
                        <div class="fc-mod-gender fc-mod-gender--f">
                            <strong>Feminino</strong>
                            <span>Ativo agora</span>
                        </div>
                    </div>
                </div>

                <div class="fc-mod-track">
                    <article class="fc-mod-panel fc-mod-panel--campo" data-num="01">
                        <p class="fc-mod-code">Modalidade</p>
                        <h3 class="fc-mod-name">Futebol de Campo</h3>
                        <p class="fc-mod-tagline">11×11. O palco clássico — do amador ao profissional.</p>
                        <div class="fc-mod-mf">
                            <b>Masculino</b>
                            <b>Feminino</b>
                        </div>
                    </article>

                    <article class="fc-mod-panel fc-mod-panel--futsal" data-num="02">
                        <p class="fc-mod-code">Modalidade</p>
                        <h3 class="fc-mod-name">Futsal</h3>
                        <p class="fc-mod-tagline">Quadra rápida. Decisão em cada toque.</p>
                        <div class="fc-mod-mf">
                            <b>Masculino</b>
                            <b>Feminino</b>
                        </div>
                    </article>

                    <article class="fc-mod-panel fc-mod-panel--fut7" data-num="03">
                        <p class="fc-mod-code">Modalidade</p>
                        <h3 class="fc-mod-name">Fut 7</h3>
                        <p class="fc-mod-tagline">Society em alta. Ritmo intenso, vitrine real.</p>
                        <div class="fc-mod-mf">
                            <b>Masculino</b>
                            <b>Feminino</b>
                        </div>
                    </article>
                </div>

                <p class="fc-mod-foot">
                    Perfis e buscas no FootConnect cobrem <strong>Campo, Futsal e Fut 7</strong> —
                    com atletas e profissionais do <strong>masculino e do feminino</strong> no mesmo ecossistema.
                </p>
            </div>
        </section>

        {{-- Notícias --}}
        @if(isset($news) && $news->isNotEmpty())
            <section class="fc-section" id="noticias">
                <div class="container">
                    <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
                        <div>
                            <p class="fc-pill mb-2"><span class="fc-pill-dot"></span> Notícias</p>
                            <h2 class="fc-section-title mb-1">No radar do FootConnect</h2>
                            <p class="fc-section-lead mb-0">Campanhas, novidades e comunicados do mercado.</p>
                        </div>
                        <a href="{{ route('news.index') }}" class="fc-btn-ghost" style="font-size: 0.85rem;">Ver todas</a>
                    </div>
                    <div class="row g-3">
                        @foreach($news as $post)
                            <div class="col-md-6 col-lg-4">
                                <a href="{{ route('news.show', $post->slug) }}" class="fc-landing-news-card">
                                    @if($post->image_url)
                                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="fc-landing-news-img">
                                    @endif
                                    <div class="fc-landing-news-body">
                                        <p class="fc-landing-news-date">
                                            {{ optional($post->published_at)->format('d/m/Y') ?? $post->created_at->format('d/m/Y') }}
                                        </p>
                                        <h3 class="fc-landing-news-title">{{ $post->title }}</h3>
                                        <p class="fc-landing-news-excerpt">{{ $post->excerpt_or_body }}</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- Perfis G1–G4 --}}
        <section class="fc-section" id="perfis">
            <div class="container">
                <p class="fc-pill mb-2"><span class="fc-pill-dot"></span> Perfis</p>
                <h2 class="fc-section-title">Um plano para cada papel no futebol</h2>
                <p class="fc-section-lead">
                    Cada grupo tem funcionalidades e preços pensados para a sua realidade — do atleta em busca de visibilidade ao clube organizando peneiras.
                </p>
                <div class="row g-3">
                    @foreach ($planGroups as $group)
                        @php
                            $isGreen = ($group['accent'] ?? '') === 'green';
                            $monthly = $group['monthly'];
                        @endphp
                        <div class="col-md-6 col-xl-3">
                            <div class="fc-card fc-profile-card {{ $isGreen ? 'fc-profile-card--green' : 'fc-profile-card--yellow' }}">
                                <div class="fc-profile-icon">{{ $group['icon'] }}</div>
                                <span class="fc-profile-code">{{ $group['code'] }}</span>
                                <h3 class="fc-profile-name">{{ $group['short_label'] }}</h3>
                                <p class="fc-profile-desc">{{ $group['description'] }}</p>
                                @if ($monthly)
                                    <p class="fc-profile-price mb-3">
                                        {{ $monthly->formatted_price }}
                                        <small>/ mês</small>
                                    </p>
                                @endif
                                <a href="{{ route('onboarding.user-type') }}" class="fc-btn-ghost w-100 text-center" style="font-size: 0.85rem; padding: 0.5rem;">
                                    Escolher {{ $group['code'] }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Benefícios --}}
        <section class="fc-section" style="background: rgba(15,23,42,0.5);">
            <div class="container">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-5">
                        <p class="fc-pill mb-2"><span class="fc-pill-dot"></span> Recursos</p>
                        <h2 class="fc-section-title">Tudo que você precisa para se destacar</h2>
                        <p class="fc-section-lead mb-0">
                            Ambiente 100% profissional, sem distrações. Feito para quem leva o futebol a sério.
                        </p>
                    </div>
                    <div class="col-lg-7">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="fc-card">
                                    <div style="font-size: 1.25rem; margin-bottom: 0.5rem;">⚽</div>
                                    <h4 style="font-size: 0.95rem; font-weight: 600;">Perfil esportivo completo</h4>
                                    <p class="mb-0" style="font-size: 0.85rem; color: var(--fc-muted);">Vídeos, fotos, estatísticas, posição, características físicas e biografia.</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="fc-card">
                                    <div style="font-size: 1.25rem; margin-bottom: 0.5rem;">🔍</div>
                                    <h4 style="font-size: 0.95rem; font-weight: 600;">Busca avançada</h4>
                                    <p class="mb-0" style="font-size: 0.85rem; color: var(--fc-muted);">Filtros por posição, idade, cidade, modalidade e muito mais.</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="fc-card">
                                    <div style="font-size: 1.25rem; margin-bottom: 0.5rem;">⭐</div>
                                    <h4 style="font-size: 0.95rem; font-weight: 600;">Favoritos</h4>
                                    <p class="mb-0" style="font-size: 0.85rem; color: var(--fc-muted);">Organize sua lista de talentos e acompanhe candidatos com facilidade.</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="fc-card">
                                    <div style="font-size: 1.25rem; margin-bottom: 0.5rem;">💬</div>
                                    <h4 style="font-size: 0.95rem; font-weight: 600;">Mensagens internas</h4>
                                    <p class="mb-0" style="font-size: 0.85rem; color: var(--fc-muted);">Contato direto entre atletas e profissionais, sem sair da plataforma.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Planos e preços --}}
        <section class="fc-section" id="planos">
            <div class="container">
                <div class="text-center mb-4">
                    <p class="fc-pill mb-2 justify-content-center"><span class="fc-pill-dot"></span> Planos</p>
                    <h2 class="fc-section-title">Valores atualizados</h2>
                    <p class="fc-section-lead mx-auto">
                        Cobrança recorrente via Stripe. Cancele quando quiser nas configurações da conta.
                    </p>
                    <div class="fc-billing-toggle">
                        <button type="button" class="fc-billing-btn active" data-billing="monthly">Mensal</button>
                        <button type="button" class="fc-billing-btn" data-billing="yearly">
                            Anual
                            <span class="fc-billing-save">{{ $annualDiscount }}% OFF</span>
                        </button>
                    </div>
                </div>

                <div class="row g-4">
                    @foreach ($planGroups as $index => $group)
                        @php
                            $isGreen = ($group['accent'] ?? '') === 'green';
                            $monthly = $group['monthly'];
                            $yearly = $group['yearly'];
                        @endphp
                        <div class="col-md-6 col-xl-3">
                            <div class="fc-card fc-plan-card {{ $index === 0 ? 'fc-plan-card--featured' : '' }} {{ $isGreen ? 'fc-profile-card--green' : 'fc-profile-card--yellow' }}" style="margin-top: {{ $index === 0 ? '10px' : '0' }};">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span style="font-size: 1.25rem;">{{ $group['icon'] }}</span>
                                    <span class="fc-profile-code">{{ $group['code'] }}</span>
                                </div>
                                <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: 0.25rem;">{{ $group['short_label'] }}</h3>
                                <p style="font-size: 0.8rem; color: var(--fc-muted); margin-bottom: 0; line-height: 1.45;">{{ $group['plan_description'] }}</p>

                                <div class="fc-price-monthly active">
                                    @if ($monthly)
                                        <div class="fc-plan-price">{{ $monthly->formatted_price }}</div>
                                        <div class="fc-plan-interval">por mês</div>
                                    @else
                                        <div class="fc-plan-price">—</div>
                                    @endif
                                </div>
                                <div class="fc-price-yearly">
                                    @if ($yearly)
                                        <div class="fc-plan-price">
                                            {{ $yearly->formatted_price }}
                                            <span class="fc-tag-off">{{ $annualDiscount }}% OFF</span>
                                        </div>
                                        <div class="fc-plan-interval">por ano (economia de {{ $annualDiscount }}%)</div>
                                    @else
                                        <div class="fc-plan-price">—</div>
                                    @endif
                                </div>

                                <ul class="fc-plan-features">
                                    @if ($group['role'] === 'player')
                                        <li>Perfil esportivo e vitrine de talentos</li>
                                        <li>Upload de vídeos e estatísticas</li>
                                        <li>Contato com profissionais do mercado</li>
                                    @elseif ($group['key'] === 'g2')
                                        <li>Busca avançada e favoritos</li>
                                        <li>Gestão de carreira e negócios</li>
                                        <li>Mensagens com atletas</li>
                                    @elseif ($group['key'] === 'g3')
                                        <li>Scouting e filtros detalhados</li>
                                        <li>Desenvolvimento de talentos</li>
                                        <li>Comunicação direta com jogadores</li>
                                    @else
                                        <li>Peneiras e gestão de projetos</li>
                                        <li>Base de candidatos centralizada</li>
                                        <li>Acesso completo à plataforma</li>
                                    @endif
                                </ul>

                                <a href="{{ route('onboarding.user-type') }}" class="fc-btn-green w-100 text-center" style="font-size: 0.85rem;">
                                    Assinar {{ $group['code'] }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <p class="text-center mt-4 mb-0" style="font-size: 0.8rem; color: var(--fc-muted);">
                    Todos os planos incluem acesso imediato após confirmação do pagamento. Valores em reais (BRL).
                </p>
            </div>
        </section>

        {{-- Indique e Ganhe --}}
        <section class="fc-section" style="padding-top: 0;">
            <div class="container">
                <div class="fc-referral-banner">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-8">
                            <p class="fc-pill mb-2"><span class="fc-pill-dot"></span> Indique e Ganhe</p>
                            <h2 class="fc-section-title" style="font-size: 1.5rem;">Ganhe 25% recorrente por indicação</h2>
                            <p class="mb-0" style="color: var(--fc-muted); max-width: 520px;">
                                Compartilhe seu link, indique novos assinantes e receba comissão em cada renovação.
                                Saque automático via PIX em até 2 dias após liberação.
                            </p>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <a href="{{ route('onboarding.user-type') }}" class="fc-btn-green">Criar conta e participar</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- FAQ --}}
        <section class="fc-section" id="faq">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-4">
                        <p class="fc-pill mb-2"><span class="fc-pill-dot"></span> FAQ</p>
                        <h2 class="fc-section-title">Dúvidas frequentes</h2>
                        <p class="fc-section-lead mb-0">Respostas rápidas sobre acesso, planos e uso da plataforma.</p>
                    </div>
                    <div class="col-lg-8">
                        <div class="fc-card" style="padding: 0 1.5rem;">
                            <div class="fc-faq-item">
                                <p class="fc-faq-q">Qual a diferença entre G1, G2, G3 e G4?</p>
                                <p class="fc-faq-a">
                                    <strong>G1</strong> é para atletas (perfil e vitrine). <strong>G2</strong> para empresários e agentes.
                                    <strong>G3</strong> para treinadores e olheiros. <strong>G4</strong> para clubes, projetos e peneiras.
                                    Cada perfil tem campos e preços específicos.
                                </p>
                            </div>
                            <div class="fc-faq-item">
                                <p class="fc-faq-q">Preciso pagar antes de acessar o app?</p>
                                <p class="fc-faq-a">
                                    Sim. O FootConnect é um app fechado para assinantes. Você escolhe o perfil, o plano (mensal ou anual),
                                    realiza o pagamento seguro e em seguida completa seu cadastro.
                                </p>
                            </div>
                            <div class="fc-faq-item">
                                <p class="fc-faq-q">Vale a pena o plano anual?</p>
                                <p class="fc-faq-a">
                                    O plano anual oferece {{ $annualDiscount }}% de desconto em relação ao valor mensal acumulado em 12 meses.
                                    Ideal para quem vai usar a plataforma de forma contínua ao longo da temporada.
                                </p>
                            </div>
                            <div class="fc-faq-item">
                                <p class="fc-faq-q">Posso cancelar ou trocar de plano?</p>
                                <p class="fc-faq-a">
                                    Sim. Em <strong>Configurações → Plano</strong> você visualiza sua assinatura, período de renovação
                                    e pode cancelar a qualquer momento, com total transparência.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- CTA final --}}
        <section class="fc-section" style="padding-top: 0;">
            <div class="container">
                <div class="fc-cta-band">
                    <h2 class="fc-section-title mb-2">Pronto para entrar no jogo?</h2>
                    <p class="mb-4" style="color: var(--fc-muted);">Escolha seu perfil, assine e comece a se conectar hoje.</p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        <a href="{{ route('onboarding.user-type') }}" class="fc-btn-green">Criar conta FootConnect</a>
                        <a href="{{ route('login') }}" class="fc-btn-ghost">Já sou assinante</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer>
            <div class="container">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="fc-logo" style="width: 32px; height: 32px; font-size: 0.75rem;">FC</div>
                        <span style="font-size: 0.8rem; color: var(--fc-muted);">
                            © {{ date('Y') }} FootConnect. Conexão profissional no futebol.
                        </span>
                    </div>
                    <div class="d-flex gap-3" style="font-size: 0.8rem; color: var(--fc-muted);">
                        <span>App fechado para assinantes</span>
                        <span>Pagamento seguro via Stripe</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.fc-billing-btn');
            const monthlyBlocks = document.querySelectorAll('.fc-price-monthly');
            const yearlyBlocks = document.querySelectorAll('.fc-price-yearly');

            buttons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const billing = btn.dataset.billing;
                    buttons.forEach(function (b) { b.classList.remove('active'); });
                    btn.classList.add('active');

                    monthlyBlocks.forEach(function (el) {
                        el.classList.toggle('active', billing === 'monthly');
                    });
                    yearlyBlocks.forEach(function (el) {
                        el.classList.toggle('active', billing === 'yearly');
                    });
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
