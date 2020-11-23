<?php

namespace Modules\Accounting\Http\Middleware;

use Closure;

class YearActivatedMiddleware
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
            if(year()->isActive()){
                return $next($request);
            }else{
                return back()->with('error', __('accounting::years.inactive'));
            }
        }else{
            session()->flash('error', __('accounting::years.notfound'));
            if(auth()->user()->isAbleTo('years-create')){
                return redirect()->route('years.index');
            }
            return back();
        }
    }
}
