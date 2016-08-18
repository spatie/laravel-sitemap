<?php

namespace Spatie\Sitemap\Test;

use Spatie\Crawler\Url as CrawlerUrl;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class SitemapGeneratorTest extends TestCase
{
    /** @test */
    public function it_can_generate_a_sitemap()
    {
        $sitemapPath = $this->getTempDirectory('test.xml');

        SitemapGenerator::create('http://localhost:4020')->writeToFile($sitemapPath);

        $this->assertIsEqualToContentsOfStub('generateEntireSite', file_get_contents($sitemapPath));
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

        $this->assertIsEqualToContentsOfStub('modifyGenerated', file_get_contents($sitemapPath));
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

        $this->assertIsEqualToContentsOfStub('skipUrlWhileGenerating', file_get_contents($sitemapPath));
    }

  /**
    public function it_will_not_crawl_an_url_if_should_crawl_returns_false()
    {
        $sitemapPath = $this->getTempDirectory('test.xml');

        SitemapGenerator::create('http://localhost:4020')
            ->shouldCrawl(function (CrawlerUrl $url) {
                return $url->segment(1) !== 'page3';
            })
            ->writeToFile($sitemapPath);

        $this->assertIsEqualToContentsOfStub('skipUrlWhileGenerating', file_get_contents($sitemapPath));
    }
   */
}
