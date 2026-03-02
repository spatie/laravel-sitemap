---
title: Images
weight: 2
---

URLs can have images. See the [Google Image Sitemaps documentation](https://developers.google.com/search/docs/advanced/sitemaps/image-sitemaps) for more information.

```php
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

Sitemap::create()
    ->add(
        Url::create('https://example.com')
            ->addImage('https://example.com/images/home.jpg', 'Home page image')
    )
    ->writeToFile($sitemapPath);
```
