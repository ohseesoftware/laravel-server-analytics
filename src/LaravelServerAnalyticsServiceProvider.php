<?php

namespace OhSeeSoftware\LaravelServerAnalytics;

use Illuminate\Support\ServiceProvider;
use OhSeeSoftware\LaravelServerAnalytics\Http\Middleware\LogRequest;

class LaravelServerAnalyticsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-server-analytics');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-server-analytics');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravel-server-analytics.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-server-analytics'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-server-analytics'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-server-analytics'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-server-analytics');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-server-analytics', function () {
            return new LaravelServerAnalytics;
        });

        $this->app->singleton(LogRequest::class);
    }
}
