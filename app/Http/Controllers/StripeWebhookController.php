<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Stripe\Event;
use Stripe\Stripe;

class StripeWebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $payload = $request->getContent();
        $sigHeader = $request->server('HTTP_STRIPE_SIGNATURE');
        $secret = env('STRIPE_WEBHOOK_SECRET');

        if (! $secret) {
            return response('Webhook desconfigurado', 500);
        }

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $secret
            );
        } catch (\Throwable $e) {
            Log::warning('Stripe webhook error', ['message' => $e->getMessage()]);

            return response('Invalid payload', 400);
        }

        Stripe::setApiKey(config('stripe.secret'));

        if ($event->type === 'checkout.session.completed') {
            /** @var Event $event */
            $session = $event->data->object;

            $customerId = $session->customer;
            $subscriptionId = $session->subscription;
            $planKey = $session->metadata->plan_key ?? null;
            $userType = $session->metadata->user_type ?? null;

            if (! $customerId || ! $subscriptionId || ! $planKey || ! $userType) {
                return response('Missing data', 200);
            }

            // Neste estágio ainda não temos o usuário criado.
            // Vamos criar / atualizar depois, no registro, associando pelo customer_id se necessário.
        }

        if (str_starts_with($event->type, 'customer.subscription.')) {
            $subscription = $event->data->object;
            $subscriptionId = $subscription->id;

            $user = User::where('stripe_subscription_id', $subscriptionId)->first();

            if ($user) {
                $user->subscription_status = $subscription->status;
                if ($subscription->current_period_end) {
                    $user->current_period_end = \Carbon\Carbon::createFromTimestamp(
                        $subscription->current_period_end
                    );
                }
                $user->save();
            }
        }

        return response('OK', 200);
    }
}

