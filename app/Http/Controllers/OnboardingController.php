<?php

namespace App\Http\Controllers;

use App\Models\PlanPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Stripe\Checkout\Session as StripeCheckoutSession;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;

class OnboardingController extends Controller
{
    public function chooseUserType()
    {
        return view('onboarding.user-type');
    }

    public function storeUserType(Request $request)
    {
        $data = $request->validate([
            'role' => ['required', 'in:player,scout'],
        ]);

        Session::put('onboarding.role', $data['role']);

        // Agora redireciona para criar conta primeiro
        return redirect()->route('onboarding.register');
    }

    public function showRegister()
    {
        $role = Session::get('onboarding.role');

        if (! $role) {
            return redirect()->route('onboarding.user-type');
        }

        return view('onboarding.register', [
            'role' => $role,
        ]);
    }

    public function register(Request $request)
    {
        $role = Session::get('onboarding.role');

        if (! $role) {
            return redirect()->route('onboarding.user-type');
        }

        $data = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // Extrai o nome do email
        $name = explode('@', $data['email'])[0];
        $name = ucfirst($name);

        $user = \App\Models\User::create([
            'name' => $name,
            'email' => $data['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
            'role' => $role,
        ]);

        // Autentica o usuário
        \Illuminate\Support\Facades\Auth::login($user);

        // Redireciona para escolher plano
        return redirect()->route('onboarding.plans');
    }

    public function plans(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('onboarding.user-type');
        }

        $planPrices = PlanPrice::getPlansForOnboarding();
        $playerPlan = $planPrices->firstWhere('plan_key', 'player_quarterly');
        $scoutMonthly = $planPrices->firstWhere('plan_key', 'scout_monthly');
        $scoutYearly = $planPrices->firstWhere('plan_key', 'scout_yearly');

        return view('onboarding.plans', [
            'role' => $user->role,
            'playerPlan' => $playerPlan,
            'scoutMonthly' => $scoutMonthly,
            'scoutYearly' => $scoutYearly,
        ]);
    }

