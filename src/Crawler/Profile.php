<?php

namespace Spatie\Sitemap\Crawler;

use Spatie\Crawler\CrawlProfiles\CrawlProfile;

class Profile implements CrawlProfile
{
    /** @var callable */
    protected $callback;

    public function __construct(protected string $baseUrl) {}

    public function shouldCrawlCallback(callable $callback): void
    {
        $this->callback = $callback;
    }

    public function shouldCrawl(string $url): bool
    {
        return ($this->callback)($url);
    }
}
