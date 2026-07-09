<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->isAdmin()) {
            return $next($request);
        }

        $isActive = $user->subscription_status === 'active'
            && (! $user->current_period_end || $user->current_period_end->isFuture());

        if (! $isActive) {
            $planGroup = $user->plan_group;

            if ($planGroup && config('plans.groups.'.$planGroup)) {
                return redirect()->route('onboarding.plans');
            }

            return redirect()->route('onboarding.user-type');
        }

        return $next($request);
    }
}
