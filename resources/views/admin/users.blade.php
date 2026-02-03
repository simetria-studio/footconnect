@extends('admin.layout')

@section('title', 'Usuários')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold fc-text-primary mb-1">Usuários</h1>
        <p class="small fc-text-secondary mb-0">Listar, filtrar e gerenciar usuários e assinaturas</p>
    </div>
</div>

@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card fc-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users') }}" class="row g-3">
            <div class="col-12 col-md-4">
                <label for="q" class="form-label small fc-text-secondary">Buscar por nome ou e-mail</label>
                <input type="text" class="form-control bg-dark border-secondary text-white" id="q" name="q" value="{{ request('q') }}" placeholder="Nome ou e-mail...">
            </div>
            <div class="col-12 col-md-2">
                <label for="role" class="form-label small fc-text-secondary">Tipo</label>
                <select class="form-select bg-dark border-secondary text-white" id="role" name="role">
                    <option value="">Todos</option>
                    <option value="player" {{ request('role') === 'player' ? 'selected' : '' }}>Jogador</option>
                    <option value="scout" {{ request('role') === 'scout' ? 'selected' : '' }}>Profissional</option>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <label for="subscription" class="form-label small fc-text-secondary">Assinatura</label>
                <select class="form-select bg-dark border-secondary text-white" id="subscription" name="subscription">
                    <option value="">Todas</option>
                    <option value="active" {{ request('subscription') === 'active' ? 'selected' : '' }}>Ativa</option>
                    <option value="canceled" {{ request('subscription') === 'canceled' ? 'selected' : '' }}>Cancelada</option>
                    <option value="none" {{ request('subscription') === 'none' ? 'selected' : '' }}>Sem assinatura</option>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <label for="active" class="form-label small fc-text-secondary">Conta</label>
                <select class="form-select bg-dark border-secondary text-white" id="active" name="active">
                    <option value="">Todas</option>
                    <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Ativa</option>
                    <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inativa</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-success">Filtrar</button>
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Limpar</a>
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
                        <th class="border-0">Tipo</th>
                        <th class="border-0">Pagamento / Plano</th>
                        <th class="border-0">Fim do período</th>
                        <th class="border-0">Conta</th>
                        <th class="border-0">Cadastro</th>
                        <th class="border-0 pe-4 text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr class="{{ !$u->isActive() ? 'opacity-75' : '' }}">
                            <td class="ps-4">
                                <span class="fw-semibold fc-text-primary">{{ $u->full_name ?: '—' }}</span>
                                <br><span class="small fc-text-secondary">{{ $u->email }}</span>
                            </td>
                            <td>
                                @if($u->role === 'player')
                                    <span class="badge bg-success">Jogador</span>
                                @else
                                    <span class="badge bg-info">Profissional</span>
                                @endif
                                @if($u->is_admin)
                                    <span class="badge bg-warning text-dark">Admin</span>
                                @endif
                            </td>
                            <td class="small">
                                @if($u->subscription_status === 'active')
                                    <span class="text-success">Ativa</span>
                                    @if($u->plan_type || $u->plan_interval)
                                        <br><span class="fc-text-secondary">{{ $u->plan_type ?? '—' }} / {{ $u->plan_interval ?? '—' }}</span>
                                    @endif
                                @elseif($u->subscription_status === 'canceled')
                                    <span class="text-warning">Cancelada</span>
                                @else
                                    <span class="fc-text-secondary">{{ $u->subscription_status ?: 'Sem assinatura' }}</span>
                                @endif
                            </td>
                            <td class="small fc-text-secondary">
                                {{ $u->current_period_end ? $u->current_period_end->format('d/m/Y') : '—' }}
                            </td>
                            <td>
                                @if($u->isActive())
                                    <span class="badge bg-success">Ativa</span>
                                @else
                                    <span class="badge bg-secondary">Inativa</span>
                                @endif
                            </td>
                            <td class="small fc-text-secondary">{{ $u->created_at->format('d/m/Y H:i') }}</td>
                            <td class="pe-4 text-end">
                                @if(!$u->is_admin && $u->id !== auth()->id())
                                    <div class="btn-group btn-group-sm">
                                        @if($u->subscription_status === 'active' && $u->stripe_subscription_id)
                                            <form method="POST" action="{{ route('admin.users.cancel-plan', $u) }}" class="d-inline" onsubmit="return confirm('Cancelar o plano deste usuário no Stripe?');">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-warning" title="Cancelar plano">Cancelar plano</button>
                                            </form>
                                        @endif
                                        @if($u->isActive())
                                            <form method="POST" action="{{ route('admin.users.deactivate', $u) }}" class="d-inline" onsubmit="return confirm('Inativar este usuário? Ele será deslogado ao acessar o app.');">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger" title="Inativar">Inativar</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.users.reactivate', $u) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success" title="Reativar">Reativar</button>
                                            </form>
                                        @endif
                                    </div>
                                @else
                                    <span class="small fc-text-secondary">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 fc-text-secondary">Nenhum usuário encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
        <div class="card-footer fc-border">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
