<?php

namespace App\Http\Middleware;

use Closure;

class AppEngineCronRequest
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
        if ($request->header('X-Appengine-Cron')) {
            return $next($request);
        } else {
            abort(403, 'Forbidden request.');
        }
    }
}
