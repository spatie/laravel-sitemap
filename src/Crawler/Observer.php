<?php

namespace Spatie\Sitemap\Crawler;

use Spatie\Crawler\Url;
use Spatie\Crawler\CrawlObserver;

class Observer implements CrawlObserver
{
    /** @var callable */
    protected $hasCrawled;

    public function __construct(callable $hasCrawled)
    {
        $this->hasCrawled = $hasCrawled;
    }

    /**
     * Called when the crawler will crawl the url.
     *
     * @param \Spatie\Crawler\Url $url
     */
    public function willCrawl(Url $url)
    {
    }

    /**
     * Called when the crawler has crawled the given url.
     *
     * @param \Spatie\Crawler\Url $url
     * @param \Psr\Http\Message\ResponseInterface|null $response
     * @param \Spatie\Crawler\Url $foundOnUrl
     */
    public function hasBeenCrawled(Url $url, $response, Url $foundOnUrl = null)
    {
        ($this->hasCrawled)($url, $response);
    }

    /**
     * Called when the crawl has ended.
     */
    public function finishedCrawling()
    {
    }
}
