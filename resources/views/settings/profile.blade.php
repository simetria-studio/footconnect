@extends('layouts.app')

@section('title', 'Configurações de perfil — FootConnect')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h1 class="h5 fw-bold fc-text-primary mb-0">Configurações da conta</h1>
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
            </div>

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Perfil Básico -->
            <div class="card fc-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Perfil básico</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('settings.profile.update') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="full_name" class="form-label">Nome completo</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="city" class="form-label">Cidade</label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $user->city) }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="state" class="form-label">Estado</label>
                                <input type="text" class="form-control" id="state" name="state" value="{{ old('state', $user->state) }}" placeholder="Ex: SP">
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger mt-3">
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mt-3">
                            <button type="submit" class="btn btn-success">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Alterar Senha -->
            <div class="card fc-card">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Alterar senha</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('settings.password.update') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="current_password" class="form-label">Senha atual</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>
                            <div class="col-12">
                                <label for="password" class="form-label">Nova senha</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-12">
                                <label for="password_confirmation" class="form-label">Confirmar nova senha</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-success">Atualizar senha</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
