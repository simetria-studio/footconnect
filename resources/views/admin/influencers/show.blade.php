@extends('admin.layout')

@section('title', 'Influenciador — '.$influencer->email)
@section('page-title', $influencer->full_name ?: $influencer->name)
@section('page-subtitle', $influencer->email)

@section('content')
@php
    $credentials = session('influencer_credentials');
@endphp

<div class="mb-3 d-flex gap-2 flex-wrap">
    <a href="{{ route('admin.influencers.index') }}" class="btn btn-sm btn-outline-secondary">← Lista</a>
    <a href="{{ route('admin.influencers.edit', $influencer) }}" class="btn btn-sm btn-outline-success">Editar</a>
</div>

@if($credentials)
    <div class="card fc-card mb-4 border-success">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Dados para enviar ao influenciador</h5>
            <span class="badge bg-warning text-dark">A senha aparece só nesta tela</span>
        </div>
        <div class="card-body">
            <p class="small fc-text-secondary">Copie e envie por WhatsApp ou e-mail. A senha não fica salva em texto aberto.</p>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <span class="small fc-text-secondary">Login</span>
                    <p class="mb-0 fw-semibold" id="cred-email">{{ $credentials['email'] }}</p>
                </div>
                <div class="col-md-6">
                    <span class="small fc-text-secondary">Senha</span>
                    <p class="mb-0 fw-semibold"><code id="cred-password">{{ $credentials['password'] }}</code></p>
                </div>
                <div class="col-md-6">
                    <span class="small fc-text-secondary">Código</span>
                    <p class="mb-0"><code id="cred-code">{{ $credentials['referral_code'] }}</code></p>
                </div>
                <div class="col-md-6">
                    <span class="small fc-text-secondary">Link de indicação</span>
                    <p class="mb-0 small"><a href="{{ $credentials['referral_link'] }}" target="_blank" rel="noopener" id="cred-link">{{ $credentials['referral_link'] }}</a></p>
                </div>
            </div>
            <textarea id="cred-message" class="form-control bg-dark border-secondary text-white small mb-3" rows="8" readonly>Olá! Seu acesso de influenciador FootConnect:

Login: {{ $credentials['email'] }}
Senha: {{ $credentials['password'] }}
Código: {{ $credentials['referral_code'] }}
Link: {{ $credentials['referral_link'] }}

