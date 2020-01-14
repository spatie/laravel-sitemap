<?php

namespace Spatie\Sitemap\Test;

use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlInternalUrls;
use Spatie\Crawler\CrawlSubdomains;
use Spatie\Sitemap\Crawler\Profile;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapGenerator;

class CrawlProfileTest extends TestCase
{
    /**
     * @var Crawler
     */
    private $crawler;

    public function setUp(): void
    {
        parent::setUp();

        $this->crawler = $this->createMock(Crawler::class);

        $this->crawler->method('setCrawlObserver')->willReturn($this->crawler);
        $this->crawler->method('setConcurrency')->willReturn($this->crawler);
    }

    /** @test */
    public function it_can_use_the_default_profile()
    {
        $this->crawler
            ->method('setCrawlProfile')
            ->with($this->isInstanceOf(Profile::class))
            ->willReturn($this->crawler);

        $sitemapGenerator = new SitemapGenerator($this->crawler);

        $sitemap = $sitemapGenerator->getSitemap();

        $this->assertInstanceOf(Sitemap::class, $sitemap);
    }

    /** @test */
    public function it_can_use_the_custom_profile()
    {
        config(['sitemap.crawl_profile' => CustomCrawlProfile::class]);

        $this->crawler
            ->method('setCrawlProfile')
            ->with($this->isInstanceOf(CustomCrawlProfile::class))
            ->willReturn($this->crawler);

        $sitemapGenerator = new SitemapGenerator($this->crawler);

        $sitemap = $sitemapGenerator->getSitemap();

        $this->assertInstanceOf(Sitemap::class, $sitemap);
    }

    /** @test */
    public function it_can_use_the_subdomain_profile()
    {
        config(['sitemap.crawl_profile' => CrawlSubdomains::class]);

        $this->crawler
            ->method('setCrawlProfile')
            ->with($this->isInstanceOf(CrawlSubdomains::class))
            ->willReturn($this->crawler);

        $sitemapGenerator = new SitemapGenerator($this->crawler);

        $sitemap = $sitemapGenerator->getSitemap();

        $this->assertInstanceOf(Sitemap::class, $sitemap);
    }

    /** @test */
    public function it_can_use_the_internal_profile()
    {
        config(['sitemap.crawl_profile' => CrawlInternalUrls::class]);

        $this->crawler
            ->method('setCrawlProfile')
            ->with($this->isInstanceOf(CrawlInternalUrls::class))
            ->willReturn($this->crawler);

        $sitemapGenerator = new SitemapGenerator($this->crawler);

        $sitemap = $sitemapGenerator->getSitemap();

        $this->assertInstanceOf(Sitemap::class, $sitemap);
    }
}
