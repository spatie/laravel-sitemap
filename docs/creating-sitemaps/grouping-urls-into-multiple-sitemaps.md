---
title: Grouping URLs into multiple sitemaps
weight: 5
---

Splitting large sitemaps divides URLs across files purely by count. Sometimes you instead want to group URLs by meaning, for example one sitemap for your blog and another for the rest of the site. Grouping like this makes it easier to track indexing coverage per section in Google Search Console.

Pass a closure to `writeToFile`. It receives each `Url` and returns the path of the file that URL belongs in. Every distinct path becomes its own sitemap file.

```php
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

SitemapGenerator::create('https://example.com')
    ->writeToFile(fn (Url $url) => str_starts_with($url->path(), '/blog')
        ? public_path('sitemap-blog.xml')
        : public_path('sitemap-pages.xml'));
```

Returning `null` (or an empty string) from the closure leaves that URL out of every sitemap, so the closure doubles as a filter.

## Writing a sitemap index

By default no sitemap index is written. Call `sitemapIndexPath` to also generate an index that references each written sitemap.

```php
SitemapGenerator::create('https://example.com')
    ->sitemapIndexPath(public_path('sitemap.xml'))
    ->writeToFile(fn (Url $url) => str_starts_with($url->path(), '/blog')
        ? public_path('sitemap-blog.xml')
        : public_path('sitemap-pages.xml'));
```

## Combining with a maximum number of tags

When you combine grouping with `maxTagsPerSitemap`, a group that exceeds the limit is split further into numbered files (`sitemap-blog_0.xml`, `sitemap-blog_1.xml`, ...). Each of those chunks is listed in the index.

```php
SitemapGenerator::create('https://example.com')
    ->maxTagsPerSitemap(20000)
    ->sitemapIndexPath(public_path('sitemap.xml'))
    ->writeToFile(fn (Url $url) => str_starts_with($url->path(), '/blog')
        ? public_path('sitemap-blog.xml')
        : public_path('sitemap-pages.xml'));
```
