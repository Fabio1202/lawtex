<?php

namespace App\Http\Middleware;

use App\Http\NonceManager;
use Closure;
use Illuminate\Http\Request;

class AddCSPHeadersMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // if production
        if (app()->environment('production')) {
            $response->headers->set('Content-Security-Policy',
                "default-src 'self'; ".
                "script-src 'self' 'unsafe-eval' 'nonce-".NonceManager::generateNonce()."'; ".
                "style-src 'self' 'unsafe-inline' fonts.bunny.net; ".
                "img-src 'self' data:; ".
                "font-src 'self' fonts.bunny.net; ");
        }

        return $response;
    }
}
