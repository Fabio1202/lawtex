<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class IsAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->roles->contains('name', 'admin')) {
            return $next($request);
        }
        abort(401, 'Unauthorized');
    }
}
