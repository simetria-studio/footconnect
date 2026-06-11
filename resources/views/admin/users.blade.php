@extends('admin.layout')

@section('title', 'Usuários')
@section('page-title', 'Usuários')
@section('page-subtitle', 'Listar, filtrar e gerenciar contas')

@section('content')
<div class="card fc-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users') }}" class="row g-3">
            <div class="col-12 col-md-3">
                <label class="form-label small fc-text-secondary">Buscar</label>
                <input type="text" class="form-control bg-dark border-secondary text-white" name="q" value="{{ request('q') }}" placeholder="Nome, e-mail ou código...">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fc-text-secondary">Tipo</label>
                <select class="form-select bg-dark border-secondary text-white" name="role">
                    <option value="">Todos</option>
                    <option value="player" {{ request('role') === 'player' ? 'selected' : '' }}>Jogador</option>
                    <option value="scout" {{ request('role') === 'scout' ? 'selected' : '' }}>Profissional</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fc-text-secondary">Plano</label>
                <select class="form-select bg-dark border-secondary text-white" name="plan_group">
                    <option value="">Todos</option>
                    @foreach(['g1','g2','g3','g4'] as $g)
                        <option value="{{ $g }}" {{ request('plan_group') === $g ? 'selected' : '' }}>{{ strtoupper($g) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fc-text-secondary">Assinatura</label>
                <select class="form-select bg-dark border-secondary text-white" name="subscription">
                    <option value="">Todas</option>
                    <option value="active" {{ request('subscription') === 'active' ? 'selected' : '' }}>Ativa</option>
                    <option value="canceled" {{ request('subscription') === 'canceled' ? 'selected' : '' }}>Cancelada</option>
                    <option value="none" {{ request('subscription') === 'none' ? 'selected' : '' }}>Sem assinatura</option>
                </select>
            </div>
            <div class="col-6 col-md-1">
                <label class="form-label small fc-text-secondary">Conta</label>
                <select class="form-select bg-dark border-secondary text-white" name="active">
                    <option value="">Todas</option>
                    <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Ativa</option>
                    <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inativa</option>
                </select>
            </div>
            <div class="col-6 col-md-2 d-flex align-items-end">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="affiliate_blocked" value="1" id="affiliate_blocked" {{ request('affiliate_blocked') ? 'checked' : '' }}>
                    <label class="form-check-label small" for="affiliate_blocked">Afiliado bloqueado</label>
                </div>
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-success btn-sm">Filtrar</button>
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary btn-sm">Limpar</a>
            </div>
        </form>
    </div>
</div>

<div class="card fc-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr>
                        <th class="border-0 ps-4">Usuário</th>
                        <th class="border-0">Tipo / Plano</th>
                        <th class="border-0">Assinatura</th>
                        <th class="border-0">Indicações</th>
                        <th class="border-0">Conta</th>
                        <th class="border-0">Cadastro</th>
                        <th class="border-0 pe-4 text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr class="{{ !$u->isActive() ? 'opacity-75' : '' }}">
                            <td class="ps-4">
                                <a href="{{ route('admin.users.show', $u) }}" class="fw-semibold fc-text-primary text-decoration-none">{{ $u->full_name ?: '—' }}</a>
                                <br><span class="small fc-text-secondary">{{ $u->email }}</span>
                                @if($u->referral_code)<br><code class="small">{{ $u->referral_code }}</code>@endif
                            </td>
                            <td>
                                @if($u->role === 'player')<span class="badge bg-success">Jogador</span>@else<span class="badge bg-info">Prof.</span>@endif
                                @if($u->plan_group)<span class="badge bg-secondary">{{ strtoupper($u->plan_group) }}</span>@endif
                                @if($u->is_admin)<span class="badge bg-warning text-dark">Admin</span>@endif
                                @if($u->referral_program_blocked)<span class="badge bg-danger">Bloq.</span>@endif
                            </td>
                            <td class="small">
                                @if($u->subscription_status === 'active')
                                    <span class="text-success">Ativa</span>
                                    <br><span class="fc-text-secondary">{{ $u->plan_interval ?? '—' }}</span>
                                    @if($u->current_period_end)<br><span class="fc-text-secondary">até {{ $u->current_period_end->format('d/m/Y') }}</span>@endif
                                @elseif($u->subscription_status === 'canceled')
                                    <span class="text-warning">Cancelada</span>
                                @else
                                    <span class="fc-text-secondary">—</span>
                                @endif
                            </td>
                            <td class="small">{{ $u->referrals_count ?? 0 }}</td>
                            <td>
                                @if($u->isActive())<span class="badge bg-success">Ativa</span>@else<span class="badge bg-secondary">Inativa</span>@endif
                            </td>
                            <td class="small fc-text-secondary">{{ $u->created_at->format('d/m/Y') }}</td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('admin.users.show', $u) }}" class="btn btn-sm btn-outline-success">Detalhes</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 fc-text-secondary">Nenhum usuário encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
        <div class="card-footer fc-border">{{ $users->links() }}</div>
    @endif
</div>
@endsection
