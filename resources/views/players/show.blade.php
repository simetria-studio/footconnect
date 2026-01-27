@extends('layouts.app')

@section('title', 'Perfil do jogador — FootConnect')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="mb-4">
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary mb-3">Voltar</a>
            </div>

            <!-- Header do Perfil -->
            <div class="card fc-card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-4">
                        <div class="fc-avatar-lg bg-success flex-shrink-0">
                            {{ strtoupper(substr($user->full_name ?? $user->email, 0, 2)) }}
                        </div>
                        <div class="flex-grow-1">
                            <h1 class="h5 fw-bold fc-text-primary mb-2">{{ $user->full_name ?? $user->email }}</h1>
                            <p class="small fc-text-secondary mb-2">
                                <span class="badge bg-success me-2">{{ $profile->position ?? 'Posição não informada' }}</span>
                                @if($profile->age)
                                    <span>{{ $profile->age }} anos</span>
                                @endif
                            </p>
                            <p class="small text-muted mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                </svg>
                                {{ $profile->city }}@if($profile->state), {{ $profile->state }}@endif
                            </p>
                            @if($profile->bio)
                                <p class="small fc-text-secondary mb-0">{{ $profile->bio }}</p>
                            @else
                                <p class="small text-muted mb-0">Jogador ainda não adicionou uma biografia.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estatísticas Físicas -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card fc-card h-100">
                        <div class="card-body text-center">
                            <p class="small text-muted mb-1">Altura</p>
                            <p class="fw-bold fc-text-primary mb-0">{{ $profile->height_cm ? $profile->height_cm.' cm' : '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card fc-card h-100">
                        <div class="card-body text-center">
                            <p class="small text-muted mb-1">Peso</p>
                            <p class="fw-bold fc-text-primary mb-0">{{ $profile->weight_kg ? $profile->weight_kg.' kg' : '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card fc-card h-100">
                        <div class="card-body text-center">
                            <p class="small text-muted mb-1">Clube atual</p>
                            <p class="fw-bold fc-text-primary mb-0">{{ $profile->current_club ?: '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card fc-card h-100">
                        <div class="card-body text-center">
                            <p class="small text-muted mb-1">Pé dominante</p>
                            <p class="fw-bold fc-text-primary mb-0">
                                @if($profile->dominant_foot === 'right') Destro
                                @elseif($profile->dominant_foot === 'left') Canhoto
                                @elseif($profile->dominant_foot === 'both') Ambidestro
                                @else -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vídeos -->
            <div class="card fc-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Vídeos em destaque</h5>
                </div>
                <div class="card-body">
                    @forelse($profile->videos as $video)
                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom fc-border">
                            <div>
                                <h6 class="fw-semibold fc-text-primary mb-1">{{ $video->title ?: 'Vídeo sem título' }}</h6>
                                <a href="{{ $video->url }}" target="_blank" class="btn btn-sm btn-outline-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                        <path d="M6.271 5.055a.5.5 0 0 1 .475 0l3.5 2a.5.5 0 0 1 0 .89l-3.5 2a.5.5 0 0 1-.475 0l-3.5-2a.5.5 0 0 1 0-.89z"/>
                                    </svg>
                                    Assistir
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Nenhum vídeo cadastrado ainda.</p>
                    @endforelse
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="card fc-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Estatísticas recentes</h5>
                </div>
                <div class="card-body">
                    @forelse($profile->stats as $stat)
                        <div class="d-flex align-items-start justify-content-between py-2 border-bottom fc-border">
                            <div>
                                <h6 class="fw-semibold fc-text-primary mb-2">{{ $stat->season ?: 'Temporada' }}</h6>
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
                        <p class="text-muted mb-0">Nenhuma estatística cadastrada.</p>
                    @endforelse
                </div>
            </div>

            @if(auth()->id() !== $user->id)
                <form method="GET" action="{{ route('messages.start', $user->id) }}">
                    <button type="submit" class="btn btn-success w-100">Enviar mensagem</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
