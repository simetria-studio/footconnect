<?php

return [
    'groups' => [
        'g1' => [
            'code' => 'G1',
            'label' => 'Atleta (Jogador)',
            'short_label' => 'Jogador',
            'role' => 'player',
            'icon' => '⚽',
            'title' => 'Quero ser vitrine de talentos',
            'description' => 'Monte um perfil esportivo completo com dados físicos, posição, pé dominante, vídeos, fotos e estatísticas para apresentar a clubes, empresários e agentes.',
            'plan_description' => 'Perfil esportivo completo, vitrine de vídeos, fotos, estatísticas e contato direto com empresários, agentes e olheiros dentro do app.',
            'accent' => 'green',
        ],
        'g2' => [
            'code' => 'G2',
            'label' => 'Empresário, Agente, Investidor, Executivo',
            'short_label' => 'Empresário / Agente',
            'role' => 'scout',
            'icon' => '💼',
            'title' => 'Quero gerenciar carreiras e negócios',
            'description' => 'Acesso à base de jogadores, filtros avançados, favoritos e mensagens internas — ideal para empresários, agentes, investidores e executivos.',
            'plan_description' => 'Busca avançada de jogadores, perfis detalhados, lista de favoritos e mensagens internas para gestão de carreira e negócios.',
            'accent' => 'yellow',
        ],
        'g3' => [
            'code' => 'G3',
            'label' => 'Treinador, Olheiro, Professor (Escola), Scout',
            'short_label' => 'Treinador / Olheiro',
            'role' => 'scout',
            'icon' => '🎯',
            'title' => 'Quero buscar e desenvolver talentos',
            'description' => 'Ferramentas de scouting, filtros por posição e perfil, favoritos e contato direto — para treinadores, olheiros, professores e scouts.',
            'plan_description' => 'Filtros avançados de busca, perfis detalhados de jogadores e comunicação direta para scouting e desenvolvimento de talentos.',
            'accent' => 'yellow',
        ],
        'g4' => [
            'code' => 'G4',
            'label' => 'Clube, Projeto, Peneiras, Camp (Evento)',
            'short_label' => 'Clube / Projeto',
            'role' => 'scout',
            'icon' => '🏟️',
            'title' => 'Quero organizar peneiras e projetos',
            'description' => 'Gestão de base de jogadores, peneiras, eventos e projetos esportivos com acesso completo à plataforma para clubes e organizações.',
            'plan_description' => 'Acesso completo para clubes, projetos, peneiras e eventos — busca de talentos, gestão de candidatos e comunicação centralizada.',
            'accent' => 'yellow',
        ],
    ],

    'annual_discount_percent' => 30,

    'valid_plan_keys' => [
        'g1_monthly', 'g1_yearly',
        'g2_monthly', 'g2_yearly',
        'g3_monthly', 'g3_yearly',
        'g4_monthly', 'g4_yearly',
    ],
];
