@extends('admin.layout')

@section('title', 'Destaque do mês')
@section('page-title', 'Destaque do mês')
@section('page-subtitle', 'Admin escolhe o usuário; a landing puxa foto, vídeo e história do perfil')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <form method="GET" action="{{ route('admin.highlights.index') }}" class="d-flex flex-wrap gap-2 align-items-end">
        <div>
            <label class="form-label small mb-1" for="month">Mês</label>
            <select class="form-select form-select-sm bg-dark border-secondary text-white" id="month" name="month">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ (int) request('month', $month) === $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
        </div>
        <div>
            <label class="form-label small mb-1" for="year">Ano</label>
            <input type="number" class="form-control form-control-sm bg-dark border-secondary text-white" id="year" name="year" value="{{ request('year', $year) }}" min="2024" max="2100" style="width: 100px;">
        </div>
        <button type="submit" class="btn btn-sm btn-outline-success">Filtrar</button>
        <a href="{{ route('admin.highlights.index') }}" class="btn btn-sm btn-outline-secondary">Limpar</a>
    </form>
    <a href="{{ route('admin.highlights.create') }}" class="btn btn-success btn-sm">Novo destaque</a>
</div>

@if(session('status'))
    <div class="alert alert-success py-2">{{ session('status') }}</div>
@endif

<div class="card fc-card">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Usuário</th>
                    <th>Categoria</th>
                    <th>Período</th>
                    <th>Perfil</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($highlights as $highlight)
                    <tr class="{{ ! $highlight->is_active ? 'opacity-50' : '' }}">
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                @if($highlight->displayPhotoUrl())
                                    <img src="{{ $highlight->displayPhotoUrl() }}" alt="" class="rounded" style="width: 48px; height: 48px; object-fit: cover;">
                                @else
                                    <div class="rounded bg-secondary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 0.7rem;">—</div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $highlight->displayName() }}</div>
                                    <div class="small fc-text-secondary">{{ $highlight->user?->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="small">{{ $highlight->category_label }}</td>
                        <td class="small">{{ $highlight->period_label }}</td>
                        <td class="small">
                            @if($highlight->displayPhotoUrl()) Foto @endif
                            @if($highlight->displayVideoUrl())
                                @if($highlight->displayPhotoUrl()) · @endif Vídeo
                            @endif
                            @if($highlight->displayStory())
                                @if($highlight->displayPhotoUrl() || $highlight->displayVideoUrl()) · @endif História
                            @endif
                            @if(! $highlight->displayPhotoUrl() && ! $highlight->displayVideoUrl() && ! $highlight->displayStory())
                                Sem mídia/história no perfil
                            @endif
                        </td>
                        <td>
                            @if($highlight->is_active)
                                <span class="badge bg-success">Ativo</span>
                            @else
                                <span class="badge bg-secondary">Inativo</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-1 flex-wrap">
                                <a href="{{ route('admin.highlights.edit', $highlight) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                <form method="POST" action="{{ route('admin.highlights.toggle', $highlight) }}">@csrf
                                    <button class="btn btn-sm btn-outline-success" type="submit">{{ $highlight->is_active ? 'Desativar' : 'Ativar' }}</button>
                                </form>
                                <form method="POST" action="{{ route('admin.highlights.destroy', $highlight) }}" onsubmit="return confirm('Remover este destaque?')">@csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 fc-text-secondary">Nenhum destaque cadastrado neste filtro.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($highlights->hasPages())
        <div class="p-3">{{ $highlights->links() }}</div>
    @endif
</div>
@endsection
