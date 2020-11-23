<?php

namespace Modules\Accounting\Http\Middleware;

use Closure;

class YearClosedMiddleware
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
            if(year()->status == \Modules\Accounting\Models\Year::STATUS_OPENED){
                return back()->with('error', __('accounting::years.is_opened'));
            }
            else if(year()->status == \Modules\Accounting\Models\Year::STATUS_CLOSED){
                return $next($request);
            }else{
                return back()->with('error', __('accounting::years.is_archived'));
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
