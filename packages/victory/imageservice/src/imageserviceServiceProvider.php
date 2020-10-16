<?php

namespace victory\imageservice;

use Illuminate\Support\ServiceProvider;

class imageserviceServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'victory');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'victory');
         $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
         $this->app['router']->namespace('victory\\imageservice\\Controllers')
         ->middleware(['web'])
         ->group(function () {
             $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
         });

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/imageservice.php', 'imageservice');

        // Register the service the package provides.
        $this->app->singleton('imageservice', function ($app) {
            return new imageservice;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['imageservice'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/imageservice.php' => config_path('imageservice.php'),
        ], 'imageservice.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/victory'),
        ], 'imageservice.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/victory'),
        ], 'imageservice.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/victory'),
        ], 'imageservice.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
