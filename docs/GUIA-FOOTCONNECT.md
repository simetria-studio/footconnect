# FootConnect — Guia do que foi criado e como configurar

Documento de referência das funcionalidades implementadas neste projeto e dos passos para colocar tudo em funcionamento.

---

## Índice

1. [Visão geral](#1-visão-geral)
2. [Requisitos](#2-requisitos)
3. [Instalação inicial](#3-instalação-inicial)
4. [Variáveis de ambiente](#4-variáveis-de-ambiente)
5. [Planos de assinatura (G1–G4)](#5-planos-de-assinatura-g1g4)
6. [Perfis por grupo](#6-perfis-por-grupo)
7. [Indique e Ganhe (afiliados)](#7-indique-e-ganhe-afiliados)
8. [Busca, filtros e favoritos](#8-busca-filtros-e-favoritos)
9. [Stripe (pagamentos)](#9-stripe-pagamentos)
10. [Tarefas agendadas (cron)](#10-tarefas-agendadas-cron)
11. [Painel administrativo](#11-painel-administrativo)
12. [Rotas principais](#12-rotas-principais)
13. [Arquivos de configuração](#13-arquivos-de-configuração)
14. [Migrations criadas](#14-migrations-criadas)
15. [Comandos úteis](#15-comandos-úteis)
16. [Dados de teste](#16-dados-de-teste)
17. [Observações importantes](#17-observações-importantes)

---

## 1. Visão geral

O FootConnect é uma plataforma Laravel para conectar **atletas** e **profissionais do futebol**, com:

- Onboarding em 4 passos (perfil → cadastro → plano → pagamento)
- 4 grupos de plano com preços mensais e anuais
- Perfis detalhados por tipo de usuário
- Assinaturas recorrentes via **Stripe**
- Programa **Indique e Ganhe** com comissão de 25% e PIX automático
- Busca avançada de jogadores e lista de favoritos
- Mensagens internas entre usuários
- Painel admin para usuários e preços

---

## 2. Requisitos

- PHP 8.2+
- Composer
- Node.js + npm (para Vite/assets)
- Banco de dados: MySQL/MariaDB (recomendado em produção) ou SQLite (desenvolvimento)
- Conta **Stripe** (modo teste ou produção)
- Servidor web (Laragon, Nginx, Apache, etc.)

---

## 3. Instalação inicial

```bash
# Clonar / entrar na pasta do projeto
cd FootConnect

# Dependências PHP
composer install

# Dependências front-end
npm install

# Ambiente
cp .env.example .env
php artisan key:generate

# Banco (exemplo MySQL no .env)
php artisan migrate

# Link simbólico para uploads (fotos)
php artisan storage:link

# Compilar assets
npm run build
# ou em desenvolvimento:
npm run dev

# Servidor local
php artisan serve
```

Acesse: `http://localhost:8000`

---

## 4. Variáveis de ambiente

Adicione ao arquivo `.env`:

```env
APP_NAME=FootConnect
APP_URL=http://localhost:8000

# Banco (exemplo Laragon/MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=footconnect
DB_USERNAME=root
DB_PASSWORD=

# Stripe
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...

# Opcional: IDs de preços já criados no Stripe
# Se vazios, os preços são criados automaticamente no checkout
STRIPE_PRICE_G1_MONTHLY=
STRIPE_PRICE_G1_YEARLY=
STRIPE_PRICE_G2_MONTHLY=
STRIPE_PRICE_G2_YEARLY=
STRIPE_PRICE_G3_MONTHLY=
STRIPE_PRICE_G3_YEARLY=
STRIPE_PRICE_G4_MONTHLY=
STRIPE_PRICE_G4_YEARLY=

# Opcional: cor do checkout Stripe
STRIPE_PRIMARY_COLOR=#22c55e
```

| Variável | Obrigatória | Descrição |
|----------|-------------|-----------|
| `APP_URL` | Sim | URL base do site (links de indicação usam isso) |
| `STRIPE_KEY` | Sim | Chave pública Stripe |
| `STRIPE_SECRET` | Sim | Chave secreta Stripe |
| `STRIPE_WEBHOOK_SECRET` | Sim | Secret do endpoint de webhook |
| `STRIPE_PRICE_*` | Não | IDs de preço fixos no Stripe |

---

## 5. Planos de assinatura (G1–G4)

### Grupos e preços

| Grupo | Perfil | Mensal | Anual (30% off) |
|-------|--------|--------|-----------------|
| **G1** | Atleta (Jogador) | R$ 19,90 | R$ 167,16 |
| **G2** | Empresário, Agente, Investidor, Executivo | R$ 250,00 | R$ 2.100,00 |
| **G3** | Treinador, Olheiro, Professor, Scout | R$ 100,00 | R$ 840,00 |
| **G4** | Clube, Projeto, Peneiras, Camp | R$ 300,00 | R$ 2.520,00 |

### Chaves dos planos (banco / Stripe)

- `g1_monthly`, `g1_yearly`
- `g2_monthly`, `g2_yearly`
- `g3_monthly`, `g3_yearly`
- `g4_monthly`, `g4_yearly`

### Fluxo do usuário

1. `/onboarding/tipo-usuario` — escolhe G1, G2, G3 ou G4
2. `/onboarding/criar-conta` — e-mail e senha
3. `/onboarding/planos` — mensal ou anual
4. Checkout Stripe → `/onboarding/sucesso`

### Configuração

- Preços no banco: tabela `plan_prices`
- Edição pelo admin: `/admin/plan-prices`
- Definições de grupo: `config/plans.php`

### Papel no sistema

| Grupo | Interface |
|-------|-----------|
| G1 | Jogador (`role = player`) |
| G2, G3, G4 | Profissional (`role = scout`) |

Campo `plan_group` na tabela `users` guarda `g1`, `g2`, `g3` ou `g4`.

---

## 6. Perfis por grupo

### G1 — Atleta (Jogador)

**Rota:** `/me/player-profile`

Campos: modalidade (campo/futsal/fut7), sexo, nome, idade, data de nascimento, altura, posição, pé dominante, características, estudante (escola/série), cidade, estado, país, instituição (clube/projeto/escolinha), federado, premiações.

Também: fotos (`/me/player-photos`), vídeos (`/me/player-videos`), estatísticas (`/me/player-stats`).

### G2, G3, G4 — Profissionais

**Rota:** `/me/scout-profile`

Campos: nome, idade, cidade, estado, país, empresa (sim/não + nome), atuação (regional/nacional/internacional), federado (sim/não + federação).

Também: fotos (`/me/scout-photos`).

### Localização

Estados e países: `config/locations.php`

---

## 7. Indique e Ganhe (afiliados)

### Regras de negócio

| Item | Valor |
|------|-------|
| Comissão | 25% (recorrente) |
| Pagamento | PIX automático |
| Liberação | 2 dias após compensação do pagamento |
| Código | Ex.: `FOOT23` (personalizável) |
| Link | `{APP_URL}/indicacao/{CODIGO}` |

### Benefícios no app

- Link exclusivo
- Código personalizado (formato `FOOT` + 2–12 caracteres)
- Dashboard de ganhos
- Ranking oficial (`/indique-e-ganhe/ranking`)
- Histórico de saques PIX

### Política anti-fraude

- Contas falsas / sem assinatura ativa **não contabilizam**
- **Autoindicação proibida** (mesmo usuário, e-mail ou IP)
- **Spam:** mais de 3 cadastros do mesmo IP em 24h → bloqueio permanente do afiliado
- Indicações inválidas aparecem como "Não contabilizado"

Configuração: `config/referrals.php` → chave `antifraud`

### Como o indicado é vinculado

1. Acessa `/indicacao/FOOT23` ou onboarding com `?ref=FOOT23`
2. Código fica na sessão
3. No cadastro, `referred_by_id` é gravado no usuário
4. IP de registro é salvo para validação anti-fraude

### Comissões (fluxo técnico)

1. Stripe envia webhook `invoice.paid`
2. Sistema cria registro em `referral_commissions`
3. Após 2 dias, comissão fica `available`
4. Se o indicador tem PIX cadastrado, saque automático é criado em `referral_withdrawals`

### Configuração

Arquivo: `config/referrals.php`

```php
'commission_percent' => 25,
'payout_delay_days' => 2,
'code_prefix' => 'FOOT',
```

### Área no app

- Menu inferior: **Indique**
- URL: `/indique-e-ganhe`
- Também em: Configurações → Perfil → Indique e Ganhe

### Serviço principal

`app/Services/ReferralService.php`

### Comando manual de pagamentos

```bash
php artisan referrals:process-payouts
```

---

## 8. Busca, filtros e favoritos

### Filtros avançados

**Rota:** `/players` (menu **Buscar**)

Filtros: posição, modalidade, sexo, pé dominante, cidade, estado, país, instituição, idade (min/máx), altura (min/máx), federado, somente favoritos.

Controller: `PlayerProfileController@index`

### Favoritos

- **Favoritar:** botão na busca e no perfil do jogador (apenas profissionais)
- **Lista:** `/favoritos`
- **Atalho:** home do profissional → card Favoritos

Tabela: `favorites` (`scout_id`, `player_id`)

---

## 9. Stripe (pagamentos)

### 1. Criar conta e obter chaves

[Dashboard Stripe](https://dashboard.stripe.com) → Developers → API keys

### 2. Configurar webhook

URL do endpoint:

```
https://seu-dominio.com/stripe/webhook
```

**Eventos obrigatórios:**

- `checkout.session.completed`
- `invoice.paid` ← necessário para comissões de indicação
- `customer.subscription.updated`
- `customer.subscription.deleted`

Copie o **Signing secret** para `STRIPE_WEBHOOK_SECRET`.

### 3. Teste local (Stripe CLI)

```bash
stripe listen --forward-to localhost:8000/stripe/webhook
```

Use o `whsec_...` exibido no terminal como `STRIPE_WEBHOOK_SECRET`.

### 4. Checkout

- Modo: assinatura recorrente
- Locale: `pt-BR`
- Preços criados automaticamente se não existirem no Stripe
- Branding: [Stripe Dashboard → Settings → Branding](https://dashboard.stripe.com/settings/branding)

---

## 10. Tarefas agendadas (cron)

O programa de indicações processa comissões e PIX automático **a cada hora**.

Arquivo: `routes/console.php`

```php
Schedule::command('referrals:process-payouts')->hourly();
```

### Produção — adicionar ao crontab do servidor

```cron
* * * * * cd /caminho/para/FootConnect && php artisan schedule:run >> /dev/null 2>&1
```

### Desenvolvimento local

```bash
php artisan schedule:work
```

---

## 11. Painel administrativo

**URL:** `/admin`  
**Acesso:** usuário com `is_admin = true`

Painel com **sidebar** e as seguintes seções:

| Seção | Rota | Funcionalidades |
|-------|------|-----------------|
| Dashboard | `/admin` | Métricas, gráfico de cadastros, planos G1–G4, comissões |
| Usuários | `/admin/users` | Lista com filtros avançados, link para detalhe |
| Detalhe do usuário | `/admin/users/{id}` | Assinatura, PIX, indicações, ações admin |
| Assinaturas | `/admin/subscriptions` | MRR estimado, renovações próximas, distribuição |
| Planos e preços | `/admin/plan-prices` | Editar preços G1–G4, ativar/desativar planos |
| Afiliados | `/admin/referrals` | Ranking, bloqueados, indicações inválidas, comissões |
| Saques PIX | `/admin/referrals/withdrawals` | Histórico de pagamentos a afiliados |

**Ações por usuário:** tornar admin, cancelar plano, inativar/reativar, bloquear/desbloquear afiliado, invalidar indicação.

**Processar pagamentos:** botão em Afiliados ou `php artisan referrals:process-payouts`

Para tornar um usuário admin (via tinker ou SQL):

```bash
php artisan tinker
>>> \App\Models\User::where('email', 'admin@email.com')->update(['is_admin' => true]);
```

---

## 12. Rotas principais

### Públicas

| Rota | Descrição |
|------|-----------|
| `/` | Landing page |
| `/login` | Login |
| `/onboarding/tipo-usuario` | Escolha do plano G1–G4 |
| `/onboarding/criar-conta` | Cadastro |
| `/indicacao/{codigo}` | Captura código de indicação |

### Autenticadas (com assinatura ativa)

| Rota | Descrição |
|------|-----------|
| `/home` | Home (jogador ou profissional) |
| `/players` | Busca de jogadores |
| `/players/{id}` | Perfil público do jogador |
| `/favoritos` | Lista de favoritos |
| `/messages` | Mensagens |
| `/indique-e-ganhe` | Programa de afiliados |
| `/indique-e-ganhe/ranking` | Ranking oficial |
| `/me/player-profile` | Editar perfil G1 |
| `/me/scout-profile` | Editar perfil G2–G4 |
| `/settings/profile` | Configurações da conta |
| `/settings/plan` | Gerenciar assinatura |

---

## 13. Arquivos de configuração

| Arquivo | Conteúdo |
|---------|----------|
| `config/plans.php` | Grupos G1–G4, labels, planos válidos |
| `config/referrals.php` | Comissão, anti-fraude, PIX, benefícios |
| `config/locations.php` | Estados BR e países |
| `config/stripe.php` | Chaves e IDs de preço Stripe |

---

## 14. Migrations criadas

| Migration | O que faz |
|-----------|-----------|
| `2026_06_11_000000_update_plans_to_g1_g4` | Insere planos G1–G4, desativa planos antigos |
| `2026_06_11_000001_add_plan_group_to_users_table` | Campo `plan_group` em users |
| `2026_06_11_100000_update_profile_fields_for_plan_groups` | Campos de perfil G1 e G2–G4 |
| `2026_06_11_200000_create_referral_system_tables` | Indicações, comissões, saques, PIX |
| `2026_06_11_300000_add_referral_antifraud_fields` | Anti-fraude e contabilização |

Rodar todas:

```bash
php artisan migrate
```

---

## 15. Comandos úteis

```bash
# Migrations
php artisan migrate
php artisan migrate:status

# Processar comissões e PIX de indicações
php artisan referrals:process-payouts

# Scheduler (dev)
php artisan schedule:work

# Limpar cache de config (após alterar config/*.php)
php artisan config:clear
php artisan cache:clear

# Seed de dados de teste
php artisan db:seed

# Rotas
php artisan route:list
```

---

## 16. Dados de teste

Após `php artisan db:seed`:

| E-mail | Senha | Tipo |
|--------|-------|------|
| `jogador1@footconnect.test` | `password` | G1 — Jogador |
| `jogador2@footconnect.test` | `password` | G1 — Jogador |
| `empresario@footconnect.test` | `password` | G2 — Profissional |
| `olheiro@footconnect.test` | `password` | G3 — Profissional |

Usuários de teste já vêm com assinatura ativa simulada (sem Stripe real).

---

## 17. Observações importantes

### PIX automático

O fluxo de saque via PIX está **simulado** no sistema: comissões liberadas geram um registro de saque marcado como concluído. Para transferências PIX reais, integre um gateway (Asaas, Mercado Pago, Efi, etc.) no `ReferralService::processAutomaticPayouts()`.

### Middleware de assinatura

Rotas do app exigem `subscription.active`. Usuários sem assinatura ativa são redirecionados para `/subscription/required`.

### Upload de fotos

Fotos ficam em `storage/app/public`. É necessário:

```bash
php artisan storage:link
```

### Indicação na URL

Formatos aceitos:

- `https://seusite.com/indicacao/FOOT23`
- `https://seusite.com/onboarding/tipo-usuario?ref=FOOT23`

### Personalizar comissão ou prazo PIX

Edite `config/referrals.php` e rode `php artisan config:clear`.

### Personalizar preços dos planos

1. Admin: `/admin/plan-prices`, ou
2. Banco: tabela `plan_prices`, ou
3. Nova migration com `updateOrInsert`

Preços no Stripe são recriados automaticamente no checkout se o valor no banco mudar (novo price ID).

---

## Estrutura de pastas relevante

```
app/
├── Http/Controllers/
│   ├── OnboardingController.php    # Cadastro e checkout
│   ├── ReferralController.php      # Indique e Ganhe
│   ├── FavoriteController.php      # Favoritos
│   ├── PlayerProfileController.php # Perfil e busca G1
│   └── ScoutProfileController.php# Perfil G2–G4
├── Models/
│   ├── ReferralCommission.php
│   ├── ReferralWithdrawal.php
│   └── PlanPrice.php
├── Services/
│   └── ReferralService.php         # Lógica de indicações
└── Console/Commands/
    └── ProcessReferralPayouts.php

config/
├── plans.php
├── referrals.php
├── locations.php
└── stripe.php

resources/views/
├── onboarding/          # Fluxo de cadastro
├── referrals/           # Indique e Ganhe + ranking
├── players/             # Busca e perfil jogador
├── scouts/              # Perfil profissional
└── favorites/           # Lista de favoritos

docs/
└── GUIA-FOOTCONNECT.md  # Este documento
```

---

*Última atualização: junho de 2026 — FootConnect*
