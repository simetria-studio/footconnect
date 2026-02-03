@extends('admin.layout')

@section('title', 'Preços dos planos')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold fc-text-primary mb-1">Preços dos planos</h1>
        <p class="small fc-text-secondary mb-0">Configure os valores exibidos no onboarding e usados no checkout. Valores em reais (R$).</p>
    </div>
</div>

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

<div class="card fc-card">
    <div class="card-header">
        <h5 class="mb-0 fw-bold fc-text-primary">Planos de assinatura</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.plan-prices.update') }}">
            @csrf
            <div class="table-responsive">
                <table class="table table-dark mb-0">
                    <thead>
                        <tr>
                            <th class="border-0 ps-4">Plano</th>
                            <th class="border-0">Recorrência</th>
                            <th class="border-0">Preço (R$)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plans as $plan)
                            <tr>
                                <td class="ps-4 align-middle">
                                    <span class="fw-semibold fc-text-primary">{{ $plan->name }}</span>
                                    <br><span class="small fc-text-secondary">{{ $plan->plan_key }}</span>
                                </td>
                                <td class="align-middle small fc-text-secondary">
                                    @if($plan->interval === 'month')
                                        @if($plan->interval_count > 1)
                                            A cada {{ $plan->interval_count }} meses
                                        @else
                                            Mensal
                                        @endif
                                    @else
                                        Anual
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <input type="number" step="0.01" min="0" max="99999.99"
                                           class="form-control form-control-sm bg-dark border-secondary text-white"
                                           name="amount_{{ $plan->id }}"
                                           value="{{ number_format($plan->amount_reais, 2, '.', '') }}"
                                           style="max-width: 120px;">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-success">Salvar preços</button>
            </div>
        </form>
    </div>
</div>

<div class="card fc-card mt-4">
    <div class="card-body">
        <p class="small fc-text-secondary mb-0">
            <strong>Observação:</strong> Ao alterar um preço, o próximo checkout usará o novo valor. No Stripe, um novo preço pode ser criado automaticamente se o valor for diferente dos existentes. Assinaturas já ativas mantêm o valor contratado.
        </p>
    </div>
</div>
@endsection
