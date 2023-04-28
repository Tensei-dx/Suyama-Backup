<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AbortIfUnauthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($request->is('scheduler')) {
            info($request->header());
            return $next($request);
        }

        if (!Auth::guard($guard)->check()) {
            if ($request->is('/')) {
                return redirect('/login');
            } else if (!$request->is('login')) {
                abort(401, 'Unauthorized action.');
            }
        }

        return $next($request);
    }
}
