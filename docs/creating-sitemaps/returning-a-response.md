---
title: Returning a response
weight: 6
---

Both `Sitemap` and `SitemapIndex` implement Laravel's `Responsable` interface, so you can return them directly from a route or controller:

```php
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;

Route::get('sitemap.xml', function () {
    return Sitemap::create()
        ->add('/page1')
        ->add('/page2');
});

Route::get('sitemap_index.xml', function () {
    return SitemapIndex::create()
        ->add('/pages_sitemap.xml')
        ->add('/posts_sitemap.xml');
});
```

This will return an XML response with the correct `text/xml` content type.
