@php
    $groupConfig = config('plans.groups.'.$planGroup);
    $isGreenAccent = ($groupConfig['accent'] ?? 'green') === 'green';
    $monthlyKey = $planGroup.'_monthly';
    $yearlyKey = $planGroup.'_yearly';
@endphp

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Planos — FootConnect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Inter", sans-serif;
            background: radial-gradient(circle at top, #0f172a 0%, #020617 45%, #020617 100%);
            color: #f9fafb;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .fc-onboarding-wrapper {
            width: 100%;
            max-width: 960px;
            padding: 2.5rem 1.5rem;
        }
        .fc-onboarding-card {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 24px;
            border: 1px solid rgba(148, 163, 184, 0.35);
            box-shadow: 0 24px 80px rgba(15, 23, 42, 0.85);
            padding: 2rem;
        }
        @media (min-width: 992px) {
            .fc-onboarding-card {
                padding: 2.5rem 3rem;
            }
        }
        .fc-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            border-radius: 999px;
            padding: 0.25rem 0.75rem;
            font-size: 0.7rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #e5e7eb;
            border: 1px solid rgba(148, 163, 184, 0.5);
            background: rgba(15, 23, 42, 0.9);
            margin-bottom: 0.75rem;
        }
        .fc-pill-dot {
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: #22c55e;
            box-shadow: 0 0 10px rgba(34, 197, 94, 0.9);
        }
        .fc-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .fc-subtitle {
            font-size: 0.95rem;
            color: #9ca3af;
            max-width: 520px;
        }
        .fc-plan-card {
            border-radius: 18px;
            border: 1px solid rgba(148, 163, 184, 0.4);
            background: radial-gradient(circle at top left, rgba(34, 197, 94, 0.14), transparent 60%), rgba(15, 23, 42, 0.9);
            padding: 1.5rem 1.75rem;
        }
        .fc-plan-card--yellow {
            background: radial-gradient(circle at top left, rgba(250, 204, 21, 0.14), transparent 60%), rgba(15, 23, 42, 0.95);
        }
        .fc-plan-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #a7f3d0;
            margin-bottom: 0.15rem;
        }
        .fc-plan-label-secondary {
            color: #facc15;
        }
        .fc-plan-code {
            font-size: 0.65rem;
            font-weight: 700;
            color: #6b7280;
            margin-left: 0.35rem;
        }
        .fc-price-note {
            font-size: 0.8rem;
            color: #9ca3af;
        }
        .fc-plan-description {
            font-size: 0.85rem;
            color: #9ca3af;
            margin-top: 0.35rem;
        }
        .fc-cta {
            margin-top: 1.5rem;
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            align-items: center;
        }
        .fc-btn-primary {
            border: none;
            border-radius: 999px;
            padding: 0.65rem 1.6rem;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            color: #020617;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            box-shadow: 0 10px 30px rgba(34, 197, 94, 0.4);
            transition: all 0.2s ease-out;
        }
        .fc-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 45px rgba(34, 197, 94, 0.55);
        }
        .fc-small-note {
            font-size: 0.8rem;
            color: #6b7280;
        }
        .fc-radios {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 0.9rem;
            margin-top: 1rem;
        }
        @media (min-width: 768px) {
            .fc-radios {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        .fc-radio-label {
            border-radius: 16px;
            padding: 1.1rem 1.25rem;
            cursor: pointer;
            border: 1px solid rgba(148, 163, 184, 0.4);
            background: rgba(15, 23, 42, 0.9);
            display: block;
            transition: all 0.2s ease-out;
        }
        .fc-radio-label:hover {
            border-color: rgba(34, 197, 94, 0.7);
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.9);
        }
        .fc-radio-label input {
            display: none;
        }
        .fc-radio-title {
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.1rem;
        }
        .fc-radio-price {
            font-size: 0.9rem;
            color: #e5e7eb;
            margin-bottom: 0.15rem;
        }
        .fc-radio-text {
            font-size: 0.8rem;
            color: #9ca3af;
        }
        .fc-radio-label--primary {
            border-color: rgba(34, 197, 94, 0.9) !important;
            background: radial-gradient(circle at top left, rgba(34, 197, 94, 0.25), transparent 60%), rgba(15, 23, 42, 0.95) !important;
            box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2);
        }
        .fc-radio-label--primary .fc-radio-title {
            color: #22c55e;
        }
        .fc-radio-label--primary-yellow {
            border-color: rgba(250, 204, 21, 0.9) !important;
            background: radial-gradient(circle at top left, rgba(250, 204, 21, 0.2), transparent 60%), rgba(15, 23, 42, 0.95) !important;
            box-shadow: 0 0 0 2px rgba(250, 204, 21, 0.2);
        }
        .fc-radio-label--primary-yellow .fc-radio-title {
            color: #facc15;
        }
        .fc-radio-content {
            pointer-events: none;
        }
        .fc-tag-saving {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.15rem 0.6rem;
            border-radius: 999px;
            font-size: 0.7rem;
            background: rgba(250, 204, 21, 0.12);
            color: #facc15;
            border: 1px solid rgba(250, 204, 21, 0.6);
            margin-left: 0.3rem;
        }
    </style>
