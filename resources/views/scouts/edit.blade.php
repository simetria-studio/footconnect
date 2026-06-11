@php
    $planGroup = $user->plan_group ?? 'g2';
    $groupConfig = config('plans.groups.'.$planGroup, config('plans.groups.g2'));
@endphp

@extends('layouts.app')

@section('title', 'Editar perfil profissional — FootConnect')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h5 fw-bold fc-text-primary mb-1">Perfil {{ $groupConfig['code'] }} — {{ $groupConfig['label'] }}</h1>
                    <p class="small fc-text-secondary mb-0">
                        Mostre para quem você trabalha e como atua no mercado do futebol.
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

                        <div class="col-12 col-md-6">
                            <label for="age" class="form-label">Idade</label>
                            <input type="number" class="form-control" id="age" name="age" value="{{ old('age', $profile->age) }}" min="18" max="99">
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="city" class="form-label">Cidade</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $profile->city ?? $user->city) }}">
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="state" class="form-label">Estado</label>
                            <select class="form-select" id="state" name="state">
                                <option value="">Selecione</option>
                                @foreach(config('locations.brazilian_states') as $uf => $name)
                                    <option value="{{ $uf }}" {{ old('state', $profile->state ?? $user->state) === $uf ? 'selected' : '' }}>{{ $uf }} — {{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="country" class="form-label">País</label>
                            <select class="form-select" id="country" name="country">
                                <option value="">Selecione</option>
                                @foreach(config('locations.countries') as $country)
                                    <option value="{{ $country }}" {{ old('country', $profile->country ?? $user->country ?? 'Brasil') === $country ? 'selected' : '' }}>{{ $country }}</option>
                                @endforeach
                            </select>
                        </div>

                        <x-yes-no-field
                            name="has_company"
                            label="Empresa"
                            :value="$profile->has_company"
                            detail-name="company_name"
                            detail-label="Qual empresa?"
                            :detail-value="$profile->company_name ?? $profile->organization"
                        />

                        <div class="col-12">
                            <label class="form-label">Atuação</label>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach(['regional' => 'Regional', 'nacional' => 'Nacional', 'internacional' => 'Internacional'] as $value => $label)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="scope" id="scope_{{ $value }}" value="{{ $value }}" {{ old('scope', $profile->scope) === $value ? 'checked' : '' }}>
                                        <label class="form-check-label" for="scope_{{ $value }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <x-yes-no-field
                            name="is_federated"
                            label="Federado"
                            :value="$profile->is_federated"
                            detail-name="federation_name"
                            detail-label="Qual federação?"
                            :detail-value="$profile->federation_name"
                        />
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

            <div class="card fc-card mt-4">
                <div class="card-body">
                    <h2 class="h6 fw-bold fc-text-primary mb-2">Fotos</h2>
                    <p class="small fc-text-secondary mb-3">
                        Adicione fotos de onde trabalhou, clubes, agências ou eventos para enriquecer seu perfil.
                    </p>
                    <a href="{{ route('me.scout-photos') }}" class="btn btn-outline-success btn-sm">Gerenciar fotos</a>
                </div>
            </div>
        </div>
    </div>
</div>

<x-profile-form-scripts />
@endsection
