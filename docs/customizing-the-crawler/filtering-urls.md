---
title: Filtering URLs
weight: 2
---

## Leaving out some links

If you don't want a crawled link to appear in the sitemap, just don't return it in the callable you pass to `hasCrawled`.

```php
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

SitemapGenerator::create('https://example.com')
    ->hasCrawled(function (Url $url) {
        if ($url->segment(1) === 'contact') {
            return;
        }

        return $url;
    })
    ->writeToFile($sitemapPath);
```

## Preventing the crawler from crawling some pages

You can also instruct the underlying crawler to not crawl some pages by passing a `callable` to `shouldCrawl`.

> `shouldCrawl` will only work with the default crawl `Profile` or custom crawl profiles that implement a `shouldCrawlCallback` method.

```php
use Spatie\Sitemap\SitemapGenerator;

SitemapGenerator::create('https://example.com')
    ->shouldCrawl(function (string $url) {
        // All pages will be crawled, except the contact page.
        // Links present on the contact page won't be added to the
        // sitemap unless they are present on a crawlable page.

        return ! str_contains(parse_url($url, PHP_URL_PATH) ?? '', '/contact');
    })
    ->writeToFile($sitemapPath);
```