</head>
<body>
<div class="fc-onboarding-wrapper">
    <div class="fc-onboarding-card">
        <div class="mb-4">
            <div class="fc-pill">
                <span class="fc-pill-dot"></span>
                Passo 3 de 4 • Escolha seu plano
            </div>
            <h1 class="fc-title">Escolha seu plano</h1>
            <p class="fc-subtitle">
                O acesso ao FootConnect é exclusivo para assinantes. Selecione a periodicidade ideal e siga para o pagamento seguro.
            </p>
        </div>

        @if(request('canceled'))
            <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                <svg style="width: 20px; height: 20px; color: #f87171; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>
                    <p style="font-size: 0.9rem; font-weight: 600; color: #fca5a5; margin: 0 0 0.25rem;">Pagamento cancelado</p>
                    <p style="font-size: 0.85rem; color: #9ca3af; margin: 0;">Você pode tentar novamente selecionando um plano abaixo.</p>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('onboarding.checkout') }}">
            @csrf

            <div class="fc-plan-card {{ $isGreenAccent ? '' : 'fc-plan-card--yellow' }}">
                <p class="fc-plan-label {{ $isGreenAccent ? '' : 'fc-plan-label-secondary' }}">
                    {{ $groupConfig['short_label'] }}
                    <span class="fc-plan-code">{{ $groupConfig['code'] }}</span>
                </p>
                <h2 style="font-size: 0.95rem; font-weight: 600; margin: 0 0 0.35rem;">
                    {{ $groupConfig['label'] }}
                </h2>
                <p class="fc-plan-description">
                    {{ $groupConfig['plan_description'] }}
                </p>

                <div class="fc-radios">
                    <label class="fc-radio-label {{ $isGreenAccent ? 'fc-radio-label--primary' : 'fc-radio-label--primary-yellow' }}">
                        <input type="radio" name="plan" value="{{ $monthlyKey }}" checked>
                        <div class="fc-radio-content">
                            <div class="fc-radio-title">Plano Mensal</div>
                            <div class="fc-radio-price">
                                {{ $monthlyPlan ? $monthlyPlan->formatted_price : '—' }}
                                <span class="fc-price-note">/ mês</span>
                            </div>
                            <p class="fc-radio-text">
                                Flexibilidade total com renovação mensal automática.
                            </p>
                        </div>
                    </label>

                    <label class="fc-radio-label" style="border-color: rgba(250, 204, 21, 0.6);">
                        <input type="radio" name="plan" value="{{ $yearlyKey }}">
                        <div class="fc-radio-content">
                            <div class="fc-radio-title">
                                Plano Anual
                                <span class="fc-tag-saving">
                                    {{ config('plans.annual_discount_percent') }}% OFF
                                </span>
                            </div>
                            <div class="fc-radio-price">
                                {{ $yearlyPlan ? $yearlyPlan->formatted_price : '—' }}
                                <span class="fc-price-note">/ ano</span>
                            </div>
                            <p class="fc-radio-text">
                                Economia de {{ config('plans.annual_discount_percent') }}% com acesso garantido por 12 meses.
                            </p>
                        </div>
                    </label>
                </div>

                <div class="fc-cta">
                    <button type="submit" class="fc-btn-primary">
                        Assinar e ir para pagamento
                    </button>
                    <span class="fc-small-note">
                        Cobrança recorrente gerenciada pelo Stripe, com opção de cancelamento no app.
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('input[name="plan"]');
        const isGreen = {{ $isGreenAccent ? 'true' : 'false' }};

        function updateSelection() {
            radios.forEach(radio => {
                const label = radio.closest('.fc-radio-label');
                label.classList.remove('fc-radio-label--primary', 'fc-radio-label--primary-yellow');
                if (radio.checked) {
                    label.classList.add(isGreen ? 'fc-radio-label--primary' : 'fc-radio-label--primary-yellow');
                }
            });
        }

        updateSelection();

        radios.forEach(radio => {
            radio.addEventListener('change', updateSelection);
            radio.closest('.fc-radio-label').addEventListener('click', function(e) {
                if (e.target !== radio) {
                    radio.checked = true;
                    updateSelection();
                }
            });
        });
    });
</script>
</body>
</html>
