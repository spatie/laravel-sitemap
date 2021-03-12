<?php

namespace Spatie\Sitemap\Crawler;

use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlProfiles\CrawlProfile;

class Profile extends CrawlProfile
{
    /** @var callable */
    protected $callback;

    public function shouldCrawlCallback(callable $callback): void
    {
        $this->callback = $callback;
    }

    public function shouldCrawl(UriInterface $url): bool
    {
        return ($this->callback)($url);
    }
}
