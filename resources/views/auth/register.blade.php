@extends('layouts.app')

@section('title', __('ui.auth.register_title'))

@php
    $role = old('role', request('role', 'player'));
@endphp

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="text-center mb-4">
                <h1 class="h3 fw-bold fc-text-primary mb-2">{{ __('ui.nav.signup') }}</h1>
                <p class="small fc-text-secondary">{{ __('ui.auth.register_lead') }}</p>
            </div>

            <form method="POST" action="{{ route('register.post') }}" class="card fc-card">
                <div class="card-body">
                    @csrf

                    <input type="hidden" name="checkout_session_id" value="{{ session('checkout_session_id', '') }}">

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('ui.auth.email') }}</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('ui.auth.password') }}</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">{{ __('ui.auth.confirm_password_short') }}</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('ui.auth.user_type') }}</label>
                        <input type="hidden" name="role" value="{{ $role }}">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="card fc-card {{ $role === 'player' ? 'border-success' : '' }}" style="cursor: pointer; {{ $role === 'player' ? 'background: var(--fc-accent-green-light);' : '' }}" onclick="document.querySelector('input[name=role]').value='player'; location.reload();">
                                    <div class="card-body p-3">
                                        <p class="fw-semibold mb-1 small">{{ __('ui.plans.groups.g1.short_label') }}</p>
                                        <p class="small fc-text-secondary mb-0">{{ __('ui.auth.player_hint') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card fc-card {{ $role === 'scout' ? 'border-success' : '' }}" style="cursor: pointer; {{ $role === 'scout' ? 'background: var(--fc-accent-green-light);' : '' }}" onclick="document.querySelector('input[name=role]').value='scout'; location.reload();">
                                    <div class="card-body p-3">
                                        <p class="fw-semibold mb-1 small">{{ __('ui.auth.scout_label') }}</p>
                                        <p class="small fc-text-secondary mb-0">{{ __('ui.auth.scout_hint') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-success w-100 mb-3">{{ __('ui.nav.signup') }}</button>

                    <p class="text-center small text-muted mb-0">
                        {{ __('ui.auth.already') }}
                        <a href="{{ route('login') }}" class="text-decoration-none" style="color: var(--fc-accent-green);">
                            {{ __('ui.nav.login') }}
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
