<?php

namespace Spatie\Sitemap\Crawler;

use Spatie\Crawler\Url;
use Spatie\Crawler\CrawlProfile;
use Spatie\Crawler\Url as CrawlerUrl;

class Profile implements CrawlProfile
{
    /** @var callable */
    protected $profile;

    /** @var string */
    protected $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function shouldCrawlCallback(callable $callback): self
    {
        $this->profile = $callback;

        return $this;
    }

    /*
     * Determine if the given url should be crawled.
     */
    public function shouldCrawl(Url $url): bool
    {
        if ($url->host !== CrawlerUrl::create($this->baseUrl)->host) {
            return false;
        }

        if (is_callable($this->profile)) {
            return ($this->profile)($url);
        }

        return true;
    }
}
