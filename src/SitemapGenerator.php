<?php

namespace Spatie\Sitemap;

use Spatie\Crawler\Crawler;

/**
 * 	$siteMap = SitemapGenerator::create('https://spatie.be')
* ->hasCrawled(SitemapProfile::class) // or closure
* ->writeToFile($path);
 */

class SitemapGenerator
{
    /** @var string */
    protected $url = '';

    public static function create(string $url)
    {

    }

    protected function __construct(Crawler $crawler)
    {

    }

    public function setUrl(string $url)
    {
        $this->url = $url;

        return $this;
    }
}
