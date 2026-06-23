<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar senha — FootConnect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('auth.partials.card-styles')
</head>
<body>
<div class="fc-login-wrapper">
    <div class="fc-login-card">
        <div class="fc-logo-badge">FC</div>

        <h1 class="fc-title">Esqueceu a senha?</h1>
        <p class="fc-subtitle">
            Informe o e-mail da sua conta. Enviaremos um link para você criar uma nova senha.
        </p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="fc-form-group">
                <label for="email" class="fc-form-label">E-mail</label>
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
                Enviar link de recuperação
            </button>

            <p class="fc-link-text">
                Lembrou a senha?
                <a href="{{ route('login') }}">Voltar ao login</a>
            </p>
        </form>
    </div>
</div>
</body>
</html>
