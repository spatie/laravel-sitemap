<?php

namespace Spatie\Sitemap;

use Illuminate\Support\ServiceProvider;

class SkeletonServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-sitemap');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/laravel-sitemap'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../config/laravel-sitemap.php' => config_path('laravel-sitemap.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-sitemap.php', 'laravel-sitemap');
    }
}
