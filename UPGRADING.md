# Upgrading

## From 7.0 to 8.0

### Requirements

- PHP 8.4 or higher is required (was 8.2+)
- Laravel 12 or higher is required (Laravel 11 support was dropped)

### Crawler upgrade

`spatie/crawler` is updated from `^8.0` to `^9.0`. This brings several breaking changes.

#### `shouldCrawl` callback

The `shouldCrawl` callback now receives a `string` URL instead of a `UriInterface` object.

```php
// Before
use Psr\Http\Message\UriInterface;

SitemapGenerator::create('https://example.com')
    ->shouldCrawl(function (UriInterface $url) {
        return strpos($url->getPath(), '/contact') === false;
    })
    ->writeToFile($path);

// After
SitemapGenerator::create('https://example.com')
    ->shouldCrawl(function (string $url) {
        return ! str_contains(parse_url($url, PHP_URL_PATH) ?? '', '/contact');
    })
    ->writeToFile($path);
```

#### `hasCrawled` callback

The `hasCrawled` callback now receives a `Spatie\Crawler\CrawlResponse` as its second argument instead of `Psr\Http\Message\ResponseInterface`.

```php
// Before
use Psr\Http\Message\ResponseInterface;
use Spatie\Sitemap\Tags\Url;

->hasCrawled(function (Url $url, ?ResponseInterface $response = null) {
    return $url;
})

// After
use Spatie\Crawler\CrawlResponse;
use Spatie\Sitemap\Tags\Url;

->hasCrawled(function (Url $url, ?CrawlResponse $response = null) {
    return $url;
})
```

#### Custom crawl profiles

Custom crawl profiles must implement the `Spatie\Crawler\CrawlProfiles\CrawlProfile` interface. The `shouldCrawl` method now receives a `string` instead of `UriInterface`.

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

#### Observer class removed

The `Spatie\Sitemap\Crawler\Observer` class has been removed. If you were extending it, you no longer need to. The package now handles crawl observation internally using closure callbacks.

### Config file changes

The config file has been simplified. If you have published the config file, update it:

```php
// Before
use GuzzleHttp\RequestOptions;
use Spatie\Sitemap\Crawler\Profile;

return [
    'guzzle_options' => [
        RequestOptions::COOKIES => true,
        RequestOptions::CONNECT_TIMEOUT => 10,
        RequestOptions::TIMEOUT => 10,
        RequestOptions::ALLOW_REDIRECTS => false,
    ],
    'execute_javascript' => false,
    'chrome_binary_path' => '',
    'crawl_profile' => Profile::class,
];

// After
use Spatie\Sitemap\Crawler\Profile;

return [
    'guzzle_options' => [

    ],
    'execute_javascript' => false,
    'chrome_binary_path' => null,
    'crawl_profile' => Profile::class,
];
```

The guzzle options are now merged with the crawler's defaults (cookies enabled, connect timeout 10s, request timeout 10s, redirects followed). You only need to specify options you want to override.

### Removed dependencies

`guzzlehttp/guzzle` and `symfony/dom-crawler` are no longer direct dependencies. If your application relies on these packages directly, make sure to require them in your own `composer.json`.

### Other changes

- Redirects are now followed by default
- The `configureCrawler` callback now receives the crawler before it starts crawling, instead of during construction. If you were relying on the order of operations, review your `configureCrawler` usage.

## From 6.0 to 7.0

- `spatie/crawler` is updated to `^8.0`. 

## From 5.0 to 6.0

No API changes were made. If you're on PHP 8, you should be able to upgrade from v5 to v6 without having to make any changes.

## From 4.0 to 5.0

- `spatie/crawler` is updated to `^4.0`. This version made changes to the way custom `Profiles` and `Observers` are made. Please see the [UPGRADING](https://github.com/spatie/crawler/blob/master/UPGRADING.md) guide of `spatie/crawler` to know how to update any custom crawl profiles or observers - if you have any.

## From 3.0 to 4.0

- `spatie/crawler` is updated to `^3.0`. This version introduced the use of PSR-7 `UriInterface` instead of a custom `Url` class. Please see the [UPGRADING](https://github.com/spatie/crawler/blob/master/UPGRADING.md) guide of `spatie/crawler` to know how to update any custom crawl profiles - if you have any.
