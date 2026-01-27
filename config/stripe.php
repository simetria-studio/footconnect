<?php

return [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),

    'prices' => [
        // Opcional: IDs dos preços se já existirem no Stripe
        // Se não definidos, os preços serão criados automaticamente
        'player_quarterly' => env('STRIPE_PRICE_PLAYER_QUARTERLY'), // R$ 19,90 / 3 meses
        'scout_monthly' => env('STRIPE_PRICE_SCOUT_MONTHLY'), // R$ 29,90 / mês
        'scout_yearly' => env('STRIPE_PRICE_SCOUT_YEARLY'), // R$ 251,20 / ano (30% off)
    ],

    /*
    |--------------------------------------------------------------------------
    | Stripe Checkout Appearance
    |--------------------------------------------------------------------------
    |
    | O design visual do checkout hospedado do Stripe é controlado principalmente
    | pelo painel do Stripe. Para personalizar:
    |
    | 1. Acesse: https://dashboard.stripe.com/settings/branding
    | 2. Adicione seu logo (recomendado: 128x128px, PNG ou SVG)
    | 3. Configure as cores da marca (cor primária e secundária)
    | 4. Personalize a aparência geral
    |
    | As configurações abaixo são aplicadas via API quando disponíveis.
    */
    'appearance' => [
        'theme' => 'stripe', // 'stripe' ou 'night' (modo escuro)
        'primary_color' => env('STRIPE_PRIMARY_COLOR', '#22c55e'), // Verde FootConnect
    ],
];

