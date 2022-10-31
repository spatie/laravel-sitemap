<?php

use Psr\Http\Message\UriInterface;
use Spatie\Crawler\Crawler;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sitemap\Test\CustomCrawlProfile;

use function Spatie\Snapshots\assertMatchesXmlSnapshot;

function checkIfTestServerIsRunning()
{
    try {
        file_get_contents('http://localhost:4020');
    } catch (Throwable $e) {
        handleTestServerNotRunning();
    }
}

function handleTestServerNotRunning()
{
    if (getenv('TRAVIS')) {
        test()->fail('The test server is not running on Travis.');
    }

    test()->markTestSkipped('The test server is not running.');
}

beforeEach(function () {
    checkIfTestServerIsRunning();

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

it('will not crawl an url of shouldCrawl() returns false', function () {
    $sitemapPath = $this->temporaryDirectory->path('test.xml');

    SitemapGenerator::create('http://localhost:4020')
        ->shouldCrawl(function (UriInterface $url) {
            return !strpos($url->getPath(), 'page3');
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
