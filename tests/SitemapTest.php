<?php

use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function Spatie\Snapshots\assertMatchesXmlSnapshot;

beforeEach(function () {
    $this->sitemap = new Sitemap;
});

it('provides a create method', function () {
    $sitemap = Sitemap::create();

    expect($sitemap)->toBeInstanceOf(Sitemap::class);
});

it('can render an empty sitemap', function () {
    assertMatchesXmlSnapshot($this->sitemap->render());
});

it('can write a sitemap to a file', function () {
    $path = temporaryDirectory()->path('test.xml');

    $this->sitemap->writeToFile($path);

    assertMatchesXmlSnapshot(file_get_contents($path));
});

it('can write a sitemap to a storage disk', function () {
    Storage::fake('sitemap');
    $this->sitemap->writeToDisk('sitemap', 'sitemap.xml');
    $visibility = Storage::disk('sitemap')->getVisibility('sitemap.xml');

    assertMatchesXmlSnapshot(Storage::disk('sitemap')->get('sitemap.xml'));
    expect($visibility)->toBe('private');
});

it('can write a sitemap to a storage disk with public visibility', function () {
    Storage::fake('sitemap');
    $this->sitemap->writeToDisk('sitemap', 'sitemap.xml', true);
    $visibility = Storage::disk('sitemap')->getVisibility('sitemap.xml');

    assertMatchesXmlSnapshot(Storage::disk('sitemap')->get('sitemap.xml'));
    expect($visibility)->toBe('public');
});

test('an url string can be added to the sitemap', function () {
    $this->sitemap->add('/home');

    assertMatchesXmlSnapshot($this->sitemap->render());
});

test('an empty string cannot be added to the sitemap', function () {
    $this->sitemap->add('');
    $this->sitemap->add('  ');

    assertMatchesXmlSnapshot($this->sitemap->render());
});

test('an url cannot be added twice to the sitemap', function () {
    $this->sitemap->add('/home');
    $this->sitemap->add('/home');

    assertMatchesXmlSnapshot($this->sitemap->render());
});

test('an url with an alternate can be added to the sitemap', function () {
    $url = Url::create('/home')
        ->addAlternate('/thuis', 'nl')
        ->addAlternate('/maison', 'fr');

    $this->sitemap->add($url);

    assertMatchesXmlSnapshot($this->sitemap->render());
});

test('an url object can be added to the sitemap', function () {
    $this->sitemap->add(Url::create('/home'));

    assertMatchesXmlSnapshot($this->sitemap->render());
});

test('multiple urls can be added to the sitemap', function () {
    $this->sitemap
        ->add(Url::create('/home'))
        ->add(Url::create('/contact'));

    assertMatchesXmlSnapshot($this->sitemap->render());
});

it('can render an url with all its set properties', function () {
    $this->sitemap
        ->add(
            Url::create('/home')
                ->setLastModificationDate($this->now->subDay())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
                ->setPriority(0.1)
        );

    assertMatchesXmlSnapshot($this->sitemap->render());
});

it('can render an url with priority 0', function () {
    $this->sitemap
        ->add(
            Url::create('/home')
                ->setLastModificationDate($this->now->subDay())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
                ->setPriority(0.0)
        );

    assertMatchesXmlSnapshot($this->sitemap->render());
});

it('can determine if it contains a given url', function () {
    $this->sitemap
        ->add('/page1')
        ->add('/page2')
        ->add('/page3');

    expect($this->sitemap->hasUrl('/page2'))->toBeTrue();
});

it('can get a specific url', function () {
    $this->sitemap->add('/page1');
    $this->sitemap->add('/page2');

    $url = $this->sitemap->getUrl('/page2');

    expect($url)->toBeInstanceOf(Url::class)
        ->url->toBe('/page2');
});

it('returns null when getting a non-existing url', function () {
    expect($this->sitemap->getUrl('/page1'))->toBeNull();

    $this->sitemap->add('/page1');

    expect($this->sitemap->getUrl('/page1'))->not->toBeNull()
        ->and($this->sitemap->getUrl('/page2'))->toBeNull();
});

