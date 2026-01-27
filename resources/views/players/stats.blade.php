@extends('layouts.app')

@section('title', 'Minhas estatísticas — FootConnect')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h5 fw-bold fc-text-primary mb-1">Suas estatísticas</h1>
                    <p class="small fc-text-secondary mb-0">
                        Registre os números das suas últimas temporadas para mostrar sua evolução a clubes e empresários.
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

            <!-- Formulário de Adicionar Estatística -->
            <div class="card fc-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Adicionar nova temporada</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('me.player-stats.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="season" class="form-label">Temporada</label>
                            <input type="text" class="form-control" id="season" name="season" value="{{ old('season') }}" placeholder="Ex: 2024/2025" required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="matches_played" class="form-label">Jogos</label>
                                <input type="number" class="form-control" id="matches_played" name="matches_played" value="{{ old('matches_played', 0) }}" min="0" required>
                            </div>
                            <div class="col-6">
                                <label for="minutes_played" class="form-label">Minutos</label>
                                <input type="number" class="form-control" id="minutes_played" name="minutes_played" value="{{ old('minutes_played', 0) }}" min="0" required>
                            </div>
                            <div class="col-6">
                                <label for="goals" class="form-label">Gols</label>
                                <input type="number" class="form-control" id="goals" name="goals" value="{{ old('goals', 0) }}" min="0" required>
                            </div>
                            <div class="col-6">
                                <label for="assists" class="form-label">Assistências</label>
                                <input type="number" class="form-control" id="assists" name="assists" value="{{ old('assists', 0) }}" min="0" required>
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
                        <button type="submit" class="btn btn-success">Salvar estatística</button>
                    </form>
                </div>
            </div>

            <!-- Histórico de Estatísticas -->
            <div class="card fc-card">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Histórico cadastrado</h5>
                </div>
                <div class="card-body">
                    @forelse($stats as $stat)
                        <div class="d-flex align-items-start justify-content-between py-3 border-bottom fc-border">
                            <div class="flex-grow-1">
                                <h6 class="fw-bold fc-text-primary mb-2">{{ $stat->season ?: 'Temporada' }}</h6>
                                <div class="row g-2 small">
                                    <div class="col-6">
                                        <span class="fc-text-secondary">Jogos:</span>
                                        <span class="fw-semibold fc-text-primary">{{ $stat->matches_played }}</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="fc-text-secondary">Minutos:</span>
                                        <span class="fw-semibold fc-text-primary">{{ $stat->minutes_played }}</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="fc-text-secondary">Gols:</span>
                                        <span class="fw-semibold fc-text-primary">{{ $stat->goals }}</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="fc-text-secondary">Assistências:</span>
                                        <span class="fw-semibold fc-text-primary">{{ $stat->assists }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="fc-text-secondary mb-0">Você ainda não cadastrou estatísticas.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
