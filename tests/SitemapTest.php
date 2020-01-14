<?php

namespace Spatie\Sitemap\Test;

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SitemapTest extends TestCase
{
    /** @var \Spatie\Sitemap\Sitemap */
    protected $sitemap;

    public function setUp(): void
    {
        parent::setUp();

        $this->sitemap = new Sitemap();
    }

    /** @test */
    public function an_url_string_can_be_added_to_the_sitemap()
    {
        $this->sitemap->add('/home');

        $expectedOutput = file_get_contents(__DIR__ . '/expectedOutput/sitemapWithAnUrl.xml');

        $this->assertEquals($expectedOutput, $this->sitemap->render());
    }
}
