<?php

namespace Spatie\Sitemap\Test;

use Spatie\Crawler\Crawler;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Crawler\Profile;
use Spatie\Sitemap\SitemapGenerator;

class CrawlProfileTest extends TestCase
{
    /**
     * @var Crawler
     */
    private $crawler;

    public function setUp()
    {
        parent::setUp();

        $this->crawler = $this->createMock(Crawler::class);

        $this->crawler->method('setCrawlObserver')->willReturn($this->crawler);
        $this->crawler->method('setConcurrency')->willReturn($this->crawler);
    }

    /** @test */
    public function it_should_use_the_default_crawl_profile()
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
    public function it_should_use_a_custom_crawl_profile()
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
}
