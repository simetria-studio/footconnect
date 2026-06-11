<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ReferralService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Stripe\Event;
use Stripe\Stripe;

class StripeWebhookController extends Controller
{
    public function __invoke(Request $request, ReferralService $referralService): Response
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
            $session = $event->data->object;

            $customerId = $session->customer;
            $subscriptionId = $session->subscription;

            if ($customerId && $subscriptionId) {
                $user = User::where('stripe_customer_id', $customerId)->first();

                if (! $user) {
                    $user = User::where('email', $session->customer_email ?? '')->first();
                    if ($user) {
                        $user->stripe_customer_id = $customerId;
                        $user->stripe_subscription_id = $subscriptionId;
                        $user->save();
                    }
                }
            }
        }

        if ($event->type === 'invoice.paid') {
            $invoice = $event->data->object;
            $customerId = $invoice->customer;
            $amountPaid = (int) ($invoice->amount_paid ?? 0);
            $paidAt = isset($invoice->status_transitions->paid_at)
                ? Carbon::createFromTimestamp($invoice->status_transitions->paid_at)
                : now();

            if ($customerId && $amountPaid > 0 && $invoice->id) {
                $user = User::where('stripe_customer_id', $customerId)->first();

                if ($user) {
                    $referralService->recordCommissionFromInvoice(
                        $user,
                        $invoice->id,
                        $amountPaid,
                        $paidAt
                    );
                }
            }
        }

        if (str_starts_with($event->type, 'customer.subscription.')) {
            $subscription = $event->data->object;
            $subscriptionId = $subscription->id;

            $user = User::where('stripe_subscription_id', $subscriptionId)->first();

            if ($user) {
                $user->subscription_status = $subscription->status;
                if ($subscription->current_period_end) {
                    $user->current_period_end = Carbon::createFromTimestamp(
                        $subscription->current_period_end
                    );
                }
                $user->save();
            }
        }

        return response('OK', 200);
    }
}
