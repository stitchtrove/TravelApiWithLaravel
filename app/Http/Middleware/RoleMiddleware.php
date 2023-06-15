<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::user()) {
            abort(401);
        }

        if (!Auth::user()->hasRole('admin')) {
            abort(403);
        }

        return $next($request);
    }
}
