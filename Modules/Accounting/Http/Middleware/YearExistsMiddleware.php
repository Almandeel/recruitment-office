<?php

namespace Modules\Accounting\Http\Middleware;

use Closure;

class YearExistsMiddleware
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
        if(year()){
            return $next($request);
        }else{
            session()->flash('error', __('accounting::years.notfound'));
            if(auth()->user()->isAbleTo('years-create')){
                return redirect()->route('years.index');
            }
            return back();
        }
    }
}
