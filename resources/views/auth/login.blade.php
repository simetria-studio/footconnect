<!DOCTYPE html>
<html lang="{{ config('locales.supported.'.app()->getLocale().'.html', 'pt-BR') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('ui.auth.login_title') }}</title>
    @include('partials.favicon')
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
        <div class="d-flex justify-content-end mb-2">
            <x-locale-switcher />
        </div>
        <div class="fc-logo-badge">
            <a href="{{ route('landing') }}">@include('partials.brand-logo', ['height' => 72])</a>
        </div>
        
        <h1 class="fc-title">{{ __('ui.auth.welcome_back') }}</h1>
        <p class="fc-subtitle">
            {{ __('ui.auth.login_lead') }}
        </p>

        <form method="POST" action="{{ route('login.post') }}">
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

            <div class="fc-form-group">
                <label for="password" class="fc-form-label">{{ __('ui.auth.password') }}</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    class="fc-form-input"
                    placeholder="{{ __('ui.auth.password_placeholder') }}"
                >
                <div class="fc-forgot-link">
                    <a href="{{ route('password.request') }}">{{ __('ui.auth.forgot') }}</a>
                </div>
            </div>

            <div class="fc-remember-group">
                <label class="fc-checkbox-wrapper">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span class="fc-checkbox-label">{{ __('ui.auth.remember') }}</span>
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
                {{ __('ui.auth.login') }}
            </button>

            <p class="fc-link-text">
                {{ __('ui.auth.no_account') }}
                <a href="{{ route('landing') }}">{{ __('ui.auth.signup') }}</a>
            </p>
        </form>
    </div>
</div>
</body>
</html>
