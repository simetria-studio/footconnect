@extends('layouts.app')

@section('title', 'Favoritos — FootConnect')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h5 fw-bold fc-text-primary mb-0">Jogadores favoritos</h1>
        <a href="{{ route('players.index') }}" class="btn btn-sm btn-outline-success">Buscar jogadores</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">
        @forelse ($favorites as $favorite)
            @php $player = $favorite->player; $profile = $player->playerProfile; @endphp
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card fc-card h-100">
                    <div class="card-body">
                        <h6 class="fw-bold fc-text-primary mb-2">
                            <a href="{{ route('players.show', $player) }}" class="text-decoration-none fc-text-primary">
                                {{ $player->full_name ?? $player->email }}
                            </a>
                        </h6>
                        @if($profile)
                            <p class="small fc-text-secondary mb-2">
                                <span class="badge bg-success me-1">{{ $profile->position ?? 'N/A' }}</span>
                                @if($profile->age) {{ $profile->age }} anos @endif
                            </p>
                            <p class="small text-muted mb-3">
                                {{ $profile->city }}@if($profile->state), {{ $profile->state }}@endif
                            </p>
                        @endif
                        <form method="POST" action="{{ route('favorites.toggle', $player) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">Remover dos favoritos</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card fc-card">
                    <div class="card-body text-center py-5">
                        <p class="fc-text-secondary mb-3">Você ainda não favoritou nenhum jogador.</p>
                        <a href="{{ route('players.index') }}" class="btn btn-success btn-sm">Explorar jogadores</a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if($favorites->hasPages())
        <div class="mt-4 d-flex justify-content-center">{{ $favorites->links() }}</div>
    @endif
</div>
@endsection
