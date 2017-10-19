<?php

namespace Spatie\Sitemap\Crawler;

use Spatie\Crawler\Url;
use Spatie\Crawler\CrawlProfile;

class Profile implements CrawlProfile
{
    /** @var callable */
    protected $profile;

    public function shouldCrawlCallback(callable $callback)
    {
        $this->profile = $callback;
    }

    /*
     * Determine if the given url should be crawled.
     */
    public function shouldCrawl(Url $url): bool
    {
        return ($this->profile)($url);
    }
}
