@extends('admin.layout')

@section('title', 'Usuário — '.$user->email)
@section('page-title', $user->full_name ?: $user->name)
@section('page-subtitle', $user->email)

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-secondary">← Voltar à lista</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card fc-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Informações gerais</h5>
                <div class="d-flex gap-1 flex-wrap">
                    @if($user->role === 'player')<span class="badge bg-success">Jogador</span>@else<span class="badge bg-info">Profissional</span>@endif
                    @if($user->plan_group)<span class="badge bg-secondary">{{ strtoupper($user->plan_group) }}</span>@endif
                    @if($user->is_admin)<span class="badge bg-warning text-dark">Admin</span>@endif
                    @if(!$user->isActive())<span class="badge bg-danger">Conta inativa</span>@endif
                    @if($user->referral_program_blocked)<span class="badge bg-danger">Afiliado bloqueado</span>@endif
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3 small">
                    <div class="col-md-6"><span class="fc-text-secondary">ID</span><br><strong>#{{ $user->id }}</strong></div>
                    <div class="col-md-6"><span class="fc-text-secondary">Cadastro</span><br><strong>{{ $user->created_at->format('d/m/Y H:i') }}</strong></div>
                    <div class="col-md-6"><span class="fc-text-secondary">Cidade / Estado / País</span><br><strong>{{ $user->city ?? '—' }} / {{ $user->state ?? '—' }} / {{ $user->country ?? '—' }}</strong></div>
                    <div class="col-md-6"><span class="fc-text-secondary">IP de registro</span><br><strong>{{ $user->referral_registration_ip ?? '—' }}</strong></div>
                    <div class="col-md-6"><span class="fc-text-secondary">Código de indicação</span><br><strong>{{ $user->referral_code ?? '—' }}</strong></div>
                    <div class="col-md-6"><span class="fc-text-secondary">Indicado por</span><br>
                        @if($user->referrer)
                            <a href="{{ route('admin.users.show', $user->referrer) }}">{{ $user->referrer->full_name ?? $user->referrer->email }}</a>
                            @unless($user->referral_is_counted)<span class="badge bg-danger ms-1">Inválida</span>@endunless
                        @else<strong>—</strong>@endif
                    </div>
                    @if($user->referral_invalid_reason)
                        <div class="col-12"><span class="fc-text-secondary">Motivo indicação inválida</span><br><strong class="text-danger">{{ $user->referral_invalid_reason }}</strong></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card fc-card mb-3">
            <div class="card-header"><h5 class="mb-0 fw-bold">Assinatura</h5></div>
            <div class="card-body small">
                <p class="mb-1"><span class="fc-text-secondary">Status:</span>
                    @if($user->subscription_status === 'active')<span class="text-success fw-semibold">Ativa</span>
                    @elseif($user->subscription_status === 'canceled')<span class="text-warning">Cancelada</span>
                    @else<span class="fc-text-secondary">{{ $user->subscription_status ?: 'Sem assinatura' }}</span>@endif
                </p>
                <p class="mb-1"><span class="fc-text-secondary">Plano:</span> {{ $user->plan_type ?? '—' }} / {{ $user->plan_interval ?? '—' }}</p>
                <p class="mb-1"><span class="fc-text-secondary">Renovação:</span> {{ $user->current_period_end?->format('d/m/Y') ?? '—' }}</p>
                <p class="mb-0"><span class="fc-text-secondary">Stripe:</span> <code class="small">{{ $user->stripe_subscription_id ? substr($user->stripe_subscription_id, 0, 20).'...' : '—' }}</code></p>
            </div>
        </div>
        <div class="card fc-card">
            <div class="card-header"><h5 class="mb-0 fw-bold">PIX (afiliado)</h5></div>
            <div class="card-body small">
                <p class="mb-1"><span class="fc-text-secondary">Tipo:</span> {{ $user->pix_key_type ?? '—' }}</p>
                <p class="mb-0"><span class="fc-text-secondary">Chave:</span> {{ $user->pix_key ?? 'Não cadastrada' }}</p>
            </div>
        </div>
    </div>
</div>

@if($user->id !== auth()->id())
<div class="card fc-card mb-4">
    <div class="card-header"><h5 class="mb-0 fw-bold">Ações administrativas</h5></div>
    <div class="card-body d-flex flex-wrap gap-2">
        @unless($user->is_admin)
            <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}">@csrf
                <button class="btn btn-sm btn-outline-warning">{{ $user->is_admin ? 'Remover admin' : 'Tornar admin' }}</button>
            </form>
            @if($user->subscription_status === 'active')
                <form method="POST" action="{{ route('admin.users.cancel-plan', $user) }}" onsubmit="return confirm('Cancelar assinatura?');">@csrf
                    <button class="btn btn-sm btn-outline-warning">Cancelar plano</button>
                </form>
            @endif
            @if($user->isActive())
                <form method="POST" action="{{ route('admin.users.deactivate', $user) }}" onsubmit="return confirm('Inativar conta?');">@csrf
                    <button class="btn btn-sm btn-outline-danger">Inativar conta</button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.users.reactivate', $user) }}">@csrf
                    <button class="btn btn-sm btn-outline-success">Reativar conta</button>
                </form>
            @endif
            @if($user->referral_program_blocked)
                <form method="POST" action="{{ route('admin.users.unblock-referral', $user) }}">@csrf
                    <button class="btn btn-sm btn-success">Desbloquear afiliado</button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.users.block-referral', $user) }}" onsubmit="return confirm('Bloquear programa de indicação?');">@csrf
                    <button class="btn btn-sm btn-outline-danger">Bloquear afiliado</button>
                </form>
            @endif
            @if($user->referred_by_id && $user->referral_is_counted)
                <form method="POST" action="{{ route('admin.users.invalidate-referral', $user) }}" onsubmit="return confirm('Invalidar esta indicação?');">@csrf
                    <button class="btn btn-sm btn-outline-danger">Invalidar indicação</button>
                </form>
            @endif
        @endunless
    </div>
</div>
@endif

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card fc-card">
            <div class="card-header"><h5 class="mb-0 fw-bold">Indicados ({{ $user->referrals_count }})</h5></div>
            <div class="card-body p-0">
                @forelse($referrals as $r)
                    <div class="px-3 py-2 border-bottom fc-border small">
                        <a href="{{ route('admin.users.show', $r) }}">{{ $r->full_name ?? $r->email }}</a>
                        @unless($r->referral_is_counted)<span class="badge bg-danger">Inválido</span>@endunless
                    </div>
                @empty
                    <p class="small fc-text-secondary p-3 mb-0">Nenhum indicado.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card fc-card">
            <div class="card-header"><h5 class="mb-0 fw-bold">Comissões</h5></div>
            <div class="card-body p-0">
                @forelse($commissions as $c)
                    <div class="px-3 py-2 border-bottom fc-border small d-flex justify-content-between">
                        <span>{{ $c->formatted_commission }}</span>
                        <span class="badge bg-secondary">{{ $c->status }}</span>
                    </div>
                @empty
                    <p class="small fc-text-secondary p-3 mb-0">Nenhuma comissão.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card fc-card">
            <div class="card-header"><h5 class="mb-0 fw-bold">Saques</h5></div>
            <div class="card-body p-0">
                @forelse($withdrawals as $w)
                    <div class="px-3 py-2 border-bottom fc-border small d-flex justify-content-between">
                        <span>{{ $w->formatted_amount }}</span>
                        <span class="badge bg-success">{{ $w->status_label }}</span>
                    </div>
                @empty
                    <p class="small fc-text-secondary p-3 mb-0">Nenhum saque.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
