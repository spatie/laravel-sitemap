---
title: Creating a sitemap index
weight: 3
---

You can create a sitemap index:

```php
use Spatie\Sitemap\SitemapIndex;

SitemapIndex::create()
    ->add('/pages_sitemap.xml')
    ->add('/posts_sitemap.xml')
    ->writeToFile($sitemapIndexPath);
```

You can pass a `Spatie\Sitemap\Tags\Sitemap` object to manually set the `lastModificationDate` property.

```php
use Carbon\Carbon;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Sitemap;

SitemapIndex::create()
    ->add('/pages_sitemap.xml')
    ->add(Sitemap::create('/posts_sitemap.xml')
        ->setLastModificationDate(Carbon::yesterday()))
    ->writeToFile($sitemapIndexPath);
```

You can check if the index contains a specific sitemap and retrieve it:

```php
$sitemapIndex->hasSitemap('/pages_sitemap.xml'); // returns true or false
$sitemapIndex->getSitemap('/pages_sitemap.xml'); // returns a Sitemap tag object or null
```

The generated sitemap index will look similar to this:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>http://www.example.com/pages_sitemap.xml</loc>
        <lastmod>2016-01-01T00:00:00+00:00</lastmod>
    </sitemap>
    <sitemap>
        <loc>http://www.example.com/posts_sitemap.xml</loc>
        <lastmod>2015-12-31T00:00:00+00:00</lastmod>
    </sitemap>
</sitemapindex>
```
