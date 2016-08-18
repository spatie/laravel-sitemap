<?php

namespace Spatie\Sitemap\Test;

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapTest extends TestCase
{
    /** @var \Spatie\Sitemap\Sitemap */
    protected $sitemap;

    /** @test */
    public function setUp()
    {
        parent::setUp();

        $this->sitemap = new Sitemap();
    }

    /** @test */
    public function it_provides_a_create_method()
    {
        $sitemap = Sitemap::create();

        $this->assertInstanceOf(Sitemap::class, $sitemap);
    }

    /** @test */
    public function it_can_render_an_empty_sitemap()
    {
        $this->assertIsEqualToContentsOfStub('empty', $this->sitemap->render());
    }

    /** @test */
    public function it_can_write_a_sitemap_to_a_file()
    {
        $path = $this->getTempDirectory('test.xml');

        $this->sitemap->writeToFile($path);

        $this->assertIsEqualToContentsOfStub('empty', file_get_contents($path));
    }

    /** @test */
    public function an_url_string_can_be_added_to_the_sitemap()
    {
        $this->sitemap->add('/home');

        $this->assertIsEqualToContentsOfStub('singleUrl', $this->sitemap->render());
    }

    /** @test */
    public function an_url_object_can_be_added_to_the_sitemap()
    {
        $this->sitemap->add(Url::create('/home'));

        $this->assertIsEqualToContentsOfStub('singleUrl', $this->sitemap->render());
    }

    /** @test */
    public function multiple_urls_can_be_added_to_the_sitemap()
    {
        $this->sitemap
            ->add(Url::create('/home'))
            ->add(Url::create('/contact'));

        $this->assertIsEqualToContentsOfStub('multipleUrls', $this->sitemap->render());
    }


    /** @test */
    public function it_can_render_an_url_with_all_its_set_properties()
    {
        $this->sitemap
            ->add(Url::create('/home')
                ->setLastModificationDate($this->now->subDay())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
                ->setPriority(0.1)
            );

        $this->assertIsEqualToContentsOfStub('customUrl', $this->sitemap->render());
    }
}
