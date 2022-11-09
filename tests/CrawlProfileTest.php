<?php

use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlProfiles\CrawlInternalUrls;
use Spatie\Crawler\CrawlProfiles\CrawlSubdomains;
use Spatie\Sitemap\Crawler\Profile;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Test\CustomCrawlProfile;

beforeEach(function () {
    $this->crawler = $this->createMock(Crawler::class);

    $this->crawler->method('setCrawlObserver')->willReturn($this->crawler);
    $this->crawler->method('setConcurrency')->willReturn($this->crawler);
});

it('can use default profile', function () {
    $this->crawler
        ->method('setCrawlProfile')
        ->with($this->isInstanceOf(Profile::class))
        ->willReturn($this->crawler);

    $sitemapGenerator = new SitemapGenerator($this->crawler);

    $sitemap = $sitemapGenerator->setUrl('')->getSitemap();

    expect($sitemap)->toBeInstanceOf(Sitemap::class);
});

it('can use the custom profile', function () {
    config(['sitemap.crawl_profile' => CustomCrawlProfile::class]);

    $this->crawler
        ->method('setCrawlProfile')
        ->with($this->isInstanceOf(CustomCrawlProfile::class))
        ->willReturn($this->crawler);

    $sitemapGenerator = new SitemapGenerator($this->crawler);

    $sitemap = $sitemapGenerator->setUrl('')->getSitemap();

    expect($sitemap)->toBeInstanceOf(Sitemap::class);
});

it('can use the subdomain profile', function () {
    config(['sitemap.crawl_profile' => CrawlSubdomains::class]);

    $this->crawler
        ->method('setCrawlProfile')
        ->with($this->isInstanceOf(CrawlSubdomains::class))
        ->willReturn($this->crawler);

    $sitemapGenerator = new SitemapGenerator($this->crawler);

    $sitemap = $sitemapGenerator->setUrl('')->getSitemap();

    expect($sitemap)->toBeInstanceOf(Sitemap::class);
});

it('can use the internal profile', function () {
    config(['sitemap.crawl_profile' => CrawlInternalUrls::class]);

    $this->crawler
        ->method('setCrawlProfile')
        ->with($this->isInstanceOf(CrawlInternalUrls::class))
        ->willReturn($this->crawler);

    $sitemapGenerator = new SitemapGenerator($this->crawler);

    $sitemap = $sitemapGenerator->setUrl('')->getSitemap();

    expect($sitemap)->toBeInstanceOf(Sitemap::class);
});
