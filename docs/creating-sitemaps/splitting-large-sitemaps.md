---
title: Splitting large sitemaps
weight: 4
---

When you have a large number of URLs, you can automatically split them into multiple sitemap files with a sitemap index. Call the `maxTagsPerSitemap` method to set the maximum number of URLs per file.

## When using the sitemap generator

```php
use Spatie\Sitemap\SitemapGenerator;

SitemapGenerator::create('https://example.com')
    ->maxTagsPerSitemap(20000)
    ->writeToFile(public_path('sitemap.xml'));
```

## When building a sitemap manually

This also works when building a sitemap manually. When the number of URLs exceeds the limit, `writeToFile` and `writeToDisk` will automatically create chunk files (`sitemap_0.xml`, `sitemap_1.xml`, ...) and write a sitemap index as the main file:

```php
use Spatie\Sitemap\Sitemap;

Sitemap::create()
    ->maxTagsPerSitemap(20000)
    ->add(/* ... */)
    ->writeToFile(public_path('sitemap.xml'));
```

If the number of URLs is within the limit, a single sitemap file will be written as normal.
