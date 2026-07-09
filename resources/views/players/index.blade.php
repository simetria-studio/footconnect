@extends('layouts.app')

@section('title', 'Jogadores — FootConnect')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h5 fw-bold fc-text-primary mb-0">Buscar jogadores</h1>
        <div class="d-flex gap-2">
            @if(auth()->user()->role === 'scout')
                <a href="{{ route('favorites.index') }}" class="btn btn-sm btn-outline-success">Favoritos</a>
            @endif
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card fc-card mb-4">
        <div class="card-header">
            <h5 class="mb-0 fw-bold">Filtros avançados</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('players.index') }}" class="row g-3">
                <div class="col-6 col-md-3">
                    <label for="modality" class="form-label small">Modalidade</label>
                    <select class="form-select form-select-sm" id="modality" name="modality">
                        <option value="">Todas</option>
                        <option value="campo" {{ request('modality') === 'campo' ? 'selected' : '' }}>Futebol de Campo</option>
                        <option value="futsal" {{ request('modality') === 'futsal' ? 'selected' : '' }}>Futsal</option>
                        <option value="fut7" {{ request('modality') === 'fut7' ? 'selected' : '' }}>Fut 7</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label for="position" class="form-label small">Posição</label>
                    <select class="form-select form-select-sm" id="position" name="position">
                        <option value="">Todas</option>
                        @php $currentModality = request('modality'); @endphp
                        @foreach(($currentModality ? (config('positions.by_modality.'.$currentModality) ?? []) : collect(config('positions.by_modality'))->flatten()->unique()) as $pos)
                            <option value="{{ $pos }}" {{ request('position') === $pos ? 'selected' : '' }}>{{ $pos }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label for="gender" class="form-label small">Sexo</label>
                    <select class="form-select form-select-sm" id="gender" name="gender">
                        <option value="">Todos</option>
                        <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Masculino</option>
                        <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Feminino</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label for="dominant_foot" class="form-label small">Pé dominante</label>
                    <select class="form-select form-select-sm" id="dominant_foot" name="dominant_foot">
                        <option value="">Todos</option>
                        <option value="right" {{ request('dominant_foot') === 'right' ? 'selected' : '' }}>Destro</option>
                        <option value="left" {{ request('dominant_foot') === 'left' ? 'selected' : '' }}>Canhoto</option>
                        <option value="both" {{ request('dominant_foot') === 'both' ? 'selected' : '' }}>Ambidestro</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label for="state" class="form-label small">Estado</label>
                    <select class="form-select form-select-sm" id="state" name="state">
                        <option value="">Todos</option>
                        @foreach(config('locations.brazilian_states') as $uf => $name)
                            <option value="{{ $uf }}" {{ request('state') === $uf ? 'selected' : '' }}>{{ $uf }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label for="city" class="form-label small">Cidade</label>
                    <select class="form-select form-select-sm" id="city" name="city" {{ request('state') ? '' : 'disabled' }}>
                        <option value="">{{ request('state') ? 'Todas as cidades' : 'Selecione o estado' }}</option>
                        @if(request('city'))
                            <option value="{{ request('city') }}" selected>{{ request('city') }}</option>
                        @endif
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label for="country" class="form-label small">País</label>
                    <select class="form-select form-select-sm" id="country" name="country">
                        <option value="">Todos</option>
                        @foreach(config('locations.countries') as $country)
                            <option value="{{ $country }}" {{ request('country') === $country ? 'selected' : '' }}>{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label for="institution_type" class="form-label small">Instituição</label>
                    <select class="form-select form-select-sm" id="institution_type" name="institution_type">
                        <option value="">Todas</option>
                        <option value="clube" {{ request('institution_type') === 'clube' ? 'selected' : '' }}>Clube</option>
                        <option value="projeto" {{ request('institution_type') === 'projeto' ? 'selected' : '' }}>Projeto</option>
                        <option value="escolinha" {{ request('institution_type') === 'escolinha' ? 'selected' : '' }}>Escolinha</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="age_min" class="form-label small">Idade mín.</label>
                    <input type="number" class="form-control form-control-sm" id="age_min" name="age_min" value="{{ request('age_min') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label for="age_max" class="form-label small">Idade máx.</label>
                    <input type="number" class="form-control form-control-sm" id="age_max" name="age_max" value="{{ request('age_max') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label for="height_min" class="form-label small">Altura mín. (cm)</label>
                    <input type="number" class="form-control form-control-sm" id="height_min" name="height_min" value="{{ request('height_min') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label for="height_max" class="form-label small">Altura máx. (cm)</label>
                    <input type="number" class="form-control form-control-sm" id="height_max" name="height_max" value="{{ request('height_max') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label for="is_federated" class="form-label small">Federado</label>
                    <select class="form-select form-select-sm" id="is_federated" name="is_federated">
                        <option value="">Todos</option>
                        <option value="1" {{ request('is_federated') === '1' ? 'selected' : '' }}>Sim</option>
                        <option value="0" {{ request('is_federated') === '0' ? 'selected' : '' }}>Não</option>
                    </select>
                </div>
                @if(auth()->user()->role === 'scout')
                    <div class="col-12 col-md-4 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="favorites_only" value="1" id="favorites_only" {{ request('favorites_only') ? 'checked' : '' }}>
                            <label class="form-check-label small" for="favorites_only">Somente favoritos</label>
                        </div>
                    </div>
                @endif
                <div class="col-12">
                    <button type="submit" class="btn btn-success btn-sm">Filtrar</button>
                    <a href="{{ route('players.index') }}" class="btn btn-outline-secondary btn-sm">Limpar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3">
        @forelse ($players as $player)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card fc-card fc-card-hover h-100">
                    <div class="card-body">
                        <a href="{{ route('players.show', $player->user) }}" class="text-decoration-none">
                            <h6 class="fw-bold fc-text-primary mb-2">
                                {{ $player->user->full_name ?? $player->user->email }}
                            </h6>
                            <p class="small fc-text-secondary mb-1">
                                @if($player->modality_label)
                                    <span class="badge bg-secondary me-1">{{ $player->modality_label }}</span>
                                @endif
                                <span class="badge bg-success me-1">{{ $player->position ?? 'N/A' }}</span>
                                @if($player->age)<span>{{ $player->age }} anos</span>@endif
                            </p>
                            <p class="small text-muted mb-0">
                                {{ $player->city }}@if($player->state), {{ $player->state }}@endif
                            </p>
                        </a>
                        @if(auth()->user()->role === 'scout')
                            <form method="POST" action="{{ route('favorites.toggle', $player->user) }}" class="mt-3">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ in_array($player->user_id, $favoritePlayerIds) ? 'btn-warning' : 'btn-outline-warning' }}">
                                    {{ in_array($player->user_id, $favoritePlayerIds) ? '★ Favorito' : '☆ Favoritar' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
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

    @if($players->hasPages())
        <div class="mt-4 d-flex justify-content-center">{{ $players->links() }}</div>
    @endif
</div>
@endsection

@push('scripts')
    @include('partials.dynamic-filters-script', [
        'selectedPosition' => request('position'),
        'selectedCity' => request('city'),
        'requireModality' => false,
        'positionEmptyLabel' => 'Todas',
        'cityEmptyLabel' => 'Todas as cidades',
    ])
@endpush
