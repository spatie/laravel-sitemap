<?php

namespace Spatie\Sitemap;

use Spatie\Crawler\Crawler;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\ServiceProvider;

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

        $this->publishes([
            __DIR__.'/../resources/config/laravel-sitemap.php' => config_path('laravel-sitemap.php'),
        ], 'config');

        $this->app->when(SitemapGenerator::class)
            ->needs(Crawler::class)
            ->give(function () {
                return Crawler::create([
                    RequestOptions::COOKIES => config('laravel-sitemap.cookies'),
                    RequestOptions::CONNECT_TIMEOUT => config('laravel-sitemap.connect_timeout'),
                    RequestOptions::TIMEOUT => config('laravel-sitemap.timeout'),
                    RequestOptions::ALLOW_REDIRECTS => config('laravel-sitemap.allow_redirects'),
                ]);
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../resources/config/laravel-sitemap.php', 'laravel-sitemap');
    }
}
