<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PlayerProfile;
use App\Models\ScoutProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $nextQuarter = $now->copy()->addMonths(3);
        $nextMonth = $now->copy()->addMonth();
        $nextYear = $now->copy()->addYear();

        // Jogadores de teste
        $player1 = User::create([
            'name' => 'Jogador Teste 1',
            'full_name' => 'Jogador Teste 1',
            'email' => 'jogador1@footconnect.test',
            'password' => Hash::make('password'),
            'role' => 'player',
            'city' => 'São Paulo',
            'state' => 'SP',
            'plan_group' => 'g1',
            'plan_type' => 'g1',
            'plan_interval' => 'monthly',
            'country' => 'Brasil',
            'subscription_status' => 'active',
            'current_period_end' => $nextQuarter,
        ]);

        PlayerProfile::create([
            'user_id' => $player1->id,
            'modality' => 'campo',
            'gender' => 'male',
            'position' => 'Atacante',
            'age' => 21,
            'height_cm' => 180,
            'current_club' => 'Clube Teste FC',
            'institution_type' => 'clube',
            'institution_name' => 'Clube Teste FC',
            'city' => 'São Paulo',
            'state' => 'SP',
            'country' => 'Brasil',
            'dominant_foot' => 'right',
            'characteristics' => 'Atacante de velocidade, bom 1x1 e finalização com a perna direita.',
            'is_federated' => true,
        ]);

        $player2 = User::create([
            'name' => 'Jogador Teste 2',
            'full_name' => 'Jogador Teste 2',
            'email' => 'jogador2@footconnect.test',
            'password' => Hash::make('password'),
            'role' => 'player',
            'city' => 'Rio de Janeiro',
            'state' => 'RJ',
            'country' => 'Brasil',
            'plan_group' => 'g1',
            'plan_type' => 'g1',
            'plan_interval' => 'monthly',
            'subscription_status' => 'active',
            'current_period_end' => $nextQuarter,
        ]);

        PlayerProfile::create([
            'user_id' => $player2->id,
            'modality' => 'futsal',
            'gender' => 'male',
            'position' => 'Zagueiro',
            'age' => 24,
            'height_cm' => 188,
            'institution_type' => 'clube',
            'institution_name' => 'Defesa Forte FC',
            'city' => 'Rio de Janeiro',
            'state' => 'RJ',
            'country' => 'Brasil',
            'dominant_foot' => 'left',
            'characteristics' => 'Zagueiro canhoto, forte no jogo aéreo e saída de bola.',
            'is_federated' => false,
        ]);

        // Profissionais de teste
        $scoutMonthly = User::create([
            'name' => 'Empresário Mensal',
            'full_name' => 'Empresário Mensal',
            'email' => 'empresario@footconnect.test',
            'password' => Hash::make('password'),
            'role' => 'scout',
            'plan_group' => 'g2',
            'city' => 'Belo Horizonte',
            'state' => 'MG',
            'country' => 'Brasil',
            'plan_type' => 'g2',
            'plan_interval' => 'monthly',
            'subscription_status' => 'active',
            'current_period_end' => $nextMonth,
        ]);

        ScoutProfile::create([
            'user_id' => $scoutMonthly->id,
            'age' => 42,
            'has_company' => true,
            'company_name' => 'Agência Talentos do Futuro',
            'scope' => 'nacional',
            'city' => 'Belo Horizonte',
            'state' => 'MG',
            'country' => 'Brasil',
            'is_federated' => true,
            'federation_name' => 'Federação Mineira de Futebol',
        ]);

        $scoutYearly = User::create([
            'name' => 'Olheiro Anual',
            'full_name' => 'Olheiro Anual',
            'email' => 'olheiro@footconnect.test',
            'password' => Hash::make('password'),
            'role' => 'scout',
            'plan_group' => 'g3',
            'city' => 'Porto Alegre',
            'state' => 'RS',
            'country' => 'Brasil',
            'plan_type' => 'g3',
            'plan_interval' => 'yearly',
            'subscription_status' => 'active',
            'current_period_end' => $nextYear,
        ]);

        ScoutProfile::create([
            'user_id' => $scoutYearly->id,
            'age' => 38,
            'has_company' => true,
            'company_name' => 'Clube Observador FC',
            'scope' => 'internacional',
            'city' => 'Porto Alegre',
            'state' => 'RS',
            'country' => 'Brasil',
            'is_federated' => false,
        ]);
    }
}
