@extends('layouts.app')

@section('title', 'Editar perfil profissional — FootConnect')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h5 fw-bold fc-text-primary mb-1">Seu perfil profissional</h1>
                    <p class="small fc-text-secondary mb-0">
                        Mostre para quem você trabalha, sua função e como atua no mercado do futebol.
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

            <form method="POST" action="{{ route('me.scout-profile.update') }}" class="card fc-card">
                <div class="card-body">
                    @csrf

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="full_name" class="form-label">Nome completo</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}" required>
                        </div>

                        <div class="col-12">
                            <label for="professional_type" class="form-label">Função</label>
                            <select class="form-select" id="professional_type" name="professional_type">
                                <option value="">Selecione</option>
                                <option value="empresario" {{ old('professional_type', $profile->professional_type) === 'empresario' ? 'selected' : '' }}>Empresário</option>
                                <option value="agente" {{ old('professional_type', $profile->professional_type) === 'agente' ? 'selected' : '' }}>Agente</option>
                                <option value="treinador" {{ old('professional_type', $profile->professional_type) === 'treinador' ? 'selected' : '' }}>Treinador</option>
                                <option value="olheiro" {{ old('professional_type', $profile->professional_type) === 'olheiro' ? 'selected' : '' }}>Olheiro</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="organization" class="form-label">Clube / Agência / Organização</label>
                            <input type="text" class="form-control" id="organization" name="organization" value="{{ old('organization', $profile->organization) }}" placeholder="Ex: Clube ABC">
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
                            <label for="website" class="form-label">Site / Portfólio</label>
                            <input type="url" class="form-control" id="website" name="website" value="{{ old('website', $profile->website) }}" placeholder="https://...">
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
