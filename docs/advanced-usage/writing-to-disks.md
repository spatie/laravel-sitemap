---
title: Writing to disks
weight: 2
---

You can use one of your available filesystem disks to write the sitemap to.

```php
use Spatie\Sitemap\SitemapGenerator;

SitemapGenerator::create('https://example.com')
    ->getSitemap()
    ->writeToDisk('public', 'sitemap.xml');
```

You may need to set the file visibility on one of your sitemaps. For example, if you are writing a sitemap to S3 that you want to be publicly available. You can set the third parameter to `true` to make it public.

```php
use Spatie\Sitemap\SitemapGenerator;

SitemapGenerator::create('https://example.com')
    ->getSitemap()
    ->writeToDisk('public', 'sitemap.xml', true);
```

A sitemap index can also be written to a disk:

```php
use Spatie\Sitemap\SitemapIndex;

SitemapIndex::create()
    ->add('/pages_sitemap.xml')
    ->add('/posts_sitemap.xml')
    ->writeToDisk('public', 'sitemap.xml');
```
