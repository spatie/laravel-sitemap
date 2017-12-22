<?php

namespace Spatie\Sitemap\Test;

use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlProfile;

class CustomCrawlProfile implements CrawlProfile
{
    /**
     * Determine if the given url should be crawled.
     *
     * @param \Psr\Http\Message\UriInterface $url
     *
     * @return bool
     */
    public function shouldCrawl(UriInterface $url): bool
    {
        if ($url->getHost() !== 'localhost') {
            return false;
        }

        return is_null($url->segment(1));
    }
}
