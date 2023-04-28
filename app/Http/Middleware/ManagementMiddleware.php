<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * <Class Name> ManagementMiddleware
 * Create : 2021.09.02 TP Jermaine SPRINT_05 TASK114
 **/
class ManagementMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->USER_TYPE === 1) {
            return $next($request);
        } else {
            return abort(403, 'Unauthorized action.');
        }
    }
}
