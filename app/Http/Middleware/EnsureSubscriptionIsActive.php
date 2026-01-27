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

        $isActive = $user->subscription_status === 'active'
            && (! $user->current_period_end || $user->current_period_end->isFuture());

        if (! $isActive) {
            return redirect()->route('subscription.required');
        }

        return $next($request);
    }
}

