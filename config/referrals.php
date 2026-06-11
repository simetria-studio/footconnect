<?php

return [
    'commission_percent' => 25,
    'recurring' => true,
    'payout_delay_days' => 2,
    'code_prefix' => 'FOOT',

    'custom_code_min_length' => 6,
    'custom_code_max_length' => 16,
    'custom_code_pattern' => '/^FOOT[A-Z0-9]{2,12}$/',

    'antifraud' => [
        'self_referral_blocked' => true,
        'same_ip_not_counted' => true,
        'max_referrals_same_ip_per_day' => 3,
        'spam_block_permanent' => true,
        'require_active_subscription_for_commission' => true,
    ],

    'policy' => [
        'Contas falsas ou sem assinatura ativa não serão contabilizadas.',
        'Autoindicação é proibida e resulta em desqualificação da indicação.',
        'Spam ou abuso do programa pode gerar bloqueio permanente do afiliado.',
        'Comissões são recorrentes enquanto o indicado mantiver assinatura válida.',
    ],

    'affiliate_benefits' => [
        'Link exclusivo de indicação',
        'Código personalizado (ex: FOOT23)',
        'Dashboard de ganhos em tempo real',
        'Ranking oficial de afiliados',
        'Comissões recorrentes de 25%',
        'Recebimento automático via PIX',
    ],

    'pix_key_types' => [
        'cpf' => 'CPF',
        'cnpj' => 'CNPJ',
        'email' => 'E-mail',
        'phone' => 'Telefone',
        'random' => 'Chave aleatória',
    ],
];
