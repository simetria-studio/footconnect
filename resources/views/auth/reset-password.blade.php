<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nova senha — FootConnect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('auth.partials.card-styles')
</head>
<body>
<div class="fc-login-wrapper">
    <div class="fc-login-card">
        <div class="fc-logo-badge">FC</div>

        <h1 class="fc-title">Nova senha</h1>
        <p class="fc-subtitle">
            Escolha uma nova senha para acessar sua conta FootConnect.
        </p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="fc-form-group">
                <label for="email" class="fc-form-label">E-mail</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $email) }}"
                    required
                    autofocus
                    class="fc-form-input"
                    placeholder="seu@email.com"
                >
            </div>

            <div class="fc-form-group">
                <label for="password" class="fc-form-label">Nova senha</label>
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
                <label for="password_confirmation" class="fc-form-label">Confirmar nova senha</label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    class="fc-form-input"
                    placeholder="Repita a nova senha"
                >
            </div>

            @if ($errors->any())
                <div class="fc-error-message">
                    {{ $errors->first() }}
                </div>
            @endif

            <button type="submit" class="fc-btn-primary">
                Redefinir senha
            </button>

            <p class="fc-link-text">
                <a href="{{ route('login') }}">Voltar ao login</a>
            </p>
        </form>
    </div>
</div>
</body>
</html>
