<?php

namespace Spatie\Sitemap;

use Illuminate\Support\ServiceProvider;
use Spatie\Crawler\Crawler;

class SitemapServiceProvider extends ServiceProvider
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

        $this->app->when(SitemapGenerator::class)
            ->needs(Crawler::class)
            ->give(function () {
                return Crawler::create();
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