Entre em {{ url('/login') }} e altere a senha no primeiro acesso.</textarea>
            <button type="button" class="btn btn-success btn-sm" id="copy-credentials">Copiar mensagem</button>
        </div>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card fc-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Informações</h5>
                <div class="d-flex gap-1 flex-wrap">
                    <span class="badge bg-info text-dark">Influenciador</span>
                    @if($influencer->isActive())
                        <span class="badge bg-success">Ativo</span>
                    @else
                        <span class="badge bg-danger">Inativo</span>
                    @endif
                    @if($influencer->referral_program_blocked)
                        <span class="badge bg-danger">Programa bloqueado</span>
                    @endif
                </div>
            </div>
            <div class="card-body small">
                <div class="row g-3">
                    <div class="col-md-6"><span class="fc-text-secondary">Cadastro</span><br><strong>{{ $influencer->created_at->format('d/m/Y H:i') }}</strong></div>
                    <div class="col-md-6"><span class="fc-text-secondary">Plano</span><br><strong>Cortesia (sem cobrança)</strong></div>
                    <div class="col-md-6"><span class="fc-text-secondary">Cidade / Estado</span><br><strong>{{ $influencer->city ?? '—' }} / {{ $influencer->state ?? '—' }}</strong></div>
                    <div class="col-md-6"><span class="fc-text-secondary">Login</span><br><strong>{{ $influencer->email }}</strong></div>
                    <div class="col-12">
                        <span class="fc-text-secondary">Código</span><br>
                        <code class="fs-6">{{ $influencer->referral_code }}</code>
                    </div>
                    <div class="col-12">
                        <span class="fc-text-secondary">Link de indicação</span><br>
                        <a href="{{ $referralLink }}" target="_blank" rel="noopener">{{ $referralLink }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card fc-card mb-3">
            <div class="card-header"><h5 class="mb-0 fw-bold">PIX</h5></div>
            <div class="card-body small">
                <p class="mb-1"><span class="fc-text-secondary">Tipo:</span> {{ strtoupper($influencer->pix_key_type ?? '—') }}</p>
                <p class="mb-0"><span class="fc-text-secondary">Chave:</span> {{ $influencer->pix_key ?? 'Não cadastrada' }}</p>
            </div>
        </div>
        <div class="card fc-card">
            <div class="card-header"><h5 class="mb-0 fw-bold">Ganhos</h5></div>
            <div class="card-body small">
                <p class="mb-1"><span class="fc-text-secondary">Indicados válidos:</span> {{ $stats['referrals_count'] }}</p>
                <p class="mb-1"><span class="fc-text-secondary">Assinantes ativos:</span> {{ $stats['active_referrals'] }}</p>
                <p class="mb-0"><span class="fc-text-secondary">Comissões:</span> R$ {{ number_format($stats['total_earnings_cents'] / 100, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card fc-card mb-4">
    <div class="card-header"><h5 class="mb-0 fw-bold">Ações</h5></div>
    <div class="card-body d-flex flex-wrap gap-2">
        <form method="POST" action="{{ route('admin.influencers.reset-password', $influencer) }}" onsubmit="return confirm('Gerar uma nova senha? A anterior deixa de funcionar.');">
            @csrf
            <input type="hidden" name="send_credentials" value="1">
            <button class="btn btn-sm btn-outline-warning" type="submit">Gerar nova senha e enviar e-mail</button>
        </form>
        @if($influencer->isActive())
            <form method="POST" action="{{ route('admin.users.deactivate', $influencer) }}" onsubmit="return confirm('Inativar esta conta?');">@csrf
                <button class="btn btn-sm btn-outline-danger">Inativar conta</button>
            </form>
        @else
            <form method="POST" action="{{ route('admin.users.reactivate', $influencer) }}">@csrf
                <button class="btn btn-sm btn-outline-success">Reativar conta</button>
            </form>
        @endif
        @if($influencer->referral_program_blocked)
            <form method="POST" action="{{ route('admin.users.unblock-referral', $influencer) }}">@csrf
                <button class="btn btn-sm btn-success">Desbloquear programa</button>
            </form>
        @else
            <form method="POST" action="{{ route('admin.users.block-referral', $influencer) }}" onsubmit="return confirm('Bloquear o programa de indicação?');">@csrf
                <button class="btn btn-sm btn-outline-danger">Bloquear programa</button>
            </form>
        @endif
        <a href="{{ route('admin.users.show', $influencer) }}" class="btn btn-sm btn-outline-secondary">Ver ficha completa</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card fc-card">
            <div class="card-header"><h5 class="mb-0 fw-bold">Indicados ({{ $influencer->referrals_count }})</h5></div>
            <div class="card-body p-0">
                @forelse($referrals as $r)
                    <div class="px-3 py-2 border-bottom fc-border small">
                        <a href="{{ route('admin.users.show', $r) }}">{{ $r->full_name ?? $r->email }}</a>
                        @unless($r->referral_is_counted)<span class="badge bg-danger">Inválido</span>@endunless
                    </div>
                @empty
                    <p class="small fc-text-secondary p-3 mb-0">Nenhum indicado ainda.</p>
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
            <div class="card-header"><h5 class="mb-0 fw-bold">Saques PIX</h5></div>
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

@push('scripts')
<script>
document.getElementById('copy-credentials')?.addEventListener('click', async function () {
    const text = document.getElementById('cred-message')?.value || '';
    try {
        await navigator.clipboard.writeText(text);
        this.textContent = 'Copiado!';
        setTimeout(() => { this.textContent = 'Copiar mensagem'; }, 2000);
    } catch (e) {
        document.getElementById('cred-message')?.select();
        document.execCommand('copy');
    }
});
</script>
@endpush
