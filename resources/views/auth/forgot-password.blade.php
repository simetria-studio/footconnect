<!DOCTYPE html>
<html lang="{{ config('locales.supported.'.app()->getLocale().'.html', 'pt-BR') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('ui.auth.forgot_title') }}</title>
    @include('partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('auth.partials.card-styles')
</head>
<body>
<div class="fc-login-wrapper">
    <div class="fc-login-card">
        <div class="d-flex justify-content-end mb-2">
            <x-locale-switcher />
        </div>
        <div class="fc-logo-badge">
            <a href="{{ route('landing') }}">@include('partials.brand-logo', ['height' => 72])</a>
        </div>

        <h1 class="fc-title">{{ __('ui.auth.forgot_heading') }}</h1>
        <p class="fc-subtitle">
            {{ __('ui.auth.forgot_lead') }}
        </p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="fc-form-group">
                <label for="email" class="fc-form-label">{{ __('ui.auth.email') }}</label>
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
                {{ __('ui.auth.send_link') }}
            </button>

            <p class="fc-link-text">
                {{ __('ui.auth.remembered') }}
                <a href="{{ route('login') }}">{{ __('ui.auth.back_login') }}</a>
            </p>
        </form>
    </div>
</div>
</body>
</html>
