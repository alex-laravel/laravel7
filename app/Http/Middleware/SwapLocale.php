<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class SwapLocale
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Closure
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('locale')) {
            return $next($request);
        }

        $locale = session()->get('locale');
        $allowedLocales = config('locale.languages');

        if (!array_key_exists($locale, $allowedLocales)) {
            return $next($request);
        }

        app()->setLocale($locale);
        setlocale(LC_TIME, $allowedLocales[$locale][1]);
        Carbon::setLocale($allowedLocales[$locale][0]);

        return $next($request);
    }
}
