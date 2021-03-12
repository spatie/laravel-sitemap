<?php

namespace Spatie\Sitemap;

use Spatie\Crawler\Crawler;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SitemapServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-sitemap')
            ->hasConfigFile()
            ->hasViews();
    }

    public function packageRegistered(): void
    {
        $this->app->when(SitemapGenerator::class)
            ->needs(Crawler::class)
            ->give(static fn (): Crawler => Crawler::create(config('sitemap.guzzle_options')));
    }
}
