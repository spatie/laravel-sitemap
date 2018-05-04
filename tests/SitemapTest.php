<?php

namespace Spatie\Sitemap\Test;

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapTest extends TestCase
{
    /** @var \Spatie\Sitemap\Sitemap */
    protected $sitemap;

    public function setUp()
    {
        parent::setUp();

        $this->sitemap = new Sitemap('');
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
        $this->assertMatchesXmlSnapshot($this->sitemap->render());
    }

    /** @test */
    public function it_can_write_a_sitemap_to_a_file()
    {
        $path = $this->temporaryDirectory->path('test.xml');

        $this->sitemap->writeToFile($path);

        $this->assertMatchesXmlSnapshot(file_get_contents($path));
    }

    /** @test */
    public function an_url_string_can_be_added_to_the_sitemap()
    {
        $this->sitemap->add('/home');

        $this->assertMatchesXmlSnapshot($this->sitemap->render());
    }

    /** @test */
    public function a_url_string_can_not_be_added_twice_to_the_sitemap()
    {
        $this->sitemap->add('/home');
        $this->sitemap->add('/home');

        $this->assertMatchesXmlSnapshot($this->sitemap->render());
    }

    /** @test */
    public function an_url_with_an_alternate_can_be_added_to_the_sitemap()
    {
        $url = Url::create('/home')
            ->addAlternate('/thuis', 'nl')
            ->addAlternate('/maison', 'fr');

        $this->sitemap->add($url);

        $this->assertMatchesXmlSnapshot($this->sitemap->render());
    }

    /** @test */
    public function an_url_object_can_be_added_to_the_sitemap()
    {
        $this->sitemap->add(Url::create('/home'));

        $this->assertMatchesXmlSnapshot($this->sitemap->render());
    }

    /** @test */
    public function multiple_urls_can_be_added_to_the_sitemap()
    {
        $this->sitemap
            ->add(Url::create('/home'))
            ->add(Url::create('/contact'));

        $this->assertMatchesXmlSnapshot($this->sitemap->render());
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

        $this->assertMatchesXmlSnapshot($this->sitemap->render());
    }

    /** @test */
    public function it_can_render_a_sitemap_with_custom_xsl()
    {
        $sitemap = new Sitemap('/foo.xsl');
        $sitemap->add('/home');

        $this->assertMatchesXmlSnapshot($sitemap->render());
    }

    /** @test */
    public function it_can_determine_if_it_contains_a_given_url()
    {
        $this->sitemap
            ->add('/page1')
            ->add('/page2')
            ->add('/page3');

        $this->assertTrue($this->sitemap->hasUrl('/page2'));
    }

    /** @test */
    public function it_can_get_a_specific_url()
    {
        $this->sitemap->add('/page1');
        $this->sitemap->add('/page2');

        $url = $this->sitemap->getUrl('/page2');

        $this->assertInstanceOf(Url::class, $url);
        $this->assertSame('/page2', $url->url);
    }

    /** @test */
    public function it_returns_null_when_getting_a_non_existing_url()
    {
        $this->assertNull($this->sitemap->getUrl('/page1'));

        $this->sitemap->add('/page1');

        $this->assertNotNull($this->sitemap->getUrl('/page1'));

        $this->assertNull($this->sitemap->getUrl('/page2'));
    }
}
