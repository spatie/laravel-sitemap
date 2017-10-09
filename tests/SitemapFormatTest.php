<?php

namespace Spatie\Sitemap\Test;

use Spatie\Sitemap\SitemapGenerator;

class SitemapFormatTest extends TestCase
{


    /** @test */
    public function sitemap_file_is_formatted_properly()
    {
        $sitemapRendered = SitemapGenerator::create('http://localhost:4020')->getSitemap()->render();

        $this->assertEquals(file_get_contents(__DIR__.'/sitemapStubs/formattedSitemap.xml'), $sitemapRendered);
    }

}