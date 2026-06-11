<?php

return [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),

    'prices' => [
        // Opcional: IDs dos preços se já existirem no Stripe
        // Se não definidos, os preços serão criados automaticamente
        'g1_monthly' => env('STRIPE_PRICE_G1_MONTHLY'),
        'g1_yearly' => env('STRIPE_PRICE_G1_YEARLY'),
        'g2_monthly' => env('STRIPE_PRICE_G2_MONTHLY'),
        'g2_yearly' => env('STRIPE_PRICE_G2_YEARLY'),
        'g3_monthly' => env('STRIPE_PRICE_G3_MONTHLY'),
        'g3_yearly' => env('STRIPE_PRICE_G3_YEARLY'),
        'g4_monthly' => env('STRIPE_PRICE_G4_MONTHLY'),
        'g4_yearly' => env('STRIPE_PRICE_G4_YEARLY'),
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
        'theme' => 'stripe',
        'primary_color' => env('STRIPE_PRIMARY_COLOR', '#22c55e'),
    ],
];
