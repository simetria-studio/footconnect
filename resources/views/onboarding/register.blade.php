@php
    $isPlayer = $role === 'player';
@endphp

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Criar conta — FootConnect</title>
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
            max-width: 480px;
            padding: 2.5rem 1.5rem;
        }
        .fc-onboarding-card {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 24px;
            border: 1px solid rgba(148, 163, 184, 0.35);
            box-shadow: 0 24px 80px rgba(15, 23, 42, 0.85);
            padding: 2rem;
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
        .fc-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .fc-subtitle {
            font-size: 0.95rem;
            color: #9ca3af;
            margin-bottom: 1.5rem;
        }
        .fc-user-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #22c55e;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }
        .fc-form-group {
            margin-bottom: 1.25rem;
        }
        .fc-form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #e5e7eb;
            margin-bottom: 0.5rem;
        }
        .fc-form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            background: rgba(5, 6, 8, 0.8);
            border: 1px solid rgba(148, 163, 184, 0.3);
            color: #f9fafb;
            font-size: 0.9rem;
            transition: all 0.2s ease-out;
        }
        .fc-form-input:focus {
            outline: none;
            border-color: #22c55e;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }
        .fc-btn-primary {
            width: 100%;
            border: none;
            border-radius: 999px;
            padding: 0.85rem 1.6rem;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            color: #020617;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            box-shadow: 0 10px 30px rgba(34, 197, 94, 0.4);
            transition: all 0.2s ease-out;
            margin-top: 1.5rem;
        }
        .fc-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 45px rgba(34, 197, 94, 0.55);
            background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
        }
        .fc-error-message {
            padding: 0.75rem 1rem;
            border-radius: 12px;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }
        .fc-help-text {
            font-size: 0.85rem;
            color: #9ca3af;
            margin-top: 0.5rem;
            line-height: 1.5;
        }
    </style>
</head>
<body>
<div class="fc-onboarding-wrapper">
    <div class="fc-onboarding-card">
        <div class="fc-pill">
            <span class="fc-pill-dot"></span>
            Passo 2 de 4 • Criar conta
        </div>

        <h1 class="fc-title">Criar sua conta</h1>
        <p class="fc-subtitle">
            Crie seu acesso ao FootConnect. Depois você escolherá seu plano de assinatura.
        </p>

        <div class="fc-user-type-badge">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            {{ $isPlayer ? 'Jogador' : 'Empresário / Agente / Treinador / Olheiro' }}
        </div>

        <form method="POST" action="{{ route('onboarding.register.post') }}">
            @csrf

            <div class="fc-form-group">
                <label for="email" class="fc-form-label">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="fc-form-input"
                    placeholder="seu@email.com"
                >
                <p class="fc-help-text">Use um email válido. Você precisará dele para fazer login.</p>
            </div>

            <div class="fc-form-group">
                <label for="password" class="fc-form-label">Senha</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    class="fc-form-input"
                    placeholder="Mínimo 8 caracteres"
                >
            </div>

            <div class="fc-form-group">
                <label for="password_confirmation" class="fc-form-label">Confirmar senha</label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    class="fc-form-input"
                    placeholder="Digite a senha novamente"
                >
            </div>

            @if ($errors->any())
                <div class="fc-error-message">
                    {{ $errors->first() }}
                </div>
            @endif

            <button type="submit" class="fc-btn-primary">
                Criar conta e continuar
            </button>
        </form>
    </div>
</div>
</body>
</html>
