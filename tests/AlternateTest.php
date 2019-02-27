<?php

namespace Spatie\Sitemap\Test;

use Spatie\Sitemap\Tags\Alternate;

class AlternateTest extends TestCase
{
    /** @var \Spatie\Sitemap\Tags\Alternate */
    protected $alternate;

    public function setUp(): void
    {
        parent::setUp();

        $this->alternate = new Alternate('defaultUrl', 'en');
    }

    /** @test */
    public function url_can_be_set()
    {
        $this->alternate->setUrl('testUrl');

        $this->assertEquals('testUrl', $this->alternate->url);
    }

    /** @test */
    public function locale_can_be_set()
    {
        $this->alternate->setLocale('en');

        $this->assertEquals('en', $this->alternate->locale);
    }
}
