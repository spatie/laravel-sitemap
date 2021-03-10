<?php

namespace Spatie\Sitemap;

use Illuminate\Support\ServiceProvider;
use Spatie\Crawler\Crawler;

class SitemapServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-sitemap');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/laravel-sitemap'),
            ], 'views');

            $this->publishes([
                __DIR__ . '/../config/sitemap.php' => config_path('sitemap.php'),
            ], 'config');
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sitemap.php', 'sitemap');

        $this->app->when(SitemapGenerator::class)
            ->needs(Crawler::class)
            ->give(static function (): Crawler {
                return Crawler::create(config('sitemap.guzzle_options'));
            });
    }
}
