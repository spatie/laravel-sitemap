<?php

use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Sitemap;
use function Spatie\Snapshots\assertMatchesXmlSnapshot;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    $this->index = new SitemapIndex();
});

it('provides a `create` method', function () {
    $index = SitemapIndex::create();

    expect($index)->toBeInstanceOf(SitemapIndex::class);
});

it('can render an empty index', function () {
    assertMatchesXmlSnapshot($this->index->render());
});

it('can write an index to a file', function () {
    $path = temporaryDirectory()->path('test.xml');

    $this->index->writeToFile($path);

    assertMatchesXmlSnapshot(file_get_contents($path));
});

it('can write a sitemap to a storage disk', function () {
    Storage::fake('sitemap');
    $this->index->writeToDisk('sitemap', 'sitemap.xml');

    assertMatchesXmlSnapshot(Storage::disk('sitemap')->get('sitemap.xml'));
});

test('an url string can be added to the index', function () {
    $this->index->add('/sitemap1.xml');

    assertMatchesXmlSnapshot($this->index->render());
});

test('a sitemap object can be added to the index', function () {
    $this->index->add(Sitemap::create('/sitemap1.xml'));

    assertMatchesXmlSnapshot($this->index->render());
});

test('multiple sitemaps can be added to the index', function () {
    $this->index
        ->add(Sitemap::create('/sitemap1.xml'))
        ->add(Sitemap::create('/sitemap2.xml'));

    assertMatchesXmlSnapshot($this->index->render());
});

it('can render a sitemap with all its set properties', function () {
    $this->index
        ->add(
            Sitemap::create('/sitemap1.xml')
                ->setLastModificationDate($this->now->subDay())
        );

    assertMatchesXmlSnapshot($this->index->render());
});

it('can determine if it contains a given sitemap', function () {
    $this->index
        ->add('/sitemap1.xml')
        ->add('/sitemap2.xml')
        ->add('/sitemap3.xml');

    expect($this->index->hasSitemap('/sitemap2.xml'))->toBeTrue();
});

it('can get a specific sitemap', function () {
    $this->index->add('/sitemap1.xml');
    $this->index->add('/sitemap2.xml');

    $sitemap = $this->index->getSitemap('/sitemap2.xml');

    expect($sitemap)->toBeInstanceOf(Sitemap::class)
        ->url->toBe('/sitemap2.xml');
});

it('returns null when getting a non-existing sitemap', function () {
    expect($this->index->getSitemap('/sitemap1.xml'))->toBeNull();

    $this->index->add('/sitemap1.xml');

    expect($this->index->getSitemap('/sitemap1.xml'))->not->toBeNull()
        ->and($this->index->getSitemap('/sitemap2.xml'))->toBeNull();
});

test('an instance can return a response', function () {
    $this->index->add('/sitemap1.xml');

    expect($this->index->toResponse(new Request))->toBeInstanceOf(Response::class);
});
