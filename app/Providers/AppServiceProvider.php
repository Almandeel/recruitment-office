<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!auth()->guard('office')->check()) {
            date_default_timezone_set(env('APP_TIMEZONE', 'Asia/Riyadh'));
            // dd(\Modules\Services\Models\Contract::initial(\Carbon\Carbon::parse('-1 day')->format('Y-m-d')));
        }
        
        Schema::defaultStringLength(191);
    }
}
