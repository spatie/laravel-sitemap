<?php

namespace Spatie\Sitemap\Test\Crawler;

use Spatie\Crawler\CrawlProfiles\CrawlProfile;

class CustomCrawlProfile implements CrawlProfile
{
    public function __construct(protected string $baseUrl)
    {
    }

    public function shouldCrawl(string $url): bool
    {
        if (parse_url($url, PHP_URL_HOST) !== 'localhost') {
            return false;
        }

        return parse_url($url, PHP_URL_PATH) === '/';
    }
}
