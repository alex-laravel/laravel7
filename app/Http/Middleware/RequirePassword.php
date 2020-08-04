<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\RequirePassword as RequirePasswordMiddleware;
use Illuminate\Http\Request;

class RequirePassword extends RequirePasswordMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param string|null $redirectToRoute
     * @return mixed
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if ($this->shouldConfirmPassword($request)) {
            return $this->responseFactory->redirectGuest(
                $this->urlGenerator->route($redirectToRoute ?? 'auth.password.confirm')
            );
        }

        return $next($request);
    }
}
