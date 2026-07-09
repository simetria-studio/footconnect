<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Finalize sua assinatura — FootConnect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --fc-bg: #0d2818;
            --fc-card: rgba(15, 36, 25, 0.95);
            --fc-green: #22c55e;
            --fc-text: #f8fafc;
            --fc-muted: #94a3b8;
            --fc-border: rgba(148, 163, 184, 0.25);
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Inter", sans-serif;
            color: var(--fc-text);
            background:
                radial-gradient(ellipse 80% 50% at 50% -10%, rgba(34, 197, 94, 0.18), transparent 55%),
                linear-gradient(180deg, #0d2818 0%, #0a1f14 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .fc-gate {
            width: 100%;
            max-width: 420px;
            text-align: center;
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 24px;
            padding: 2.25rem 1.75rem;
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.35);
        }

        .fc-gate-brand {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: -0.02em;
        }

        .fc-gate-logo {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--fc-green), #16a34a);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 800;
            color: #000;
        }

        .fc-gate h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 0.65rem;
            letter-spacing: -0.02em;
        }

        .fc-gate p {
            margin: 0 0 1.75rem;
            font-size: 0.95rem;
            line-height: 1.5;
            color: var(--fc-muted);
        }

        .fc-gate-cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--fc-green), #16a34a);
            color: #000;
            font-weight: 700;
            font-size: 0.95rem;
            text-decoration: none;
            padding: 0.85rem 1.25rem;
            transition: transform 0.15s ease, filter 0.15s ease;
        }

        .fc-gate-cta:hover {
            filter: brightness(1.08);
            transform: translateY(-1px);
            color: #000;
        }

        .fc-gate-secondary {
            display: inline-block;
            margin-top: 1rem;
            font-size: 0.85rem;
            color: var(--fc-muted);
            text-decoration: none;
        }

        .fc-gate-secondary:hover {
            color: var(--fc-text);
        }
    </style>
</head>
<body>
<main class="fc-gate">
    <div class="fc-gate-brand">
        <span class="fc-gate-logo">FC</span>
        FootConnect
    </div>

    <h1>Finalize sua assinatura</h1>
    <p>
        Sua conta foi criada, mas o pagamento ainda não foi concluído.
        Escolha um plano para liberar o acesso ao FootConnect.
    </p>

    <a href="{{ auth()->check() && auth()->user()->plan_group ? route('onboarding.plans') : route('onboarding.user-type') }}" class="fc-gate-cta">
        Ir para o pagamento
    </a>

    @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="fc-gate-secondary" style="background: none; border: 0; cursor: pointer; font: inherit;">
                Sair da conta
            </button>
        </form>
    @else
        <a href="{{ route('login') }}" class="fc-gate-secondary">Fazer login</a>
    @endauth
</main>
</body>
</html>
