@extends('layouts.app')

@section('title', 'Indique e Ganhe — FootConnect')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h5 fw-bold fc-text-primary mb-1">Indique e Ganhe</h1>
                    <p class="small fc-text-secondary mb-0">
                        {{ $stats['commission_percent'] }}% de comissão recorrente enquanto seu indicado assinar.
                    </p>
                </div>
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
            </div>

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($stats['is_blocked'] ?? false)
                <div class="alert alert-danger mb-4">
                    <strong>Programa bloqueado.</strong> Sua conta foi bloqueada permanentemente por violação da política anti-fraude (spam ou autoindicação).
                </div>
            @endif

            <div class="card fc-card mb-4 border-warning">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Política anti-fraude</h5>
                </div>
                <div class="card-body">
                    <ul class="small fc-text-secondary mb-0">
                        @foreach(config('referrals.policy') as $rule)
                            <li>{{ $rule }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="card fc-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Benefícios para afiliados</h5>
                    <a href="{{ route('referrals.ranking') }}" class="btn btn-sm btn-outline-success">Ranking oficial</a>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        @foreach(config('referrals.affiliate_benefits') as $benefit)
                            <div class="col-12 col-md-6">
                                <span class="small fc-text-secondary">✓ {{ $benefit }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card fc-card h-100 text-center">
                        <div class="card-body py-3">
                            <p class="small text-muted mb-1">Indicados</p>
                            <p class="h4 fw-bold fc-text-primary mb-0">{{ $stats['referrals_count'] }}</p>
                            <p class="small fc-text-secondary mb-0">{{ $stats['active_referrals'] }} ativos</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card fc-card h-100 text-center">
                        <div class="card-body py-3">
                            <p class="small text-muted mb-1">Ganhos totais</p>
                            <p class="h5 fw-bold text-success mb-0">R$ {{ number_format($stats['total_earnings_cents'] / 100, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card fc-card h-100 text-center">
                        <div class="card-body py-3">
                            <p class="small text-muted mb-1">A liberar</p>
                            <p class="h5 fw-bold fc-text-primary mb-0">R$ {{ number_format($stats['pending_cents'] / 100, 2, ',', '.') }}</p>
                            <p class="small fc-text-secondary mb-0">em {{ config('referrals.payout_delay_days') }} dias</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card fc-card h-100 text-center">
                        <div class="card-body py-3">
                            <p class="small text-muted mb-1">Saques (PIX)</p>
                            <p class="h5 fw-bold fc-text-primary mb-0">R$ {{ number_format($stats['withdrawn_cents'] / 100, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card fc-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Seu código e link exclusivo</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('referrals.code.update') }}" class="mb-3">
                        @csrf
                        <label class="form-label small fc-text-secondary">Código personalizado (ex: FOOT23)</label>
                        <div class="input-group">
                            <input type="text" class="form-control fw-bold text-uppercase" id="referral-code" name="referral_code" value="{{ old('referral_code', $user->referral_code) }}" {{ ($stats['is_blocked'] ?? false) ? 'readonly' : '' }} maxlength="16" placeholder="FOOT23">
                            @unless($stats['is_blocked'] ?? false)
                                <button type="submit" class="btn btn-success">Salvar</button>
                            @endunless
                            <button type="button" class="btn btn-outline-success" onclick="copyText('referral-code')">Copiar</button>
                        </div>
                        <p class="small fc-text-secondary mt-1 mb-0">Deve começar com FOOT e ter de 6 a 16 caracteres.</p>
                    </form>
                    <div>
                        <label class="form-label small fc-text-secondary">Link exclusivo</label>
                        <div class="input-group">
                            <input type="text" class="form-control small" id="referral-link" value="{{ $referralLink }}" readonly>
                            <button type="button" class="btn btn-outline-success" onclick="copyText('referral-link')">Copiar</button>
                        </div>
                    </div>
                    <p class="small fc-text-secondary mt-3 mb-0">
                        Compartilhe o código <strong>{{ $user->referral_code }}</strong> ou o link acima.
                        Você ganha <strong>{{ $stats['commission_percent'] }}%</strong> de comissão recorrente em cada pagamento do indicado,
                        dentro ou fora do plano afiliado. O valor é creditado automaticamente via PIX
                        <strong>{{ config('referrals.payout_delay_days') }} dias</strong> após a compensação do pagamento.
                    </p>
                </div>
            </div>

            <div class="card fc-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Recebimento via PIX</h5>
                </div>
                <div class="card-body">
                    <p class="small fc-text-secondary mb-3">
                        Cadastre sua chave PIX para receber automaticamente na sua conta
                        {{ config('referrals.payout_delay_days') }} dias após cada pagamento compensado do indicado.
                    </p>
                    <form method="POST" action="{{ route('referrals.pix.update') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label for="pix_key_type" class="form-label">Tipo de chave</label>
                                <select class="form-select" id="pix_key_type" name="pix_key_type" required>
                                    <option value="">Selecione</option>
                                    @foreach(config('referrals.pix_key_types') as $value => $label)
                                        <option value="{{ $value }}" {{ old('pix_key_type', $user->pix_key_type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-8">
                                <label for="pix_key" class="form-label">Chave PIX</label>
                                <input type="text" class="form-control" id="pix_key" name="pix_key" value="{{ old('pix_key', $user->pix_key) }}" required placeholder="Sua chave PIX">
                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger mt-3 mb-0">
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="mt-3">
                            <button type="submit" class="btn btn-success">Salvar chave PIX</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card fc-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Seus indicados</h5>
                    <span class="badge bg-success">{{ $stats['referrals_count'] }}</span>
                </div>
                <div class="card-body p-0">
                    @forelse($referrals as $referral)
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom fc-border">
                            <div>
                                <p class="mb-0 small fw-semibold fc-text-primary">{{ $referral->full_name ?? $referral->name }}</p>
                                <p class="mb-0 small fc-text-secondary">Desde {{ $referral->created_at->format('d/m/Y') }}</p>
                            </div>
                            <div class="text-end">
                                @unless($referral->referral_is_counted)
                                    <span class="badge bg-danger mb-1">Não contabilizado</span>
                                    @if($referral->referral_invalid_reason)
                                        <p class="small text-danger mb-0">{{ $referral->referral_invalid_reason }}</p>
                                    @endif
                                @else
                                    <span class="badge {{ $referral->subscription_status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $referral->subscription_status === 'active' ? 'Assinante ativo' : 'Inativo' }}
                                    </span>
                                @endunless
                            </div>
                        </div>
                    @empty
                        <p class="small fc-text-secondary p-3 mb-0">Nenhum indicado ainda. Compartilhe seu link!</p>
                    @endforelse
                </div>
            </div>

            <div class="card fc-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Histórico de ganhos</h5>
                </div>
                <div class="card-body p-0">
                    @forelse($commissions as $commission)
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom fc-border">
                            <div>
                                <p class="mb-0 small fw-semibold fc-text-primary">{{ $commission->formatted_commission }}</p>
                                <p class="mb-0 small fc-text-secondary">
                                    {{ $commission->referred->full_name ?? $commission->referred->name ?? 'Indicado' }}
                                    · {{ $commission->commission_percent }}%
                                </p>
                            </div>
                            <span class="badge {{ match($commission->status) {
                                'paid' => 'bg-success',
                                'available' => 'bg-info',
                                default => 'bg-warning text-dark',
                            } }}">
                                @switch($commission->status)
                                    @case('paid') Pago @break
                                    @case('available') Disponível @break
                                    @default Liberação em {{ $commission->available_at?->format('d/m/Y') }}
                                @endswitch
                            </span>
                        </div>
                    @empty
                        <p class="small fc-text-secondary p-3 mb-0">Nenhuma comissão registrada ainda.</p>
                    @endforelse
                </div>
            </div>

            <div class="card fc-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Saques via PIX</h5>
                </div>
                <div class="card-body p-0">
                    @forelse($withdrawals as $withdrawal)
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom fc-border">
                            <div>
                                <p class="mb-0 small fw-semibold fc-text-primary">{{ $withdrawal->formatted_amount }}</p>
                                <p class="mb-0 small fc-text-secondary">
                                    {{ $withdrawal->processed_at?->format('d/m/Y H:i') ?? $withdrawal->created_at->format('d/m/Y') }}
                                    @if($withdrawal->is_automatic) · Automático @endif
                                </p>
                            </div>
                            <span class="badge {{ $withdrawal->status === 'completed' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $withdrawal->status_label }}
                            </span>
                        </div>
                    @empty
                        <p class="small fc-text-secondary p-3 mb-0">Nenhum saque realizado ainda.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyText(elementId) {
        const el = document.getElementById(elementId);
        el.select();
        el.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(el.value);
    }
</script>
@endpush
@endsection
