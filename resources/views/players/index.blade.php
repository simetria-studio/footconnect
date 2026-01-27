@extends('layouts.app')

@section('title', 'Jogadores — FootConnect')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h5 fw-bold fc-text-primary mb-0">Jogadores</h1>
        <a href="{{ route('home') }}" class="btn btn-sm btn-outline-success">Voltar</a>
    </div>

    <!-- Filtros -->
    <div class="card fc-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('players.index') }}" class="row g-3">
                <div class="col-6 col-md-3">
                    <label for="position" class="form-label small">Posição</label>
                    <input type="text" class="form-control form-control-sm" id="position" name="position" value="{{ request('position') }}" placeholder="Ex: Atacante">
                </div>
                <div class="col-6 col-md-3">
                    <label for="city" class="form-label small">Cidade</label>
                    <input type="text" class="form-control form-control-sm" id="city" name="city" value="{{ request('city') }}" placeholder="Ex: São Paulo">
                </div>
                <div class="col-6 col-md-2">
                    <label for="age_min" class="form-label small">Idade mín.</label>
                    <input type="number" class="form-control form-control-sm" id="age_min" name="age_min" value="{{ request('age_min') }}" placeholder="18">
                </div>
                <div class="col-6 col-md-2">
                    <label for="age_max" class="form-label small">Idade máx.</label>
                    <input type="number" class="form-control form-control-sm" id="age_max" name="age_max" value="{{ request('age_max') }}" placeholder="35">
                </div>
                <div class="col-12 col-md-2">
                    <label for="dominant_foot" class="form-label small">Pé dominante</label>
                    <select class="form-select form-select-sm" id="dominant_foot" name="dominant_foot">
                        <option value="">Todos</option>
                        <option value="right" {{ request('dominant_foot') === 'right' ? 'selected' : '' }}>Destro</option>
                        <option value="left" {{ request('dominant_foot') === 'left' ? 'selected' : '' }}>Canhoto</option>
                        <option value="both" {{ request('dominant_foot') === 'both' ? 'selected' : '' }}>Ambidestro</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success btn-sm">Filtrar</button>
                    <a href="{{ route('players.index') }}" class="btn btn-outline-secondary btn-sm">Limpar</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Jogadores -->
    <div class="row g-3">
        @forelse ($players as $player)
            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('players.show', $player->user) }}" class="text-decoration-none">
                    <div class="card fc-card fc-card-hover h-100">
                        <div class="card-body">
                            <h6 class="fw-bold fc-text-primary mb-2">
                                {{ $player->user->full_name ?? $player->user->email }}
                            </h6>
                            <p class="small fc-text-secondary mb-1">
                                <span class="badge bg-success me-1">{{ $player->position ?? 'N/A' }}</span>
                                @if($player->age)
                                    <span>{{ $player->age }} anos</span>
                                @endif
                            </p>
                            <p class="small text-muted mb-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                </svg>
                                {{ $player->city }}@if($player->state), {{ $player->state }}@endif
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="card fc-card">
                    <div class="card-body text-center py-5">
                        <p class="fc-text-secondary mb-0">Nenhum jogador encontrado com os filtros atuais.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Paginação -->
    @if($players->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $players->links() }}
        </div>
    @endif
</div>
@endsection
