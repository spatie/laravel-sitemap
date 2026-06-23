<?php

use Spatie\Crawler\Crawler;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sitemap\Test\Crawler\CustomCrawlProfile;

use function Spatie\Snapshots\assertMatchesXmlSnapshot;

beforeEach(function () {
    ensureTestServerIsRunning();

    $this->temporaryDirectory = temporaryDirectory();
});

it('can generate a sitemap', function () {
    $sitemapPath = $this->temporaryDirectory->path('test.xml');

    SitemapGenerator::create('http://localhost:4020')
        ->writeToFile($sitemapPath);

    assertMatchesXmlSnapshot(file_get_contents($sitemapPath));
});

it('will create new sitemaps if the maximum amount is crossed', function () {
    $sitemapPath = $this->temporaryDirectory->path('test_chunk.xml');

    SitemapGenerator::create('http://localhost:4020')
        ->maxTagsPerSitemap(1)
        ->writeToFile($sitemapPath);

    $content = file_get_contents($sitemapPath);

    foreach (range(0, 5) as $index) {
        $filename = "test_chunk_{$index}.xml";
        $subsitemap = file_get_contents($this->temporaryDirectory->path($filename));

        expect($subsitemap)->not->toBeEmpty()
            ->and($content)->toContain("test_chunk_{$index}.xml")
            ->and($subsitemap)
            ->toContain('<loc>')
            ->toContain('<url>')
            ->toContain('<urlset');
    }
});

it('can modify the attributes while generating the sitemap', function () {
    $sitemapPath = $this->temporaryDirectory->path('test.xml');

    SitemapGenerator::create('http://localhost:4020')
        ->hasCrawled(function (Url $url) {
            if ($url->segment(1) === 'page3') {
                $url->setPriority(0.6);
            }

            return $url;
        })
        ->writeToFile($sitemapPath);

    assertMatchesXmlSnapshot(file_get_contents($sitemapPath));
});

it(
    'will not add the url to the sitemap if hasCrawled() does not return it',
    function () {
        $sitemapPath = $this->temporaryDirectory->path('test.xml');

        SitemapGenerator::create('http://localhost:4020')
            ->hasCrawled(function (Url $url) {
                if ($url->segment(1) === 'page3') {
                    return;
                }

                return $url;
            })
            ->writeToFile($sitemapPath);

        assertMatchesXmlSnapshot(file_get_contents($sitemapPath));
    }
);

it('will not crawl an url if shouldCrawl() returns false', function () {
    $sitemapPath = $this->temporaryDirectory->path('test.xml');

    SitemapGenerator::create('http://localhost:4020')
        ->shouldCrawl(function (string $url) {
            return ! str_contains(parse_url($url, PHP_URL_PATH) ?? '', 'page3');
        })
        ->writeToFile($sitemapPath);

    assertMatchesXmlSnapshot(file_get_contents($sitemapPath));
});

it('will not crawl an url if listed in robots.txt', function () {
    $sitemapPath = $this->temporaryDirectory->path('test.xml');

    SitemapGenerator::create('http://localhost:4020')
        ->writeToFile($sitemapPath);

    expect(file_get_contents($sitemapPath))->not->toContain('/not-allowed');
});

it('will crawl an url if robots.txt check is disabled', function () {
    $sitemapPath = $this->temporaryDirectory->path('test.xml');

    SitemapGenerator::create('http://localhost:4020')
        ->configureCrawler(function (Crawler $crawler) {
            $crawler->ignoreRobots();
        })
        ->writeToFile($sitemapPath);

    expect(file_get_contents($sitemapPath))->toContain('/not-allowed');
});

it('can use a custom profile', function () {
    config(['sitemap.crawl_profile' => CustomCrawlProfile::class]);

    $sitemapPath = $this->temporaryDirectory->path('test.xml');

    SitemapGenerator::create('http://localhost:4020')
        ->writeToFile($sitemapPath);

    assertMatchesXmlSnapshot(file_get_contents($sitemapPath));
});

it('can write grouped sitemaps using a closure', function () {
    $page3Path = $this->temporaryDirectory->path('sitemap-page3.xml');
    $pagesPath = $this->temporaryDirectory->path('sitemap-pages.xml');

    SitemapGenerator::create('http://localhost:4020')
        ->writeToFile(fn (Url $url) => $url->segment(1) === 'page3' ? $page3Path : $pagesPath);

    expect(file_get_contents($pagesPath))
        ->toContain('/page1')
        ->not->toContain('/page3');

    expect(file_get_contents($page3Path))
        ->toContain('/page3')
        ->not->toContain('/page1');
});

it('writes a sitemap index when an index path is set', function () {
    $indexPath = $this->temporaryDirectory->path('sitemap.xml');
    $page3Path = $this->temporaryDirectory->path('sitemap-page3.xml');
    $pagesPath = $this->temporaryDirectory->path('sitemap-pages.xml');

    SitemapGenerator::create('http://localhost:4020')
        ->sitemapIndexPath($indexPath)
        ->writeToFile(fn (Url $url) => $url->segment(1) === 'page3' ? $page3Path : $pagesPath);

    expect(file_get_contents($indexPath))
        ->toContain('<sitemapindex')
        ->toContain('sitemap-page3.xml')
        ->toContain('sitemap-pages.xml');
});

it('does not write a sitemap index when no index path is set', function () {
    $indexPath = $this->temporaryDirectory->path('sitemap.xml');
    $pagesPath = $this->temporaryDirectory->path('sitemap-pages.xml');

    SitemapGenerator::create('http://localhost:4020')
        ->writeToFile(fn (Url $url) => $pagesPath);

    expect(file_exists($indexPath))->toBeFalse();
});

it('skips a url when the group closure returns null', function () {
    $pagesPath = $this->temporaryDirectory->path('sitemap-pages.xml');

    SitemapGenerator::create('http://localhost:4020')
        ->writeToFile(fn (Url $url) => $url->segment(1) === 'page3' ? null : $pagesPath);

    expect(file_get_contents($pagesPath))
        ->toContain('/page1')
        ->not->toContain('/page3');
});

it('splits an overflowing group and lists each chunk in the index', function () {
    $indexPath = $this->temporaryDirectory->path('sitemap.xml');
    $pagesPath = $this->temporaryDirectory->path('sitemap-pages.xml');

    SitemapGenerator::create('http://localhost:4020')
        ->maxTagsPerSitemap(1)
        ->sitemapIndexPath($indexPath)
        ->writeToFile(fn (Url $url) => $pagesPath);

    $index = file_get_contents($indexPath);

    expect($index)
        ->toContain('sitemap-pages_0.xml')
        ->toContain('sitemap-pages_1.xml');

    expect(file_get_contents($this->temporaryDirectory->path('sitemap-pages_0.xml')))
        ->toContain('<url>');
});
