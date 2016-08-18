<?php

namespace Spatie\Sitemap;

use Psr\Http\Message\ResponseInterface;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\Url as CrawlerUrl;
use Spatie\Sitemap\Crawler\Observer;
use Spatie\Sitemap\Crawler\Profile;
use Spatie\Sitemap\Tags\Url;

class SitemapGenerator
{
    /** @var string */
    protected $url = '';

    /** @var \Spatie\Crawler\Crawler */
    protected $crawler;

    /** @var callable */
    protected $shouldCrawl;

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
        return app(static::class)->setUrl($url);
    }

    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;

        $this->sitemap = new Sitemap();

        $this->shouldCrawl = function (CrawlerUrl $url) {
            return true;
        };

        $this->hasCrawled = function (Url $url, ResponseInterface $response = null) {
            return $url;
        };
    }

    public function setUrl(string $url)
    {
        $this->url = $url;

        return $this;
    }

    public function shouldCrawl(callable $shouldCrawl)
    {
        $this->shouldCrawl = $shouldCrawl;

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
            ->setCrawlProfile($this->getCrawlProfile())
            ->setCrawlObserver($this->getCrawlObserver())
            ->startCrawling($this->url);

        return $this->sitemap;
    }

    public function writeToFile(string $path)
    {
        $this->getSitemap()->writeToFile($path);

        return $this;
    }

    protected function getCrawlProfile(): Profile
    {
        $shouldCrawl = function(CrawlerUrl $url) {
            if ($url->host !== CrawlerUrl::create($this->url)->host) {
                return false;
            }

            return $this->shouldCrawl;
        };

        return new Profile($shouldCrawl);
    }

    protected function getCrawlObserver(): Observer
    {
        $performAfterUrlHasBeenCrawled = function (CrawlerUrl $crawlerUrl, ResponseInterface $response = null) {
            $sitemapUrl = ($this->hasCrawled)(Url::create((string) $crawlerUrl), $response);

            if ($sitemapUrl) {
                $this->sitemap->add($sitemapUrl);
            }
        };

        return new Observer($performAfterUrlHasBeenCrawled);
    }
}
