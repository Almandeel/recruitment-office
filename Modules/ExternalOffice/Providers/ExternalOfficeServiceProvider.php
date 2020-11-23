<?php

namespace Modules\ExternalOffice\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class ExternalOfficeServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path('ExternalOffice', 'Database/Migrations'));
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
            module_path('ExternalOffice', 'Config/config.php') => config_path('externaloffice.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('ExternalOffice', 'Config/config.php'), 'externaloffice'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/externaloffice');

        $sourcePath = module_path('ExternalOffice', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/externaloffice';
        }, \Config::get('view.paths')), [$sourcePath]), 'externaloffice');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/externaloffice');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'externaloffice');
        } else {
            $this->loadTranslationsFrom(module_path('ExternalOffice', 'Resources/lang'), 'externaloffice');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        // if (! app()->environment('production') && $this->app->runningInConsole()) {
        if (! app()->environment('production')) {
            app(Factory::class)->load(module_path('ExternalOffice', 'Database/factories'));
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
