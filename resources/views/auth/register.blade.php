@extends('layouts.app')

@section('title', 'Criar conta — FootConnect')

@php
    $role = old('role', request('role', 'player'));
@endphp

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="text-center mb-4">
                <h1 class="h3 fw-bold fc-text-primary mb-2">Criar conta</h1>
                <p class="small fc-text-secondary">Complete seu cadastro para acessar o FootConnect.</p>
            </div>

            <form method="POST" action="{{ route('register.post') }}" class="card fc-card">
                <div class="card-body">
                    @csrf

                    <input type="hidden" name="checkout_session_id" value="{{ session('checkout_session_id', '') }}">

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar senha</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipo de usuário</label>
                        <input type="hidden" name="role" value="{{ $role }}">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="card fc-card {{ $role === 'player' ? 'border-success' : '' }}" style="cursor: pointer; {{ $role === 'player' ? 'background: var(--fc-accent-green-light);' : '' }}" onclick="document.querySelector('input[name=role]').value='player'; location.reload();">
                                    <div class="card-body p-3">
                                        <p class="fw-semibold mb-1 small">Jogador</p>
                                        <p class="small fc-text-secondary mb-0">Perfil esportivo completo.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card fc-card {{ $role === 'scout' ? 'border-success' : '' }}" style="cursor: pointer; {{ $role === 'scout' ? 'background: var(--fc-accent-green-light);' : '' }}" onclick="document.querySelector('input[name=role]').value='scout'; location.reload();">
                                    <div class="card-body p-3">
                                        <p class="fw-semibold mb-1 small">Empresário / Agente / Treinador / Olheiro</p>
                                        <p class="small fc-text-secondary mb-0">Busca avançada de talentos.</p>
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

                    <button type="submit" class="btn btn-success w-100 mb-3">Criar conta</button>

                    <p class="text-center small text-muted mb-0">
                        Já é cadastrado?
                        <a href="{{ route('login') }}" class="text-decoration-none" style="color: var(--fc-accent-green);">
                            Entrar
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
