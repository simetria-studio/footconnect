<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Escolha seu perfil — FootConnect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Inter", sans-serif;
            background: radial-gradient(circle at top, #0f172a 0%, #020617 45%, #020617 100%);
            color: #f9fafb;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .fc-onboarding-wrapper {
            width: 100%;
            max-width: 960px;
            padding: 2.5rem 1.5rem;
        }
        .fc-onboarding-card {
            background: rgba(15, 23, 42, 0.9);
            border-radius: 24px;
            border: 1px solid rgba(148, 163, 184, 0.35);
            box-shadow: 0 24px 80px rgba(15, 23, 42, 0.85);
            padding: 2rem;
        }
        @media (min-width: 992px) {
            .fc-onboarding-card {
                padding: 2.5rem 3rem;
            }
        }
        .fc-onboarding-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .fc-onboarding-subtitle {
            font-size: 0.95rem;
            color: #9ca3af;
            max-width: 440px;
        }
        .fc-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            border-radius: 999px;
            padding: 0.25rem 0.75rem;
            font-size: 0.7rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #e5e7eb;
            border: 1px solid rgba(148, 163, 184, 0.5);
            background: rgba(15, 23, 42, 0.9);
            margin-bottom: 0.75rem;
        }
        .fc-pill-dot {
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: #22c55e;
            box-shadow: 0 0 10px rgba(34, 197, 94, 0.9);
        }
        .fc-option {
            width: 100%;
            text-align: left;
            border-radius: 18px;
            padding: 1.25rem 1.5rem;
            border: 1px solid rgba(148, 163, 184, 0.35);
            background: radial-gradient(circle at top left, rgba(34, 197, 94, 0.12), transparent 60%), rgba(15, 23, 42, 0.9);
            color: #e5e7eb;
            cursor: pointer;
            transition: all 0.2s ease-out;
            display: flex;
            gap: 1rem;
        }
        .fc-option + .fc-option {
            margin-top: 0.85rem;
        }
        .fc-option-icon {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: rgba(15, 23, 42, 0.95);
            border: 1px solid rgba(148, 163, 184, 0.4);
            font-size: 1.2rem;
        }
        .fc-option-content-title {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #a7f3d0;
            margin-bottom: 0.15rem;
        }
        .fc-option-content-title--yellow {
            color: #facc15;
        }
        .fc-option h2 {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.15rem;
        }
        .fc-option p {
            font-size: 0.8rem;
            color: #9ca3af;
            margin: 0;
        }
        .fc-option:hover {
            border-color: rgba(34, 197, 94, 0.7);
            box-shadow: 0 14px 40px rgba(15, 23, 42, 0.9);
            transform: translateY(-2px);
        }
        .fc-hint {
            margin-top: 1.25rem;
            font-size: 0.8rem;
            color: #6b7280;
        }
        .fc-hint strong {
            color: #e5e7eb;
            font-weight: 500;
        }
    </style>
</head>
<body>
<div class="fc-onboarding-wrapper">
    <div class="fc-onboarding-card">
        <div class="mb-4">
            <div class="fc-pill">
                <span class="fc-pill-dot"></span>
                Passo 1 de 4 • Escolha seu perfil
            </div>
            <h1 class="fc-onboarding-title">Como você quer usar o FootConnect?</h1>
            <p class="fc-onboarding-subtitle">
                Escolha o tipo de conta que melhor representa seu papel no futebol profissional. Isso define o conteúdo e as telas que você verá dentro do app.
            </p>
        </div>

        <form method="POST" action="{{ route('onboarding.user-type.store') }}">
            @csrf

            <button
                type="submit"
                name="role"
                value="player"
                class="fc-option"
            >
                <div class="fc-option-icon">⚽</div>
                <div>
                    <p class="fc-option-content-title">Jogador</p>
                    <h2>Quero ser vitrine de talentos</h2>
                    <p>
                        Monte um perfil esportivo completo com dados físicos, posição, pé dominante, vídeos, fotos e estatísticas
                        para apresentar a clubes, empresários e agentes.
                    </p>
                </div>
            </button>

            <button
                type="submit"
                name="role"
                value="scout"
                class="fc-option"
            >
                <div class="fc-option-icon">🎯</div>
                <div>
                    <p class="fc-option-content-title fc-option-content-title--yellow">
                        Empresário / Agente / Treinador / Olheiro
                    </p>
                    <h2>Quero buscar talentos</h2>
                    <p>
                        Tenha acesso a filtros avançados de jogadores, lista de favoritos e contato direto via mensagens internas,
                        facilitando o seu trabalho de scouting e gestão de elenco.
                    </p>
                </div>
            </button>

            <p class="fc-hint">
                Você poderá ver e gerenciar seu tipo de conta depois em <strong>Configurações &gt; Perfil</strong>, mantendo sempre
                o foco em um uso profissional do FootConnect.
            </p>
        </form>
    </div>
</div>
</body>
</html>

