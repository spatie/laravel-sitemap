<?php

use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use function Spatie\Snapshots\assertMatchesXmlSnapshot;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    $this->sitemap = new Sitemap();
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

    assertMatchesXmlSnapshot(Storage::disk('sitemap')->get('sitemap.xml'));
});

test('an url string can be added to the sitemap', function () {
    $this->sitemap->add('/home');

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

test('sitemapable object can be added', function () {
    $this->sitemap
        ->add(new class implements Sitemapable {
            public function toSitemapTag(): Url | string | array
            {
                return '/';
            }
        })
        ->add(new class implements Sitemapable {
            public function toSitemapTag(): Url | string | array
            {
                return Url::create('/home');
            }
        })
        ->add(new class implements Sitemapable {
            public function toSitemapTag(): Url | string | array
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
        new class implements Sitemapable {
            public function toSitemapTag(): Url | string | array
            {
                return 'blog/post-1';
            }
        },
        new class implements Sitemapable {
            public function toSitemapTag(): Url | string | array
            {
                return 'blog/post-2';
            }
        },
        new class implements Sitemapable {
            public function toSitemapTag(): Url | string | array
            {
                return 'blog/post-3';
            }
        },
    ]));

    assertMatchesXmlSnapshot($this->sitemap->render());
});
