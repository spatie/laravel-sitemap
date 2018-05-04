<?php

namespace Spatie\Sitemap\Crawler;

use Spatie\Robots\Robots;
use Spatie\Crawler\CrawlProfile;
use Psr\Http\Message\UriInterface;

class Profile extends CrawlProfile
{
    /** @var callable */
    protected $profile;

    /** @var \Spatie\Robots\Robots */
    protected $robots;

    public function __construct()
    {
        $this->robots = Robots::create();
    }

    public function shouldCrawlCallback(callable $callback)
    {
        $this->profile = $callback;
    }

    /*
     * Determine if the given url should be crawled.
     */
    public function shouldCrawl(UriInterface $url): bool
    {
        $mayIndex = $this->robots->mayIndex($url);

        return $mayIndex && ($this->profile)($url);
    }
}
