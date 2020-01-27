<?php

namespace Spatie\Sitemap\Test;

use Spatie\Sitemap\Tags\Image;

class ImageTest extends TestCase
{
    /** @var \Spatie\Sitemap\Tags\Image */
    protected $image;

    public function setUp(): void
    {
        parent::setUp();

        $this->image = new Image('defaultUrl');
    }

    /** @test */
    public function url_can_be_set()
    {
        $this->image->setUrl('testUrl');

        $this->assertEquals('testUrl', $this->image->url);
    }

    /** @test */
    public function caption_can_be_set()
    {
        $this->image->setCaption('testCaption');

        $this->assertEquals('testCaption', $this->image->caption);
    }

    /** @test */
    public function title_can_be_set()
    {
        $this->image->setTitle('testTitle');

        $this->assertEquals('testTitle', $this->image->title);
    }

    /** @test */
    public function geo_location_can_be_set()
    {
        $this->image->setGeoLocation('testGeoLocation');

        $this->assertEquals('testGeoLocation', $this->image->geoLocation);
    }

    /** @test */
    public function license_can_be_set()
    {
        $this->image->setLicense('testLicense');

        $this->assertEquals('testLicense', $this->image->license);
    }
}
