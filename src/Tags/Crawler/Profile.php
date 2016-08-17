<?php

namespace Spatie\Sitemap\Crawler;

use Spatie\Crawler\CrawlProfile;
use Spatie\Crawler\Url;

class Profile implements CrawlProfile
{
    public function __construct(callable $profile)
    {
        $this->profile = $profile;
    }

    /**
     * Determine if the given url should be crawled.
     *
     * @param \Spatie\Crawler\Url $url
     *
     * @return bool
     */
    public function shouldCrawl(Url $url)
    {
        return ($this->profile)($url);
    }
}
