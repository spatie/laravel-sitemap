<?php

namespace Spatie\Sitemap\Test;

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class DummyTest extends TestCase
{
    /** @test */
    public function it_renders()
    {
        $sitemap = new Sitemap();

        $sitemap->add(Url::create('https://spatie.be'));

        $sitemap ->writeToFile($this->getTempDirectory('dummy.xml'));
    }

    /** @test */
    public function it_crawls()
    {
        $sitemapGenerator = SitemapGenerator::create('https://spatie.be')
            ->writeToFile($this->getTempDirectory('test.xml'));
    }
}
