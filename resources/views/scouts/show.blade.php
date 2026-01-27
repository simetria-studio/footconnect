@extends('layouts.app')

@section('title', 'Perfil profissional — FootConnect')

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
                                <span class="badge bg-success me-2">
                                    @switch($profile->professional_type)
                                        @case('empresario') Empresário @break
                                        @case('agente') Agente @break
                                        @case('treinador') Treinador @break
                                        @case('olheiro') Olheiro @break
                                        @default Profissional do futebol
                                    @endswitch
                                </span>
                            </p>
                            <p class="small text-muted mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                </svg>
                                {{ $profile->city }}@if($profile->state), {{ $profile->state }}@endif
                            </p>
                            @if($profile->organization)
                                <p class="small fc-text-secondary mb-2">
                                    <strong>Organização:</strong> {{ $profile->organization }}
                                </p>
                            @endif
                            @if($profile->bio)
                                <p class="small fc-text-secondary mb-0">{{ $profile->bio }}</p>
                            @else
                                <p class="small text-muted mb-0">Profissional ainda não adicionou uma biografia.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($profile->website)
                <div class="card fc-card mb-4">
                    <div class="card-body">
                        <p class="small text-muted mb-1">Site / Portfólio</p>
                        <a href="{{ $profile->website }}" target="_blank" class="btn btn-sm btn-outline-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m19.5.5-2.5-2.5a.5.5 0 0 0-.707.707L18.293 9H10.5a.5.5 0 0 0 0 1h7.793l-2 2a.5.5 0 0 0 .707.707l2.5-2.5a.5.5 0 0 0 0-.707"/>
                            </svg>
                            Visitar site
                        </a>
                    </div>
                </div>
            @endif

            @if(auth()->id() !== $user->id)
                <form method="GET" action="{{ route('messages.start', $user->id) }}">
                    <button type="submit" class="btn btn-success w-100">Enviar mensagem</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
