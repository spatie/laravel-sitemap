<?php

namespace Spatie\Sitemap\Test;

use Throwable;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Crawler\Url as CrawlerUrl;

class SitemapGeneratorTest extends TestCase
{
    /** @var \Spatie\Sitemap\SitemapGenerator */
    protected $sitemapGenerator;

    public function setUp()
    {
        $this->skipIfTestServerIsNotRunning();

        parent::setUp();

        $this->sitemapGenerator = SitemapGenerator::create('http://localhost:4020')->setConcurrency(1);
    }

    /** @test */
    public function it_can_generate_a_sitemap()
    {
        $sitemapPath = $this->getTempDirectory('test.xml');

        SitemapGenerator::create('http://localhost:4020')->writeToFile($sitemapPath);

        $this->assertMatchesXmlSnapshot(file_get_contents($sitemapPath));
    }

    /** @test */
    public function it_can_modify_the_attributes_while_generating_the_sitemap()
    {
        $sitemapPath = $this->getTempDirectory('test.xml');

        SitemapGenerator::create('http://localhost:4020')
            ->hasCrawled(function (Url $url) {
                if ($url->segment(1) === 'page3') {
                    $url->setPriority(0.6);
                }

                return $url;
            })
            ->writeToFile($sitemapPath);

        $this->assertMatchesXmlSnapshot(file_get_contents($sitemapPath));
    }

    /** @test */
    public function it_will_not_add_the_url_to_the_site_map_if_has_crawled_does_not_return_it()
    {
        $sitemapPath = $this->getTempDirectory('test.xml');

        SitemapGenerator::create('http://localhost:4020')
            ->hasCrawled(function (Url $url) {
                if ($url->segment(1) === 'page3') {
                    return;
                }

                return $url;
            })
            ->writeToFile($sitemapPath);

        $this->assertMatchesXmlSnapshot(file_get_contents($sitemapPath));
    }

    /** @test */
    public function it_will_not_crawl_an_url_if_should_crawl_returns_false()
    {
        $sitemapPath = $this->getTempDirectory('test.xml');

        SitemapGenerator::create('http://localhost:4020')
            ->shouldCrawl(function (CrawlerUrl $url) {
                return $url->segment(1) !== 'page3';
            })
            ->writeToFile($sitemapPath);

        $this->assertMatchesXmlSnapshot(file_get_contents($sitemapPath));
    }

    protected function skipIfTestServerIsNotRunning()
    {
        try {
            file_get_contents('http://localhost:4020');
        } catch (Throwable $e) {
            $this->markTestSkipped('The testserver is not running.');
        }
    }
}
