<?php

namespace Spatie\Sitemap;

use Spatie\Crawler\Crawler;
use Spatie\Crawler\Url as CrawlerUrl;
use Spatie\Sitemap\Crawler\Observer;
use Spatie\Sitemap\Tags\Url;

/**
 * 	$siteMap = SitemapGenerator::create('https://spatie.be')
* ->hasCrawled(SitemapProfile::class) // or closure
* ->writeToFile($path);
 */

class SitemapGenerator
{
    /** @var string */
    protected $url = '';

    /** @var \Spatie\Crawler\Crawler */
    protected $crawler;

    /** @var callable */
    protected $hasCrawled;

    /** @var \Spatie\Sitemap\Sitemap */
    protected $sitemap;

    /**
     * @param string $url
     *
     * @return static
     */
    public static function create(string $url)
    {
        return app(SitemapGenerator::class)->setUrl($url);
    }

    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;

        $this->sitemap = new Sitemap();

        $this->hasCrawled = function(Url $url) {
            return $url;
        };
    }

    public function setUrl(string $url)
    {
        $this->url = $url;

        return $this;
    }

    public function hasCrawled(callable $hasCrawled)
    {
        $this->hasCrawled = $hasCrawled;

        return $this;
    }

    /**
     * @return \Spatie\Sitemap\Sitemap
     */
    public function getSitemap()
    {
        $this->crawler
            ->setCrawlObserver($this->getObserver())
            ->startCrawling($this->url);

        return $this->sitemap;
    }

    public function writeToFile($path)
    {
        $this->getSitemap()->writeToFile($path);

        return $this;
    }

    /**
     * @return \Spatie\Sitemap\Crawler\Observer
     */
    protected function getObserver()
    {
        $performAfterUrlHasBeenCrawled = function (CrawlerUrl $url) {
            $sitemapUrl = ($this->hasCrawled)(Url::create((string)$url));

            if ($sitemapUrl) {
                $this->sitemap->add($sitemapUrl);
            }
        };

        return new Observer($performAfterUrlHasBeenCrawled);
    }
}
