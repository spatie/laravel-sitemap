---
title: Generating a sitemap
weight: 1
---

The easiest way is to crawl the given domain and generate a sitemap with all found links. The destination of the sitemap should be specified by `$path`.

```php
use Spatie\Sitemap\SitemapGenerator;

SitemapGenerator::create('https://example.com')->writeToFile($path);
```

The generated sitemap will look similar to this:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
    <url>
        <loc>https://example.com</loc>
        <lastmod>2016-01-01T00:00:00+00:00</lastmod>
    </url>
    <url>
        <loc>https://example.com/page</loc>
        <lastmod>2016-01-01T00:00:00+00:00</lastmod>
    </url>

    ...
</urlset>
```

## Manually adding links

You can manually add links to a crawled sitemap:

```php
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

SitemapGenerator::create('https://example.com')
    ->getSitemap()
    ->add(Url::create('/extra-page'))
    ->add(Url::create('/another-extra-page'))
    ->writeToFile($sitemapPath);
```
