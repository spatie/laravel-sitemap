<?php

namespace Spatie\Sitemap;

use GuzzleHttp\Psr7\Uri;
use Spatie\Crawler\Crawler;
use Spatie\Sitemap\Tags\Url;
use Spatie\Crawler\CrawlProfile;
use Illuminate\Support\Collection;
use Psr\Http\Message\UriInterface;
use Spatie\Sitemap\Crawler\Profile;
use Spatie\Sitemap\Crawler\Observer;
use Psr\Http\Message\ResponseInterface;

class SitemapGenerator
{
    /** @var \Illuminate\Support\Collection */
    protected $sitemaps;

    /** @var \GuzzleHttp\Psr7\Uri */
    protected $urlToBeCrawled = '';

    /** @var \Spatie\Crawler\Crawler */
    protected $crawler;

    /** @var callable */
    protected $shouldCrawl;

    /** @var callable */
    protected $hasCrawled;

    /** @var int */
    protected $concurrency = 10;

    /** @var bool|int $chunk */
    protected $chunk = false;

    /** @var int|null */
    protected $maximumCrawlCount = null;

    /**
     * @param string $urlToBeCrawled
     *
     * @return static
     */
    public static function create(string $urlToBeCrawled)
    {
        return app(static::class)->setUrl($urlToBeCrawled);
    }

    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;

        $this->sitemaps = new Collection([new Sitemap]);

        $this->hasCrawled = function (Url $url, ResponseInterface $response = null) {
            return $url;
        };
    }

    public function setConcurrency(int $concurrency)
    {
        $this->concurrency = $concurrency;
    }

    public function setMaximumCrawlCount(int $maximumCrawlCount)
    {
        $this->maximumCrawlCount = $maximumCrawlCount;
    }

    public function maxItemsPerSitemap(int $chunk = 50000): self
    {
        $this->chunk = $chunk;

        return $this;
    }

    public function setUrl(string $urlToBeCrawled)
    {
        $this->urlToBeCrawled = new Uri($urlToBeCrawled);

        if ($this->urlToBeCrawled->getPath() === '') {
            $this->urlToBeCrawled = $this->urlToBeCrawled->withPath('/');
        }

        return $this;
    }

    public function shouldCrawl(callable $shouldCrawl)
    {
        $this->shouldCrawl = $shouldCrawl;

        return $this;
    }

    public function hasCrawled(callable $hasCrawled)
    {
        $this->hasCrawled = $hasCrawled;

        return $this;
    }

    public function getSitemap(): Sitemap
    {
        if (config('sitemap.execute_javascript')) {
            $this->crawler->executeJavaScript(config('sitemap.chrome_binary_path'));
        }

        if (! is_null($this->maximumCrawlCount)) {
            $this->crawler->setMaximumCrawlCount($this->maximumCrawlCount);
        }

        $this->crawler
            ->setCrawlProfile($this->getCrawlProfile())
            ->setCrawlObserver($this->getCrawlObserver())
            ->setConcurrency($this->concurrency)
            ->startCrawling($this->urlToBeCrawled);

        return $this->sitemaps->first();
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function writeToFile(string $path)
    {
        $sitemap = $this->getSitemap();

        if ($this->chunk) {
            $sitemap = SitemapIndex::create();
            $format = str_replace('.xml', '_%d.xml', $path);

            // Parses each sub-sitemaps, writes and pushs them into the sitemap
            // index
            $this->sitemaps->each(function (Sitemap $item, int $key) use ($sitemap, $format) {
                $path = sprintf($format, $key);

                $item->writeToFile(sprintf($format, $key));
                $sitemap->add(last(explode('public', $path)));
            });
        }

        $sitemap->writeToFile($path);

        return $this;
    }

    protected function getCrawlProfile(): CrawlProfile
    {
        $shouldCrawl = function (UriInterface $url) {
            if ($url->getHost() !== $this->urlToBeCrawled->getHost()) {
                return false;
            }

            if (! is_callable($this->shouldCrawl)) {
                return true;
            }

            return ($this->shouldCrawl)($url);
        };

        $profileClass = config('sitemap.crawl_profile', Profile::class);
        $profile = new $profileClass($this->urlToBeCrawled);

        if (method_exists($profile, 'shouldCrawlCallback')) {
            $profile->shouldCrawlCallback($shouldCrawl);
        }

        return $profile;
    }

    protected function getCrawlObserver(): Observer
    {
        $performAfterUrlHasBeenCrawled = function (UriInterface $crawlerUrl, ResponseInterface $response = null) {
            $sitemapUrl = ($this->hasCrawled)(Url::create((string) $crawlerUrl), $response);

            if ($this->shouldAddSitemap()) {
                $this->sitemaps->prepend(new Sitemap);
            }

            if ($sitemapUrl) {
                $this->sitemaps->first()->add($sitemapUrl);
            }
        };

        return new Observer($performAfterUrlHasBeenCrawled);
    }

    protected function shouldAddSitemap(): bool
    {
        return $this->chunk && count($this->sitemaps->first()->getTags()) >= $this->chunk;
    }
}
