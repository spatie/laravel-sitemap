<?php

namespace Spatie\Sitemap\Test;

use Spatie\Crawler\Url;
use Spatie\Crawler\CrawlProfile;

class CustomCrawlProfile implements CrawlProfile
{

    /**
     * Determine if the given url should be crawled.
     *
     * @param \Spatie\Crawler\Url $url
     *
     * @return bool
     */
    public function shouldCrawl(Url $url): bool
    {
        return true;
    }
}