    public function checkout(Request $request)
    {
        // Agora usa o usuário autenticado
        $user = $request->user();

        if (! $user) {
            return redirect()->route('onboarding.user-type');
        }

        $role = $user->role;

        $data = $request->validate([
            'plan' => ['required', 'in:player_quarterly,scout_monthly,scout_yearly'],
        ]);

        $stripeKey = config('stripe.secret');

        if (! $stripeKey) {
            Log::error('Stripe secret key missing');
            abort(500, 'Configuração de pagamento indisponível. Verifique a variável STRIPE_SECRET no arquivo .env');
        }

        Stripe::setApiKey($stripeKey);

        // Busca ou cria o preço dinamicamente
        $priceId = $this->getOrCreatePrice($data['plan']);

        // Obtém informações do plano para melhorar a experiência
        $planInfo = $this->getPlanInfo($data['plan']);

        $session = StripeCheckoutSession::create([
            'mode' => 'subscription',
            'line_items' => [
                [
                    'price' => $priceId,
                    'quantity' => 1,
                ],
            ],
            'metadata' => [
                'user_type' => $role,
                'plan_key' => $data['plan'],
            ],
            'locale' => 'pt-BR', // Português brasileiro
            'billing_address_collection' => 'required', // Solicita endereço de cobrança
            'phone_number_collection' => [
                'enabled' => true, // Solicita telefone
            ],
            'payment_method_types' => ['card'], // Apenas cartão de crédito
            'allow_promotion_codes' => true, // Permite códigos promocionais
            // Nota: consent_collection requer URL dos termos configurada no painel do Stripe
            // Configure em: https://dashboard.stripe.com/settings/public
            // Depois, descomente as linhas abaixo:
            // 'consent_collection' => [
            //     'terms_of_service' => 'required',
            // ],
            'custom_text' => [
                'submit' => [
                    'message' => '🔒 Pagamento seguro processado pelo Stripe. Ao assinar, você concorda com nossos Termos de Serviço e Política de Privacidade. Cancele a qualquer momento.',
                ],
                // Nota: shipping_address requer shipping_address_collection habilitado
                // Como é uma assinatura digital, não precisamos de endereço de entrega
            ],
            // Nota: customer_creation não é necessário no modo 'subscription' - o customer é criado automaticamente
            // Nota: O design visual (cores, logo) deve ser configurado no painel do Stripe
            // Acesse: https://dashboard.stripe.com/settings/branding
            'subscription_data' => [
                'description' => $planInfo['description'],
                'metadata' => [
                    'user_id' => $user->id,
                    'user_type' => $role,
                    'plan_key' => $data['plan'],
                    'plan_name' => $planInfo['name'],
                ],
            ],
            'customer_email' => $user->email, // Preenche email do usuário autenticado
            'success_url' => route('onboarding.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('onboarding.plans').'?canceled=1',
        ]);

        Log::info('Stripe checkout session created', ['session' => $session]);
        return redirect($session->url);
    }

    /**
     * Busca ou cria um preço no Stripe dinamicamente
     */
    private function getOrCreatePrice(string $planKey): string
    {
        // Primeiro, tenta buscar do .env (caso já exista)
        $priceId = config('stripe.prices.'.$planKey);
        if ($priceId) {
            // Verifica se o preço ainda existe no Stripe
            try {
                Price::retrieve($priceId);
                return $priceId;
            } catch (\Exception $e) {
                Log::warning('Price ID from config not found in Stripe, creating new one', ['priceId' => $priceId]);
            }
        }

        $planModel = PlanPrice::getByKey($planKey);
        if (! $planModel) {
            abort(500, 'Plano inválido ou inativo.');
        }

        $plan = [
            'name' => $planModel->name,
            'amount' => $planModel->amount_cents,
            'currency' => $planModel->currency,
            'interval' => $planModel->interval,
            'interval_count' => $planModel->interval_count,
        ];

        // Busca produto existente ou cria um novo
        $productName = 'FootConnect - '.($planKey === 'player_quarterly' ? 'Jogador' : 'Olheiro');
        $products = Product::all(['limit' => 100, 'active' => true]);
        $product = null;

        foreach ($products->data as $p) {
            if ($p->name === $productName) {
                $product = $p;
                break;
            }
        }

        if (! $product) {
            $productDescription = $planKey === 'player_quarterly'
                ? 'Acesso completo à plataforma FootConnect para jogadores. Crie seu perfil profissional, adicione vídeos e estatísticas, e conecte-se com olheiros e profissionais do futebol.'
                : 'Acesso completo à plataforma FootConnect para profissionais. Busque jogadores com filtros avançados, visualize perfis detalhados, e comunique-se diretamente com os talentos.';

            // URL da imagem do produto (pode ser uma imagem hospedada publicamente)
            // Por enquanto, vamos usar uma imagem placeholder ou você pode adicionar uma URL real
            $productImageUrl = $this->getProductImageUrl($planKey);

            $productData = [
                'name' => $productName,
                'description' => $productDescription,
                'metadata' => [
                    'platform' => 'footconnect',
                    'user_type' => $planKey === 'player_quarterly' ? 'player' : 'scout',
                ],
            ];

            // Adiciona imagem se disponível
            if ($productImageUrl) {
                $productData['images'] = [$productImageUrl];
            }

            $product = Product::create($productData);
        } elseif (! empty($product->images)) {
            // Se o produto existe mas não tem imagem, atualiza
            $productImageUrl = $this->getProductImageUrl($planKey);
            if ($productImageUrl && empty($product->images)) {
                Product::update($product->id, [
                    'images' => [$productImageUrl],
                ]);
            }
        }

        // Busca preço existente com as mesmas características
        $prices = Price::all([
            'product' => $product->id,
            'active' => true,
            'limit' => 100,
        ]);

        foreach ($prices->data as $price) {
            if (
                $price->unit_amount === $plan['amount'] &&
                $price->currency === $plan['currency'] &&
                $price->recurring &&
                $price->recurring->interval === $plan['interval'] &&
                ($plan['interval'] === 'year' || $price->recurring->interval_count === $plan['interval_count'])
            ) {
                Log::info('Using existing Stripe price', ['priceId' => $price->id, 'plan' => $planKey]);
                return $price->id;
            }
        }

        // Cria novo preço
        $priceData = [
            'product' => $product->id,
            'unit_amount' => $plan['amount'],
            'currency' => $plan['currency'],
            'recurring' => [
                'interval' => $plan['interval'],
            ],
        ];

        if ($plan['interval'] === 'month' && isset($plan['interval_count'])) {
            $priceData['recurring']['interval_count'] = $plan['interval_count'];
        }

        $price = Price::create($priceData);

        Log::info('Created new Stripe price', ['priceId' => $price->id, 'plan' => $planKey]);

        return $price->id;
    }

    /**
     * Obtém a URL da imagem do produto
     */
    private function getProductImageUrl(string $planKey): ?string
    {
        // Você pode adicionar URLs de imagens reais aqui
        // As imagens devem estar hospedadas publicamente (ex: S3, CDN, ou pasta public)
        $baseUrl = rtrim(config('app.url'), '/');

        // Tenta encontrar uma imagem na pasta public
        $imagePath = $planKey === 'player_quarterly'
            ? '/images/products/player-plan.png'
            : '/images/products/scout-plan.png';

        // Retorna null se não houver imagem (você pode adicionar imagens depois)
        // Para usar, adicione as imagens em public/images/products/
        return null; // Altere para $baseUrl . $imagePath quando tiver as imagens
    }

    /**
     * Obtém o produto do Stripe para um plano
     */
    private function getProductForPlan(string $planKey)
    {
        $productName = 'FootConnect - '.($planKey === 'player_quarterly' ? 'Jogador' : 'Olheiro');
        $products = Product::all(['limit' => 100, 'active' => true]);

        foreach ($products->data as $p) {
            if ($p->name === $productName) {
                return $p;
            }
        }

        return null;
    }

    /**
     * Obtém informações do plano para melhorar a experiência
     */
    private function getPlanInfo(string $planKey): array
    {
        $planModel = PlanPrice::getByKey($planKey);

        if ($planModel) {
            return [
                'name' => $planModel->name,
                'description' => $planModel->description ?? 'Assinatura do FootConnect',
            ];
        }

        return [
            'name' => 'FootConnect',
            'description' => 'Assinatura do FootConnect',
        ];
    }

    public function success(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('onboarding.user-type');
        }

        $sessionId = $request->query('session_id');

        if (! $sessionId) {
            return redirect()->route('onboarding.plans');
        }

        // Tenta vincular a assinatura imediatamente
        if (config('stripe.secret')) {
            try {
                Stripe::setApiKey(config('stripe.secret'));
                $session = StripeCheckoutSession::retrieve($sessionId);

                if ($session->customer && $session->subscription) {
                    $user->stripe_customer_id = $session->customer;
                    $user->stripe_subscription_id = $session->subscription;
                    $user->plan_type = $user->role === 'player' ? 'player' : 'scout';

                    $planKey = $session->metadata->plan_key ?? null;
                    $user->plan_interval = match ($planKey) {
                        'player_quarterly' => 'quarterly',
                        'scout_monthly' => 'monthly',
                        'scout_yearly' => 'yearly',
                        default => null,
                    };

                    $user->subscription_status = 'active';
                    $user->save();
                }
            } catch (\Throwable $e) {
                Log::error('Error linking subscription', ['error' => $e->getMessage()]);
            }
        }

        // Limpa a sessão de onboarding
        Session::forget('onboarding.role');

        // Redireciona para home (usuário já está autenticado)
        return redirect()->route('home')->with('status', 'Pagamento aprovado! Bem-vindo ao FootConnect.');
    }
}

