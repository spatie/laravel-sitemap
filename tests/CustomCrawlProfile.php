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
     * @return bool
     */
    public function shouldCrawl(Url $url): bool
    {
        if ($url->host !== 'localhost') {
            return false;
        }

        return is_null($url->segment(1));
    }
}
