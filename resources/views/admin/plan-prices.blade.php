@extends('admin.layout')

@section('title', 'Planos e preços')
@section('page-title', 'Planos e preços')
@section('page-subtitle', 'Configure valores G1–G4 exibidos no onboarding e checkout')

@section('content')
@php
    $groups = ['g1' => 'G1 — Atleta', 'g2' => 'G2 — Empresário/Agente', 'g3' => 'G3 — Treinador/Olheiro', 'g4' => 'G4 — Clube/Projeto'];
@endphp

@foreach($groups as $key => $label)
    @php $groupPlans = $plans->filter(fn($p) => str_starts_with($p->plan_key, $key.'_')); @endphp
    @if($groupPlans->isNotEmpty())
        <div class="card fc-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">{{ $label }}</h5>
            </div>
            <div class="card-body p-0">
                <form method="POST" action="{{ route('admin.plan-prices.update') }}">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-dark mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Plano</th>
                                    <th>Recorrência</th>
                                    <th>Preço (R$)</th>
                                    <th>Exibição</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupPlans as $plan)
                                    <tr class="{{ !$plan->is_active ? 'opacity-50' : '' }}">
                                        <td class="ps-4 align-middle">
                                            <span class="fw-semibold">{{ $plan->name }}</span>
                                            <br><code class="small">{{ $plan->plan_key }}</code>
                                        </td>
                                        <td class="align-middle small">{{ $plan->interval === 'year' ? 'Anual' : 'Mensal' }}</td>
                                        <td class="align-middle">
                                            @if($plan->is_active)
                                                <input type="number" step="0.01" min="0" class="form-control form-control-sm bg-dark border-secondary text-white" name="amount_{{ $plan->id }}" value="{{ number_format($plan->amount_reais, 2, '.', '') }}" style="max-width: 120px;">
                                            @else
                                                <span class="small fc-text-secondary">{{ $plan->display_label }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle small fc-text-secondary">{{ $plan->display_label }}</td>
                                        <td class="align-middle">
                                            @if($plan->is_active)
                                                <span class="badge bg-success">Ativo</span>
                                            @else
                                                <span class="badge bg-secondary">Inativo</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 border-top fc-border">
                        <button type="submit" class="btn btn-success btn-sm">Salvar preços {{ strtoupper($key) }}</button>
                    </div>
                </form>
                <div class="px-3 pb-3 d-flex flex-wrap gap-2">
                    @foreach($groupPlans as $plan)
                        <form method="POST" action="{{ route('admin.plan-prices.toggle', $plan) }}" class="d-inline">@csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                {{ $plan->is_active ? 'Desativar' : 'Ativar' }} {{ $plan->plan_key }}
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endforeach

<div class="card fc-card">
    <div class="card-body small fc-text-secondary">
        <strong>Observações:</strong>
        <ul class="mb-0 mt-2">
            <li>Alterações de preço afetam novos checkouts. Assinaturas ativas mantêm o valor contratado.</li>
            <li>Planos inativos não aparecem no onboarding.</li>
            <li>O Stripe pode criar novos price IDs automaticamente quando o valor mudar.</li>
        </ul>
    </div>
</div>
@endsection
