<?php

namespace Modules\Accounting\Providers;

use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class AccountingServiceProvider extends ServiceProvider
{
    
    
    /**
    * The filters base class name.
    *
    * @var array
    */
    protected $middleware = [
    'Accounting' => [
    'year.exists'    => 'YearExistsMiddleware',
    'year.opened'    => 'YearOpenedMiddleware',
    'year.closed'    => 'YearClosedMiddleware',
    'year.activated'    => 'YearActivatedMiddleware',
    ],
    ];
    
    /**
    * Boot the application events.
    *
    * @return void
    */
    public function boot()
    {
        ini_set('memory_limit', -1);
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path('Accounting', 'Database/Migrations'));
        $this->registerMiddleware($this->app['router']);
        //        \Accounting\Models\Entry::created(function ($entry) {
        //        });
    }
    
    /**
    * Register the service provider.
    *
    * @return void
    */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
    
    /**
    * Register config.
    *
    * @return void
    */
    protected function registerConfig()
    {
        $this->publishes([
        module_path('Accounting', 'Config/config.php') => config_path('accounting.php'),
        ], 'config');
        $this->mergeConfigFrom(
        module_path('Accounting', 'Config/config.php'), 'accounting'
        );
    }
    
    /**
    * Register views.
    *
    * @return void
    */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/accounting');
        
        $sourcePath = module_path('Accounting', 'Resources/views');
        
        $this->publishes([
        $sourcePath => $viewPath
        ],'views');
        
        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/accounting';
        }, \Config::get('view.paths')), [$sourcePath]), 'accounting');
    }
    
    /**
    * Register translations.
    *
    * @return void
    */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/accounting');
        
        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'accounting');
        } else {
            $this->loadTranslationsFrom(module_path('Accounting', 'Resources/lang'), 'accounting');
        }
    }
    
    /**
    * Register an additional directory of factories.
    *
    * @return void
    */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(module_path('Accounting', 'Database/factories'));
        }
    }
    
    
    /**
    * Register the filters.
    *
    * @param  Router $router
    * @return void
    */
    public function registerMiddleware(Router $router)
    {
        foreach ($this->middleware as $module => $middlewares) {
            foreach ($middlewares as $name => $middleware) {
                $class = "Modules\\{$module}\\Http\\Middleware\\{$middleware}";
                
                // $router->middleware($name, $class);
                $router->aliasMiddleware($name, $class);
            }
        }
    }
    
    /**
    * Get the services provided by the provider.
    *
    * @return array
    */
    public function provides()
    {
        return [];
    }
}