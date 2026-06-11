@extends('admin.layout')

@section('title', 'Afiliados')
@section('page-title', 'Indique e Ganhe')
@section('page-subtitle', 'Gestão de afiliados, comissões e anti-fraude')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div class="d-flex flex-wrap gap-2">
        <span class="badge bg-dark border border-secondary p-2">Comissões: {{ $stats['counted_commissions'] }}/{{ $stats['total_commissions'] }}</span>
        <span class="badge bg-success p-2">Pagos: R$ {{ number_format($stats['total_paid_cents'] / 100, 2, ',', '.') }}</span>
        <span class="badge bg-warning text-dark p-2">Pendentes: R$ {{ number_format($stats['pending_cents'] / 100, 2, ',', '.') }}</span>
    </div>
    <form method="POST" action="{{ route('admin.referrals.process-payouts') }}">@csrf
        <button class="btn btn-sm btn-success">Processar pagamentos PIX</button>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card fc-card">
            <div class="card-header"><h5 class="mb-0 fw-bold">Top afiliados</h5></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0">
                        <thead><tr><th class="ps-3">#</th><th>Afiliado</th><th>Código</th><th>Indicados</th><th>Ganhos</th></tr></thead>
                        <tbody>
                            @foreach($topAffiliates as $i => $a)
                                <tr>
                                    <td class="ps-3">{{ $i + 1 }}</td>
                                    <td><a href="{{ route('admin.users.show', $a) }}">{{ $a->full_name ?? $a->email }}</a></td>
                                    <td><code>{{ $a->referral_code }}</code></td>
                                    <td>{{ $a->valid_referrals }}</td>
                                    <td class="text-success">R$ {{ number_format($a->earnings_cents / 100, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            @if($topAffiliates->isEmpty())
                                <tr><td colspan="5" class="text-center py-3 fc-text-secondary">Nenhum afiliado com ganhos ainda.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card fc-card border-danger">
            <div class="card-header"><h5 class="mb-0 fw-bold text-danger">Afiliados bloqueados</h5></div>
            <div class="card-body p-0">
                @forelse($blockedAffiliates as $u)
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom fc-border">
                        <a href="{{ route('admin.users.show', $u) }}">{{ $u->full_name ?? $u->email }}</a>
                        <form method="POST" action="{{ route('admin.users.unblock-referral', $u) }}">@csrf
                            <button class="btn btn-sm btn-outline-success">Desbloquear</button>
                        </form>
                    </div>
                @empty
                    <p class="small fc-text-secondary p-3 mb-0">Nenhum bloqueio ativo.</p>
                @endforelse
            </div>
            @if($blockedAffiliates->hasPages())<div class="card-footer">{{ $blockedAffiliates->links() }}</div>@endif
        </div>
    </div>
</div>

<div class="card fc-card mb-4">
    <div class="card-header"><h5 class="mb-0 fw-bold">Indicações inválidas (anti-fraude)</h5></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0">
                <thead><tr><th class="ps-3">Usuário</th><th>Motivo</th><th>Cadastro</th><th></th></tr></thead>
                <tbody>
                    @forelse($invalidReferrals as $u)
                        <tr>
                            <td class="ps-3">{{ $u->full_name ?? $u->email }}</td>
                            <td class="small text-danger">{{ $u->referral_invalid_reason ?? '—' }}</td>
                            <td class="small">{{ $u->created_at->format('d/m/Y') }}</td>
                            <td><a href="{{ route('admin.users.show', $u) }}" class="btn btn-sm btn-outline-secondary">Ver</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-3 fc-text-secondary">Nenhuma indicação inválida registrada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($invalidReferrals->hasPages())<div class="card-footer">{{ $invalidReferrals->links() }}</div>@endif
</div>

<div class="card fc-card">
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0 fw-bold">Histórico de comissões</h5>
        <a href="{{ route('admin.referrals.withdrawals') }}" class="btn btn-sm btn-outline-success">Ver saques PIX</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0">
                <thead><tr><th class="ps-3">Data</th><th>Indicador</th><th>Indicado</th><th>Valor</th><th>Status</th><th>Válida</th></tr></thead>
                <tbody>
                    @foreach($recentCommissions as $c)
                        <tr>
                            <td class="ps-3 small">{{ $c->created_at->format('d/m/Y') }}</td>
                            <td class="small"><a href="{{ route('admin.users.show', $c->referrer) }}">{{ $c->referrer->email }}</a></td>
                            <td class="small">{{ $c->referred->email ?? '—' }}</td>
                            <td class="text-success small">{{ $c->formatted_commission }}</td>
                            <td><span class="badge bg-secondary">{{ $c->status }}</span></td>
                            <td>@if($c->is_counted)<span class="text-success">Sim</span>@else<span class="text-danger">Não</span>@endif</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($recentCommissions->hasPages())<div class="card-footer">{{ $recentCommissions->links() }}</div>@endif
</div>
@endsection
