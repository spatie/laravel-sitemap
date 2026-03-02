---
title: Crawl profiles
weight: 1
---

You can create a custom crawl profile by implementing the `Spatie\Crawler\CrawlProfiles\CrawlProfile` interface and customizing the `shouldCrawl()` method for full control over what URL, domain, or subdomain should be crawled:

```php
use Spatie\Crawler\CrawlProfiles\CrawlProfile;

class CustomCrawlProfile implements CrawlProfile
{
    public function __construct(protected string $baseUrl)
    {
    }

    public function shouldCrawl(string $url): bool
    {
        if (parse_url($url, PHP_URL_HOST) !== 'localhost') {
            return false;
        }

        return parse_url($url, PHP_URL_PATH) === '/';
    }
}
```

Register your `CustomCrawlProfile::class` in `config/sitemap.php`:

```php
return [
    /*
     * The sitemap generator uses a CrawlProfile implementation to determine
     * which urls should be crawled for the sitemap.
     */
    'crawl_profile' => CustomCrawlProfile::class,
];
```
