@extends('admin.layout')

@section('title', 'Assinaturas')
@section('page-title', 'Assinaturas')
@section('page-subtitle', 'Gestão e análise de assinaturas ativas')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card fc-card fc-stat-card">
            <div class="card-body">
                <p class="stat-label">MRR estimado</p>
                <p class="stat-value text-success mb-0">R$ {{ number_format($mrrEstimateCents / 100, 2, ',', '.') }}</p>
                <p class="stat-trend fc-text-secondary mt-1">Baseado nos planos ativos</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card fc-card fc-stat-card">
            <div class="card-body">
                <p class="stat-label">Assinaturas ativas</p>
                <p class="stat-value fc-text-primary mb-0">{{ $activeCount }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card fc-card fc-stat-card">
            <div class="card-body">
                <p class="stat-label">Vencendo em 7 dias</p>
                <p class="stat-value text-warning mb-0">{{ $expiringSoon->count() }}</p>
            </div>
        </div>
    </div>
</div>

@if($expiringSoon->isNotEmpty())
<div class="card fc-card mb-4 border-warning">
    <div class="card-header"><h5 class="mb-0 fw-bold text-warning">Renovações próximas (7 dias)</h5></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0">
                <thead><tr><th class="ps-3">Usuário</th><th>Plano</th><th>Vence em</th><th></th></tr></thead>
                <tbody>
                    @foreach($expiringSoon as $u)
                        <tr>
                            <td class="ps-3">{{ $u->full_name ?? $u->email }}</td>
                            <td>{{ strtoupper($u->plan_group ?? '—') }} / {{ $u->plan_interval ?? '—' }}</td>
                            <td>{{ $u->current_period_end->format('d/m/Y') }} <span class="fc-text-secondary">({{ $u->current_period_end->diffForHumans() }})</span></td>
                            <td><a href="{{ route('admin.users.show', $u) }}" class="btn btn-sm btn-outline-success">Ver</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-lg-4">
        <div class="card fc-card">
            <div class="card-header"><h5 class="mb-0 fw-bold">Distribuição por plano</h5></div>
            <div class="card-body p-0">
                @forelse($byPlan as $row)
                    <div class="d-flex justify-content-between px-3 py-2 border-bottom fc-border small">
                        <span>{{ strtoupper($row->plan_group) }} — {{ $row->plan_interval }}</span>
                        <span class="badge bg-success">{{ $row->total }}</span>
                    </div>
                @empty
                    <p class="small fc-text-secondary p-3 mb-0">Sem dados.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card fc-card">
            <div class="card-header"><h5 class="mb-0 fw-bold">Todas as assinaturas ativas</h5></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Usuário</th>
                                <th>Plano</th>
                                <th>Intervalo</th>
                                <th>Renovação</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($active as $u)
                                <tr>
                                    <td class="ps-3">
                                        <span class="fw-semibold">{{ $u->full_name ?? $u->email }}</span>
                                        <br><span class="small fc-text-secondary">{{ $u->email }}</span>
                                    </td>
                                    <td>{{ strtoupper($u->plan_group ?? '—') }}</td>
                                    <td>{{ $u->plan_interval ?? '—' }}</td>
                                    <td class="small">{{ $u->current_period_end?->format('d/m/Y') ?? '—' }}</td>
                                    <td><a href="{{ route('admin.users.show', $u) }}" class="btn btn-sm btn-outline-secondary">Detalhes</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-4 fc-text-secondary">Nenhuma assinatura ativa.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($active->hasPages())
                <div class="card-footer">{{ $active->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
