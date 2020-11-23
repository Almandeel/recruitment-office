<?php

namespace App\Http\Middleware;

use Closure;

class ExternalOfficeMiddleware
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
        if (auth()->guard('office')->check() && request()->segment(1) != 'office' && request()->segment(1) != 'logout') {
            return redirect()->to('/office');
        } else if (request()->segment(1) == 'office') {
            redirect()->back();
        }

        app()->setlocale('en');

        return $next($request);
    }
}
