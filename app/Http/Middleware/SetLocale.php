<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = config('locales.supported', []);
        $requested = $request->query('lang');

        if (is_string($requested) && isset($supported[$requested])) {
            $request->session()->put('locale', $requested);
            $locale = $requested;
        } else {
            $locale = $request->session()->get('locale');
        }

        if (! is_string($locale) || ! isset($supported[$locale])) {
            $locale = config('app.locale', 'pt');
        }

        if (! isset($supported[$locale])) {
            $locale = 'pt';
        }

        App::setLocale($locale);
        Carbon::setLocale($supported[$locale]['carbon'] ?? $locale);

        return $next($request);
    }
}
