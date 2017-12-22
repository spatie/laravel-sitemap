<?php

namespace Spatie\Sitemap\Crawler;

use Psr\Http\Message\UriInterface;
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
     * @param \Psr\Http\Message\UriInterface $url
     */
    public function willCrawl(UriInterface $url)
    {
    }

    /**
     * Called when the crawler has crawled the given url.
     *
     * @param \Psr\Http\Message\UriInterface $url
     * @param \Psr\Http\Message\ResponseInterface|null $response
     * @param \Psr\Http\Message\UriInterface $foundOnUrl
     */
    public function hasBeenCrawled(UriInterface $url, $response, ?UriInterface $foundOnUrl = null)
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
