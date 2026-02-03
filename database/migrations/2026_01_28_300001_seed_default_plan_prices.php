<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $defaults = [
            [
                'plan_key' => 'player_quarterly',
                'amount_cents' => 1990,
                'currency' => 'brl',
                'interval' => 'month',
                'interval_count' => 3,
                'name' => 'FootConnect - Jogador (Trimestral)',
                'description' => 'Acesso completo à plataforma FootConnect para jogadores. Perfil completo, vídeos, estatísticas e conexão com olheiros profissionais.',
                'display_label' => 'R$ 19,90 / 3 meses',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_key' => 'scout_monthly',
                'amount_cents' => 2990,
                'currency' => 'brl',
                'interval' => 'month',
                'interval_count' => 1,
                'name' => 'FootConnect - Olheiro (Mensal)',
                'description' => 'Acesso completo à plataforma FootConnect para profissionais. Busca avançada de jogadores, perfis detalhados e sistema de mensagens.',
                'display_label' => 'R$ 29,90 / mês',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_key' => 'scout_yearly',
                'amount_cents' => 25120,
                'currency' => 'brl',
                'interval' => 'year',
                'interval_count' => 1,
                'name' => 'FootConnect - Olheiro (Anual)',
                'description' => 'Acesso completo à plataforma FootConnect para profissionais. Economia de 30% com plano anual.',
                'display_label' => 'R$ 251,20 / ano',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($defaults as $row) {
            DB::table('plan_prices')->updateOrInsert(
                ['plan_key' => $row['plan_key']],
                $row
            );
        }
    }

    public function down(): void
    {
        DB::table('plan_prices')->whereIn('plan_key', ['player_quarterly', 'scout_monthly', 'scout_yearly'])->delete();
    }
};