test('a url object cannot be added twice to the sitemap', function () {
    $this->sitemap->add(Url::create('/home'));
    $this->sitemap->add(Url::create('/home'));

    assertMatchesXmlSnapshot($this->sitemap->render());
});

test('an instance can return a response', function () {
    $this->sitemap->add(Url::create('/home'));

    expect($this->sitemap->toResponse(new Request))
        ->toBeInstanceOf(Response::class);
});

test('multiple urls can be added in one call', function () {
    $this->sitemap->add([
        Url::create('/'),
        '/home',
        Url::create('/home'), // filtered
    ]);

    assertMatchesXmlSnapshot($this->sitemap->render());
});

test('sitemapable object with empty string cannot be added', function () {
    $this->sitemap
        ->add(new class implements Sitemapable
        {
            public function toSitemapTag(): Url|string|array
            {
                return '';
            }
        })
        ->add(new class implements Sitemapable
        {
            public function toSitemapTag(): Url|string|array
            {
                return '  ';
            }
        });

    assertMatchesXmlSnapshot($this->sitemap->render());
});

test('sitemapable object can be added', function () {
    $this->sitemap
        ->add(new class implements Sitemapable
        {
            public function toSitemapTag(): Url|string|array
            {
                return '/';
            }
        })
        ->add(new class implements Sitemapable
        {
            public function toSitemapTag(): Url|string|array
            {
                return Url::create('/home');
            }
        })
        ->add(new class implements Sitemapable
        {
            public function toSitemapTag(): Url|string|array
            {
                return [
                    'blog/post-1',
                    Url::create('/blog/post-2'),
                ];
            }
        });

    assertMatchesXmlSnapshot($this->sitemap->render());
});

test('sitemapable objects can be added', function () {
    $this->sitemap->add(collect([
        new class implements Sitemapable
        {
            public function toSitemapTag(): Url|string|array
            {
                return 'blog/post-1';
            }
        },
        new class implements Sitemapable
        {
            public function toSitemapTag(): Url|string|array
            {
                return 'blog/post-2';
            }
        },
        new class implements Sitemapable
        {
            public function toSitemapTag(): Url|string|array
            {
                return 'blog/post-3';
            }
        },
    ]));

    assertMatchesXmlSnapshot($this->sitemap->render());
});

it('can render a sitemap with a stylesheet', function () {
    $this->sitemap
        ->setStylesheet('/sitemap.xsl')
        ->add('/home');

    $rendered = $this->sitemap->render();

    expect($rendered)->toContain('<?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>');
    assertMatchesXmlSnapshot($rendered);
});

it('does not render a stylesheet when not set', function () {
    $this->sitemap->add('/home');

    expect($this->sitemap->render())->not->toContain('xml-stylesheet');
});

it('can split a sitemap into multiple files with an index', function () {
    $path = temporaryDirectory()->path('sitemap.xml');

    $this->sitemap
        ->maxTagsPerSitemap(2)
        ->add('/page1')
        ->add('/page2')
        ->add('/page3')
        ->add('/page4')
        ->add('/page5')
        ->writeToFile($path);

    $indexContent = file_get_contents($path);

    expect($indexContent)->toContain('<sitemapindex');

    $dir = dirname($path);

    expect(file_exists("{$dir}/sitemap_0.xml"))->toBeTrue()
        ->and(file_exists("{$dir}/sitemap_1.xml"))->toBeTrue()
        ->and(file_exists("{$dir}/sitemap_2.xml"))->toBeTrue();

    $chunk0 = file_get_contents("{$dir}/sitemap_0.xml");
    $chunk1 = file_get_contents("{$dir}/sitemap_1.xml");
    $chunk2 = file_get_contents("{$dir}/sitemap_2.xml");

    expect($chunk0)->toContain('/page1')->toContain('/page2')
        ->and($chunk1)->toContain('/page3')->toContain('/page4')
        ->and($chunk2)->toContain('/page5');
});

it('does not split when tag count is within the limit', function () {
    $path = temporaryDirectory()->path('sitemap.xml');

    $this->sitemap
        ->maxTagsPerSitemap(10)
        ->add('/page1')
        ->add('/page2')
        ->writeToFile($path);

    $content = file_get_contents($path);

    expect($content)->toContain('<urlset')
        ->and($content)->not->toContain('<sitemapindex');
});

