<?php

namespace Spatie\Sitemap\Test;

use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Sitemap;

class SitemapIndexTest extends TestCase
{
    /** @var \Spatie\Sitemap\SitemapIndex */
    protected $index;

    /** @test */
    public function setUp()
    {
        parent::setUp();

        $this->index = new SitemapIndex();
    }

    /** @test */
    public function it_provides_a_create_method()
    {
        $index = SitemapIndex::create();

        $this->assertInstanceOf(SitemapIndex::class, $index);
    }

    /** @test */
    public function it_can_render_an_empty_index()
    {
        $this->assertIsEqualToContentsOfStub('emptyIndex', $this->index->render());
    }

    /** @test */
    public function it_can_write_an_index_to_a_file()
    {
        $path = $this->getTempDirectory('test.xml');

        $this->index->writeToFile($path);

        $this->assertIsEqualToContentsOfStub('emptyIndex', file_get_contents($path));
    }

    /** @test */
    public function an_url_string_can_be_added_to_the_index()
    {
        $this->index->add('/sitemap1.xml');

        $this->assertIsEqualToContentsOfStub('singleSitemap', $this->index->render());
    }

    /** @test */
    public function a_sitemap_object_can_be_added_to_the_index()
    {
        $this->index->add(Sitemap::create('/sitemap1.xml'));

        $this->assertIsEqualToContentsOfStub('singleSitemap', $this->index->render());
    }

    /** @test */
    public function multiple_sitemaps_can_be_added_to_the_index()
    {
        $this->index
            ->add(Sitemap::create('/sitemap1.xml'))
            ->add(Sitemap::create('/sitemap2.xml'));

        $this->assertIsEqualToContentsOfStub('multipleSitemaps', $this->index->render());
    }

    /** @test */
    public function it_can_render_a_sitemaps_with_all_its_set_properties()
    {
        $this->index
            ->add(Sitemap::create('/sitemap1.xml')
                ->setLastModificationDate($this->now->subDay())
            );

        $this->assertIsEqualToContentsOfStub('customSitemap', $this->index->render());
    }

    /** @test */
    public function it_can_determine_if_it_contains_a_given_sitemap()
    {
        $this->index
            ->add('/sitemap1.xml')
            ->add('/sitemap2.xml')
            ->add('/sitemap3.xml');

        $this->assertTrue($this->index->hasSitemap('/sitemap2.xml'));
    }

    /** @test */
    public function it_can_get_a_specific_sitemap()
    {
        $this->index->add('/sitemap1.xml');
        $this->index->add('/sitemap2.xml');

        $sitemap = $this->index->getSitemap('/sitemap2.xml');

        $this->assertInstanceOf(Sitemap::class, $sitemap);
        $this->assertSame('/sitemap2.xml', $sitemap->url);
    }

    /** @test */
    public function it_returns_null_when_getting_a_non_existing_sitemap()
    {
        $this->assertNull($this->index->getSitemap('/sitemap1.xml'));

        $this->index->add('/sitemap1.xml');

        $this->assertNotNull($this->index->getSitemap('/sitemap1.xml'));

        $this->assertNull($this->index->getSitemap('/sitemap2.xml'));
    }
}
