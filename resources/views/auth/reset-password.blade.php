<!DOCTYPE html>
<html lang="{{ config('locales.supported.'.app()->getLocale().'.html', 'pt-BR') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('ui.auth.reset_title') }}</title>
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

        <h1 class="fc-title">{{ __('ui.auth.reset_heading') }}</h1>
        <p class="fc-subtitle">
            {{ __('ui.auth.reset_lead') }}
        </p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="fc-form-group">
                <label for="email" class="fc-form-label">{{ __('ui.auth.email') }}</label>
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
                <label for="password" class="fc-form-label">{{ __('ui.auth.new_password') }}</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    class="fc-form-input"
                    placeholder="{{ __('ui.auth.password_min') }}"
                >
            </div>

            <div class="fc-form-group">
                <label for="password_confirmation" class="fc-form-label">{{ __('ui.auth.confirm_password') }}</label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    class="fc-form-input"
                    placeholder="{{ __('ui.auth.confirm_placeholder') }}"
                >
            </div>

            @if ($errors->any())
                <div class="fc-error-message">
                    {{ $errors->first() }}
                </div>
            @endif

            <button type="submit" class="fc-btn-primary">
                {{ __('ui.auth.reset_submit') }}
            </button>

            <p class="fc-link-text">
                <a href="{{ route('login') }}">{{ __('ui.auth.back_login') }}</a>
            </p>
        </form>
    </div>
</div>
</body>
</html>
