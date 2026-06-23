<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entrar — FootConnect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('auth.partials.card-styles')
    <style>
        .fc-remember-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        .fc-checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }
        .fc-checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 1px solid rgba(148, 163, 184, 0.3);
            background: rgba(5, 6, 8, 0.8);
            cursor: pointer;
            accent-color: #22c55e;
        }
        .fc-checkbox-wrapper input[type="checkbox"]:checked {
            background: #22c55e;
            border-color: #22c55e;
        }
        .fc-checkbox-label {
            font-size: 0.85rem;
            color: #e5e7eb;
            cursor: pointer;
            user-select: none;
        }
    </style>
</head>
<body>
<div class="fc-login-wrapper">
    <div class="fc-login-card">
        <div class="fc-logo-badge">FC</div>
        
        <h1 class="fc-title">Bem-vindo de volta</h1>
        <p class="fc-subtitle">
            Acesse sua conta para continuar no FootConnect.
        </p>

        <form method="POST" action="{{ route('login.post') }}">
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
            </div>

            <div class="fc-form-group">
                <label for="password" class="fc-form-label">Senha</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    class="fc-form-input"
                    placeholder="Digite sua senha"
                >
                <div class="fc-forgot-link">
                    <a href="{{ route('password.request') }}">Esqueci minha senha</a>
                </div>
            </div>

            <div class="fc-remember-group">
                <label class="fc-checkbox-wrapper">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span class="fc-checkbox-label">Lembrar-me</span>
                </label>
            </div>

            @if (session('status'))
                <div class="fc-success-message">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="fc-error-message">
                    {{ $errors->first() }}
                </div>
            @endif

            <button type="submit" class="fc-btn-primary">
                Entrar
            </button>

            <p class="fc-link-text">
                Ainda não tem conta?
                <a href="{{ route('landing') }}">Criar conta</a>
            </p>
        </form>
    </div>
</div>
</body>
</html>
