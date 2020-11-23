<?php

namespace App\Http\Middleware;

use Closure;

class InternalOfficeMiddleware
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
        if (request()->segment(1) != 'office') {
            app()->setlocale('ar');
        }

        return $next($request);
    }
}
