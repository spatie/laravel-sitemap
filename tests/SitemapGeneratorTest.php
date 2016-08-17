<?php

namespace Spatie\Sitemap\Test;

use Spatie\Sitemap\SitemapGenerator;

class SitemapGeneratorTest extends TestCase
{
    public function it_crawls()
    {
        $sitemapGenerator = SitemapGenerator::create('https://spatie.be')
            ->writeToFile($this->getTempDirectory('test.xml'));
    }
}