it('can split a sitemap when writing to disk', function () {
    Storage::fake('sitemap');

    $this->sitemap
        ->maxTagsPerSitemap(2)
        ->add('/page1')
        ->add('/page2')
        ->add('/page3')
        ->writeToDisk('sitemap', 'sitemap.xml');

    expect(Storage::disk('sitemap')->exists('sitemap.xml'))->toBeTrue()
        ->and(Storage::disk('sitemap')->exists('sitemap_0.xml'))->toBeTrue()
        ->and(Storage::disk('sitemap')->exists('sitemap_1.xml'))->toBeTrue();

    $indexContent = Storage::disk('sitemap')->get('sitemap.xml');

    expect($indexContent)->toContain('<sitemapindex');
});

it('propagates stylesheet to chunk sitemaps and index when splitting', function () {
    $path = temporaryDirectory()->path('sitemap.xml');

    $this->sitemap
        ->setStylesheet('/sitemap.xsl')
        ->maxTagsPerSitemap(2)
        ->add('/page1')
        ->add('/page2')
        ->add('/page3')
        ->writeToFile($path);

    $indexContent = file_get_contents($path);
    $dir = dirname($path);
    $chunk0 = file_get_contents("{$dir}/sitemap_0.xml");
    $chunk1 = file_get_contents("{$dir}/sitemap_1.xml");

    expect($indexContent)->toContain('<?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>')
        ->and($chunk0)->toContain('<?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>')
        ->and($chunk1)->toContain('<?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>');
});

it('can sort urls alphabetically', function () {
    $this->sitemap
        ->add('/zebra')
        ->add('/apple')
        ->add('/monkey')
        ->add('/banana')
        ->sort();

    $tags = $this->sitemap->getTags();

    expect($tags[0]->url)->toBe('/apple')
        ->and($tags[1]->url)->toBe('/banana')
        ->and($tags[2]->url)->toBe('/monkey')
        ->and($tags[3]->url)->toBe('/zebra');
});

it('returns itself when sorting for method chaining', function () {
    $result = $this->sitemap
        ->add('/zebra')
        ->add('/apple')
        ->sort();

    expect($result)->toBe($this->sitemap);
});

it('can sort an empty sitemap without errors', function () {
    $result = $this->sitemap->sort();

    expect($result)->toBe($this->sitemap)
        ->and($this->sitemap->getTags())->toBeEmpty();
});

it('renders sorted urls in correct order', function () {
    $this->sitemap
        ->add('/zoo')
        ->add('/about')
        ->add('/contact')
        ->add('/blog')
        ->sort();

    $rendered = $this->sitemap->render();

    // Check that URLs appear in alphabetical order in the rendered XML
    $aboutPos = strpos($rendered, '/about');
    $blogPos = strpos($rendered, '/blog');
    $contactPos = strpos($rendered, '/contact');
    $zooPos = strpos($rendered, '/zoo');

    expect($aboutPos)->toBeLessThan($blogPos)
        ->and($blogPos)->toBeLessThan($contactPos)
        ->and($contactPos)->toBeLessThan($zooPos);
});

it('can sort url objects with different properties', function () {
    $this->sitemap
        ->add(Url::create('/zoo')->setPriority(1.0))
        ->add(Url::create('/about')->setPriority(0.5))
        ->add(Url::create('/blog')->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
        ->sort();

    $tags = $this->sitemap->getTags();

    expect($tags[0]->url)->toBe('/about')
        ->and($tags[1]->url)->toBe('/blog')
        ->and($tags[2]->url)->toBe('/zoo');
});

it('sorts urls case-sensitively with uppercase first', function () {
    $this->sitemap
        ->add('/Zebra')
        ->add('/apple')
        ->add('/BANANA')
        ->sort();

    $tags = $this->sitemap->getTags();

    // PHP's sort() compares strings case-sensitively, uppercase letters come before lowercase
    expect($tags[0]->url)->toBe('/BANANA')
        ->and($tags[1]->url)->toBe('/Zebra')
        ->and($tags[2]->url)->toBe('/apple');
});
