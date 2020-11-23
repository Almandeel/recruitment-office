<?php

namespace App\Http\Middleware;

use Closure;

class ActiveUserMiddleware
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
        if (auth()->guard('office')->check() && $request->url() != route('offices.users.not-active') && $request->url() != route('logout')) {
            if (auth()->guard('office')->user()->office->status == 0) {
                return redirect()->route('offices.users.not-active');
            }
        }

        return $next($request);
    }
}
