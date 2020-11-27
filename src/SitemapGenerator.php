<?php

namespace Spatie\Sitemap;

use Closure;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlProfiles\CrawlProfile;
use Spatie\Sitemap\Crawler\Observer;
use Spatie\Sitemap\Crawler\Profile;
use Spatie\Sitemap\Tags\Url;

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

    /** @var bool|int */
    protected $maximumTagsPerSitemap = false;

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

    public function configureCrawler(Closure $closure): self
    {
        call_user_func_array($closure, [$this->crawler]);

        return $this;
    }

    public function setConcurrency(int $concurrency)
    {
        $this->concurrency = $concurrency;

        return $this;
    }

    public function setMaximumCrawlCount(int $maximumCrawlCount)
    {
        $this->maximumCrawlCount = $maximumCrawlCount;

        return $this;
    }

    public function maxTagsPerSitemap(int $maximumTagsPerSitemap = 50000): self
    {
        $this->maximumTagsPerSitemap = $maximumTagsPerSitemap;

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

        if ($this->maximumTagsPerSitemap) {
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

            if ($this->shouldStartNewSitemapFile()) {
                $this->sitemaps->push(new Sitemap);
            }

            if ($sitemapUrl) {
                $this->sitemaps->last()->add($sitemapUrl);
            }
        };

        return new Observer($performAfterUrlHasBeenCrawled);
    }

    protected function shouldStartNewSitemapFile(): bool
    {
        if (! $this->maximumTagsPerSitemap) {
            return false;
        }

        $currentNumberOfTags = count($this->sitemaps->last()->getTags());

        return $currentNumberOfTags >= $this->maximumTagsPerSitemap;
    }
}
