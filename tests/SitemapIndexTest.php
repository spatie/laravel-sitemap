<?php

namespace Spatie\Sitemap\Test;

use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Sitemap;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SitemapIndexTest extends TestCase
{
    /** @var \Spatie\Sitemap\SitemapIndex */
    protected $index;

    public function setUp(): void
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
        $this->assertMatchesXmlSnapshot($this->index->render());
    }

    /** @test */
    public function it_can_write_an_index_to_a_file()
    {
        $path = $this->temporaryDirectory->path('test.xml');

        $this->index->writeToFile($path);

        $this->assertMatchesXmlSnapshot(file_get_contents($path));
    }

    /** @test */
    public function it_can_write_a_sitemap_to_a_storage_disk()
    {
        Storage::fake('sitemap');
        $this->index->writeToDisk('sitemap', 'sitemap.xml');

        $this->assertMatchesXmlSnapshot(Storage::disk('sitemap')->get('sitemap.xml'));
    }

    /** @test */
    public function an_url_string_can_be_added_to_the_index()
    {
        $this->index->add('/sitemap1.xml');

        $this->assertMatchesXmlSnapshot($this->index->render());
    }

    /** @test */
    public function a_sitemap_object_can_be_added_to_the_index()
    {
        $this->index->add(Sitemap::create('/sitemap1.xml'));

        $this->assertMatchesXmlSnapshot($this->index->render());
    }

    /** @test */
    public function multiple_sitemaps_can_be_added_to_the_index()
    {
        $this->index
            ->add(Sitemap::create('/sitemap1.xml'))
            ->add(Sitemap::create('/sitemap2.xml'));

        $this->assertMatchesXmlSnapshot($this->index->render());
    }

    /** @test */
    public function it_can_render_a_sitemaps_with_all_its_set_properties()
    {
        $this->index
            ->add(Sitemap::create('/sitemap1.xml')
                ->setLastModificationDate($this->now->subDay())
            );

        $this->assertMatchesXmlSnapshot($this->index->render());
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

    /** @test */
    public function an_instance_can_return_a_response()
    {
        $this->index->add('/sitemap1.xml');

        $this->assertInstanceOf(Response::class, $this->index->toResponse(new Request));
    }
}
