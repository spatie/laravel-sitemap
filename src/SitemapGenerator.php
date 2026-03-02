<?php

namespace Spatie\Sitemap;

use Closure;
use Illuminate\Support\Collection;
use Spatie\Browsershot\Browsershot;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlProfiles\CrawlProfile;
use Spatie\Crawler\CrawlResponse;
use Spatie\Crawler\JavaScriptRenderers\BrowsershotRenderer;
use Spatie\Sitemap\Crawler\Profile;
use Spatie\Sitemap\Tags\Url;

class SitemapGenerator
{
    protected Collection $sitemaps;

    protected string $urlToBeCrawled;

    /** @var callable */
    protected $shouldCrawl;

    /** @var callable */
    protected $hasCrawled;

    protected ?Closure $configureCrawlerCallback = null;

    protected int $concurrency = 10;

    protected int $maximumTagsPerSitemap = 0;

    protected ?int $maximumCrawlCount = null;

    public static function create(string $urlToBeCrawled): static
    {
        return app(static::class)->setUrl($urlToBeCrawled);
    }

    public function __construct()
    {
        $this->sitemaps = new Collection([new Sitemap]);

        $this->hasCrawled = fn (Url $url, ?CrawlResponse $response = null) => $url;
    }

    public function configureCrawler(Closure $closure): static
    {
        $this->configureCrawlerCallback = $closure;

        return $this;
    }

    public function setConcurrency(int $concurrency): static
    {
        $this->concurrency = $concurrency;

        return $this;
    }

    public function setMaximumCrawlCount(int $maximumCrawlCount): static
    {
        $this->maximumCrawlCount = $maximumCrawlCount;

        return $this;
    }

    public function maxTagsPerSitemap(int $maximumTagsPerSitemap = 50000): static
    {
        $this->maximumTagsPerSitemap = $maximumTagsPerSitemap;

        return $this;
    }

    public function setUrl(string $urlToBeCrawled): static
    {
        $this->urlToBeCrawled = $urlToBeCrawled;

        return $this;
    }

    public function shouldCrawl(callable $shouldCrawl): static
    {
        $this->shouldCrawl = $shouldCrawl;

        return $this;
    }

    public function hasCrawled(callable $hasCrawled): static
    {
        $this->hasCrawled = $hasCrawled;

        return $this;
    }

    public function getSitemap(): Sitemap
    {
        $crawler = Crawler::create($this->urlToBeCrawled, config('sitemap.guzzle_options', []));

        if (config('sitemap.execute_javascript')) {
            if ($chromeBinaryPath = config('sitemap.chrome_binary_path')) {
                $browsershot = new Browsershot;
                $browsershot->setChromePath($chromeBinaryPath);

                $crawler->executeJavaScript(
                    new BrowsershotRenderer($browsershot)
                );
            } else {
                $crawler->executeJavaScript();
            }
        }

        if (! is_null($this->maximumCrawlCount)) {
            $crawler->limit($this->maximumCrawlCount);
        }

        $crawler
            ->crawlProfile($this->getCrawlProfile())
            ->concurrency($this->concurrency)
            ->onCrawled(function (string $url, CrawlResponse $response) {
                $sitemapUrl = ($this->hasCrawled)(Url::create($url), $response);

                if ($this->shouldStartNewSitemapFile()) {
                    $this->sitemaps->push(new Sitemap);
                }

                if ($sitemapUrl) {
                    $this->sitemaps->last()->add($sitemapUrl);
                }
            });

        if ($this->configureCrawlerCallback) {
            ($this->configureCrawlerCallback)($crawler);
        }

        $crawler->start();

        return $this->sitemaps->first();
    }

    public function writeToFile(string $path): static
    {
        $sitemap = $this->getSitemap();

        if ($this->maximumTagsPerSitemap) {
            $sitemap = SitemapIndex::create();
            $fileFormat = str_replace('.xml', '_%d.xml', $path);
            $urlFormat = str_replace('.xml', '_%d.xml', $this->toUrlPath($path));

            $this->sitemaps->each(function (Sitemap $item, int $key) use ($sitemap, $fileFormat, $urlFormat) {
                $item->writeToFile(sprintf($fileFormat, $key));
                $sitemap->add(sprintf($urlFormat, $key));
            });
        }

        $sitemap->writeToFile($path);

        return $this;
    }

    protected function toUrlPath(string $filePath): string
    {
        $publicPath = rtrim(public_path(), '/').'/';

        if (str_starts_with($filePath, $publicPath)) {
            return '/'.substr($filePath, strlen($publicPath));
        }

        return '/'.basename($filePath);
    }

    protected function getCrawlProfile(): CrawlProfile
    {
        $shouldCrawl = function (string $url) {
            if (parse_url($url, PHP_URL_HOST) !== parse_url($this->urlToBeCrawled, PHP_URL_HOST)) {
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

    protected function shouldStartNewSitemapFile(): bool
    {
        if (! $this->maximumTagsPerSitemap) {
            return false;
        }

        $currentNumberOfTags = count($this->sitemaps->last()->getTags());

        return $currentNumberOfTags >= $this->maximumTagsPerSitemap;
    }
}
