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
            'plan_type' => 'player',
            'plan_interval' => 'quarterly',
            'subscription_status' => 'active',
            'current_period_end' => $nextQuarter,
        ]);

        PlayerProfile::create([
            'user_id' => $player1->id,
            'position' => 'Atacante',
            'age' => 21,
            'height_cm' => 180,
            'weight_kg' => 75,
            'current_club' => 'Clube Teste FC',
            'city' => 'São Paulo',
            'state' => 'SP',
            'dominant_foot' => 'right',
            'bio' => 'Atacante de velocidade, bom 1x1 e finalização com a perna direita.',
        ]);

        $player2 = User::create([
            'name' => 'Jogador Teste 2',
            'full_name' => 'Jogador Teste 2',
            'email' => 'jogador2@footconnect.test',
            'password' => Hash::make('password'),
            'role' => 'player',
            'city' => 'Rio de Janeiro',
            'state' => 'RJ',
            'plan_type' => 'player',
            'plan_interval' => 'quarterly',
            'subscription_status' => 'active',
            'current_period_end' => $nextQuarter,
        ]);

        PlayerProfile::create([
            'user_id' => $player2->id,
            'position' => 'Zagueiro',
            'age' => 24,
            'height_cm' => 188,
            'weight_kg' => 82,
            'current_club' => 'Defesa Forte FC',
            'city' => 'Rio de Janeiro',
            'state' => 'RJ',
            'dominant_foot' => 'left',
            'bio' => 'Zagueiro canhoto, forte no jogo aéreo e saída de bola.',
        ]);

        // Profissionais de teste
        $scoutMonthly = User::create([
            'name' => 'Empresário Mensal',
            'full_name' => 'Empresário Mensal',
            'email' => 'empresario@footconnect.test',
            'password' => Hash::make('password'),
            'role' => 'scout',
            'city' => 'Belo Horizonte',
            'state' => 'MG',
            'plan_type' => 'scout',
            'plan_interval' => 'monthly',
            'subscription_status' => 'active',
            'current_period_end' => $nextMonth,
        ]);

        ScoutProfile::create([
            'user_id' => $scoutMonthly->id,
            'professional_type' => 'empresario',
            'organization' => 'Agência Talentos do Futuro',
            'city' => 'Belo Horizonte',
            'state' => 'MG',
            'website' => 'https://agenciatestes.com',
            'bio' => 'Empresário focado em jovens talentos sub-17 e sub-20.',
        ]);

        $scoutYearly = User::create([
            'name' => 'Olheiro Anual',
            'full_name' => 'Olheiro Anual',
            'email' => 'olheiro@footconnect.test',
            'password' => Hash::make('password'),
            'role' => 'scout',
            'city' => 'Porto Alegre',
            'state' => 'RS',
            'plan_type' => 'scout',
            'plan_interval' => 'yearly',
            'subscription_status' => 'active',
            'current_period_end' => $nextYear,
        ]);

        ScoutProfile::create([
            'user_id' => $scoutYearly->id,
            'professional_type' => 'olheiro',
            'organization' => 'Clube Observador FC',
            'city' => 'Porto Alegre',
            'state' => 'RS',
            'bio' => 'Olheiro especializado em mercado sul-americano.',
        ]);
    }
}
