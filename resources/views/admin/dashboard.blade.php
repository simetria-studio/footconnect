@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Visão geral da plataforma')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card fc-card fc-stat-card h-100">
            <div class="card-body">
                <p class="stat-label mb-1">Usuários totais</p>
                <p class="stat-value fc-text-primary mb-0">{{ $stats['total_users'] }}</p>
                <p class="stat-trend text-success mt-1">+{{ $stats['new_users_month'] }} este mês</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card fc-card fc-stat-card h-100">
            <div class="card-body">
                <p class="stat-label mb-1">Assinaturas ativas</p>
                <p class="stat-value text-success mb-0">{{ $stats['active_subscriptions'] }}</p>
                <p class="stat-trend fc-text-secondary mt-1">{{ $stats['canceled_subscriptions'] }} canceladas</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card fc-card fc-stat-card h-100">
            <div class="card-body">
                <p class="stat-label mb-1">Jogadores / Profissionais</p>
                <p class="stat-value fc-text-primary mb-0">{{ $stats['players'] }} / {{ $stats['scouts'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card fc-card fc-stat-card h-100">
            <div class="card-body">
                <p class="stat-label mb-1">Contas inativas</p>
                <p class="stat-value text-warning mb-0">{{ $stats['inactive_accounts'] }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card fc-card fc-stat-card h-100">
            <div class="card-body">
                <p class="stat-label mb-1">Indicações válidas</p>
                <p class="stat-value fc-text-primary mb-0">{{ $stats['valid_referrals'] }}</p>
                <p class="stat-trend fc-text-secondary mt-1">{{ $stats['total_referrals'] }} total</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card fc-card fc-stat-card h-100">
            <div class="card-body">
                <p class="stat-label mb-1">Comissões geradas</p>
                <p class="stat-value text-success mb-0">R$ {{ number_format($stats['commissions_total_cents'] / 100, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card fc-card fc-stat-card h-100">
            <div class="card-body">
                <p class="stat-label mb-1">Saques PIX</p>
                <p class="stat-value fc-text-primary mb-0">R$ {{ number_format($stats['withdrawals_total_cents'] / 100, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card fc-card fc-stat-card h-100">
            <div class="card-body">
                <p class="stat-label mb-1">Afiliados bloqueados</p>
                <p class="stat-value text-danger mb-0">{{ $stats['blocked_affiliates'] }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card fc-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Cadastros — últimos 14 dias</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-end gap-1" style="height: 120px;">
                    @php $maxSignup = $signupsByDay->max() ?: 1; @endphp
                    @foreach($signupsByDay as $date => $count)
                        <div class="flex-fill text-center" title="{{ $date }}: {{ $count }}">
                            <div class="bg-success rounded-top mx-auto" style="width: 100%; max-width: 28px; height: {{ max(4, ($count / $maxSignup) * 100) }}px; opacity: 0.85;"></div>
                            <span class="d-block small fc-text-secondary mt-1" style="font-size: 0.6rem;">{{ \Carbon\Carbon::parse($date)->format('d/m') }}</span>
                        </div>
                    @endforeach
                    @if($signupsByDay->isEmpty())
                        <p class="small fc-text-secondary mb-0">Sem cadastros no período.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card fc-card h-100">
            <div class="card-header"><h5 class="mb-0 fw-bold">Por plano (G1–G4)</h5></div>
            <div class="card-body p-0">
                @forelse($planGroups as $group => $total)
                    <div class="d-flex justify-content-between px-3 py-2 border-bottom fc-border">
                        <span class="small fw-semibold">{{ strtoupper($group) }}</span>
                        <span class="badge bg-success">{{ $total }}</span>
                    </div>
                @empty
                    <p class="small fc-text-secondary p-3 mb-0">Nenhum plano atribuído ainda.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card fc-card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 fw-bold">Últimos usuários</h5>
                <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-success">Ver todos</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0">
                        <thead><tr><th class="ps-3">Usuário</th><th>Plano</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($recentUsers as $u)
                                <tr>
                                    <td class="ps-3">
                                        <a href="{{ route('admin.users.show', $u) }}" class="text-decoration-none fw-semibold fc-text-primary">{{ $u->full_name ?: $u->email }}</a>
                                    </td>
                                    <td class="small">{{ strtoupper($u->plan_group ?? '—') }}</td>
                                    <td>
                                        @if($u->subscription_status === 'active')<span class="badge bg-success">Ativa</span>
                                        @else<span class="badge bg-secondary">{{ $u->subscription_status ?: '—' }}</span>@endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card fc-card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 fw-bold">Últimas comissões</h5>
                <a href="{{ route('admin.referrals') }}" class="btn btn-sm btn-outline-success">Afiliados</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0">
                        <thead><tr><th class="ps-3">Indicador</th><th>Valor</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse($recentCommissions as $c)
                                <tr>
                                    <td class="ps-3 small">{{ $c->referrer->full_name ?? $c->referrer->email }}</td>
                                    <td class="small text-success">{{ $c->formatted_commission }}</td>
                                    <td><span class="badge bg-secondary">{{ $c->status }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center py-3 fc-text-secondary">Nenhuma comissão ainda.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-12">
        <div class="card fc-card">
            <div class="card-body d-flex flex-wrap gap-2">
                <span class="small fc-text-secondary me-2 align-self-center">Engajamento:</span>
                <span class="badge bg-dark border border-secondary">{{ $stats['conversations'] }} conversas</span>
                <span class="badge bg-dark border border-secondary">{{ $stats['messages'] }} mensagens</span>
                <span class="badge bg-dark border border-secondary">{{ $stats['favorites'] }} favoritos</span>
                @foreach($subscriptionsByInterval as $interval => $count)
                    <span class="badge bg-dark border border-secondary">{{ $count }} assin. {{ $interval }}</span>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
