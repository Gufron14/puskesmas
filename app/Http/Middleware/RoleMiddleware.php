<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle($request, \Closure $next, ...$roles)
{
    if (auth()->check() && in_array(auth()->user()->role, $roles)) {
        return $next($request);
    }

    abort(403, 'Unauthorized');
}


}
