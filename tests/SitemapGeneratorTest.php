<?php

namespace Spatie\Sitemap\Test;

use Spatie\Sitemap\SitemapGenerator;

class SitemapGeneratorTest extends TestCase
{
    /** @test */
    public function it_can_generate_a_sitemap()
    {
        $sitemapPath = $this->getTempDirectory('test.xml');

        SitemapGenerator::create('http://localhost:4020')->writeToFile($sitemapPath);

        $this->assertIsEqualToContentsOfStub('generator', file_get_contents($sitemapPath));


    }
}
