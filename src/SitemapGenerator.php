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
    protected $hasCrawled;

    /** @var callable */
    protected $crawlProfile;

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

        $this->hasCrawled = function (Url $url, ResponseInterface $response = null) {
            return $url;
        };

        $this->crawlProfile = function (CrawlerUrl $url) {
            return $url->host === CrawlerUrl::create($this->url)->host;
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
            ->setCrawlProfile($this->getProfile())
            ->setCrawlObserver($this->getObserver())
            ->startCrawling($this->url);

        return $this->sitemap;
    }

    public function writeToFile($path)
    {
        $this->getSitemap()->writeToFile($path);

        return $this;
    }

    protected function getObserver(): Observer
    {
        $performAfterUrlHasBeenCrawled = function (CrawlerUrl $crawlerUrl, ResponseInterface $response = null) {
            $sitemapUrl = ($this->hasCrawled)(Url::create((string) $crawlerUrl), $response);

            if ($sitemapUrl) {
                $this->sitemap->add($sitemapUrl);
            }
        };

        return new Observer($performAfterUrlHasBeenCrawled);
    }

    protected function getProfile(): Profile
    {
        return new Profile($this->crawlProfile);
    }
}
