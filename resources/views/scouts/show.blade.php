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
                            @php
                                $planGroupKey = $user->plan_group ?? $user->plan_type;
                                $groupConfig = $planGroupKey ? config('plans.groups.'.$planGroupKey) : null;
                            @endphp
                            <p class="small fc-text-secondary mb-2">
                                <span class="badge bg-success me-2">
                                    {{ $groupConfig['label'] ?? 'Profissional do futebol' }}
                                </span>
                                @if($profile->age)
                                    <span>{{ $profile->age }} anos</span>
                                @endif
                            </p>
                            <p class="small text-muted mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                </svg>
                                {{ $profile->city }}@if($profile->state), {{ $profile->state }}@endif@if($profile->country), {{ $profile->country }}@endif
                            </p>
                            @if($profile->has_company && ($profile->company_name || $profile->organization))
                                <p class="small fc-text-secondary mb-2">
                                    <strong>Empresa:</strong> {{ $profile->company_name ?? $profile->organization }}
                                </p>
                            @endif
                            @if($profile->scope_label)
                                <p class="small fc-text-secondary mb-2">
                                    <strong>Atuação:</strong> {{ $profile->scope_label }}
                                </p>
                            @endif
                            @if($profile->is_federated && $profile->federation_name)
                                <p class="small fc-text-secondary mb-0">
                                    <strong>Federação:</strong> {{ $profile->federation_name }}
                                </p>
                            @elseif($profile->is_federated)
                                <p class="small fc-text-secondary mb-0"><strong>Federado:</strong> Sim</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($profile->photos->isNotEmpty())
                <div class="card fc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0 fw-bold">Onde trabalhou / Trajetória</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($profile->photos as $photo)
                                <div class="col-6 col-md-4">
                                    <div class="ratio ratio-1x1 rounded overflow-hidden bg-light">
                                        <img
                                            src="{{ asset('storage/'.$photo->path) }}"
                                            alt="{{ $photo->caption ?: 'Foto' }}"
                                            class="img-fluid object-fit-cover"
                                        >
                                    </div>
                                    @if($photo->caption)
                                        <p class="small fc-text-secondary mt-2 mb-0">{{ $photo->caption }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
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
