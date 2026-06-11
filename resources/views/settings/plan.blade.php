@extends('layouts.app')

@section('title', 'Plano FootConnect — FootConnect')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h1 class="h5 fw-bold fc-text-primary mb-0">Plano FootConnect</h1>
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
            </div>

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @php
                $planGroupKey = $user->plan_group ?? $user->plan_type;
                $groupConfig = $planGroupKey ? config('plans.groups.'.$planGroupKey) : null;
                $tipo = $groupConfig['label'] ?? match ($user->plan_type) {
                    'player' => 'Jogador',
                    'scout' => 'Profissional',
                    default => null,
                };
                $intervalo = match ($user->plan_interval) {
                    'quarterly' => 'Trimestral',
                    'monthly' => 'Mensal',
                    'yearly' => 'Anual',
                    default => null,
                };
            @endphp

            <!-- Status da Assinatura -->
            <div class="card fc-card mb-4">
                <div class="card-body">
                    @if ($user->subscription_status === 'active')
                        <div class="alert alert-success mb-3">
                            <h6 class="fw-bold mb-1">Assinatura ativa</h6>
                            <p class="mb-1">
                                <span class="fw-semibold">{{ $tipo ?? 'Plano ativo' }}</span>
                                @if($intervalo) — {{ $intervalo }} @endif
                            </p>
                            @if($user->current_period_end)
                                <p class="small mb-0">
                                    Renovação em {{ $user->current_period_end->format('d/m/Y') }}
                                </p>
                            @endif
                        </div>
                        <p class="small fc-text-secondary mb-0">
                            Para alterar forma de pagamento ou trocar de plano, atualize diretamente no Stripe Billing
                            (integração a ser configurada).
                        </p>
                    @else
                        <div class="alert alert-danger mb-3">
                            <h6 class="fw-bold mb-1">Nenhuma assinatura ativa</h6>
                            <p class="small mb-0">
                                Seu acesso pode ser limitado. Reative um plano para continuar usando todos os recursos do FootConnect.
                            </p>
                        </div>
                        <a href="{{ route('onboarding.user-type') }}" class="btn btn-success">
                            Ver planos
                        </a>
                    @endif
                </div>
            </div>

            @if ($user->subscription_status === 'active')
                <!-- Cancelar Assinatura -->
                <div class="card fc-card">
                    <div class="card-header">
                        <h5 class="mb-0 fw-bold">Cancelar assinatura</h5>
                    </div>
                    <div class="card-body">
                        <p class="small fc-text-secondary mb-3">
                            O cancelamento interrompe futuras renovações. Seu acesso permanece até o fim do período já pago.
                        </p>
                        <form method="POST" action="{{ route('settings.plan.cancel') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger">Cancelar renovação automática</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
