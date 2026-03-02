# Upgrading

## From 7.x to 8.0

This is a major release that upgrades to `spatie/crawler` v9 and Pest v4. Breaking changes are listed below.

### PHP 8.4+ required

The minimum PHP version has been bumped to PHP 8.4.

### Laravel 11 support dropped

Support for Laravel 11 has been removed. This version requires Laravel 12 or 13.

### Crawler upgraded to v9

`spatie/crawler` has been updated from `^8.0` to `^9.0`. This is a complete rewrite of the crawler with a simplified API.

### `shouldCrawl` callback receives a `string` instead of `UriInterface`

If you use the `shouldCrawl` method on the `SitemapGenerator`, the callback now receives a plain `string` URL instead of a `Psr\Http\Message\UriInterface` instance.

```php
// Before
SitemapGenerator::create('https://example.com')
    ->shouldCrawl(function (UriInterface $url) {
        return strpos($url->getPath(), '/contact') === false;
    });

// After
SitemapGenerator::create('https://example.com')
    ->shouldCrawl(function (string $url) {
        return ! str_contains(parse_url($url, PHP_URL_PATH) ?? '', '/contact');
    });
```

### `hasCrawled` callback receives `CrawlResponse` instead of `ResponseInterface`

If you use the second parameter in the `hasCrawled` callback, it is now a `Spatie\Crawler\CrawlResponse` instance instead of `Psr\Http\Message\ResponseInterface`.

```php
// Before
use Psr\Http\Message\ResponseInterface;

SitemapGenerator::create('https://example.com')
    ->hasCrawled(function (Url $url, ?ResponseInterface $response = null) {
        return $url;
    });

// After
use Spatie\Crawler\CrawlResponse;

SitemapGenerator::create('https://example.com')
    ->hasCrawled(function (Url $url, ?CrawlResponse $response = null) {
        // $response->status(), $response->body(), $response->dom(), etc.
        return $url;
    });
```

### Custom crawl profiles must implement the `CrawlProfile` interface

`CrawlProfile` has changed from an abstract class to an interface. Custom profiles must implement it and use `string` instead of `UriInterface`.

```php
// Before
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlProfiles\CrawlProfile;

class CustomCrawlProfile extends CrawlProfile
{
    public function shouldCrawl(UriInterface $url): bool
    {
        return $url->getHost() === 'example.com';
    }
}

// After
use Spatie\Crawler\CrawlProfiles\CrawlProfile;

class CustomCrawlProfile implements CrawlProfile
{
    public function __construct(protected string $baseUrl)
    {
    }

    public function shouldCrawl(string $url): bool
    {
        return parse_url($url, PHP_URL_HOST) === 'example.com';
    }
}
```

### `configureCrawler` receives a v9 Crawler

If you use `configureCrawler`, the Crawler instance now uses the v9 API. Most methods have been renamed to shorter versions.

```php
// Before
SitemapGenerator::create('https://example.com')
    ->configureCrawler(function (Crawler $crawler) {
        $crawler->setMaximumDepth(3);
        $crawler->setConcurrency(5);
    });

// After
SitemapGenerator::create('https://example.com')
    ->configureCrawler(function (Crawler $crawler) {
        $crawler->depth(3);
        // Note: setConcurrency() still works via the SitemapGenerator's own method
    });
```

### The `configureCrawler` callback is now deferred

The `configureCrawler` closure is no longer executed immediately. It is stored and executed when `getSitemap()` or `writeToFile()` is called. In practice this should not affect most users since the methods are typically chained.

### Redirects are now followed by default

The crawler now follows redirects by default with redirect tracking enabled. The previous default `guzzle_options` config that set `ALLOW_REDIRECTS => false` has been removed. If you need the old behavior, add it to your published config:

```php
'guzzle_options' => [
    \GuzzleHttp\RequestOptions::ALLOW_REDIRECTS => false,
],
```

### `Observer` class removed

The `Spatie\Sitemap\Crawler\Observer` class has been removed. The package now uses the crawler's built-in closure callbacks. If you were extending this class, use `configureCrawler` with `onCrawled()` instead.

### JavaScript execution now uses a driver-based API

If you use JavaScript execution, `spatie/browsershot` must now be installed separately (it is no longer a dependency of the crawler). The configuration remains the same via `config/sitemap.php`.

### Dependencies removed from composer.json

`guzzlehttp/guzzle` and `symfony/dom-crawler` have been removed as direct dependencies. They are still available transitively through the crawler package.

## From 6.0 to 7.0

- `spatie/crawler` is updated to `^8.0`.

## From 5.0 to 6.0

No API changes were made. If you're on PHP 8, you should be able to upgrade from v5 to v6 without having to make any changes.

## From 4.0 to 5.0

- `spatie/crawler` is updated to `^4.0`. This version made changes to the way custom `Profiles` and `Observers` are made. Please see the [UPGRADING](https://github.com/spatie/crawler/blob/master/UPGRADING.md) guide of `spatie/crawler` to know how to update any custom crawl profiles or observers, if you have any.

## From 3.0 to 4.0

- `spatie/crawler` is updated to `^3.0`. This version introduced the use of PSR-7 `UriInterface` instead of a custom `Url` class. Please see the [UPGRADING](https://github.com/spatie/crawler/blob/master/UPGRADING.md) guide of `spatie/crawler` to know how to update any custom crawl profiles, if you have any.
