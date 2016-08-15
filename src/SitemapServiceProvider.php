<?php
namespace Spatie\Sitemap;

use Illuminate\Support\ServiceProvider;

class SitemapServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-sitemap');

        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views/vendor/laravel-sitemap'),
        ], 'views');

        $this->publishes([
            __DIR__ . '/../config/laravel-sitemap.php' => config_path('laravel-sitemap.php'),
        ], 'config');

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
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-sitemap.php', 'laravel-sitemap');
    }
}
