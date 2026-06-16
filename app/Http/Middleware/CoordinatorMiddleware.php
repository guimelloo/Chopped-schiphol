<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CoordinatorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('coordinator')->check()) {
            return redirect()->route('coordinator.login');
        }

        return $next($request);
    }
}
