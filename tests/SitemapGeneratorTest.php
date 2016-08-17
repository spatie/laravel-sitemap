<?php

namespace Spatie\Sitemap\Test;

use Spatie\Sitemap\SitemapGenerator;

class SitemapGeneratorTest extends TestCase
{
    /** @var string */
    protected $appUrl = 'http://localhost:4020';

    public function it_can_generate_a_site_map()
    {
        $sitemapPath = $this->getTempDirectory('test.xml');

        SitemapGenerator::create($this->appUrl)->writeToFile($sitemapPath);

        $this->assertIsEqualToContentsOfStub('generator.xml', file_get_contents($sitemapPath));
    }
}
