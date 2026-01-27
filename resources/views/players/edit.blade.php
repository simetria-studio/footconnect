@extends('layouts.app')

@section('title', 'Editar perfil de jogador — FootConnect')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h5 fw-bold fc-text-primary mb-1">Seu perfil de jogador</h1>
                    <p class="small fc-text-secondary mb-0">
                        Preencha suas informações para aumentar suas chances de ser encontrado por profissionais.
                    </p>
                </div>
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
            </div>

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('me.player-profile.update') }}" class="card fc-card">
                <div class="card-body">
                    @csrf

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="full_name" class="form-label">Nome completo</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}" required>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="position" class="form-label">Posição</label>
                            <input type="text" class="form-control" id="position" name="position" value="{{ old('position', $profile->position) }}" placeholder="Ex: Atacante">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="age" class="form-label">Idade</label>
                            <input type="number" class="form-control" id="age" name="age" value="{{ old('age', $profile->age) }}" min="14" max="50">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="height_cm" class="form-label">Altura (cm)</label>
                            <input type="number" class="form-control" id="height_cm" name="height_cm" value="{{ old('height_cm', $profile->height_cm) }}" min="100" max="250">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="weight_kg" class="form-label">Peso (kg)</label>
                            <input type="number" class="form-control" id="weight_kg" name="weight_kg" value="{{ old('weight_kg', $profile->weight_kg) }}" min="40" max="150" step="0.1">
                        </div>

                        <div class="col-12">
                            <label for="current_club" class="form-label">Clube atual</label>
                            <input type="text" class="form-control" id="current_club" name="current_club" value="{{ old('current_club', $profile->current_club) }}" placeholder="Ex: Clube ABC">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="city" class="form-label">Cidade</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $profile->city ?? $user->city) }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="state" class="form-label">Estado</label>
                            <input type="text" class="form-control" id="state" name="state" value="{{ old('state', $profile->state ?? $user->state) }}" placeholder="Ex: SP">
                        </div>

                        <div class="col-12">
                            <label for="dominant_foot" class="form-label">Pé dominante</label>
                            <select class="form-select" id="dominant_foot" name="dominant_foot">
                                <option value="">Selecione</option>
                                <option value="right" {{ old('dominant_foot', $profile->dominant_foot) === 'right' ? 'selected' : '' }}>Destro</option>
                                <option value="left" {{ old('dominant_foot', $profile->dominant_foot) === 'left' ? 'selected' : '' }}>Canhoto</option>
                                <option value="both" {{ old('dominant_foot', $profile->dominant_foot) === 'both' ? 'selected' : '' }}>Ambidestro</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="bio" class="form-label">Biografia curta</label>
                            <textarea class="form-control" id="bio" name="bio" rows="4" placeholder="Conte um pouco sobre você...">{{ old('bio', $profile->bio) }}</textarea>
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

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success w-100">Salvar perfil</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
