<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FootConnect — Conectando talentos ao futebol profissional</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --fc-bg-primary: #05060a;
            --fc-bg-secondary: #0b0c11;
            --fc-bg-card: #111318;
            --fc-bg-soft: #12141b;
            --fc-accent-green: #22c55e;
            --fc-accent-green-hover: #4ade80;
            --fc-text-primary: #ffffff;
            --fc-text-secondary: #a0a0b3;
            --fc-text-muted: #6b7280;
            --fc-border-subtle: rgba(148, 163, 184, 0.18);
        }

        body {
            background: radial-gradient(circle at top, #101827 0%, #020617 40%, #020617 100%);
            font-family: 'Inter', sans-serif;
            color: var(--fc-text-primary);
            min-height: 100vh;
        }

        .hero-section {
            padding: 3rem 0 2rem;
        }

        .logo-badge {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--fc-accent-green) 0%, #16a34a 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.25rem;
            color: #000;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }

        .nav-link-custom {
            color: var(--fc-text-secondary);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link-custom:hover {
            color: var(--fc-text-primary);
            background: rgba(255, 255, 255, 0.05);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--fc-accent-green) 0%, #16a34a 100%);
            border: none;
            color: #000;
            font-weight: 600;
            padding: 0.625rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.2);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
            background: linear-gradient(135deg, var(--fc-accent-green-hover) 0%, var(--fc-accent-green) 100%);
        }

        .hero-card {
            background: radial-gradient(circle at top left, rgba(34, 197, 94, 0.12), transparent 55%), var(--fc-bg-card);
            border: 1px solid var(--fc-border-subtle);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #ffffff 0%, var(--fc-text-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.125rem;
            color: var(--fc-text-secondary);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .feature-item:last-child {
            border-bottom: none;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .feature-icon.green {
            background: rgba(34, 197, 94, 0.15);
            color: var(--fc-accent-green);
        }

        .feature-icon.amber {
            background: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
        }

        .feature-icon.yellow {
            background: rgba(234, 179, 8, 0.2);
            color: #eab308;
        }

        .feature-content h4 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--fc-text-primary);
        }

        .feature-content p {
            font-size: 0.875rem;
            color: var(--fc-text-muted);
            margin: 0;
            line-height: 1.5;
        }

        .cta-section {
            margin-top: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .btn-secondary-custom {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--fc-text-primary);
            font-weight: 500;
            padding: 0.625rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }

        .btn-secondary-custom:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.3);
            color: var(--fc-text-primary);
        }

        .visual-section {
            background: radial-gradient(circle at top, rgba(34, 197, 94, 0.18), transparent 55%), linear-gradient(135deg, #050816 0%, #020617 100%);
            border-radius: 16px;
            padding: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 400px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .visual-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 0%, rgba(34, 197, 94, 0.18), transparent 55%),
                radial-gradient(circle at 80% 100%, rgba(94, 234, 212, 0.12), transparent 55%);
            opacity: 0.6;
            pointer-events: none;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .soccer-icon {
            width: 200px;
            height: 200px;
            position: relative;
            z-index: 1;
        }

        .soccer-icon svg {
            width: 100%;
            height: 100%;
            filter: drop-shadow(0 14px 40px rgba(15, 23, 42, 0.9));
            animation: float 6s ease-in-out infinite;
        }

        .section-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--fc-text-muted);
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(148, 163, 184, 0.35);
            font-size: 0.75rem;
            color: var(--fc-text-secondary);
        }

        .pill-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--fc-accent-green);
            box-shadow: 0 0 12px rgba(34, 197, 94, 0.9);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .section-subtitle {
            font-size: 0.95rem;
            color: var(--fc-text-secondary);
            margin-bottom: 1.5rem;
        }

        .fc-card-soft {
            background: var(--fc-bg-soft);
            border-radius: 16px;
            border: 1px solid var(--fc-border-subtle);
            padding: 1.5rem;
        }

        .fc-metric-value {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .fc-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            border-radius: 999px;
            padding: 0.15rem 0.6rem;
            font-size: 0.7rem;
            color: var(--fc-text-secondary);
            border: 1px solid rgba(148, 163, 184, 0.35);
            background: rgba(15, 23, 42, 0.8);
        }

        footer {
            border-top: 1px solid rgba(15, 23, 42, 0.85);
            padding: 1.5rem 0 2rem;
            margin-top: 2.5rem;
            background: linear-gradient(to top, #020617, transparent);
        }

        @media (max-width: 991.98px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-card {
                padding: 2rem;
            }

            .visual-section {
                min-height: 300px;
                padding: 2rem;
            }

            .soccer-icon {
                width: 150px;
                height: 150px;
            }
        }

        @media (max-width: 575.98px) {
            .hero-title {
                font-size: 1.75rem;
            }

            .hero-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg py-3">
        <div class="container">
            <div class="d-flex align-items-center gap-3">
                <div class="logo-badge">FC</div>
                <div>
                    <h5 class="mb-0 fw-bold">FootConnect</h5>
                    <small class="text-muted" style="color: var(--fc-text-muted) !important; font-size: 0.75rem;">Conexão profissional no futebol</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('login') }}" class="nav-link-custom">Entrar</a>
                <a href="{{ route('onboarding.user-type') }}" class="btn btn-primary-custom">Criar conta</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="hero-card">
                        <h1 class="hero-title">
                            Conecte talentos ao futebol profissional
                        </h1>
                        <p class="hero-subtitle">
                            O FootConnect é a plataforma profissional de networking esportivo e vitrine de talentos do futebol. Conecte jogadores, empresários, agentes, treinadores e olheiros em um só lugar.
                        </p>

                        <div class="features-list">
                            <div class="feature-item">
                                <div class="feature-icon green">⚽</div>
                                <div class="feature-content">
                                    <h4>Para Jogadores</h4>
                                    <p>Crie um perfil esportivo completo com idade, altura, posição, pé dominante, vídeos, fotos e estatísticas.</p>
                                </div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon amber">🔍</div>
                                <div class="feature-content">
                                    <h4>Para Empresários, Agentes, Treinadores e Olheiros</h4>
                                    <p>Encontre talentos com filtros avançados, salve favoritos e converse por mensagens internas.</p>
                                </div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon yellow">⭐</div>
                                <div class="feature-content">
                                    <h4>Acesso 100% profissional</h4>
                                    <p>App fechado, só para assinantes, ideal para apresentar a clubes, agências e investidores.</p>
                                </div>
                            </div>
                        </div>

                        <div class="cta-section">
                            <a href="{{ route('onboarding.user-type') }}" class="btn btn-primary-custom w-100">
                                Criar conta FootConnect
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-secondary-custom">
                                Já sou assinante, quero entrar
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="visual-section">
                        <div class="soccer-icon">
                            <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <!-- Campo de futebol estilizado -->
                                <rect x="20" y="20" width="160" height="160" rx="20" fill="rgba(255, 255, 255, 0.03)" stroke="rgba(255, 255, 255, 0.2)" stroke-width="2"/>
                                <line x1="100" y1="20" x2="100" y2="180" stroke="rgba(255, 255, 255, 0.2)" stroke-width="2"/>
                                <circle cx="100" cy="100" r="30" fill="none" stroke="rgba(255, 255, 255, 0.2)" stroke-width="2"/>
                                <circle cx="100" cy="100" r="5" fill="rgba(255, 255, 255, 0.4)"/>
                                <!-- Linhas do campo -->
                                <rect x="20" y="50" width="40" height="100" fill="none" stroke="rgba(255, 255, 255, 0.15)" stroke-width="1.5" rx="5"/>
                                <rect x="140" y="50" width="40" height="100" fill="none" stroke="rgba(255, 255, 255, 0.15)" stroke-width="1.5" rx="5"/>
                                <!-- Bola de futebol -->
                                <circle cx="100" cy="100" r="25" fill="rgba(255, 255, 255, 0.05)" stroke="rgba(255, 255, 255, 0.2)" stroke-width="2"/>
                                <path d="M100 75 L115 100 L100 125 L85 100 Z" fill="none" stroke="rgba(255, 255, 255, 0.2)" stroke-width="1.5"/>
                                <path d="M100 85 L110 100 L100 115 L90 100 Z" fill="rgba(255, 255, 255, 0.1)"/>
                                <!-- Destaque verde apenas no centro -->
                                <circle cx="100" cy="100" r="8" fill="var(--fc-accent-green)" opacity="0.6"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Como funciona -->
    <section class="py-4 py-md-5">
        <div class="container">
            <div class="row g-4 align-items-start">
                <div class="col-lg-5">
                    <span class="section-label">Fluxo do FootConnect</span>
                    <h2 class="section-title mt-2">Como o app funciona na prática</h2>
                    <p class="section-subtitle">
                        Em poucos minutos você cria sua conta, escolhe o tipo de usuário e já começa a se conectar de forma
                        profissional com o mercado do futebol.
                    </p>
                    <div class="fc-card-soft mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <span class="fc-badge me-2">Passos principais</span>
                        </div>
                        <ol class="mb-0 ps-3" style="font-size: 0.9rem; color: var(--fc-text-secondary);">
                            <li>Escolha se é <strong>Jogador</strong> ou <strong>Profissional (empresário, agente, treinador, olheiro)</strong>.</li>
                            <li>Selecione o plano, faça o pagamento seguro e tenha acesso imediato ao app.</li>
                            <li>Complete seu perfil e comece a se conectar via busca, favoritos e mensagens internas.</li>
                        </ol>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="fc-card-soft h-100">
                                <div class="pill mb-2">
                                    <span class="pill-dot"></span>
                                    Jogadores
                                </div>
                                <h5 class="mb-2">Perfil esportivo completo</h5>
                                <p class="mb-3" style="font-size: 0.9rem; color: var(--fc-text-secondary);">
                                    Idade, altura, peso, posição, pé dominante, vídeos, fotos, estatísticas e biografia curta em um
                                    só lugar, pensado para avaliação profissional.
                                </p>
                                <ul class="mb-0 ps-3" style="font-size: 0.85rem; color: var(--fc-text-muted);">
                                    <li>Upload de vídeos e destaques de jogos</li>
                                    <li>Estatísticas por temporada</li>
                                    <li>Visão clara para empresários e clubes</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="fc-card-soft h-100">
                                <div class="pill mb-2" style="border-color: rgba(234,179,8,0.6);">
                                    <span class="pill-dot" style="background:#eab308; box-shadow:0 0 12px rgba(234,179,8,0.8);"></span>
                                    Profissionais
                                </div>
                                <h5 class="mb-2">Busca avançada de talentos</h5>
                                <p class="mb-3" style="font-size: 0.9rem; color: var(--fc-text-secondary);">
                                    Filtros por posição, idade, cidade, pé dominante e muito mais, com possibilidade de favoritar e
                                    falar diretamente com o atleta.
                                </p>
                                <ul class="mb-0 ps-3" style="font-size: 0.85rem; color: var(--fc-text-muted);">
                                    <li>Busca segmentada por atributos esportivos</li>
                                    <li>Lista de favoritos para organizar scouting</li>
                                    <li>Mensagens internas para contato rápido</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Para quem é + Benefícios -->
    <section class="py-4 py-md-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6">
                    <span class="section-label">Para quem é</span>
                    <h2 class="section-title mt-2">Um app pensado para as duas pontas do mercado</h2>
                    <p class="section-subtitle">
                        Jogadores ganham visibilidade profissional, e empresários, agentes, treinadores e olheiros ganham velocidade
                        e organização no scouting.
                    </p>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="fc-card-soft h-100">
                                <h6 class="mb-1">Jogadores</h6>
                                <p class="mb-2" style="font-size: 0.85rem; color: var(--fc-text-secondary);">
                                    Ideal para quem quer ter um material profissional para apresentar a clubes, agentes e investidores.
                                </p>
                                <span class="fc-badge">Plano jogador • R$ 19,90 / 3 meses</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="fc-card-soft h-100">
                                <h6 class="mb-1">Profissionais</h6>
                                <p class="mb-2" style="font-size: 0.85rem; color: var(--fc-text-secondary);">
                                    Feito para quem vive de gestão de carreira, scouting e montagem de elenco.
                                </p>
                                <span class="fc-badge">Planos mensal e anual com desconto</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <span class="section-label">Por que usar o FootConnect</span>
                    <div class="row g-3 mt-2">
                        <div class="col-sm-4">
                            <div class="fc-card-soft text-center">
                                <div class="fc-metric-value mb-1">100%</div>
                                <p class="mb-1" style="font-size: 0.8rem;">Acesso fechado</p>
                                <p class="mb-0" style="font-size: 0.75rem; color: var(--fc-text-muted);">
                                    Somente assinantes, ambiente profissional.
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="fc-card-soft text-center">
                                <div class="fc-metric-value mb-1">Fluxo</div>
                                <p class="mb-1" style="font-size: 0.8rem;">Claro e guiado</p>
                                <p class="mb-0" style="font-size: 0.75rem; color: var(--fc-text-muted);">
                                    Do pagamento ao acesso em poucos passos.
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="fc-card-soft text-center">
                                <div class="fc-metric-value mb-1">Ready</div>
                                <p class="mb-1" style="font-size: 0.8rem;">para escalar</p>
                                <p class="mb-0" style="font-size: 0.75rem; color: var(--fc-text-muted);">
                                    Estrutura pensada para investidores.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Planos e preços (resumo) -->
    <section class="py-4 py-md-5">
        <div class="container">
            <div class="row g-4 align-items-start">
                <div class="col-lg-4">
                    <span class="section-label">Planos e acesso</span>
                    <h2 class="section-title mt-2">Assinatura simples e transparente</h2>
                    <p class="section-subtitle">
                        O app só é liberado após pagamento. Cada tipo de usuário tem um plano pensado para sua necessidade,
                        com cobrança recorrente e cancelamento simples.
                    </p>
                    <a href="{{ route('onboarding.user-type') }}" class="btn btn-primary-custom w-100">
                        Ver planos e criar conta
                    </a>
                </div>
                <div class="col-lg-8">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="fc-card-soft h-100">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="mb-0">Jogadores</h5>
                                    <span class="fc-badge">Perfil de atleta</span>
                                </div>
                                <p class="mb-1" style="font-size: 1.4rem; font-weight: 700;">
                                    R$ 19,90
                                    <span style="font-size: 0.8rem; color: var(--fc-text-muted);">/ a cada 3 meses</span>
                                </p>
                                <p class="mb-3" style="font-size: 0.85rem; color: var(--fc-text-secondary);">
                                    Ideal para manter um material sempre atualizado para oportunidades profissionais.
                                </p>
                                <ul class="mb-0 ps-3" style="font-size: 0.8rem; color: var(--fc-text-muted);">
                                    <li>Acesso ao painel do jogador</li>
                                    <li>Upload de vídeos, fotos e estatísticas</li>
                                    <li>Chat com profissionais interessados</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="fc-card-soft h-100">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="mb-0">Profissionais</h5>
                                    <div class="d-flex flex-column align-items-end gap-1">
                                        <span class="fc-badge">
                                            <span style="width:6px;height:6px;border-radius:50%;background:#facc15;display:inline-block;"></span>
                                            Mais vantajoso
                                        </span>
                                        <span class="fc-badge">Scouting e gestão</span>
                                    </div>
                                </div>
                                <p class="mb-1" style="font-size: 1rem; color: var(--fc-text-secondary);">
                                    Mensal: <strong>R$ 29,90</strong>
                                </p>
                                <p class="mb-2" style="font-size: 1rem; color: var(--fc-text-secondary);">
                                    Anual: <strong>R$ 251,20</strong>
                                    <span style="font-size: 0.8rem; color: var(--fc-text-muted);">(30% de desconto)</span>
                                </p>
                                <p class="mb-3" style="font-size: 0.85rem; color: var(--fc-text-secondary);">
                                    Pensado para empresários, agentes, treinadores e olheiros que precisam de organização profissional.
                                </p>
                                <ul class="mb-0 ps-3" style="font-size: 0.8rem; color: var(--fc-text-muted);">
                                    <li>Busca avançada por atletas</li>
                                    <li>Lista de jogadores favoritos</li>
                                    <li>Mensagens internas centralizadas</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ enxuto -->
    <section class="py-4 py-md-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5">
                    <span class="section-label">Dúvidas rápidas</span>
                    <h2 class="section-title mt-2">Perguntas frequentes</h2>
                    <p class="section-subtitle">
                        Resumimos as principais dúvidas sobre acesso, pagamento e uso do FootConnect.
                    </p>
                </div>
                <div class="col-lg-7">
                    <div class="fc-card-soft mb-2">
                        <h6 class="mb-1">Preciso pagar antes de criar minha conta?</h6>
                        <p class="mb-0" style="font-size: 0.9rem; color: var(--fc-text-secondary);">
                            Sim. O FootConnect é um app fechado, só para assinantes. Primeiro você escolhe o tipo de usuário e o plano,
                            realiza o pagamento e, em seguida, cria sua conta e tem acesso ao app.
                        </p>
                    </div>
                    <div class="fc-card-soft mb-2">
                        <h6 class="mb-1">O que muda entre conta de Jogador e conta de Profissional?</h6>
                        <p class="mb-0" style="font-size: 0.9rem; color: var(--fc-text-secondary);">
                            Jogadores focam na construção do perfil esportivo para serem vistos. Profissionais focam na busca, organização
                            e contato com esses jogadores. Cada fluxo e tela é adaptado para o objetivo de cada tipo de usuário.
                        </p>
                    </div>
                    <div class="fc-card-soft">
                        <h6 class="mb-1">Posso cancelar ou trocar de plano depois?</h6>
                        <p class="mb-0" style="font-size: 0.9rem; color: var(--fc-text-secondary);">
                            Sim. Dentro do app existe uma área de <strong>Configurações / Plano</strong> onde você visualiza sua assinatura,
                            período de renovação e opções de cancelamento, sempre com transparência.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="logo-badge" style="width:32px;height:32px;font-size:0.9rem;">FC</span>
                    <span style="font-size: 0.8rem; color: var(--fc-text-muted);">
                        © {{ date('Y') }} FootConnect. Conexão profissional no futebol.
                    </span>
                </div>
                <div class="d-flex gap-3" style="font-size: 0.8rem; color: var(--fc-text-muted);">
                    <span>App fechado para assinantes</span>
                    <span>Fluxo pronto para investidores</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
