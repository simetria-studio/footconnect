<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('plan_prices')
            ->whereIn('plan_key', ['player_quarterly', 'scout_monthly', 'scout_yearly'])
            ->update(['is_active' => false, 'updated_at' => now()]);

        $plans = [
            [
                'plan_key' => 'g1_monthly',
                'amount_cents' => 1990,
                'currency' => 'brl',
                'interval' => 'month',
                'interval_count' => 1,
                'name' => 'FootConnect G1 — Atleta (Mensal)',
                'description' => 'Acesso completo à plataforma FootConnect para atletas. Perfil completo, vídeos, estatísticas e conexão com profissionais do futebol.',
                'display_label' => 'R$ 19,90 / mês',
                'sort_order' => 1,
            ],
            [
                'plan_key' => 'g1_yearly',
                'amount_cents' => 16716,
                'currency' => 'brl',
                'interval' => 'year',
                'interval_count' => 1,
                'name' => 'FootConnect G1 — Atleta (Anual)',
                'description' => 'Acesso completo à plataforma FootConnect para atletas. Economia de 30% com plano anual.',
                'display_label' => 'R$ 167,16 / ano',
                'sort_order' => 2,
            ],
            [
                'plan_key' => 'g2_monthly',
                'amount_cents' => 25000,
                'currency' => 'brl',
                'interval' => 'month',
                'interval_count' => 1,
                'name' => 'FootConnect G2 — Empresário/Agente (Mensal)',
                'description' => 'Acesso completo para empresários, agentes, investidores e executivos. Busca avançada, favoritos e mensagens.',
                'display_label' => 'R$ 250,00 / mês',
                'sort_order' => 3,
            ],
            [
                'plan_key' => 'g2_yearly',
                'amount_cents' => 210000,
                'currency' => 'brl',
                'interval' => 'year',
                'interval_count' => 1,
                'name' => 'FootConnect G2 — Empresário/Agente (Anual)',
                'description' => 'Acesso completo para empresários, agentes, investidores e executivos. Economia de 30% com plano anual.',
                'display_label' => 'R$ 2.100,00 / ano',
                'sort_order' => 4,
            ],
            [
                'plan_key' => 'g3_monthly',
                'amount_cents' => 10000,
                'currency' => 'brl',
                'interval' => 'month',
                'interval_count' => 1,
                'name' => 'FootConnect G3 — Treinador/Olheiro (Mensal)',
                'description' => 'Acesso completo para treinadores, olheiros, professores e scouts. Ferramentas de scouting e busca de talentos.',
                'display_label' => 'R$ 100,00 / mês',
                'sort_order' => 5,
            ],
            [
                'plan_key' => 'g3_yearly',
                'amount_cents' => 84000,
                'currency' => 'brl',
                'interval' => 'year',
                'interval_count' => 1,
                'name' => 'FootConnect G3 — Treinador/Olheiro (Anual)',
                'description' => 'Acesso completo para treinadores, olheiros, professores e scouts. Economia de 30% com plano anual.',
                'display_label' => 'R$ 840,00 / ano',
                'sort_order' => 6,
            ],
            [
                'plan_key' => 'g4_monthly',
                'amount_cents' => 30000,
                'currency' => 'brl',
                'interval' => 'month',
                'interval_count' => 1,
                'name' => 'FootConnect G4 — Clube/Projeto (Mensal)',
                'description' => 'Acesso completo para clubes, projetos, peneiras e eventos. Gestão de talentos e comunicação centralizada.',
                'display_label' => 'R$ 300,00 / mês',
                'sort_order' => 7,
            ],
            [
                'plan_key' => 'g4_yearly',
                'amount_cents' => 252000,
                'currency' => 'brl',
                'interval' => 'year',
                'interval_count' => 1,
                'name' => 'FootConnect G4 — Clube/Projeto (Anual)',
                'description' => 'Acesso completo para clubes, projetos, peneiras e eventos. Economia de 30% com plano anual.',
                'display_label' => 'R$ 2.520,00 / ano',
                'sort_order' => 8,
            ],
        ];

        foreach ($plans as $row) {
            DB::table('plan_prices')->updateOrInsert(
                ['plan_key' => $row['plan_key']],
                array_merge($row, [
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }

    public function down(): void
    {
        DB::table('plan_prices')
            ->whereIn('plan_key', [
                'g1_monthly', 'g1_yearly',
                'g2_monthly', 'g2_yearly',
                'g3_monthly', 'g3_yearly',
                'g4_monthly', 'g4_yearly',
            ])
            ->delete();

        DB::table('plan_prices')
            ->whereIn('plan_key', ['player_quarterly', 'scout_monthly', 'scout_yearly'])
            ->update(['is_active' => true, 'updated_at' => now()]);
    }
};
